<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\District;
use App\Models\Employee;
use App\Models\Report;
use App\Models\ReportEvidence;
use App\Models\User;
use App\Services\AutoAssignmentService;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    const DUPLICATE_RADIUS_METERS = 50;

    // ── Halaman form ──────────────────────────────────────────────────────
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $districts  = District::orderBy('name')->get();

        return view('public.reports.create', compact('categories', 'districts'));
    }

    // ── Simpan laporan ────────────────────────────────────────────────────
    public function store(
        Request $request,
        WhatsAppService $whatsapp,
        AutoAssignmentService $autoAssign
    ) {
        $validated = $request->validate([
            'category_id'      => 'required|exists:categories,id',
            'title'            => 'required|string|max:255',
            'description'      => 'required|string|min:20',
            'location'         => 'required|string|max:500',
            'district_id'      => 'required|exists:districts,id',
            'latitude'         => 'required|numeric|between:-90,90',
            'longitude'        => 'required|numeric|between:-180,180',
            'evidence_files'   => Auth::check() ? 'required|array|min:1|max:5' : 'nullable|array|max:5',
            'evidence_files.*' => 'file|mimes:png,jpg,jpeg,mp4|max:10240',
        ], [
            'category_id.required'    => 'Kategori masalah wajib dipilih.',
            'title.required'          => 'Judul laporan wajib diisi.',
            'description.min'         => 'Deskripsi minimal 20 karakter.',
            'location.required'       => 'Lokasi kejadian wajib diisi.',
            'district_id.required'    => 'Kecamatan wajib dipilih.',
            'latitude.required'       => 'Silakan tentukan titik lokasi pada peta.',
            'evidence_files.required' => 'Minimal 1 foto/video bukti wajib dilampirkan.',
            'evidence_files.*.max'    => 'Ukuran file maksimal 10MB.',
            'evidence_files.*.mimes'  => 'Format file harus PNG, JPG, atau MP4.',
        ]);

        // ── Ambil info pelapor SEBELUM transaction (hindari Auth::user() di dalam closure) ──
        /** @var User|null $authUser */
        $authUser      = Auth::user();
        $isAuth        = $authUser !== null;
        $reporterPhone = $isAuth
            ? ($authUser->citizen?->phone ?? '')
            : ($request->guest_phone ?? '');
        $reporterName  = $isAuth
            ? $authUser->name
            : ($request->guest_name ?? 'Tamu');

        $newReport = null;

        DB::transaction(function () use (
            $validated, $request, $whatsapp, $autoAssign,
            $authUser, $isAuth, $reporterPhone, $reporterName,
            &$newReport
        ) {
            $lat = (float) $validated['latitude'];
            $lng = (float) $validated['longitude'];

            // ── 1. Deteksi duplikat ────────────────────────────────────────
            /** @var Report|null $parentReport */
            $parentReport = Report::whereNotIn('status', ['rejected', 'completed'])
                ->whereNull('parent_report_id')
                ->where('category_id', $validated['category_id'])
                ->whereRaw('
                    (6371000 * acos(
                        cos(radians(?)) * cos(radians(latitude)) *
                        cos(radians(longitude) - radians(?)) +
                        sin(radians(?)) * sin(radians(latitude))
                    )) <= ?
                ', [$lat, $lng, $lat, self::DUPLICATE_RADIUS_METERS])
                ->orderByRaw('
                    (6371000 * acos(
                        cos(radians(?)) * cos(radians(latitude)) *
                        cos(radians(longitude) - radians(?)) +
                        sin(radians(?)) * sin(radians(latitude))
                    )) ASC
                ', [$lat, $lng, $lat])
                ->first();

            $isDuplicate    = ($parentReport instanceof Report);
            $parentReportId = $isDuplicate ? $parentReport->id : null;

            // ── 2. Generate kode tiket ─────────────────────────────────────
            $year  = now()->year;
            $count = Report::whereYear('created_at', $year)->count() + 1;
            do {
                $code = 'TKT-' . $year . '-' . str_pad($count++, 3, '0', STR_PAD_LEFT);
            } while (Report::where('code', $code)->exists());

            // ── 3. Tentukan prioritas ──────────────────────────────────────
            $priorityMap = [
                'low'      => 'medium',
                'medium'   => 'high',
                'high'     => 'critical',
                'critical' => 'critical',
            ];

            $priority = 'medium';
            if ($isDuplicate && $parentReport instanceof Report) {
                $priority = $priorityMap[$parentReport->priority] ?? 'high';
                $parentReport->update(['priority' => $priority]);
            }

            // ── 4. Hitung SLA deadline ─────────────────────────────────────
            $slaDeadline  = $autoAssign->calculateSlaDeadline($priority);
            $slaFormatted = $slaDeadline->translatedFormat('d M Y, H:i');

            if ($isDuplicate && $parentReport instanceof Report) {
                $parentReport->update(['sla_deadline' => $slaDeadline]);
            }

            // ── 5. Buat laporan baru ───────────────────────────────────────
            /** @var Report $newReport */
            $newReport = Report::create([
                'user_id'          => $isAuth ? $authUser->id : null,
                'guest_name'       => $isAuth ? null : $request->guest_name,
                'guest_phone'      => $isAuth ? null : $request->guest_phone,
                'category_id'      => $validated['category_id'],
                'code'             => $code,
                'title'            => $validated['title'],
                'description'      => $validated['description'],
                'location'         => $validated['location'],
                'district_id'      => $validated['district_id'],
                'latitude'         => $lat,
                'longitude'        => $lng,
                'priority'         => $priority,
                'sla_deadline'     => $isDuplicate ? null : $slaDeadline,
                'parent_report_id' => $parentReportId,
                'status'           => 'pending',
            ]);

            // ── 6. Simpan bukti foto/video ─────────────────────────────────
            if ($request->hasFile('evidence_files')) {
                foreach ($request->file('evidence_files') as $file) {
                    $path = $file->store('evidences/' . $newReport->id, 'public');
                    ReportEvidence::create([
                        'report_id' => $newReport->id,
                        'file_path' => $path,
                        'file_type' => str_starts_with($file->getMimeType(), 'video/') ? 'video' : 'photo',
                    ]);
                }
            }

            // ── 7. Auto-assignment ─────────────────────────────────────────
            // $targetReport selalu Report (bukan null) karena:
            // - jika duplikat: $parentReport sudah dicek instanceof Report
            // - jika baru: $newReport baru saja dibuat
            $targetReport = ($isDuplicate && $parentReport instanceof Report)
                ? $parentReport
                : $newReport;

            /** @var Employee|null $assignedOfficer */
            $assignedOfficer = $autoAssign->assign($targetReport);

            // Ekstrak ke variabel scalar — hindari nullable chain di dalam string
            $officerName  = null;
            $officerPhone = null;
            if ($assignedOfficer instanceof Employee) {
                $officerUser  = $assignedOfficer->user;
                $officerName  = ($officerUser instanceof User) ? $officerUser->name : 'Petugas';
                $officerPhone = $assignedOfficer->phone ?: null;
            }

            // ── 8. Notifikasi WhatsApp ─────────────────────────────────────
            $officerLine = ($officerName !== null)
                ? "👷 Petugas *{$officerName}* telah ditugaskan.\n\n"
                : '';

            if ($isDuplicate && $parentReport instanceof Report) {

                // WA ke pelapor baru
                if ($reporterPhone !== '') {
                    $whatsapp->send($reporterPhone,
                        "📋 *Laporan Diterima — SILABU*\n\n"
                        . "Halo {$reporterName}!\n\n"
                        . "📌 Nomor Tiket: *{$code}*\n"
                        . "📍 Lokasi: {$validated['location']}\n\n"
                        . "ℹ️ Laporan Anda digabungkan dengan tiket *{$parentReport->code}* "
                        . "dan prioritas penanganan dinaikkan ke *" . ucfirst($priority) . "*.\n\n"
                        . $officerLine
                        . "Anda akan menerima update progress via WhatsApp ini."
                    );
                }

                // WA ke pelapor asli (parent)
                $parentUser  = $parentReport->user;
                $parentPhone = ($parentUser instanceof User)
                    ? ($parentUser->citizen?->phone ?? '')
                    : ($parentReport->guest_phone ?? '');
                $parentName  = ($parentUser instanceof User)
                    ? $parentUser->name
                    : ($parentReport->guest_name ?? 'Warga');

                if ($parentPhone !== '' && $parentPhone !== $reporterPhone) {
                    $officerLineParent = ($officerName !== null)
                        ? "👷 Petugas *{$officerName}* sedang menangani laporan Anda.\n\n"
                        : '';

                    $whatsapp->send($parentPhone,
                        "🔔 *Update Laporan {$parentReport->code} — SILABU*\n\n"
                        . "Halo {$parentName}, ada warga lain yang melaporkan masalah serupa. "
                        . "Prioritas penanganan dinaikkan ke *" . ucfirst($priority) . "*.\n\n"
                        . "🗓 Target penyelesaian: {$slaFormatted}\n\n"
                        . $officerLineParent
                        . "Anda akan menerima update progress via WhatsApp ini."
                    );
                }

                // WA ke petugas
                if ($officerPhone !== null) {
                    $whatsapp->send($officerPhone,
                        "🔔 *Penugasan Diperbarui — SILABU*\n\n"
                        . "Laporan *{$parentReport->code}* mendapat laporan duplikat baru. "
                        . "Prioritas dinaikkan ke *" . ucfirst($priority) . "*.\n\n"
                        . "📋 Judul: {$parentReport->title}\n"
                        . "📍 Lokasi: {$parentReport->location}\n"
                        . "🗓 Batas SLA: {$slaFormatted}\n\n"
                        . "Segera tindaklanjuti laporan ini."
                    );
                }

            } else {

                // WA ke pelapor (bukan duplikat)
                if ($reporterPhone !== '') {
                    $whatsapp->send($reporterPhone,
                        "✅ *Laporan Diterima — SILABU*\n\n"
                        . "Halo {$reporterName}!\n\n"
                        . "📋 Nomor Tiket: *{$code}*\n"
                        . "📝 Judul: {$validated['title']}\n"
                        . "📍 Lokasi: {$validated['location']}\n"
                        . "⚡ Prioritas: " . ucfirst($priority) . "\n"
                        . "🗓 Target selesai: {$slaFormatted}\n\n"
                        . $officerLine
                        . "Kami akan mengirim update progress via WhatsApp ini."
                    );
                }

                // WA ke petugas
                if ($officerPhone !== null) {
                    $whatsapp->send($officerPhone,
                        "🔔 *Penugasan Baru — SILABU*\n\n"
                        . "Anda mendapat penugasan laporan baru:\n\n"
                        . "📋 Tiket: *{$code}*\n"
                        . "📝 Judul: {$validated['title']}\n"
                        . "📍 Lokasi: {$validated['location']}\n"
                        . "⚡ Prioritas: " . ucfirst($priority) . "\n"
                        . "🗓 Batas SLA: {$slaFormatted}\n\n"
                        . "Segera tindaklanjuti laporan ini di aplikasi SILABU."
                    );
                }
            }
            
            session([
                'last_report_code'  => $newReport->code,
                'last_report_phone' => $this->maskPhone($reporterPhone),
            ]);
        });


        return redirect()->route('reports.success');
    }

    // ── Halaman sukses ────────────────────────────────────────────────────
    public function success()
    {
        $code  = session()->pull('last_report_code');
        $phone = session()->pull('last_report_phone');

        if ($code === null) {
            return redirect()->route('home');
        }

        /** @var Report|null $report */
        $report      = Report::where('code', $code)->with('parentReport')->first();
        $maskedPhone = (string) $phone;

        if ($report === null) {
            return redirect()->route('home');
        }

        return view('public.reports.success', compact('report', 'maskedPhone'));
    }

    // ── Index: Semua laporan publik ───────────────────────────────────────
    public function index(Request $request)
    {
        $query = Report::with(['category', 'district', 'evidences'])
            ->whereNull('parent_report_id')
            ->whereNotIn('status', ['rejected']);

        if ($request->filled('search')) {
            $s = (string) $request->search;
            $query->where(fn ($q) =>
                $q->where('title', 'like', "%{$s}%")
                  ->orWhere('code', 'like', "%{$s}%")
            );
        }

        if ($request->filled('category')) $query->where('category_id', $request->category);
        if ($request->filled('district'))  $query->where('district_id', $request->district);
        if ($request->filled('status'))    $query->where('status', $request->status);

        $reports    = $query->latest()->paginate(9)->withQueryString();
        $categories = Category::orderBy('name')->get();
        $districts  = District::orderBy('name')->get();

        return view('public.reports.index', compact('reports', 'categories', 'districts'));
    }

    // ── Show: Detail laporan publik ───────────────────────────────────────
    public function show(string $code)
    {
        /** @var Report $report */
        $report = Report::where('code', $code)
            ->with([
                'user', 'category.department', 'district', 'tags', 'evidences', 'review',
                'childReports.user', 'parentReport',
                'progresses' => fn ($q) => $q->with(['employee.user', 'employee.department'])->latest(),
                'assignments.employee.user', 'assignments.employee.department',
            ])
            ->firstOrFail();

        /** @var User|null $authUser */
        $authUser = Auth::user();
        $isOwner  = false;

        if ($authUser instanceof User) {
            $isOwner = $report->user_id === $authUser->id
                || $report->childReports->contains('user_id', $authUser->id);
        }

        return view('public.reports.show', compact('report', 'isOwner'));
    }

    // ── Helpers ───────────────────────────────────────────────────────────

    private function maskPhone(string $phone): string
    {
        if (strlen($phone) < 6) return $phone;
        return substr($phone, 0, 4)
             . str_repeat('X', max(strlen($phone) - 8, 4))
             . substr($phone, -4);
    }
}