<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

/**
 * WhatsAppService — Wrapper untuk Fonnte API
 * Docs resmi: https://docs.fonnte.com/api-send-message/
 *
 * PENTING: Memakai cURL native (bukan Laravel HTTP client) karena
 * Fonnte mensyaratkan multipart/form-data dengan CURLFile untuk
 * beberapa parameter, dan dokumentasi resmi mereka berbasis cURL.
 * Http::asForm() dari Laravel kadang menyebabkan masalah encoding
 * pada beberapa versi — jadi kita ikuti pola resmi mereka persis.
 *
 * Konfigurasi di .env:
 *   FONNTE_TOKEN=your_device_token
 *
 * Daftarkan di config/services.php:
 *   'fonnte' => [
 *       'token' => env('FONNTE_TOKEN'),
 *   ],
 */
class WhatsAppService
{
    private string $token;
    private string $apiUrl = 'https://api.fonnte.com/send';

    public function __construct()
    {
        $this->token = (string) config('services.fonnte.token', '');
    }

    /**
     * Kirim pesan teks biasa ke satu nomor WhatsApp.
     *
     * @param  string $phone    Nomor tujuan, format: 08xxx / 628xxx / +62xxx
     * @param  string $message  Isi pesan
     * @param  array  $options  Opsional: delay, schedule, typing, dll (lihat docs Fonnte)
     * @return array{success: bool, message: string, raw: array|null}
     */
    public function send(string $phone, string $message, array $options = []): array
    {
        $target = $this->normalizePhone($phone);

        if (empty($this->token)) {
            Log::warning('Fonnte: token belum dikonfigurasi di .env (FONNTE_TOKEN). Pesan tidak dikirim.', [
                'target' => $target,
            ]);
            return [
                'success' => false,
                'message' => 'Fonnte token belum dikonfigurasi.',
                'raw'     => null,
            ];
        }

        $postFields = array_merge([
            'target'      => $target,
            'message'     => $message,
            'countryCode' => '62',
        ], $options);

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL            => $this->apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 15,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => $postFields,
            CURLOPT_HTTPHEADER     => [
                'Authorization: ' . $this->token,
            ],
        ]);

        $rawResponse = curl_exec($curl);
        $curlErrno   = curl_errno($curl);
        $curlError   = curl_error($curl);
        $httpCode    = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        // ── Error koneksi (DNS, timeout, dll) ───────────────────────────
        if ($curlErrno) {
            Log::error('Fonnte: cURL error saat mengirim pesan.', [
                'target' => $target,
                'error'  => $curlError,
            ]);
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan koneksi ke layanan WhatsApp: ' . $curlError,
                'raw'     => null,
            ];
        }

        $body = json_decode($rawResponse, true);

        // ── Fonnte kadang mengembalikan response non-JSON saat token salah ──
        if (! is_array($body)) {
            Log::error('Fonnte: response tidak valid (bukan JSON).', [
                'target'      => $target,
                'http_code'   => $httpCode,
                'raw_response'=> $rawResponse,
            ]);
            return [
                'success' => false,
                'message' => 'Response dari Fonnte tidak valid. Cek token device Anda.',
                'raw'     => ['raw_response' => $rawResponse],
            ];
        }

        // ── Fonnte: status true = berhasil dikirim ke queue ─────────────
        if (($body['status'] ?? false) === true) {
            Log::info('Fonnte: pesan berhasil dikirim.', [
                'target'   => $target,
                'response' => $body,
            ]);
            return [
                'success' => true,
                'message' => 'Pesan berhasil dikirim.',
                'raw'     => $body,
            ];
        }

        Log::error('Fonnte: gagal mengirim pesan.', [
            'target'      => $target,
            'http_code'   => $httpCode,
            'response'    => $body,
        ]);

        return [
            'success' => false,
            'message' => $body['reason'] ?? $body['message'] ?? 'Gagal mengirim pesan WhatsApp.',
            'raw'     => $body,
        ];
    }

    /**
     * Kirim kode OTP dengan template pesan standar SILABA.
     */
    public function sendOtp(string $phone, string $otpCode, int $expiresInMinutes = 2): array
    {
        $message = $this->otpTemplate($otpCode, $expiresInMinutes);

        return $this->send($phone, $message, [
            'delay' => '1-3',
        ]);
    }

    /**
     * Kirim notifikasi update status laporan.
     */
    public function sendReportStatusUpdate(string $phone, string $ticketNumber, string $status, ?string $note = null): array
    {
        return $this->send($phone, $this->statusUpdateTemplate($ticketNumber, $status, $note));
    }

    /**
     * Kirim notifikasi penugasan ke petugas lapangan.
     */
    public function sendAssignmentNotification(string $phone, string $ticketNumber, string $title, string $location): array
    {
        $message = "🔔 *Tugas Baru — SILABA*\n\n"
                 . "Anda mendapat penugasan baru:\n\n"
                 . "Tiket: *{$ticketNumber}*\n"
                 . "Judul: {$title}\n"
                 . "Lokasi: {$location}\n\n"
                 . "Silakan cek aplikasi SILABA untuk detail lengkap dan mulai penanganan.";

        return $this->send($phone, $message);
    }

    // ── Templates ────────────────────────────────────────────────────────

    private function otpTemplate(string $otpCode, int $expiresInMinutes): string
    {
        return "*Kode Verifikasi SILABA*\n\n"
             . "Kode OTP Anda: *{$otpCode}*\n\n"
             . "Kode ini berlaku selama {$expiresInMinutes} menit. "
             . "Jangan bagikan kode ini kepada siapa pun, termasuk pihak yang mengaku dari SILABA.\n\n"
             . "Jika Anda tidak meminta kode ini, abaikan pesan ini.";
    }

    private function statusUpdateTemplate(string $ticketNumber, string $status, ?string $note): string
    {
        $msg = "📢 *Update Laporan SILABA*\n\n"
             . "Tiket: *{$ticketNumber}*\n"
             . "Status terbaru: *{$status}*\n";

        if ($note) {
            $msg .= "\nCatatan: {$note}\n";
        }

        $msg .= "\nCek detail lengkap di aplikasi SILABA.";

        return $msg;
    }

    // ── Helpers ──────────────────────────────────────────────────────────

    private function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        } elseif (! str_starts_with($phone, '62')) {
            $phone = '62' . $phone;
        }

        return $phone;
    }
}