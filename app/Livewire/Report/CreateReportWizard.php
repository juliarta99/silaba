<?php

namespace App\Livewire\Report;

use App\Models\Category;
use App\Models\District;
use App\Models\Report;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateReportWizard extends Component
{
    use WithFileUploads;

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
    public function submit(WhatsAppService $whatsapp): void
    {
        // Validasi berdasarkan step / role
        $rules = [
            'categoryId'  => 'required|exists:categories,id',
            'title'       => 'required|string|max:255',
            'description' => 'required|string|min:20',
            'location'    => 'required|string|max:500',
            'districtId'  => 'required|exists:districts,id',
        ];

        // Foto wajib untuk auth, opsional untuk tamu
        if (Auth::check()) {
            $rules['evidenceFiles']   = 'required|array|min:1';
            $rules['evidenceFiles.*'] = 'file|mimes:png,jpg,jpeg,mp4|max:10240';
        }

        $this->validate($rules, [
            'categoryId.required'   => 'Kategori masalah wajib dipilih.',
            'title.required'        => 'Judul laporan wajib diisi.',
            'description.min'       => 'Deskripsi minimal 20 karakter.',
            'location.required'     => 'Lokasi kejadian wajib diisi.',
            'districtId.required'   => 'Kecamatan wajib dipilih.',
            'evidenceFiles.required'=> 'Minimal 1 foto/video bukti wajib dilampirkan.',
            'evidenceFiles.*.mimes' => 'Format file harus PNG, JPG, atau MP4.',
            'evidenceFiles.*.max'   => 'Ukuran file maksimal 10MB.',
        ]);

        $this->submitting = true;

        DB::transaction(function () use ($whatsapp) {

            // Generate kode unik
            $year  = now()->year;
            $count = Report::whereYear('created_at', $year)->count() + 1;
            do {
                $code = 'TKT-' . $year . '-' . str_pad($count++, 3, '0', STR_PAD_LEFT);
            } while (Report::where('code', $code)->exists());

            // User ID: auth user atau null (tamu)
            $userId = Auth::user()->id ?? null;

            $report = Report::create([
                'user_id'     => $userId,
                'category_id' => $this->categoryId,
                'code'        => $code,
                'title'       => $this->title,
                'description' => $this->description,
                'location'    => $this->location,
                'district_id' => $this->districtId,
                'latitude'    => $this->latitude,
                'longitude'   => $this->longitude,
                'status'      => 'pending',
                'priority'    => 'medium',
                // Data tamu
                'guest_name'  => Auth::check() ? null : $this->guestName,
                'guest_phone' => Auth::check() ? null : $this->guestPhone,
            ]);

            // Simpan bukti foto/video
            foreach ($this->evidenceFiles as $file) {
                $path = $file->store('evidences/' . $report->id, 'public');
                \App\Models\ReportEvidence::create([
                    'report_id' => $report->id,
                    'file_path' => $path,
                    'file_type' => str_starts_with($file->getMimeType(), 'video/') ? 'video' : 'photo',
                ]);
            }

            // Kirim WA notifikasi
            $phone   = Auth::check()
                ? Auth::user()->citizen?->phone
                : $this->guestPhone;

            $name    = Auth::check()
                ? Auth::user()->name
                : $this->guestName;

            if ($phone) {
                $msg = "✅ *Laporan Diterima — SILABU*\n\n"
                     . "Halo {$name}, laporan Anda telah berhasil dikirim!\n\n"
                     . "📋 Nomor Tiket: *{$code}*\n"
                     . "📝 Judul: {$report->title}\n"
                     . "📍 Lokasi: {$report->location}\n"
                     . "⏱ Status: Menunggu Proses\n\n"
                     . "Kami akan mengirim update progress via WhatsApp ini. Terima kasih!";

                $whatsapp->send($phone, $msg);
            }

            // Simpan ke session untuk halaman sukses
            session([
                'last_report_code'  => $code,
                'last_report_phone' => $this->maskPhone($phone ?? ''),
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
            'evidenceFiles'   => 'max:5', // Mencegah lebih dari 5 file sekaligus (seperti di gambar.png)
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
            // Wajib di re-index (array_values) agar urutan index tetap urut (0, 1, 2)
            // sehingga foreach di blade dan Alpine tidak terjadi error "missing key"
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