<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use App\Models\RewardClaim;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class RewardClaimPdfController extends Controller
{
    public function download(RewardClaim $rewardClaim)
    {
        // Pastikan hanya pemilik yang bisa download
        abort_if($rewardClaim->user_id !== Auth::id(), 403);

        $rewardClaim->load(['reward', 'voucher']);
        $voucher = $rewardClaim->voucher;

        // ── Generate QR sebagai base64 PNG (server-side, tanpa JS) ──────────
        $qr = $this->generateQr($voucher->code);

        $pdf = app('dompdf.wrapper');
        $pdf->setOptions(['enable_remote' => true, 'isHtml5ParserEnabled' => true]);
        $pdf->loadView('pdf.reward-claim', [
            'rewardClaim' => $rewardClaim,
            'voucher'     => $voucher,
            'qr'          => $qr, 
            'isExpired'   => $voucher->valid_until
                && Carbon::parse($voucher->valid_until)->isPast(),
            'ticketNo'    => 'RWD-' . str_pad($rewardClaim->id, 4, '0', STR_PAD_LEFT),
        ]);

        $pdf->setPaper([0, 0, 400, 700], 'portrait'); // ukuran tiket ~A5 portrait

        $filename = 'tiket-reward-' . str_pad($rewardClaim->id, 4, '0', STR_PAD_LEFT) . '.pdf';
        return $pdf->download($filename);
    }

    // ── QR generator (pilih metode yang tersedia) ──────────────────────────
    private function generateQr(string $text): array
    {
        if (class_exists(\chillerlan\QRCode\QRCode::class)) {
            $options = new \chillerlan\QRCode\QROptions([
                'outputType' => \chillerlan\QRCode\Output\QRMarkupSVG::class,
                'imageBase64'=> false,
                'scale'      => 1,
            ]);

            $matrix = (new \chillerlan\QRCode\QRCode($options))
                ->getQRMatrix($text)
                ->getMatrix();

            return ['type' => 'matrix', 'matrix' => $matrix];
        }

        return ['type' => 'url', 'content' => 'https://api.qrserver.com/v1/create-qr-code/?size=160x160&data=' . urlencode($text)];
    }
}