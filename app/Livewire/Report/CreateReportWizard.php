<?php

namespace App\Livewire\Report;

use App\Models\Category;
use App\Models\District;
use App\Models\Employee;
use App\Models\Report;
use App\Models\ReportEvidence;
use App\Models\User;
use App\Services\AutoAssignmentService;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateReportWizard extends Component
{
    use WithFileUploads;

    const DUPLICATE_RADIUS_METERS = 50;

    // ── Wizard state ──────────────────────────────────────────────────────
    // Auth  → step langsung = 2 (skip step 1 data tamu)
    // Guest → step 1 = Data Pelapor, step 2 = Detail Laporan
    public int $step = 1;

    // ── Step 1: Data Pelapor (GUEST ONLY) ────────────────────────────────
    #[Validate('required|string|min:3|max:100')]
    public string $guestName = '';

    #[Validate('required|string|min:10|max:20')]
    public string $guestPhone = '';

    // ── Step 2: Detail Laporan (AUTH & GUEST) ────────────────────────────
    #[Validate('required|exists:categories,id')]
    public string $categoryId = '';

    #[Validate('required|string|max:255')]
    public string $title = '';

    #[Validate('required|string|min:20')]
    public string $description = '';

    #[Validate('required|string|max:500')]
    public string $location = '';

    #[Validate('required|exists:districts,id')]
    public string $districtId = '';

    #[Validate('nullable|numeric|between:-90,90')]
    public ?string $latitude = null;

    #[Validate('nullable|numeric|between:-180,180')]
    public ?string $longitude = null;

    // File upload via Livewire WithFileUploads
    #[Validate(['evidenceFiles.*' => 'file|mimes:png,jpg,jpeg,mp4|max:10240'])]
    public array $evidenceFiles = [];

    // ── Submission state ──────────────────────────────────────────────────
    public bool $submitting = false;

    // ── Computed ──────────────────────────────────────────────────────────
    public function mount(): void
    {
        // Auth user: skip step 1
        if (Auth::check()) {
            $this->step = 2;
        }
    }

    // ════════════════════════════════════════
    // STEP 1 → 2 (Guest: submit data pelapor)
    // ════════════════════════════════════════
    public function nextStep(): void
    {
        $this->validate([
            'guestName'  => 'required|string|min:3|max:100',
            'guestPhone' => 'required|string|min:10|max:20',
        ], [
            'guestName.required'  => 'Nama pelapor wajib diisi.',
            'guestPhone.required' => 'Nomor WhatsApp wajib diisi.',
            'guestPhone.min'      => 'Nomor WhatsApp minimal 10 digit.',
        ]);

        $this->step = 2;
    }

    public function prevStep(): void
    {
        $this->step = 1;
    }

    // ════════════════════════════════════════
    // SUBMIT LAPORAN
    // ════════════════════════════════════════
    public function submit(WhatsAppService $whatsapp, AutoAssignmentService $autoAssign): void
    {
        // Validasi berdasarkan step / role
        $rules = [
            'categoryId'  => 'required|exists:categories,id',
            'title'       => 'required|string|max:255',
            'description' => 'required|string|min:20',
            'location'    => 'required|string|max:500',
            'districtId'  => 'required|exists:districts,id',
            'latitude'    => 'required|numeric|between:-90,90',
            'longitude'   => 'required|numeric|between:-180,180',
        ];

        // Foto wajib untuk auth, opsional untuk tamu
        if (Auth::check()) {
            $rules['evidenceFiles']   = 'required|array|min:1|max:5';
            $rules['evidenceFiles.*'] = 'file|mimes:png,jpg,jpeg,mp4|max:10240';
        } else {
            $rules['evidenceFiles']   = 'nullable|array|max:5';
            $rules['evidenceFiles.*'] = 'file|mimes:png,jpg,jpeg,mp4|max:10240';
        }

        $this->validate($rules, [
            'categoryId.required'   => 'Kategori masalah wajib dipilih.',
            'title.required'        => 'Judul laporan wajib diisi.',
            'description.min'       => 'Deskripsi minimal 20 karakter.',
            'location.required'     => 'Lokasi kejadian wajib diisi.',
            'districtId.required'   => 'Kecamatan wajib dipilih.',
            'latitude.required'     => 'Silakan tentukan titik lokasi pada peta.',
            'evidenceFiles.required'=> 'Minimal 1 foto/video bukti wajib dilampirkan.',
            'evidenceFiles.*.mimes' => 'Format file harus PNG, JPG, atau MP4.',
            'evidenceFiles.*.max'   => 'Ukuran file maksimal 10MB.',
            'evidenceFiles.max'     => 'Maksimal 5 file gambar/video yang diizinkan.',
        ]);

        $this->submitting = true;

        // Ambil info pelapor SEBELUM transaction
        /** @var User|null $authUser */
        $authUser      = Auth::user();
        $isAuth        = $authUser !== null;
        $reporterPhone = $isAuth ? ($authUser->citizen?->phone ?? '') : $this->guestPhone;
        $reporterName  = $isAuth ? $authUser->name : ($this->guestName ?: 'Tamu');

        DB::transaction(function () use (
            $whatsapp, $autoAssign, $authUser, $isAuth, $reporterPhone, $reporterName
        ) {
            $lat = (float) $this->latitude;
            $lng = (float) $this->longitude;

            // ── 1. Deteksi duplikat ────────────────────────────────────────
            /** @var Report|null $parentReport */
            $parentReport = Report::whereNotIn('status', ['rejected', 'completed'])
                ->whereNull('parent_report_id')
                ->where('category_id', $this->categoryId)
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
                'guest_name'       => $isAuth ? null : $this->guestName,
                'guest_phone'      => $isAuth ? null : $this->guestPhone,
                'category_id'      => $this->categoryId,
                'code'             => $code,
                'title'            => $this->title,
                'description'      => $this->description,
                'location'         => $this->location,
                'district_id'      => $this->districtId,
                'latitude'         => $lat,
                'longitude'        => $lng,
                'priority'         => $priority,
                'sla_deadline'     => $isDuplicate ? null : $slaDeadline,
                'parent_report_id' => $parentReportId,
                'status'           => 'pending',
            ]);

            // ── 6. Simpan bukti foto/video ─────────────────────────────────
            foreach ($this->evidenceFiles as $file) {
                $path = $file->store('evidences/' . $newReport->id, 'public');
                ReportEvidence::create([
                    'report_id' => $newReport->id,
                    'file_path' => $path,
                    'file_type' => str_starts_with($file->getMimeType(), 'video/') ? 'video' : 'photo',
                ]);
            }

            // ── 7. Auto-assignment ─────────────────────────────────────────
            $targetReport = ($isDuplicate && $parentReport instanceof Report)
                ? $parentReport
                : $newReport;

            /** @var Employee|null $assignedOfficer */
            $assignedOfficer = $autoAssign->assign($targetReport);

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
                        . "📍 Lokasi: {$this->location}\n\n"
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
                        . "📝 Judul: {$this->title}\n"
                        . "📍 Lokasi: {$this->location}\n"
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
                        . "📝 Judul: {$this->title}\n"
                        . "📍 Lokasi: {$this->location}\n"
                        . "⚡ Prioritas: " . ucfirst($priority) . "\n"
                        . "🗓 Batas SLA: {$slaFormatted}\n\n"
                        . "Segera tindaklanjuti laporan ini di aplikasi SILABU."
                    );
                }
            }

            // Simpan ke session untuk halaman sukses
            session([
                'last_report_code'  => $newReport->code,
                'last_report_phone' => $this->maskPhone($reporterPhone),
            ]);
        });

        $this->redirectRoute('reports.success');
    }

    private function maskPhone(string $phone): string
    {
        if (strlen($phone) < 6) return $phone;
        return substr($phone, 0, 4)
             . str_repeat('X', max(strlen($phone) - 8, 4))
             . substr($phone, -4);
    }

    // ── Real-time Validation (Berjalan seketika saat user memilih file) ──
    public function updatedEvidenceFiles()
    {
        $this->validate([
            'evidenceFiles'   => 'max:5',
            'evidenceFiles.*' => 'file|mimes:png,jpg,jpeg,mp4|max:10240',
        ], [
            'evidenceFiles.max'       => 'Maksimal 5 file gambar/video yang diizinkan.',
            'evidenceFiles.*.mimes'   => 'Format file harus PNG, JPG, atau MP4.',
            'evidenceFiles.*.max'     => 'Ukuran masing-masing file maksimal 10MB.',
        ]);
    }

    // ── Fungsi Hapus Gambar Spesifik dari Preview ──
    public function removeEvidence($index)
    {
        if (isset($this->evidenceFiles[$index])) {
            unset($this->evidenceFiles[$index]);
            $this->evidenceFiles = array_values($this->evidenceFiles);
        }
    }

    public function render()
    {
        return view('livewire.report.create-report-wizard', [
            'categories' => Category::orderBy('name')->get(),
            'districts'  => District::orderBy('name')->get(),
        ]);
    }
}