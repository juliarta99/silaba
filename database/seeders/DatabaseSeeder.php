<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $now   = Carbon::now();
        $pass  = Hash::make('password');

        $this->command->info('Memulai seeding...');

        // ══════════════════════════════════════════════════════
        // 1. DEPARTMENTS
        // ══════════════════════════════════════════════════════
        $depts = [
            ['name' => 'Dinas Pekerjaan Umum dan Penataan Ruang',  'code' => 'PUPR'],
            ['name' => 'Dinas Lingkungan Hidup dan Kebersihan',     'code' => 'DLHK'],
            ['name' => 'Dinas Perhubungan',                         'code' => 'DISHUB'],
            ['name' => 'Satuan Polisi Pamong Praja',                'code' => 'SATPOLPP'],
            ['name' => 'Badan Penanggulangan Bencana Daerah',       'code' => 'BPBD'],
        ];
        $deptIds = [];
        foreach ($depts as $d) {
            $deptIds[$d['code']] = DB::table('departments')->insertGetId([
                'name' => $d['name'], 'code' => $d['code'],
                'phone' => $faker->numerify('0361-######'),
                'email' => strtolower($d['code']) . '@badungkab.go.id',
                'address' => 'Pusat Pemerintahan Kabupaten Badung, Mangupura',
                'created_at' => $now, 'updated_at' => $now,
            ]);
        }
        $this->command->info('✓ Departments (' . count($depts) . ')');

        // ══════════════════════════════════════════════════════
        // 2. DISTRICTS
        // ══════════════════════════════════════════════════════
        $districtNames = ['Kuta', 'Kuta Selatan', 'Kuta Utara', 'Mengwi', 'Abiansemal', 'Petang'];
        $districtIds   = [];
        foreach ($districtNames as $dn) {
            $districtIds[] = DB::table('districts')->insertGetId([
                'name' => $dn, 'slug' => Str::slug($dn),
                'email' => Str::slug($dn) . '@badungkab.go.id',
                'phone' => $faker->unique()->numerify('0361-######'),
                'created_at' => $now, 'updated_at' => $now,
            ]);
        }
        $this->command->info('✓ Districts (' . count($districtNames) . ')');

        // ══════════════════════════════════════════════════════
        // 3. CATEGORIES
        // ══════════════════════════════════════════════════════
        $cats = [
            ['dept' => 'PUPR',      'name' => 'Jalan Rusak/Berlubang'],
            ['dept' => 'PUPR',      'name' => 'Jembatan Rusak'],
            ['dept' => 'PUPR',      'name' => 'Drainase/Saluran Air'],
            ['dept' => 'PUPR',      'name' => 'Trotoar Rusak'],
            ['dept' => 'DLHK',     'name' => 'Penumpukan Sampah'],
            ['dept' => 'DLHK',     'name' => 'Pencemaran Lingkungan'],
            ['dept' => 'DISHUB',   'name' => 'Lampu Lalu Lintas Mati'],
            ['dept' => 'DISHUB',   'name' => 'Rambu Jalan Rusak'],
            ['dept' => 'DISHUB',   'name' => 'Parkir Liar'],
            ['dept' => 'SATPOLPP', 'name' => 'Gangguan Ketertiban Umum'],
            ['dept' => 'SATPOLPP', 'name' => 'Pelanggaran Reklame'],
            ['dept' => 'BPBD',     'name' => 'Pohon Tumbang'],
            ['dept' => 'BPBD',     'name' => 'Banjir/Longsor'],
        ];
        $categoryIds     = [];
        $categoryDeptMap = [];
        foreach ($cats as $c) {
            $id = DB::table('categories')->insertGetId([
                'department_id' => $deptIds[$c['dept']],
                'name'          => $c['name'],
                'slug'          => Str::slug($c['name']),
                'description'   => 'Kategori pelaporan terkait ' . strtolower($c['name']),
                'created_at'    => $now, 'updated_at' => $now,
            ]);
            $categoryIds[]        = $id;
            $categoryDeptMap[$id] = $deptIds[$c['dept']];
        }
        $this->command->info('✓ Categories (' . count($cats) . ')');

        // ══════════════════════════════════════════════════════
        // 4. TAGS
        // ══════════════════════════════════════════════════════
        $tagNames = [
            'Darurat','Fasilitas Umum','Lalu Lintas','Lingkungan',
            'Keamanan','Infrastruktur','Bencana','Pelanggaran',
            'Jalan Rusak','Lubang Aspal','Sampah','Banjir',
            'Penerangan','Pohon Tumbang','PKL','Parkir',
        ];
        $tagIds = [];
        foreach ($tagNames as $tn) {
            $tagIds[] = DB::table('tags')->insertGetId([
                'name' => $tn, 'slug' => Str::slug($tn),
                'created_at' => $now, 'updated_at' => $now,
            ]);
        }
        $this->command->info('✓ Tags (' . count($tagNames) . ')');

        // ══════════════════════════════════════════════════════
        // 5. USERS: SUPER ADMIN + ADMIN
        // ══════════════════════════════════════════════════════
        DB::table('users')->insert([
            ['name' => 'Super Administrator', 'identifier' => 'admin_pusat',
             'identifier_type' => 'username', 'password' => $pass, 'role' => 'super_admin',
             'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Admin SILABU', 'identifier' => 'admin_silabu',
             'identifier_type' => 'username', 'password' => $pass, 'role' => 'admin',
             'created_at' => $now, 'updated_at' => $now],
        ]);

        // ══════════════════════════════════════════════════════
        // 6. REGENT (Bupati)
        // ══════════════════════════════════════════════════════
        $regentUserId = DB::table('users')->insertGetId([
            'name' => 'I Nyoman Giri Prasta', 'identifier' => '197001012000011001',
            'identifier_type' => 'nip', 'password' => $pass, 'role' => 'regent',
            'created_at' => $now, 'updated_at' => $now,
        ]);
        DB::table('regents')->insert([
            'user_id' => $regentUserId, 'nip' => '197001012000011001',
            'phone' => '081111111111', 'email' => 'bupati@badungkab.go.id',
            'status' => 'active', 'start_year' => 2021, 'end_year' => null,
            'created_at' => $now, 'updated_at' => $now,
        ]);

        // ══════════════════════════════════════════════════════
        // 7. DISTRICT CHIEFS (Camat)
        // ══════════════════════════════════════════════════════
        foreach ($districtIds as $idx => $distId) {
            $nip    = $faker->unique()->numerify('1980010120050110' . str_pad($idx + 1, 2, '0', STR_PAD_LEFT));
            $userId = DB::table('users')->insertGetId([
                'name' => 'Camat ' . $districtNames[$idx], 'identifier' => $nip,
                'identifier_type' => 'nip', 'password' => $pass, 'role' => 'district_chief',
                'created_at' => $now, 'updated_at' => $now,
            ]);
            DB::table('district__chiefs')->insert([
                'user_id' => $userId, 'district_id' => $distId, 'nip' => $nip,
                'phone' => $faker->unique()->numerify('0822########'),
                'email' => 'camat.' . Str::slug($districtNames[$idx]) . '@badungkab.go.id',
                'status' => 'active', 'start_year' => 2020, 'end_year' => null,
                'created_at' => $now, 'updated_at' => $now,
            ]);
        }
        $this->command->info('✓ District Chiefs');

        // ══════════════════════════════════════════════════════
        // 8. EMPLOYEES (per department: 1 kadis + 2 supervisor + 4 field officer)
        // ══════════════════════════════════════════════════════
        $employeeData = [];
        foreach (array_keys($deptIds) as $dCode) {
            $dId = $deptIds[$dCode];
            $positions = ['head_of_department', 'supervisor', 'supervisor',
                          'field_officer', 'field_officer', 'field_officer', 'field_officer'];
            foreach ($positions as $pos) {
                $nip    = $faker->unique()->numerify('199#0#0#20100110##');
                $phone  = $faker->unique()->numerify('0833########');
                $userId = DB::table('users')->insertGetId([
                    'name' => $faker->name, 'identifier' => $nip,
                    'identifier_type' => 'nip', 'password' => $pass, 'role' => 'employee',
                    'created_at' => $now, 'updated_at' => $now,
                ]);
                $empId = DB::table('employees')->insertGetId([
                    'user_id' => $userId, 'department_id' => $dId,
                    'nip' => $nip, 'phone' => $phone,
                    'email' => $faker->unique()->safeEmail,
                    'position' => $pos, 'status' => 'active',
                    'created_at' => $now, 'updated_at' => $now,
                ]);
                $employeeData[] = ['id' => $empId, 'dept_id' => $dId, 'position' => $pos, 'phone' => $phone, 'user_id' => $userId];
            }
        }
        $this->command->info('✓ Employees (' . count($employeeData) . ')');

        // ══════════════════════════════════════════════════════
        // 9. CITIZENS (60 warga)
        // ══════════════════════════════════════════════════════
        $citizenData = [];

        // Akun demo
        $demoUserId = DB::table('users')->insertGetId([
            'name' => 'I Wayan Sukarta', 'identifier' => '5171012345678901',
            'identifier_type' => 'nik', 'password' => $pass, 'role' => 'citizen',
            'created_at' => $now, 'updated_at' => $now,
        ]);
        $demoCitizenId = DB::table('citizens')->insertGetId([
            'user_id' => $demoUserId, 'phone' => '081234567890',
            'email' => 'wayan.sukarta@email.com', 'is_active' => 1,
            'phone_verified_at' => $now->copy()->subDays(10),
            'points' => 250, 'created_at' => $now, 'updated_at' => $now,
        ]);
        $citizenData[] = ['user_id' => $demoUserId, 'citizen_id' => $demoCitizenId, 'phone' => '081234567890'];

        for ($i = 0; $i < 59; $i++) {
            $nik    = $faker->unique()->numerify('5171############');
            $phone  = $faker->unique()->numerify('0812########');
            $userId = DB::table('users')->insertGetId([
                'name' => $faker->name, 'identifier' => $nik,
                'identifier_type' => 'nik', 'password' => $pass, 'role' => 'citizen',
                'created_at' => $now, 'updated_at' => $now,
            ]);
            $cId = DB::table('citizens')->insertGetId([
                'user_id' => $userId, 'phone' => $phone,
                'email' => $faker->unique()->safeEmail, 'is_active' => 1,
                'phone_verified_at' => $now->copy()->subDays(rand(1, 60)),
                'points' => rand(0, 400),
                'created_at' => $now, 'updated_at' => $now,
            ]);
            $citizenData[] = ['user_id' => $userId, 'citizen_id' => $cId, 'phone' => $phone];
        }
        $this->command->info('✓ Citizens (' . count($citizenData) . ')');

        // ══════════════════════════════════════════════════════
        // 10. REWARDS + REWARD_VOUCHERS
        // ══════════════════════════════════════════════════════
        $rewardsData = [
            [
                'name' => 'Voucher Pulsa Rp 25.000', 'type' => 'Pulsa & Data', 'pts' => 50,
                'desc' => 'Pulsa untuk semua operator (Telkomsel, XL, Indosat, Tri). Masukkan kode di aplikasi MyTelkomsel/MyXL atau konter resmi.',
                'voucher_prefix' => 'PLR', 'voucher_format' => 'XXXX-XXXX-XXXX', 'voucher_count' => 50,
                'valid_months' => 3,
            ],
            [
                'name' => 'Voucher Belanja Rp 50.000', 'type' => 'Voucher', 'pts' => 100,
                'desc' => 'Berlaku di Indomaret, Alfamart, Circle K. Tunjukkan kode kepada kasir sebelum membayar.',
                'voucher_prefix' => 'VCH', 'voucher_format' => 'XXXX-XXXX', 'voucher_count' => 30,
                'valid_months' => 6,
            ],
            [
                'name' => 'Voucher Kopi Rp 25.000', 'type' => 'F&B', 'pts' => 50,
                'desc' => 'Berlaku di kedai kopi lokal Bali partner SILABU. Tunjukkan QR atau kode kepada barista.',
                'voucher_prefix' => 'FNB', 'voucher_format' => 'XXXX-XXXX', 'voucher_count' => 40,
                'valid_months' => 3,
            ],
            [
                'name' => 'Tiket Garuda Wisnu Kencana', 'type' => 'Wisata', 'pts' => 150,
                'desc' => 'Tiket masuk gratis ke GWK Cultural Park. Tunjukkan kode + KTP di loket tiket. Tidak dapat diwakilkan.',
                'voucher_prefix' => 'TKT', 'voucher_format' => 'XXX-XXX', 'voucher_count' => 20,
                'valid_months' => 6,
            ],
            [
                'name' => 'Merchandise SILABU', 'type' => 'Merchandise', 'pts' => 75,
                'desc' => 'Kaos, topi, atau tote bag eksklusif SILABU. Ambil di kantor SILABU (Gedung Pemkab Badung Lt. 2) Senin–Jumat 08.00–16.00 WITA.',
                'voucher_prefix' => 'MRC', 'voucher_format' => 'XXX-XXX', 'voucher_count' => 25,
                'valid_months' => 1,
            ],
            [
                'name' => 'Voucher Makan Rp 100.000', 'type' => 'F&B', 'pts' => 200,
                'desc' => 'Berlaku di warung dan restoran lokal Bali partner SILABU. Tunjukkan kode kepada kasir.',
                'voucher_prefix' => 'FNB', 'voucher_format' => 'XXXX-XXXX', 'voucher_count' => 20,
                'valid_months' => 3,
            ],
            [
                'name' => 'Sertifikat Penghargaan Warga Peduli', 'type' => 'Penghargaan', 'pts' => 125,
                'desc' => 'Sertifikat resmi dari Pemkab Badung. Akan dicetak dan dikirim ke alamat Anda dalam 14 hari kerja. Pastikan data profil lengkap.',
                'voucher_prefix' => 'SRT', 'voucher_format' => 'XXXX/SLB/YYYY', 'voucher_count' => 100,
                'valid_months' => null, // tidak kadaluarsa
            ],
            [
                'name' => 'Tiket Taman Budaya Bali', 'type' => 'Wisata', 'pts' => 100,
                'desc' => 'Tiket masuk untuk 2 orang. Tunjukkan QR di pintu masuk Taman Budaya Bali, Denpasar.',
                'voucher_prefix' => 'TKT', 'voucher_format' => 'XXX-XXX', 'voucher_count' => 15,
                'valid_months' => 6,
            ],
            [
                'name' => 'Voucher Parkir Gratis 10×', 'type' => 'Transportasi', 'pts' => 75,
                'desc' => 'Voucher 10× parkir gratis di area parkir resmi Pemkab Badung. Tunjukkan QR kepada petugas parkir.',
                'voucher_prefix' => 'PRK', 'voucher_format' => 'XXXX-XXXX', 'voucher_count' => 30,
                'valid_months' => 6,
            ],
            [
                'name' => 'Diskon Pajak Kendaraan 10%', 'type' => 'Layanan Publik', 'pts' => 300,
                'desc' => 'Potongan 10% pajak kendaraan bermotor. Sebutkan kode saat mengurus pajak kendaraan di Samsat Badung. 1 kode untuk 1 kendaraan.',
                'voucher_prefix' => 'REF', 'voucher_format' => 'XXXX-XXXX', 'voucher_count' => 30,
                'valid_months' => 12,
            ],
            [
                'name' => 'Voucher Spa Rp 150.000', 'type' => 'Wellness', 'pts' => 250,
                'desc' => 'Relaksasi di spa dan massage center partner. Hubungi merchant dengan menyebutkan kode ini untuk membuat janji. Konfirmasi H-1 sebelum kunjungan.',
                'voucher_prefix' => 'SPA', 'voucher_format' => 'XXX-XXX', 'voucher_count' => 0, // stok habis
                'valid_months' => 3,
            ],
            [
                'name' => 'Paket Data 10GB', 'type' => 'Pulsa & Data', 'pts' => 100,
                'desc' => 'Voucher data internet 10GB masa aktif 30 hari untuk semua operator. Masukkan kode di aplikasi operator.',
                'voucher_prefix' => 'PLR', 'voucher_format' => 'XXXX-XXXX-XXXX', 'voucher_count' => 0, // stok habis
                'valid_months' => 1,
            ],
        ];

        $rewardIds = [];
        $chars     = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'; // tanpa 0,O,1,I

        foreach ($rewardsData as $r) {
            $rewardId = DB::table('rewards')->insertGetId([
                'name'            => $r['name'],
                'slug'            => Str::slug($r['name']),
                'type'            => $r['type'],
                'points_required' => $r['pts'],
                'description'     => $r['desc'],
                'is_active'       => true,
                'created_at'      => $now, 'updated_at' => $now,
            ]);
            $rewardIds[] = $rewardId;

            // Generate pool voucher
            $usedCodes = [];
            for ($v = 0; $v < $r['voucher_count']; $v++) {
                // Generate kode sesuai format
                $raw = '';
                for ($c = 0; $c < 12; $c++) {
                    $raw .= $chars[random_int(0, strlen($chars) - 1)];
                }

                // Apply format
                if ($r['voucher_format'] === 'XXXX/SLB/YYYY') {
                    $code = substr($raw, 0, 4) . '/SLB/' . $now->year;
                } else {
                    $parts    = explode('-', $r['voucher_format']);
                    $codeParts = [];
                    $pos = 0;
                    foreach ($parts as $part) {
                        $len        = strlen(str_replace('X', '', $part)) === 0 ? strlen($part) : strlen($part);
                        $codeParts[]= substr($raw, $pos, strlen($part));
                        $pos       += strlen($part);
                    }
                    $code = $r['voucher_prefix'] . '-' . implode('-', $codeParts);
                }

                // Pastikan unik
                $attempt = 0;
                while (in_array($code, $usedCodes) || DB::table('reward_vouchers')->where('code', $code)->exists()) {
                    $extra = '';
                    for ($c = 0; $c < 6; $c++) $extra .= $chars[random_int(0, strlen($chars) - 1)];
                    $code = $r['voucher_prefix'] . '-' . substr($extra, 0, 4) . '-' . substr($extra, 4, 4) . '-' . ($v + $attempt++);
                }
                $usedCodes[] = $code;

                $validUntil = $r['valid_months'] !== null
                    ? $now->copy()->addMonths($r['valid_months'])->toDateString()
                    : null;

                DB::table('reward_vouchers')->insert([
                    'reward_id'   => $rewardId,
                    'code'        => $code,
                    'valid_from'  => $now->toDateString(),
                    'valid_until' => $validUntil,
                    'is_claimed'  => false,
                    'created_at'  => $now, 'updated_at' => $now,
                ]);
            }
        }
        $this->command->info('✓ Rewards (12) + Voucher pool');

        // ══════════════════════════════════════════════════════
        // 11. REPORTS (200)
        // ══════════════════════════════════════════════════════
        $statuses   = ['pending', 'pending', 'in_progress', 'in_progress', 'completed',
                       'completed', 'completed', 'rejected', 'under_review', 'waiting_for_materials'];
        $priorities = ['low', 'medium', 'medium', 'high', 'critical'];

        $areas = [
            ['lat' => [-8.78, -8.68], 'lng' => [115.16, 115.24], 'dist' => 'Kuta'],
            ['lat' => [-8.85, -8.75], 'lng' => [115.16, 115.24], 'dist' => 'Kuta Selatan'],
            ['lat' => [-8.66, -8.56], 'lng' => [115.14, 115.22], 'dist' => 'Kuta Utara'],
            ['lat' => [-8.60, -8.50], 'lng' => [115.17, 115.25], 'dist' => 'Mengwi'],
        ];

        $reportCodes = [];
        $titles = [
            'Jalan berlubang besar membahayakan pengendara',
            'Drainase tersumbat menyebabkan banjir',
            'Sampah menumpuk di pinggir jalan',
            'Lampu jalan mati sudah beberapa hari',
            'Pohon tumbang menghalangi akses jalan',
            'Trotoar rusak membahayakan pejalan kaki',
            'Rambu jalan rusak dan tidak terbaca',
            'Pencemaran sungai oleh limbah',
            'Genangan air di persimpangan utama',
            'Fasilitas umum rusak/divandalisme',
            'Tumpukan sampah di area wisata',
            'Bangunan liar di area hijau',
        ];

        for ($i = 1; $i <= 200; $i++) {
            $catId      = $faker->randomElement($categoryIds);
            $status     = $faker->randomElement($statuses);
            $priority   = $faker->randomElement($priorities);
            $citizen    = $faker->randomElement($citizenData);
            $reportDate = $now->copy()->subDays(rand(1, 90));
            $area       = $faker->randomElement($areas);
            $distIdx    = array_search($area['dist'], $districtNames);
            $distId     = $districtIds[$distIdx] ?? $faker->randomElement($districtIds);
            $lat        = $faker->randomFloat(7, $area['lat'][0], $area['lat'][1]);
            $lng        = $faker->randomFloat(7, $area['lng'][0], $area['lng'][1]);
            $code       = 'TKT-' . $reportDate->year . '-' . str_pad($i, 4, '0', STR_PAD_LEFT);
            $slaMap     = ['critical' => 1, 'high' => 3, 'medium' => 7, 'low' => 14];
            $sla        = $reportDate->copy()->addWeekdays($slaMap[$priority])->setTime(17, 0);

            $reportId = DB::table('reports')->insertGetId([
                'user_id'      => $citizen['user_id'],
                'category_id'  => $catId,
                'district_id'  => $distId,
                'code'         => $code,
                'title'        => $faker->randomElement($titles) . ' di ' . $faker->streetName,
                'description'  => $faker->paragraphs(2, true),
                'location'     => $faker->streetAddress . ', ' . $area['dist'] . ', Badung',
                'latitude'     => $lat,
                'longitude'    => $lng,
                'priority'     => $priority,
                'status'       => $status,
                'sla_deadline' => $sla,
                'created_at'   => $reportDate,
                'updated_at'   => $reportDate,
            ]);

            $reportCodes[] = [
                'id' => $reportId, 'code' => $code, 'status' => $status,
                'cat_id' => $catId, 'user_id' => $citizen['user_id'],
                'phone' => $citizen['phone'], 'date' => $reportDate,
            ];

            // Tags (2-4)
            foreach ($faker->randomElements($tagIds, rand(2, 4)) as $tId) {
                DB::table('report_tags')->insert([
                    'report_id' => $reportId, 'tag_id' => $tId,
                    'created_at' => $reportDate, 'updated_at' => $reportDate,
                ]);
            }

            // Evidence (1-3)
            for ($e = 0; $e < rand(1, 3); $e++) {
                DB::table('report_evidence')->insert([
                    'report_id' => $reportId,
                    'file_path' => 'evidences/dummy/sample_' . rand(1, 10) . '.jpg',
                    'file_type' => $faker->randomElement(['photo', 'photo', 'video']),
                    'created_at' => $reportDate, 'updated_at' => $reportDate,
                ]);
            }
        }
        $this->command->info('✓ Reports (200)');

        // ══════════════════════════════════════════════════════
        // 12. ASSIGNMENTS + PROGRESS + NOTIFICATIONS
        // ══════════════════════════════════════════════════════
        $activeStatuses = ['in_progress', 'completed', 'under_review', 'waiting_for_materials'];
        foreach ($reportCodes as $r) {
            if (! in_array($r['status'], $activeStatuses)) continue;

            $deptId  = $categoryDeptMap[$r['cat_id']] ?? null;
            $officers = array_filter($employeeData,
                fn ($e) => $e['dept_id'] == $deptId && $e['position'] === 'field_officer'
            );
            $officer = ! empty($officers)
                ? $faker->randomElement(array_values($officers))
                : $faker->randomElement($employeeData);

            $assignedAt = $r['date']->copy()->addHours(rand(1, 4));

            DB::table('assignments')->insert([
                'report_id' => $r['id'], 'employee_id' => $officer['id'],
                'created_at' => $assignedAt, 'updated_at' => $assignedAt,
            ]);

            DB::table('report_progress')->insert([
                'report_id' => $r['id'], 'employee_id' => $officer['id'],
                'title' => 'Petugas Ditugaskan',
                'description' => 'Laporan telah diteruskan ke petugas lapangan.',
                'status' => 'in_progress',
                'created_at' => $assignedAt, 'updated_at' => $assignedAt,
            ]);

            DB::table('notifications')->insert([
                'report_id' => $r['id'], 'phone' => $r['phone'],
                'message' => "✅ Laporan #{$r['code']} sedang diproses oleh petugas.",
                'is_sent' => 1, 'sent_at' => $assignedAt,
                'created_at' => $assignedAt, 'updated_at' => $assignedAt,
            ]);

            if (in_array($r['status'], ['completed', 'under_review'])) {
                $doneAt = $assignedAt->copy()->addHours(rand(3, 12));
                DB::table('report_progress')->insert([
                    'report_id' => $r['id'], 'employee_id' => $officer['id'],
                    'title' => 'Masalah Diselesaikan',
                    'description' => 'Masalah telah ditangani oleh tim lapangan.',
                    'status' => 'completed',
                    'created_at' => $doneAt, 'updated_at' => $doneAt,
                ]);
                DB::table('notifications')->insert([
                    'report_id' => $r['id'], 'phone' => $r['phone'],
                    'message' => "🎉 Laporan #{$r['code']} telah diselesaikan. Mohon konfirmasi.",
                    'is_sent' => 1, 'sent_at' => $doneAt,
                    'created_at' => $doneAt, 'updated_at' => $doneAt,
                ]);
            }
        }
        $this->command->info('✓ Assignments + Progress + Notifications');

        // ══════════════════════════════════════════════════════
        // 13. REVIEWS (laporan completed, 70% punya review)
        // ══════════════════════════════════════════════════════
        $reviewComments = [
            'Petugas sangat responsif dan cepat menangani masalah.',
            'Pelayanan memuaskan, laporan ditangani dengan baik.',
            'Terima kasih SILABU, masalah di lingkungan kami sudah teratasi!',
            'Cukup bagus, semoga bisa lebih cepat lagi ke depannya.',
            'Petugas ramah dan profesional.',
            null,
        ];
        $reviewCount = 0;
        foreach (array_filter($reportCodes, fn ($r) => $r['status'] === 'completed') as $r) {
            if (! $faker->boolean(70)) continue;
            DB::table('reviews')->insert([
                'report_id'  => $r['id'],
                'user_id'    => $r['user_id'],
                'rating'     => $faker->numberBetween(3, 5),
                'comment'    => $faker->randomElement($reviewComments),
                'created_at' => $r['date']->copy()->addDays(rand(1, 3)),
                'updated_at' => $r['date']->copy()->addDays(rand(1, 3)),
            ]);
            $reviewCount++;
        }
        $this->command->info("✓ Reviews ({$reviewCount})");

        // ══════════════════════════════════════════════════════
        // 14. REWARD CLAIMS (beberapa citizen sudah klaim)
        // ══════════════════════════════════════════════════════
        $claimCount = 0;

        // Klaim untuk demo user (I Wayan Sukarta) — 2 klaim
        foreach ([0, 1] as $ri) {
            $voucher = DB::table('reward_vouchers')
                ->where('reward_id', $rewardIds[$ri])
                ->where('is_claimed', false)
                ->first();
            if ($voucher) {
                DB::table('reward_vouchers')->where('id', $voucher->id)->update(['is_claimed' => true]);
                DB::table('reward_claims')->insert([
                    'user_id'            => $demoUserId,
                    'reward_id'          => $rewardIds[$ri],
                    'reward_voucher_id'  => $voucher->id,
                    'points_used'        => $rewardsData[$ri]['pts'],
                    'created_at'         => $now->copy()->subDays(rand(3, 10)),
                    'updated_at'         => $now->copy()->subDays(rand(3, 10)),
                ]);
                $claimCount++;
            }
        }

        // Klaim random untuk 15 citizen lain
        foreach (array_slice($citizenData, 1, 15) as $c) {
            if (! $faker->boolean(60)) continue;
            $rIdx    = $faker->numberBetween(0, count($rewardIds) - 3); // hindari stok habis (idx 10,11)
            $voucher = DB::table('reward_vouchers')
                ->where('reward_id', $rewardIds[$rIdx])
                ->where('is_claimed', false)
                ->first();
            if (! $voucher) continue;

            DB::table('reward_vouchers')->where('id', $voucher->id)->update(['is_claimed' => true]);
            DB::table('reward_claims')->insert([
                'user_id'           => $c['user_id'],
                'reward_id'         => $rewardIds[$rIdx],
                'reward_voucher_id' => $voucher->id,
                'points_used'       => $rewardsData[$rIdx]['pts'],
                'created_at'        => $now->copy()->subDays(rand(1, 20)),
                'updated_at'        => $now->copy()->subDays(rand(1, 20)),
            ]);
            $claimCount++;
        }
        $this->command->info("✓ Reward Claims ({$claimCount})");

        // ══════════════════════════════════════════════════════
        // RINGKASAN
        // ══════════════════════════════════════════════════════
        $this->command->info('');
        $this->command->info('✅ SEEDING SELESAI!');
        $this->command->table(
            ['Tabel', 'Jumlah'],
            [
                ['departments',      count($depts)],
                ['districts',        count($districtNames)],
                ['categories',       count($cats)],
                ['tags',             count($tagNames)],
                ['employees',        count($employeeData)],
                ['citizens',         count($citizenData)],
                ['reports',          200],
                ['rewards',          12],
                ['reward_vouchers',  '~380 kode'],
                ['reward_claims',    $claimCount],
                ['reviews',          $reviewCount],
            ]
        );
        $this->command->info('');
        $this->command->info('🔑 Login demo:');
        $this->command->info('   Warga    → NIK: 5171012345678901  | pass: password');
        $this->command->info('   Admin    → username: admin_pusat   | pass: password');
        $this->command->info('   Bupati   → NIP: 197001012000011001 | pass: password');
    }
}