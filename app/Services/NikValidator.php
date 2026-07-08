<?php

namespace App\Services;

/**
 * NikValidator
 * ─────────────────────────────────────────────────────────────────────────
 * Validasi STRUKTUR NIK secara lokal — TIDAK terhubung ke database
 * kependudukan nasional manapun.
 *
 * Ini adalah pendekatan MVP/development. Dukcapil tidak menyediakan API
 * publik untuk developer umum; akses resmi (Web Service Dukcapil) hanya
 * lewat Perjanjian Kerja Sama institusional. Untuk SILABA saat ini, kita
 * cukup validasi format agar mencegah typo & NIK asal-asalan — TANPA
 * memastikan identitas pelapor benar-benar valid secara nasional.
 *
 * Konsekuensi: karena tidak ada nama dari Disdukcapil, field "Nama Lengkap"
 * pada form registrasi WAJIB diisi manual oleh user (bukan readonly).
 *
 * Struktur NIK (16 digit):
 *   digit 1-2   : kode provinsi
 *   digit 3-4   : kode kabupaten/kota
 *   digit 5-6   : kode kecamatan
 *   digit 7-8   : tanggal lahir (perempuan: tanggal + 40)
 *   digit 9-10  : bulan lahir
 *   digit 11-12 : tahun lahir (2 digit terakhir)
 *   digit 13-16 : nomor urut registrasi
 */
class NikValidator
{
    /**
     * @return array{
     *     valid: bool,
     *     message: string|null,
     *     gender_hint: string|null,   // 'male' | 'female' — tebakan dari struktur NIK
     *     province_code: string|null,
     *     regency_code: string|null,
     *     district_code: string|null,
     * }
     */
    public function validate(string $nik, string $birthDate): array
    {
        // ── 1. Cek panjang & tipe ──────────────────────────────────────
        if (strlen($nik) !== 16 || ! ctype_digit($nik)) {
            return $this->fail('Format NIK tidak valid. NIK harus terdiri dari 16 digit angka.');
        }

        // ── 2. Ekstrak komponen NIK ────────────────────────────────────
        $provinceCode = substr($nik, 0, 2);
        $regencyCode  = substr($nik, 2, 2);
        $districtCode = substr($nik, 4, 2);
        $dayRaw        = (int) substr($nik, 6, 2);
        $month         = (int) substr($nik, 8, 2);
        $yearShort     = (int) substr($nik, 10, 2);

        // Kode wilayah tidak boleh 00
        if ($provinceCode === '00' || $regencyCode === '00') {
            return $this->fail('Kode wilayah pada NIK tidak valid.');
        }

        // ── 3. Decode tanggal lahir dari NIK ───────────────────────────
        // Perempuan: tanggal lahir + 40 (konvensi resmi NIK Indonesia)
        $isFemaleCode = $dayRaw > 40;
        $day = $isFemaleCode ? $dayRaw - 40 : $dayRaw;

        if ($day < 1 || $day > 31) {
            return $this->fail('Kode tanggal lahir pada NIK tidak valid.');
        }
        if ($month < 1 || $month > 12) {
            return $this->fail('Kode bulan lahir pada NIK tidak valid.');
        }

        // ── 4. Cocokkan dengan tanggal lahir yang diinput user ─────────
        $inputDate = \DateTime::createFromFormat('Y-m-d', $birthDate);
        if (! $inputDate) {
            return $this->fail('Format tanggal lahir tidak valid.');
        }

        $inputDay   = (int) $inputDate->format('d');
        $inputMonth = (int) $inputDate->format('m');
        $inputYear2 = (int) $inputDate->format('y');

        if ($inputDay !== $day) {
            return $this->fail('Tanggal lahir tidak sesuai dengan kode pada NIK.');
        }
        if ($inputMonth !== $month) {
            return $this->fail('Bulan lahir tidak sesuai dengan kode pada NIK.');
        }
        if ($inputYear2 !== $yearShort) {
            return $this->fail('Tahun lahir tidak sesuai dengan kode pada NIK.');
        }

        // ── 5. Lolos validasi struktur ──────────────────────────────────
        return [
            'valid'         => true,
            'message'       => null,
            'gender_hint'   => $isFemaleCode ? 'female' : 'male',
            'province_code' => $provinceCode,
            'regency_code'  => $regencyCode,
            'district_code' => $districtCode,
        ];
    }

    private function fail(string $message): array
    {
        return [
            'valid'         => false,
            'message'       => $message,
            'gender_hint'   => null,
            'province_code' => null,
            'regency_code'  => null,
            'district_code' => null,
        ];
    }
}