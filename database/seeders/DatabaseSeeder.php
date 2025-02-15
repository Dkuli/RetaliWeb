<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pilgrim;
use App\Models\Group;
use App\Models\TourLeader;
use App\Models\Questionnaire;
use App\Models\Question;
use App\Models\Luggage;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Create Groups with realistic destinations and capacities
        $cities = ['Makkah', 'Madinah', 'Jeddah'];
        $programs = ['Reguler', 'VIP', 'Eksekutif', 'Premium'];
        $groups = [];
        for ($i = 1; $i <= 20; $i++) {
            $program = $faker->randomElement($programs);
            $maxCapacity = match($program) {
                'VIP' => $faker->numberBetween(15, 25),
                'Eksekutif' => $faker->numberBetween(20, 30),
                'Premium' => $faker->numberBetween(25, 35),
                default => $faker->numberBetween(35, 45),
            };

            $departureDate = Carbon::now()->addMonths($i % 12);
            $duration = $faker->numberBetween(9, 14);

            $groups[] = Group::create([
                'name' => $program . ' ' . $faker->unique()->city() . ' ' . $departureDate->format('F Y'),
                'max_capacity' => $maxCapacity,
                'departure_date' => $departureDate,
                'return_date' => $departureDate->copy()->addDays($duration),
                'description' => "Paket Umrah $program dengan akomodasi hotel bintang " . ($program === 'VIP' ? "5" : "4") . ". Keberangkatan " . $departureDate->format('d F Y'),
                'itinerary' => [
                    'Hari 1' => 'Berkumpul di Bandara. Check-in dan penerbangan ke Jeddah.',
                    'Hari 2' => 'Tiba di Jeddah. Transfer ke hotel di ' . $faker->randomElement($cities) . '. Istirahat dan persiapan.',
                    'Hari 3' => 'Ziarah ke Masjid Nabawi dan sekitarnya. Sholat di Raudhah (jadwal menyesuaikan).',
                    'Hari 4' => 'Ziarah ke situs bersejarah di Madinah: Masjid Quba, Jabal Uhud, dan Masjid Qiblatain.',
                    'Hari 5' => 'Perjalanan ke Makkah. Umrah pertama.',
                    'Hari 6' => 'Ibadah di Masjidil Haram. Tawaf dan Sai.',
                    'Hari 7' => 'Ziarah tempat bersejarah di Makkah: Jabal Tsur, Jabal Nur, dan Museum Ka\'bah.',
                    'Hari 8' => 'Ibadah di Masjidil Haram. Umrah kedua.',
                    'Hari 9' => 'Persiapan kepulangan. Transfer ke Bandara Jeddah.',
                ]
            ]);
        }

        // Create Tour Leaders with realistic data
        $titles = ['Ustadz', 'Ustadzah', 'Dr.', 'H.', 'Hj.'];
        $qualifications = ['Hafiz Quran', 'S1 Syariah', 'S2 Islamic Studies', 'Pengalaman 10+ tahun', 'Sertifikasi Tour Leader'];
        $tourLeaderNames = [
            ['name' => 'Ahmad Fadhil Mahmud', 'gender' => 'male'],
            ['name' => 'Siti Aminah Zahrah', 'gender' => 'female'],
            ['name' => 'Muhammad Rizki Hasan', 'gender' => 'male'],
            ['name' => 'Nur Hidayah Putri', 'gender' => 'female'],
            ['name' => 'Abdul Rahman Hakim', 'gender' => 'male'],
            ['name' => 'Fatima Azzahra Salim', 'gender' => 'female'],
            ['name' => 'Hassan Ali Zainudin', 'gender' => 'male'],
            ['name' => 'Zainab Putri Aisha', 'gender' => 'female'],
            ['name' => 'Ibrahim Malik Fadlan', 'gender' => 'male'],
            ['name' => 'Khadijah Sari Fatima', 'gender' => 'female'],
            ['name' => 'Muhammad Yusuf Hamzah', 'gender' => 'male'],
            ['name' => 'Aisyah Rahmah Zahra', 'gender' => 'female'],
            ['name' => 'Umar Faruq Hasan', 'gender' => 'male'],
            ['name' => 'Ruqayya Safina Putri', 'gender' => 'female'],
            ['name' => 'Bilal Ahmad Zaki', 'gender' => 'male']
        ];

        foreach ($tourLeaderNames as $tl) {
            $title = $faker->randomElement($titles);
            $qualification = $faker->randomElements($qualifications, 2);

            TourLeader::create([
                'name' => $title . ' ' . $tl['name'],
                'email' => strtolower(str_replace(' ', '.', $tl['name'])) . '@example.com',
                'phone' => '08' . $faker->numerify('##########'),
                'password' => Hash::make('password123'),
                'is_active' => $faker->boolean(80),


                'current_group_id' => $groups[$faker->numberBetween(0, count($groups) - 1)]->id
            ]);
        }

        // Create Pilgrims with more realistic data
        $healthConditions = [
            'Tidak ada riwayat penyakit khusus',
            'Hipertensi terkontrol dengan obat',
            'Diabetes tipe 2 (terkontrol)',
            'Asma ringan',
            'Riwayat operasi lutut (2 tahun lalu)',
            'Alergi seafood dan kacang',
            'Kolesterol tinggi (dalam pengobatan)',
            'Arthritis ringan',
            'Migrain',
            'Asam lambung'
        ];

        $cities = [
            'Jakarta', 'Surabaya', 'Bandung', 'Medan', 'Semarang',
            'Yogyakarta', 'Malang', 'Palembang', 'Makassar', 'Padang'
        ];

        // Generate 200 pilgrims (mix of male and female)
        for ($i = 0; $i < 200; $i++) {
            $gender = $faker->randomElement(['male', 'female']);
            $firstName = $gender === 'male' ?
                $faker->randomElement(['Ahmad', 'Muhammad', 'Abdullah', 'Hassan', 'Ibrahim', 'Yusuf', 'Umar', 'Ali', 'Hamzah', 'Ismail']) :
                $faker->randomElement(['Fatima', 'Aisha', 'Khadijah', 'Zainab', 'Maryam', 'Safiya', 'Ruqayya', 'Hafsa', 'Lubna', 'Amira']);

            $lastName = $faker->randomElement(['Al-Farisi', 'Abdullah', 'Rahman', 'Husain', 'Malik', 'Hassan', 'Salim', 'Zainudin', 'Fadlan', 'Hadi']);

            $title = $gender === 'male' ? 'H.' : 'Hj.';
            $useTitle = $faker->boolean(30); // 30% chance to have Haji/Hajjah title

            $age = $faker->numberBetween(25, 70);
            $phone = $faker->randomElement(['0812', '0813', '0815', '0816', '0817', '0818', '0819', '0821', '0822', '0823']) .
                     $faker->numerify('#######');

            $city = $faker->randomElement($cities);
            $address = 'Jl. ' . $faker->streetName() . ' No. ' . $faker->buildingNumber() . ', ' .
                      $faker->randomElement(['RT 02/RW 03', 'RT 05/RW 02', 'RT 03/RW 04']) . ', ' .
                      $city . ', ' . $faker->postcode();

            $pilgrim = Pilgrim::create([
                'name' => ($useTitle ? $title . ' ' : '') . $firstName . ' ' . $lastName,
                'phone' => $phone,
                'gender' => $gender,
                'health_notes' => $faker->randomElement($healthConditions),


            ]);

            // Assign to random group
            $group = $groups[$faker->numberBetween(0, count($groups) - 1)];
            $pilgrim->group()->attach($group->id);

            // Create luggage records

        }

        // Create comprehensive questionnaires
        $questionnaires = [
            [
                'title' => 'Evaluasi Pelayanan Umrah',
                'description' => 'Kuesioner evaluasi menyeluruh tentang kualitas pelayanan ibadah umrah',
                'questions' => [
                    ['question' => 'Bagaimana penilaian Anda terhadap pelayanan tour leader?', 'type' => 'rating'],
                    ['question' => 'Bagaimana kualitas hotel di Makkah?', 'type' => 'rating'],
                    ['question' => 'Bagaimana kualitas hotel di Madinah?', 'type' => 'rating'],
                    ['question' => 'Bagaimana kualitas transportasi selama perjalanan?', 'type' => 'rating'],
                    ['question' => 'Bagaimana kualitas katering dan makanan yang disediakan?', 'type' => 'rating'],
                    ['question' => 'Apakah jadwal perjalanan sesuai dengan itinerary?', 'type' => 'yes_no'],
                    ['question' => 'Bagaimana pelayanan di bandara?', 'type' => 'rating'],
                    ['question' => 'Apakah Anda puas dengan pelayanan visa?', 'type' => 'yes_no'],
                    ['question' => 'Apa saran Anda untuk perbaikan layanan?', 'type' => 'text']
                ]
            ],
            [
                'title' => 'Evaluasi Akomodasi dan Fasilitas',
                'description' => 'Kuesioner detail tentang kualitas akomodasi dan fasilitas yang disediakan',
                'questions' => [
                    ['question' => 'Apakah kamar hotel sesuai dengan yang dijanjikan?', 'type' => 'yes_no'],
                    ['question' => 'Bagaimana kebersihan kamar hotel?', 'type' => 'rating'],
                    ['question' => 'Bagaimana lokasi hotel terhadap Masjid?', 'type' => 'rating'],
                    ['question' => 'Apakah fasilitas hotel memadai?', 'type' => 'yes_no'],
                    ['question' => 'Bagaimana kualitas AC di kamar hotel?', 'type' => 'rating'],
                    ['question' => 'Apakah Anda mengalami masalah dengan kamar hotel?', 'type' => 'yes_no'],
                    ['question' => 'Jika ada masalah, jelaskan:', 'type' => 'text'],
                    ['question' => 'Saran untuk perbaikan akomodasi:', 'type' => 'text']
                ]
            ],
            [
                'title' => 'Feedback Bimbingan Ibadah',
                'description' => 'Evaluasi kualitas bimbingan ibadah dan spiritual',
                'questions' => [
                    ['question' => 'Bagaimana kualitas bimbingan manasik?', 'type' => 'rating'],
                    ['question' => 'Apakah pembimbing memberikan penjelasan yang mudah dipahami?', 'type' => 'yes_no'],
                    ['question' => 'Bagaimana kualitas materi bimbingan yang diberikan?', 'type' => 'rating'],
                    ['question' => 'Apakah waktu bimbingan mencukupi?', 'type' => 'yes_no'],
                    ['question' => 'Bagaimana penguasaan materi pembimbing?', 'type' => 'rating'],
                    ['question' => 'Apakah pembimbing responsif terhadap pertanyaan?', 'type' => 'yes_no'],
                    ['question' => 'Saran untuk peningkatan kualitas bimbingan:', 'type' => 'text']
                ]
            ],
            [
                'title' => 'Evaluasi Transportasi dan Logistik',
                'description' => 'Penilaian terhadap layanan transportasi dan penanganan logistik',
                'questions' => [
                    ['question' => 'Bagaimana kondisi bus yang digunakan?', 'type' => 'rating'],
                    ['question' => 'Apakah jadwal transportasi tepat waktu?', 'type' => 'yes_no'],
                    ['question' => 'Bagaimana pelayanan supir bus?', 'type' => 'rating'],
                    ['question' => 'Apakah penanganan bagasi memuaskan?', 'type' => 'yes_no'],
                    ['question' => 'Bagaimana kondisi AC di bus?', 'type' => 'rating'],
                    ['question' => 'Apakah ada kehilangan bagasi?', 'type' => 'yes_no'],
                    ['question' => 'Saran untuk transportasi:', 'type' => 'text']
                ]
            ],
            [
                'title' => 'Evaluasi Katering dan Konsumsi',
                'description' => 'Penilaian kualitas makanan dan layanan katering',
                'questions' => [
                    ['question' => 'Bagaimana kualitas makanan yang disajikan?', 'type' => 'rating'],
                    ['question' => 'Apakah porsi makanan mencukupi?', 'type' => 'yes_no'],
                    ['question' => 'Bagaimana variasi menu?', 'type' => 'rating'],
                    ['question' => 'Apakah makanan sesuai selera Indonesia?', 'type' => 'yes_no'],
                    ['question' => 'Bagaimana kebersihan tempat makan?', 'type' => 'rating'],
                    ['question' => 'Apakah jadwal makan tepat waktu?', 'type' => 'yes_no'],
                    ['question' => 'Saran untuk layanan katering:', 'type' => 'text']
                ]
            ]
        ];

        // Create questionnaires with status variations
        foreach ($questionnaires as $q) {
            $startDate = Carbon::now()->subDays($faker->numberBetween(0, 60));
            $status = $faker->randomElement(['published', 'draft', 'closed', ]);

            $questionnaire = Questionnaire::create([
                'title' => $q['title'],
                'description' => $q['description'],
                'start_date' => $startDate,
                'end_date' => $startDate->copy()->addDays($faker->numberBetween(7, 30)),
                'status' => $status,
                'is_template' => $faker->boolean(20), // 20% chance to be template


            ]);

            // Create questions with proper ordering
            foreach ($q['questions'] as $index => $questionData) {
                Question::create([
                    'questionnaire_id' => $questionnaire->id,
                    'question_text' => $questionData['question'],
                    'type' => $questionData['type'],
                    'options' => $questionData['type'] === 'rating' ? ['1', '2', '3', '4', '5'] :
                                ($questionData['type'] === 'yes_no' ? ['Ya', 'Tidak'] : null),
                    'is_required' => $faker->boolean(80), // 80% chance to be required
                    'order' => $index + 1,

                ]);
            }
        }
    }
}
