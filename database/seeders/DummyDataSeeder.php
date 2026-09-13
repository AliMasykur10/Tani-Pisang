<?php

namespace Database\Seeders;

use App\Models\Lahan;
use App\Models\Transaction;
use App\Models\ProgressLog;
use App\Models\TroubleReport;
use App\Models\Schedule;
use App\Models\PanenCycle;
use App\Models\AnakanRecord;
use App\Models\User;
use Illuminate\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $lahans = Lahan::all();
        $admin = User::where('role', 'admin')->first();

        if ($lahans->isEmpty() || !$admin) {
            $this->command->warn('Belum ada Lahan atau Admin. Jalankan LahanSeeder dulu.');
            return;
        }

        $kategoriPengeluaran = ['Pupuk', 'Tenaga Kerja', 'Obat', 'Sewa Alat', 'Transportasi'];

        foreach ($lahans as $lahan) {
            // Transaksi 6 bulan ke belakang, bervariasi
            for ($bulan = 5; $bulan >= 0; $bulan--) {
                $tanggalDasar = now()->subMonths($bulan);

                // 3-6 pengeluaran per bulan
                foreach (range(1, rand(3, 6)) as $i) {
                    Transaction::create([
                        'lahan_id' => $lahan->id,
                        'user_id' => $admin->id,
                        'jenis' => 'pengeluaran',
                        'kategori' => $kategoriPengeluaran[array_rand($kategoriPengeluaran)],
                        'jumlah' => rand(50, 500) * 1000,
                        'is_cash' => true,
                        'tanggal' => $tanggalDasar->copy()->addDays(rand(1, 27)),
                        'keterangan' => 'Data dummy untuk preview',
                    ]);
                }

                // Kadang ada pemasukan kecil (bukan panen)
                if (rand(0, 1)) {
                    Transaction::create([
                        'lahan_id' => $lahan->id,
                        'user_id' => $admin->id,
                        'jenis' => 'pemasukan',
                        'kategori' => 'Penjualan Bibit/Anakan',
                        'jumlah' => rand(100, 300) * 1000,
                        'is_cash' => true,
                        'tanggal' => $tanggalDasar->copy()->addDays(rand(1, 27)),
                        'keterangan' => 'Data dummy untuk preview',
                    ]);
                }
            }

            // Progress log tiap minggu, 8 minggu ke belakang
            foreach (range(1, 8) as $i) {
                ProgressLog::create([
                    'lahan_id' => $lahan->id,
                    'user_id' => $admin->id,
                    'tanggal' => now()->subWeeks($i),
                    'keterangan' => collect([
                        'Kondisi daun sehat, warna hijau tua.',
                        'Ada beberapa daun kuning, sudah dipangkas.',
                        'Pertumbuhan normal, tinggi bertambah sekitar 15cm.',
                        'Penyiraman rutin dilakukan, tanah cukup lembab.',
                    ])->random(),
                ]);
            }

            // Trouble report — beberapa selesai, satu aktif
            TroubleReport::create([
                'lahan_id' => $lahan->id,
                'user_id' => $admin->id,
                'judul' => 'Serangan ulat daun',
                'deskripsi' => 'Ditemukan beberapa daun berlubang akibat ulat.',
                'urgensi' => 'sedang',
                'status' => 'selesai',
                'selesai_at' => now()->subDays(rand(10, 20)),
                'created_at' => now()->subDays(rand(20, 30)),
            ]);

            if (rand(0, 1)) {
                TroubleReport::create([
                    'lahan_id' => $lahan->id,
                    'user_id' => $admin->id,
                    'judul' => 'Tanah tergenang setelah hujan',
                    'deskripsi' => 'Drainase perlu dicek, air tidak surut dalam 2 hari.',
                    'urgensi' => 'tinggi',
                    'status' => 'dilaporkan',
                ]);
            }

            // Jadwal — beberapa aktif, satu terlewat
            Schedule::create([
                'lahan_id' => $lahan->id,
                'jenis' => 'Pemupukan',
                'recurring_pattern' => 'dua_mingguan',
                'next_date' => now()->addDays(rand(3, 10)),
                'status' => 'aktif',
            ]);
            Schedule::create([
                'lahan_id' => $lahan->id,
                'jenis' => 'Semprot Obat',
                'recurring_pattern' => 'mingguan',
                'next_date' => now()->subDays(2), // sengaja terlewat
                'status' => 'aktif',
            ]);

            // Panen — kalau lahan sudah fase panen/perawatan, buat 1-2 siklus
            if (in_array($lahan->fase_saat_ini, ['perawatan', 'panen'])) {
                foreach (range(1, rand(1, 2)) as $siklus) {
                    $jumlahPohon = $lahan->estimasi_jumlah_pohon - rand(0, 15);
                    $hasilKg = $jumlahPohon * (rand(8, 15));
                    $hargaPerKg = rand(4000, 6000);

                    $panen = PanenCycle::create([
                        'lahan_id' => $lahan->id,
                        'nomor_siklus' => $siklus,
                        'tanggal_panen' => now()->subMonths(6 - $siklus * 2),
                        'jumlah_pohon_produktif' => $jumlahPohon,
                        'total_hasil_kg' => $hasilKg,
                        'harga_per_kg' => $hargaPerKg,
                        'total_pemasukan' => $hasilKg * $hargaPerKg,
                    ]);

                    Transaction::create([
                        'lahan_id' => $lahan->id,
                        'user_id' => $admin->id,
                        'panen_cycle_id' => $panen->id,
                        'jenis' => 'pemasukan',
                        'kategori' => 'Penjualan Panen',
                        'jumlah' => $panen->total_pemasukan,
                        'is_cash' => true,
                        'tanggal' => $panen->tanggal_panen,
                        'keterangan' => "Panen siklus ke-{$siklus} (dummy)",
                    ]);

                    AnakanRecord::create([
                        'panen_cycle_id' => $panen->id,
                        'jumlah_muncul' => rand(150, 250),
                        'jumlah_disisakan' => $jumlahPohon,
                        'jumlah_dijual' => rand(20, 50),
                        'jumlah_dipindah_lahan_lain' => 0,
                        'jumlah_dibuang' => rand(10, 30),
                    ]);
                }
            }
        }

        $this->command->info('Dummy data berhasil dibuat untuk ' . $lahans->count() . ' lahan.');
    }
}
