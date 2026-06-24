<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── users ─────────────────────────────────────────────────────────
        // Sesuai ERD:
        //   id, name, identifier (U), identifier_type, password,
        //   picture (N), role, created_at, updated_at
        // Tidak pakai email/email_verified_at/rememberToken karena
        // login menggunakan identifier (NIK / NIP / username)
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('name', 100);

            // Identifier unik: NIK (warga), NIP (petugas/pejabat), atau username (admin)
            $table->string('identifier', 50)->unique();

            // Tipe identifier — menentukan format validasi
            $table->enum('identifier_type', ['nik', 'nip', 'username']);

            $table->string('password', 255);

            // Foto profil — nullable (N)
            $table->string('picture', 255)->nullable();

            // Role user di sistem
            $table->enum('role', [
                'citizen',        // Warga
                'employee',       // Petugas (position ditentukan di tabel employees)
                'district_chief', // TIDAK DIPAKAI — legacy, position di employees
                'regent',         // Camat / Bupati / Sekda
                'admin',          // Admin sistem
                'super_admin',    // Super admin (bisa kelola admin)
            ]);

            $table->timestamps();
        });

        // ── password_reset_tokens ──────────────────────────────────────────
        // Pakai identifier (bukan email) sebagai primary key
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('identifier')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // ── sessions ───────────────────────────────────────────────────────
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};