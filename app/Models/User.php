<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'identifier', 'identifier_type',
        'password', 'picture', 'role',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return ['password' => 'hashed'];
    }

    // ── Relations ──────────────────────────────────────────
    public function citizen(): HasOne
    {
        return $this->hasOne(Citizen::class);
    }

    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }

    public function districtChief(): HasOne
    {
        return $this->hasOne(District_Chief::class);
    }

    public function regent(): HasOne
    {
        return $this->hasOne(Regent::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function rewardClaims(): HasMany
    {
        return $this->hasMany(RewardClaim::class);
    }

    // ── Helpers ────────────────────────────────────────────
    public function isCitizen(): bool       { return $this->role === 'citizen'; }
    public function isEmployee(): bool      { return $this->role === 'employee'; }
    public function isDistrictChief(): bool { return $this->role === 'district_chief'; }
    public function isRegent(): bool        { return $this->role === 'regent'; }
    public function isAdmin(): bool         { return in_array($this->role, ['admin', 'super_admin']); }

    public function profile(): HasOne
    {
        return match($this->role) {
            'citizen'        => $this->citizen(),
            'employee'       => $this->employee(),
            'district_chief' => $this->districtChief(),
            'regent'         => $this->regent(),
            default          => $this->citizen(),
        };
    }
}
