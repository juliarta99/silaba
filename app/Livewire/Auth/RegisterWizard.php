<?php

namespace App\Livewire\Auth;

use App\Models\Citizen;
use App\Models\User;
use App\Services\NikValidator;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Validate;
use Livewire\Component;

class RegisterWizard extends Component
{
    // ── Wizard state ─────────────────────────────────────────────────────
    public int $step = 1;

    // ── Step 1 ────────────────────────────────────────────────────────────
    #[Validate('required|digits:16|unique:users,identifier')]
    public string $nik = '';

    #[Validate('required|date|before:-17 years')]
    public string $birth_date = '';

    #[Validate('required|string|min:3|max:100')]
    public string $name = '';

    public ?string $genderHint = null;

    // ── Step 2 ────────────────────────────────────────────────────────────
    #[Validate('required|email|unique:citizens,email')]
    public string $email = '';

    #[Validate('required|in:male,female')]
    public string $gender = '';

    #[Validate('required|string|min:10|max:15|unique:citizens,phone')]
    public string $phone = '';

    #[Validate('required|string|min:8|confirmed')]
    public string $password = '';

    public string $password_confirmation = '';

    #[Validate('accepted')]
    public bool $agree = false;

    // ── Step 3 ────────────────────────────────────────────────────────────
    public array $otpDigits = ['', '', '', '', '', ''];
    public int $otpExpiresInSeconds = 119;
    public ?int $newUserId = null;     // user_id
    public ?int $newCitizenId = null;  // id baris citizens — dipakai untuk lookup otp_code
    public string $maskedPhone = '';
    public bool $isResending = false;
    public ?string $whatsappWarning = null;

    // ── Step 1 → 2 ────────────────────────────────────────────────────────
    public function verifyNik(NikValidator $validator): void
    {
        $this->validate([
            'nik'        => 'required|digits:16|unique:users,identifier',
            'birth_date' => 'required|date|before:-17 years',
            'name'       => 'required|string|min:3|max:100',
        ], [
            'nik.unique'        => 'NIK ini sudah terdaftar. Silakan masuk atau gunakan NIK lain.',
            'birth_date.before' => 'Pendaftar harus berusia minimal 17 tahun.',
        ]);

        $result = $validator->validate($this->nik, $this->birth_date);

        if (! $result['valid']) {
            $this->addError('nik', $result['message']);
            return;
        }

        $this->genderHint = $result['gender_hint'];
        $this->gender = $this->gender ?: $result['gender_hint'];

        $this->step = 2;
    }

    public function backToStep1(): void
    {
        $this->step = 1;
        $this->resetValidation();
    }

    // ── Step 2 → 3 ────────────────────────────────────────────────────────
    public function completeRegistration(WhatsAppService $whatsapp): void
    {
        $this->validate([
            'email'    => 'required|email|unique:citizens,email',
            'gender'   => 'required|in:male,female',
            'phone'    => 'required|string|min:10|max:15|unique:citizens,phone',
            'password' => 'required|string|min:8|confirmed',
            'agree'    => 'accepted',
        ]);

        $user = User::create([
            'name'            => $this->name,
            'identifier'      => $this->nik,
            'identifier_type' => 'nik',
            'password'        => $this->password,
            'role'            => 'citizen',
        ]);

        // ── Generate OTP & simpan LANGSUNG ke kolom otp_code / otp_expires_at ──
        $otpCode = (string) random_int(100000, 999999);

        $hashOtpCode = Hash::make($otpCode);

        $citizen = Citizen::create([
            'user_id'         => $user->id,
            'email'           => $this->email,
            'gender'          => $this->gender,
            'phone'           => $this->phone,
            'birth_date'      => $this->birth_date,
            'points'          => 0,
            'is_active'       => true,
            'otp_code'        => $hashOtpCode,
            'otp_expires_at'  => now()->addMinutes(2),
        ]);

        $this->newUserId    = $user->id;
        $this->newCitizenId = $citizen->id;
        $this->maskedPhone  = $this->maskPhone($this->phone);

        $result = $whatsapp->sendOtp($this->phone, $otpCode, expiresInMinutes: 2);

        if (! $result['success']) {
            $this->whatsappWarning = 'Gagal mengirim OTP otomatis: ' . $result['message']
                . ' Silakan gunakan tombol "Kirim ulang kode" setelah timer habis.';
        }

        $this->otpExpiresInSeconds = 119;
        $this->step = 3;
    }

    public function backToStep2(): void
    {
        $this->step = 2;
        $this->resetValidation();
    }

    // ── Step 3: OTP handling ─────────────────────────────────────────────

    public function updatedOtpDigits(): void
    {
        if (collect($this->otpDigits)->every(fn ($d) => $d !== '')) {
            $this->verifyOtp();
        }
    }

    public function verifyOtp(): void
    {
        $code = implode('', $this->otpDigits);

        if (strlen($code) !== 6 || ! ctype_digit($code)) {
            $this->addError('otp', 'Masukkan 6 digit kode OTP.');
            return;
        }

        $citizen = Citizen::find($this->newCitizenId);

        if (! $citizen) {
            $this->addError('otp', 'Data pendaftaran tidak ditemukan. Silakan daftar ulang.');
            return;
        }

        // ── Cek expired ──────────────────────────────────────────────────
        if (! $citizen->otp_expires_at || now()->greaterThan($citizen->otp_expires_at)) {
            $this->addError('otp', 'Kode OTP sudah kedaluwarsa. Silakan kirim ulang.');
            $this->otpDigits = ['', '', '', '', '', ''];
            return;
        }

        // ── Cek kecocokan kode ───────────────────────────────────────────
        if (!Hash::check($code, $citizen->otp_code)) {
            $this->addError('otp', 'Kode OTP tidak valid.');
            $this->otpDigits = ['', '', '', '', '', ''];
            return;
        }

        // ── Sukses: tandai verified, bersihkan OTP ──────────────────────
        $citizen->update([
            'otp_code'          => null,
            'otp_expires_at'    => null,
            'phone_verified_at' => now(),
        ]);

        Auth::loginUsingId($this->newUserId);
        session()->regenerate();

        $this->dispatch('registration-complete');
        $this->redirectRoute('verify.otp.success', navigate: true);
    }

    public function resendOtp(WhatsAppService $whatsapp): void
    {
        if ($this->otpExpiresInSeconds > 0 || ! $this->newCitizenId) {
            return;
        }

        $this->isResending = true;

        $citizen = Citizen::find($this->newCitizenId);
        if (! $citizen) {
            $this->isResending = false;
            return;
        }

        $otpCode = (string) random_int(100000, 999999);

        $hashOtpCode = Hash::make($otpCode);

        $citizen->update([
            'otp_code'       => $hashOtpCode,
            'otp_expires_at' => now()->addMinutes(2),
        ]);

        $result = $whatsapp->sendOtp($citizen->phone, $otpCode, expiresInMinutes: 2);

        if (! $result['success']) {
            $this->addError('otp', 'Gagal mengirim ulang kode OTP: ' . $result['message']);
            $this->isResending = false;
            return;
        }

        $this->otpDigits = ['', '', '', '', '', ''];
        $this->otpExpiresInSeconds = 119;
        $this->resetValidation('otp');
        $this->whatsappWarning = null;
        $this->isResending = false;

        $this->dispatch('otp-resent');
    }

    public function decrementTimer(): void
    {
        if ($this->otpExpiresInSeconds > 0) {
            $this->otpExpiresInSeconds--;
        }
    }

    // ── Helpers ──────────────────────────────────────────────────────────

    private function maskPhone(string $phone): string
    {
        if (strlen($phone) < 6) return $phone;
        $prefix = substr($phone, 0, 4);
        $suffix = substr($phone, -4);
        return $prefix . str_repeat('*', max(strlen($phone) - 8, 4)) . $suffix;
    }

    public function render()
    {
        return view('livewire.auth.register-wizard');
    }
}