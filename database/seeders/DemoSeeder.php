<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Data contoh untuk demo dan screenshot (bukan data pribadi).
 * Jalankan: php artisan db:seed --class=DemoSeeder
 */
class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'demo@notive.test'],
            ['name' => 'Demo User', 'password' => 'password']
        );

        if ($user->entries()->exists()) {
            $this->command?->info('Entri demo sudah ada, seeder dilewati.');
            return;
        }

        // [hari yang lalu, judul, mood, isi, tag]
        $entries = [
            [0,  'Pagi yang tenang',             'Tenang',      'Bangun lebih awal dan menikmati teh hangat sebelum mulai bekerja.',            ['rutinitas', 'pagi']],
            [1,  'Akhirnya selesai juga',        'Senang',      'Tugas yang menumpuk minggu ini akhirnya rampung. Rasanya lega sekali.',        ['kerja']],
            [2,  'Jalan sore di taman',          'Tenang',      'Berjalan santai sambil mendengarkan musik. Pikiran terasa lebih ringan.',      ['olahraga', 'rutinitas']],
            [3,  'Rapat yang panjang',           'Lelah',       'Rapat berjalan hampir tiga jam. Pulang dengan kepala penuh.',                  ['kerja']],
            [5,  'Belajar hal baru',             'Bersemangat', 'Mencoba framework baru dan berhasil membuat halaman pertama.',                 ['belajar']],
            [6,  'Hari yang berat',              'Sedih',       'Ada kabar yang kurang menyenangkan hari ini. Butuh waktu untuk menenangkan diri.', ['refleksi']],
            [8,  'Makan malam bersama keluarga', 'Senang',      'Sudah lama tidak makan bersama. Obrolannya hangat dan menyenangkan.',          ['keluarga']],
            [10, 'Macet dan hujan',              'Marah',       'Terjebak macet satu jam di bawah hujan deras. Hari yang melelahkan.',          ['perjalanan']],
            [12, 'Latihan lari pertama',         'Bersemangat', 'Mulai latihan lari pagi. Baru dua kilometer, tapi semangatnya penuh.',         ['olahraga', 'pagi']],
            [15, 'Akhir pekan di rumah',         'Tenang',      'Membaca buku seharian tanpa membuka ponsel. Sederhana tapi menyenangkan.',     ['rutinitas']],
            [18, 'Catatan belajar',              'Bersemangat', 'Merangkum materi minggu ini supaya lebih mudah diulang nanti.',                ['belajar']],
            [21, 'Lelah tapi puas',              'Lelah',       'Minggu yang padat, tapi semua target tercapai.',                               ['kerja', 'refleksi']],
            [25, 'Bertemu teman lama',           'Senang',      'Bertukar cerita setelah bertahun-tahun tidak bertemu.',                        ['teman']],
            [30, 'Merencanakan bulan ini',       'Tenang',      'Menulis daftar target kecil yang realistis untuk bulan ini.',                  ['refleksi', 'belajar']],
            [35, 'Hari yang kacau',              'Marah',       'Banyak hal tidak berjalan sesuai rencana. Besok mulai lagi.',                  ['refleksi']],
            [40, 'Memulai kebiasaan menulis',    'Senang',      'Hari pertama menulis jurnal. Semoga bisa konsisten.',                          ['rutinitas']],
        ];

        foreach ($entries as [$daysAgo, $title, $mood, $body, $tags]) {
            $date = now()->subDays($daysAgo)->setTime(rand(7, 21), rand(0, 59));

            $entry = $user->entries()->create([
                'title'   => $title,
                'content' => '<p>' . e($body) . '</p>',
                'mood'    => $mood,
            ]);

            $entry->forceFill(['created_at' => $date, 'updated_at' => $date])->save();

            $tagIds = collect($tags)
                ->map(fn ($name) => Tag::firstOrCreate(['name' => $name])->id)
                ->all();
            $entry->tags()->sync($tagIds);
        }

        $this->command?->info('Selesai: akun demo@notive.test (password: password) dengan ' . count($entries) . ' entri contoh.');
    }
}
