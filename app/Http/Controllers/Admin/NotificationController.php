<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use Carbon\Carbon;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        // 1. Hitung Statistik Notifikasi
        $stats = [
            'total'   => Notification::count(),
            'sent'    => Notification::where('is_sent', true)->count(),
            'pending' => Notification::where('is_sent', false)->count(),
            'today'   => Notification::whereDate('created_at', Carbon::today())->count(),
        ];

        // 2. Query Data dengan Pencarian
        // Asumsi: Model Notification memiliki relasi belongTo ke model Report
        $query = Notification::with('report')->latest();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('phone', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
        }

        $notifications = $query->paginate(15);

        return view('admin.notifications.index', compact('stats', 'notifications'));
    }

    public function export(Request $request)
    {
        $query = \App\Models\Notification::with([
            'report:id,code,title,status,category_id',
            'report.category:id,name',
        ])
        ->latest('created_at');

        // Filter opsional
        if ($request->filled('is_sent')) {
            $query->where('is_sent', $request->is_sent);
        }
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $notifications = $query->get();
        $filename = 'data-notifikasi-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($notifications) {
            $h = fopen('php://output', 'w');
            fprintf($h, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($h, [
                'No',
                'Tanggal Kirim',
                'No. Tujuan (HP)',
                'Terkait Laporan',
                'Judul Laporan',
                'Kategori',
                'Status Laporan',
                'Cuplikan Pesan',
                'Status Notifikasi',
                'Dikirim Pada',
            ], ';');

            $statusLaporanLabel = [
                'pending'               => 'Baru',
                'in_progress'           => 'Diproses',
                'under_review'          => 'Menunggu Verifikasi',
                'waiting_for_materials' => 'Menunggu Material',
                'completed'             => 'Selesai',
                'rejected'              => 'Ditolak',
            ];

            $no = 1;
            foreach ($notifications as $notif) {
                // Cuplikan pesan maks 100 karakter
                $cuplikan = mb_strlen($notif->message) > 100
                    ? mb_substr($notif->message, 0, 97) . '...'
                    : $notif->message;

                // Hapus newline dari cuplikan agar tidak merusak CSV
                $cuplikan = str_replace(["\r\n", "\r", "\n"], ' ', $cuplikan);

                fputcsv($h, [
                    $no++,
                    $notif->created_at->format('d/m/Y H:i'),
                    $notif->phone,
                    $notif->report?->code ?? '—',
                    $notif->report?->title ?? '—',
                    $notif->report?->category?->name ?? '—',
                    $statusLaporanLabel[$notif->report?->status ?? ''] ?? '—',
                    $cuplikan,
                    $notif->is_sent ? 'Terkirim' : 'Gagal / Pending',
                    $notif->sent_at
                        ? \Carbon\Carbon::parse($notif->sent_at)->format('d/m/Y H:i')
                        : '—',
                ], ';');
            }

            fclose($h);
        }, $filename, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}