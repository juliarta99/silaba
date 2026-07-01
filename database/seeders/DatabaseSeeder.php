<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $now = Carbon::now();
        $defaultPassword = Hash::make('password');

        $this->command->info('Memulai proses seeding data besar... Mohon tunggu.');

        // ==========================================
        // 1. DATA DINAS (DEPARTMENTS)
        // ==========================================
        $departmentsData = [
            ['name' => 'Dinas Pekerjaan Umum dan Penataan Ruang', 'code' => 'PUPR'],
            ['name' => 'Dinas Lingkungan Hidup dan Kebersihan', 'code' => 'DLHK'],
            ['name' => 'Dinas Perhubungan', 'code' => 'DISHUB'],
            ['name' => 'Satuan Polisi Pamong Praja', 'code' => 'SATPOLPP'],
            ['name' => 'Badan Penanggulangan Bencana Daerah', 'code' => 'BPBD'],
        ];

        $departmentIds = [];
        foreach ($departmentsData as $dept) {
            $departmentIds[$dept['code']] = DB::table('departments')->insertGetId([
                'name' => $dept['name'],
                'code' => $dept['code'],
                'phone' => $faker->unique()->numerify('0361-######'),
                'email' => strtolower($dept['code']) . '@badungkab.go.id',
                'address' => 'Pusat Pemerintahan Kabupaten Badung, Mangupura',
                'created_at' => $now, 'updated_at' => $now
            ]);
        }

        // ==========================================
        // 2. DATA KECAMATAN (DISTRICTS)
        // ==========================================
        $districtsData = ['Kuta', 'Kuta Selatan', 'Kuta Utara', 'Mengwi', 'Abiansemal', 'Petang'];
        $districtIds = [];
        
        foreach ($districtsData as $dist) {
            $districtIds[] = DB::table('districts')->insertGetId([
                'name' => $dist,
                'slug' => Str::slug($dist),
                'email' => Str::slug($dist) . '@badungkab.go.id',
                'phone' => $faker->unique()->numerify('0361-######'),
                'created_at' => $now, 'updated_at' => $now
            ]);
        }

        // ==========================================
        // 3. KATEGORI & TAGS
        // ==========================================
        $categoriesData = [
            ['dept' => 'PUPR', 'name' => 'Jalan Rusak/Berlubang'],
            ['dept' => 'PUPR', 'name' => 'Jembatan Rusak'],
            ['dept' => 'DLHK', 'name' => 'Penumpukan Sampah'],
            ['dept' => 'DLHK', 'name' => 'Penebangan Pohon Liar'],
            ['dept' => 'DISHUB', 'name' => 'Lampu Lalu Lintas Mati'],
            ['dept' => 'DISHUB', 'name' => 'Rambu Jalan Rusak'],
            ['dept' => 'SATPOLPP', 'name' => 'Gangguan Ketertiban Umum'],
            ['dept' => 'SATPOLPP', 'name' => 'Pelanggaran Reklame'],
            ['dept' => 'BPBD', 'name' => 'Pohon Tumbang'],
            ['dept' => 'BPBD', 'name' => 'Banjir/Longsor'],
        ];

        $categoryIds = [];
        foreach ($categoriesData as $cat) {
            $categoryIds[] = DB::table('categories')->insertGetId([
                'department_id' => $departmentIds[$cat['dept']],
                'name' => $cat['name'],
                'slug' => Str::slug($cat['name']),
                'description' => 'Kategori pelaporan terkait ' . strtolower($cat['name']),
                'created_at' => $now, 'updated_at' => $now
            ]);
        }

        $tagIds = [];
        $tags = ['Darurat', 'Fasilitas Umum', 'Lalu Lintas', 'Lingkungan', 'Keamanan', 'Infrastruktur', 'Bencana', 'Pelanggaran'];
        foreach ($tags as $tag) {
            $tagIds[] = DB::table('tags')->insertGetId([
                'name' => $tag, 'slug' => Str::slug($tag),
                'created_at' => $now, 'updated_at' => $now
            ]);
        }

        // ==========================================
        // 4. USERS: SUPER ADMIN & REGENT
        // ==========================================
        DB::table('users')->insert([
            'name' => 'Super Administrator',
            'identifier' => 'admin_pusat',
            'identifier_type' => 'username',
            'password' => $defaultPassword,
            'role' => 'super_admin',
            'created_at' => $now, 'updated_at' => $now
        ]);

        $regentUserId = DB::table('users')->insertGetId([
            'name' => 'Bupati Badung',
            'identifier' => '197001012000011001',
            'identifier_type' => 'nip',
            'password' => $defaultPassword,
            'role' => 'regent',
            'created_at' => $now, 'updated_at' => $now
        ]);
        DB::table('regents')->insert([
            'user_id' => $regentUserId, 'nip' => '197001012000011001',
            'phone' => '081111111111', 'email' => 'bupati@badungkab.go.id',
            'start_year' => 2021, 'created_at' => $now, 'updated_at' => $now
        ]);

        // ==========================================
        // 5. USERS: CAMAT (DISTRICT CHIEFS)
        // ==========================================
        foreach ($districtIds as $index => $distId) {
            $nip = $faker->unique()->numerify('19800#0#200501100#');
            $userId = DB::table('users')->insertGetId([
                'name' => 'Camat ' . $districtsData[$index],
                'identifier' => $nip,
                'identifier_type' => 'nip',
                'password' => $defaultPassword,
                'role' => 'district_chief',
                'created_at' => $now, 'updated_at' => $now
            ]);
            DB::table('district__chiefs')->insert([
                'user_id' => $userId, 'district_id' => $distId, 'nip' => $nip,
                'phone' => $faker->unique()->numerify('0822########'),
                'email' => 'camat.' . Str::slug($districtsData[$index]) . '@badungkab.go.id',
                'start_year' => 2020, 'created_at' => $now, 'updated_at' => $now
            ]);
        }

        // ==========================================
        // 6. USERS: EMPLOYEES (25 Petugas)
        // ==========================================
        $employeeData = [];
        for ($i = 0; $i < 25; $i++) {
            $nip = $faker->unique()->numerify('19900#0#20100110##');
            $userId = DB::table('users')->insertGetId([
                'name' => $faker->name, 'identifier' => $nip,
                'identifier_type' => 'nip', 'password' => $defaultPassword,
                'role' => 'employee', 'created_at' => $now, 'updated_at' => $now
            ]);
            
            $deptId = $faker->randomElement(array_values($departmentIds));
            $empId = DB::table('employees')->insertGetId([
                'user_id' => $userId, 'department_id' => $deptId, 'nip' => $nip,
                'phone' => $faker->unique()->numerify('0833########'),
                'email' => $faker->unique()->safeEmail,
                'position' => 'field_officer', 'status' => 'active',
                'created_at' => $now, 'updated_at' => $now
            ]);
            $employeeData[] = ['id' => $empId, 'dept_id' => $deptId];
        }

        // ==========================================
        // 7. USERS: CITIZENS (50 Warga)
        // ==========================================
        $citizenUserIds = [];
        for ($i = 0; $i < 50; $i++) {
            // Citizen pertama kita buat spesifik untuk Anda agar mudah login
            $nik = ($i === 0) ? '5171012345678901' : $faker->unique()->numerify('5171############');
            $name = ($i === 0) ? 'Si Ngurah Putu Juliarta' : $faker->name;

            $userId = DB::table('users')->insertGetId([
                'name' => $name, 'identifier' => $nik,
                'identifier_type' => 'nik', 'password' => $defaultPassword,
                'role' => 'citizen', 'created_at' => $now, 'updated_at' => $now
            ]);
            $citizenUserIds[] = $userId;

            DB::table('citizens')->insert([
                'user_id' => $userId,
                'phone' => $faker->unique()->numerify('0812########'),
                'email' => $faker->unique()->safeEmail,
                'is_active' => 1,
                'otp_code' => Hash::make($faker->numerify('######')),
                'phone_verified_at' => $now->copy()->subDays(rand(1, 30)),
                'points' => rand(0, 500),
                'created_at' => $now, 'updated_at' => $now
            ]);
        }

        // ==========================================
        // 8. GENERATE 150 REPORTS
        // ==========================================
        $statuses = ['pending', 'in_progress', 'completed', 'rejected', 'under_review'];
        $priorities = ['low', 'medium', 'high', 'critical'];

        for ($i = 1; $i <= 150; $i++) {
            $catId = $faker->randomElement($categoryIds);
            $status = $faker->randomElement($statuses);
            
            // Generate koordinat acak di sekitar Badung
            $lat = $faker->latitude(-8.8, -8.4);
            $lng = $faker->longitude(115.15, 115.25);
            $reportDate = $now->copy()->subDays(rand(1, 60));

            $reportId = DB::table('reports')->insertGetId([
                'user_id' => $faker->randomElement($citizenUserIds),
                'category_id' => $catId,
                'district_id' => $faker->randomElement($districtIds),
                'code' => 'TKT-' . $reportDate->year . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'title' => $faker->sentence(6),
                'description' => $faker->paragraph(3),
                'location' => $faker->streetAddress . ', Badung',
                'latitude' => $lat, 'longitude' => $lng,
                'priority' => $faker->randomElement($priorities),
                'status' => $status,
                'created_at' => $reportDate, 'updated_at' => $reportDate
            ]);

            // Add 1-3 Tags
            $randomTags = $faker->randomElements($tagIds, rand(1, 3));
            foreach ($randomTags as $tId) {
                DB::table('report_tags')->insert([
                    'report_id' => $reportId, 'tag_id' => $tId,
                    'created_at' => $reportDate, 'updated_at' => $reportDate
                ]);
            }

            // Dummy Evidence
            DB::table('report_evidence')->insert([
                'report_id' => $reportId,
                'file_path' => 'evidences/dummy_evidence_' . rand(1, 5) . '.jpg',
                'file_type' => 'photo',
                'created_at' => $reportDate, 'updated_at' => $reportDate
            ]);

            // Jika laporan diproses/selesai, tambahkan assignment & progress
            if (in_array($status, ['in_progress', 'completed', 'under_review'])) {
                // Cari department pelapor berdasarkan kategori
                $catDeptId = DB::table('categories')->where('id', $catId)->value('department_id');
                
                // Cari petugas yang cocok dengan department tersebut
                $validEmployees = array_filter($employeeData, fn($e) => $e['dept_id'] == $catDeptId);
                $assignedEmp = empty($validEmployees) 
                    ? $faker->randomElement($employeeData)['id'] 
                    : $faker->randomElement($validEmployees)['id'];

                DB::table('assignments')->insert([
                    'report_id' => $reportId, 'employee_id' => $assignedEmp,
                    'created_at' => $reportDate->copy()->addHours(1), 
                    'updated_at' => $reportDate->copy()->addHours(1)
                ]);

                DB::table('report_progress')->insert([
                    'report_id' => $reportId, 'employee_id' => $assignedEmp,
                    'title' => 'Menuju Lokasi / Penanganan Awal',
                    'description' => 'Tim sedang meluncur ke TKP untuk melakukan pengecekan awal.',
                    'status' => 'in_progress',
                    'created_at' => $reportDate->copy()->addHours(2), 
                    'updated_at' => $reportDate->copy()->addHours(2)
                ]);

                // Jika statusnya completed, tambahkan progress final dan Review
                if ($status === 'completed') {
                    DB::table('report_progress')->insert([
                        'report_id' => $reportId, 'employee_id' => $assignedEmp,
                        'title' => 'Penyelesaian Masalah',
                        'description' => 'Masalah telah berhasil ditangani oleh tim lapangan.',
                        'status' => 'completed',
                        'created_at' => $reportDate->copy()->addDays(1), 
                        'updated_at' => $reportDate->copy()->addDays(1)
                    ]);

                    // Citizen memberi rating (70% peluang memberi review)
                    if (rand(1, 10) > 3) {
                        DB::table('reviews')->insert([
                            'report_id' => $reportId,
                            'rating' => rand(3, 5),
                            'comment' => $faker->randomElement([
                                'Terima kasih atas respon cepatnya.',
                                'Sangat membantu, jalanan sudah bagus lagi.',
                                'Pelayanan memuaskan dari Pemkab Badung.',
                                'Mantap!'
                            ]),
                            'created_at' => $reportDate->copy()->addDays(2), 
                            'updated_at' => $reportDate->copy()->addDays(2)
                        ]);
                    }
                }
            }
        }

        $this->command->info('✅ Selesai! Berhasil men-generate 50 Warga, 25 Petugas, dan 150 Laporan.');
    }
}