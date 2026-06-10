<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Hash;

class Citizen extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'phone', 'email', 'is_active',
        'otp_code', 'otp_expires_at',
        'phone_verified_at', 'points',
    ];

    protected $hidden = ['otp_code'];

    protected function casts(): array
    {
        return [
            'is_active'         => 'boolean',
            'otp_expires_at'    => 'datetime',
            'phone_verified_at' => 'datetime',
        ];
    }

    public function isOtpValid(string $otp): bool
    {
        return Hash::check($otp, $this->otp_code)
            && now()->isBefore($this->otp_expires_at);
    }

    public function isOtpExpired(): bool
    {
        return is_null($this->otp_expires_at)
            || now()->isAfter($this->otp_expires_at);
    }

    public function clearOtp(): void
    {
        $this->update([
            'otp_code'       => null,
            'otp_expires_at' => null,
        ]);
    }
}
