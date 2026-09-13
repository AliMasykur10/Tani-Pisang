<?php

namespace Database\Seeders;

use App\Models\Lahan;
use App\Models\Transaction;
use App\Models\ProgressLog;
use App\Models\TroubleReport;
use App\Models\Schedule;
use App\Models\PanenCycle;
use App\Models\AnakanRecord;
use App\Models\Partner;
use App\Models\PartnerAgreement;
use App\Models\Asset;
use App\Models\AssetAllocation;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();

        if (!$admin) {
            $this->command->warn(
                'Belum ada akun Admin. Buat dulu lewat Tinker.'
            );
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | 1. Pastikan minimal 2 lahan
        |--------------------------------------------------------------------------
        */

        if (Lahan::count() < 2) {
            Lahan::create([
                'nama' => 'Lahan Contoh 2',
                'luas_panjang_m' => 20,
                'luas_lebar_m' => 25,
                'jarak_tanam_m' => 2,
                'jarak_pagar_m' => 1,
                'estimasi_jumlah_pohon' => 120,
                'fase_saat_ini' => 'tanam',
            ]);
        }

        $lahans = Lahan::all();

        /*
        |--------------------------------------------------------------------------
        | 2. Partner
        |--------------------------------------------------------------------------
        */

        $partnerTambahan = Partner::firstOrCreate(
            ['nama' => 'Partner Contoh (Pemilik Lahan 2)'],
            ['tipe' => 'pemilik_lahan']
        );

        /*
        |--------------------------------------------------------------------------
        | 3. Data per lahan
        |--------------------------------------------------------------------------
        */

        foreach ($lahans as $index => $lahan) {

            /*
            |--------------------------------------------------------------------------
            | Partner Agreement
            |--------------------------------------------------------------------------
            */

            if (!$lahan->activeAgreement) {
                PartnerAgreement::create([
                    'partner_id' => $partnerTambahan->id,
                    'lahan_id' => $lahan->id,
                    'skema' => 'bagi_hasil',
                    'persentase_bagi_hasil' => 30,
                    'tanggal_mulai' => now()->subMonths(12),
                    'is_active' => true,
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | TRANSAKSI
            | 12 bulan
            |--------------------------------------------------------------------------
            */

            $kategoriPengeluaran = [
                'Pupuk',
                'Tenaga Kerja',
                'Obat',
                'Sewa Alat',
                'Transportasi',
                'Perawatan',
                'Bibit',
                'Irigasi',
            ];

            $kategoriPemasukan = [
                'Penjualan Bibit/Anakan',
                'Penjualan Hasil Kebun',
                'Penjualan Panen',
            ];

            for ($bulan = 11; $bulan >= 0; $bulan--) {

                $tanggalDasar = now()
                    ->copy()
                    ->startOfMonth()
                    ->subMonths($bulan);

                /*
                |--------------------------------------------------------------------------
                | Faktor perkembangan.
                | Semakin dekat bulan sekarang, aktivitas sedikit meningkat.
                |--------------------------------------------------------------------------
                */

                $progress = (11 - $bulan) / 11;

                /*
                |--------------------------------------------------------------------------
                | Pengeluaran
                |--------------------------------------------------------------------------
                */

                $jumlahTransaksi = rand(7, 12);

                for ($i = 0; $i < $jumlahTransaksi; $i++) {

                    $base = rand(150, 500) * 1000;

                    // Sedikit kenaikan biaya dari waktu ke waktu
                    $jumlah = round(
                        ($base * (0.9 + ($progress * 0.15))) / 1000
                    ) * 1000;

                    Transaction::create([
                        'lahan_id' => $lahan->id,
                        'user_id' => $admin->id,
                        'jenis' => 'pengeluaran',
                        'kategori' => $kategoriPengeluaran[array_rand($kategoriPengeluaran)],
                        'jumlah' => $jumlah,
                        'is_cash' => true,
                        'tanggal' => $tanggalDasar
                            ->copy()
                            ->addDays(rand(1, 27)),
                        'keterangan' => 'Data dummy untuk preview',
                    ]);
                }

                /*
                |--------------------------------------------------------------------------
                | Pemasukan
                |--------------------------------------------------------------------------
                */

                $jumlahPemasukan = rand(2, 5);

                for ($i = 0; $i < $jumlahPemasukan; $i++) {

                    $base = rand(250, 800) * 1000;

                    // Tren pemasukan sedikit meningkat
                    $jumlah = round(
                        ($base * (0.85 + ($progress * 0.35))) / 1000
                    ) * 1000;

                    Transaction::create([
                        'lahan_id' => $lahan->id,
                        'user_id' => $admin->id,
                        'jenis' => 'pemasukan',
                        'kategori' => $kategoriPemasukan[array_rand($kategoriPemasukan)],
                        'jumlah' => $jumlah,
                        'is_cash' => true,
                        'tanggal' => $tanggalDasar
                            ->copy()
                            ->addDays(rand(1, 27)),
                        'keterangan' => 'Data dummy untuk preview',
                    ]);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | PROGRESS LOG
            | 52 minggu
            |--------------------------------------------------------------------------
            */

            $progressLogs = [
                'Kondisi daun sehat, warna hijau tua.',
                'Pertumbuhan tanaman terlihat normal.',
                'Ada beberapa daun kuning dan sudah dipangkas.',
                'Penyiraman rutin dilakukan.',
                'Tanah cukup lembab setelah penyiraman.',
                'Muncul tunas anakan baru di beberapa titik.',
                'Pertumbuhan tinggi tanaman meningkat.',
                'Beberapa bagian tanaman perlu mendapatkan pupuk tambahan.',
                'Kondisi tanaman cukup baik setelah perawatan.',
                'Cuaca cerah dan pertumbuhan optimal.',
                'Dilakukan pemeriksaan rutin pada seluruh area lahan.',
                'Beberapa tanaman menunjukkan pertumbuhan lebih cepat.',
                'Pengairan berjalan dengan baik.',
                'Dilakukan pembersihan area sekitar tanaman.',
            ];

            foreach (range(1, 52) as $minggu) {

                ProgressLog::create([
                    'lahan_id' => $lahan->id,
                    'user_id' => $admin->id,
                    'tanggal' => now()
                        ->copy()
                        ->subWeeks($minggu),
                    'keterangan' => $progressLogs[array_rand($progressLogs)],
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | TROUBLE REPORT
            |--------------------------------------------------------------------------
            */

            $troubles = [
                [
                    'judul' => 'Serangan ulat daun',
                    'deskripsi' => 'Ditemukan beberapa daun berlubang akibat ulat.',
                    'urgensi' => 'sedang',
                ],
                [
                    'judul' => 'Tanah tergenang setelah hujan',
                    'deskripsi' => 'Drainase perlu dicek karena air tidak cepat surut.',
                    'urgensi' => 'tinggi',
                ],
                [
                    'judul' => 'Pompa air kurang optimal',
                    'deskripsi' => 'Debit air menurun dan perlu dilakukan pemeriksaan.',
                    'urgensi' => 'rendah',
                ],
                [
                    'judul' => 'Daun menguning',
                    'deskripsi' => 'Beberapa tanaman mengalami perubahan warna daun.',
                    'urgensi' => 'sedang',
                ],
                [
                    'judul' => 'Saluran irigasi tersumbat',
                    'deskripsi' => 'Aliran air tidak merata pada beberapa area.',
                    'urgensi' => 'tinggi',
                ],
                [
                    'judul' => 'Pertumbuhan tanaman lambat',
                    'deskripsi' => 'Beberapa tanaman mengalami pertumbuhan yang lebih lambat.',
                    'urgensi' => 'sedang',
                ],
                [
                    'judul' => 'Serangan hama ringan',
                    'deskripsi' => 'Ditemukan tanda-tanda serangan hama pada beberapa tanaman.',
                    'urgensi' => 'rendah',
                ],
                [
                    'judul' => 'Kondisi tanah terlalu kering',
                    'deskripsi' => 'Beberapa bagian lahan membutuhkan penyiraman tambahan.',
                    'urgensi' => 'sedang',
                ],
            ];

            foreach ($troubles as $i => $trouble) {

                $statusPool = [
                    'selesai',
                    'selesai',
                    'ditindaklanjuti',
                    'dilaporkan',
                ];

                $status = $statusPool[array_rand($statusPool)];

                $createdAt = now()->copy()->subDays(
                    rand(10, 300)
                );

                TroubleReport::create([
                    'lahan_id' => $lahan->id,
                    'user_id' => $admin->id,
                    'judul' => $trouble['judul'],
                    'deskripsi' => $trouble['deskripsi'],
                    'urgensi' => $trouble['urgensi'],
                    'status' => $status,
                    'selesai_at' => $status === 'selesai'
                        ? $createdAt->copy()->addDays(rand(1, 10))
                        : null,
                    'created_at' => $createdAt,
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | SCHEDULE
            |--------------------------------------------------------------------------
            */

            $schedules = [
                [
                    'jenis' => 'Pemupukan',
                    'recurring_pattern' => 'dua_mingguan',
                    'days' => 5,
                ],
                [
                    'jenis' => 'Semprot Obat',
                    'recurring_pattern' => 'mingguan',
                    'days' => 3,
                ],
                [
                    'jenis' => 'Cek Rutin',
                    'recurring_pattern' => 'harian',
                    'days' => 1,
                ],
                [
                    'jenis' => 'Pembersihan Lahan',
                    'recurring_pattern' => 'bulanan',
                    'days' => 15,
                ],
                [
                    'jenis' => 'Pemeriksaan Irigasi',
                    'recurring_pattern' => 'mingguan',
                    'days' => 8,
                ],
            ];

            foreach ($schedules as $schedule) {

                Schedule::create([
                    'lahan_id' => $lahan->id,
                    'jenis' => $schedule['jenis'],
                    'recurring_pattern' => $schedule['recurring_pattern'],
                    'next_date' => now()->addDays(
                        $schedule['days'] + rand(0, 5)
                    ),
                    'status' => 'aktif',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | PANEN
            |--------------------------------------------------------------------------
            |
            | Buat beberapa siklus untuk lahan yang sudah masuk
            | fase perawatan/panen.
            |--------------------------------------------------------------------------
            */

            if (
                in_array(
                    $lahan->fase_saat_ini,
                    ['perawatan', 'panen']
                )
            ) {

                $jumlahSiklus = 5;

                for ($siklus = 1; $siklus <= $jumlahSiklus; $siklus++) {

                    $jumlahPohon = max(
                        1,
                        $lahan->estimasi_jumlah_pohon - rand(0, 15)
                    );

                    // Hasil meningkat perlahan
                    $hasilPerPohon = rand(
                        9 + $siklus,
                        13 + $siklus
                    );

                    $hasilKg = $jumlahPohon * $hasilPerPohon;

                    $hargaPerKg = rand(4500, 6000);

                    $tanggalPanen = now()
                        ->copy()
                        ->subMonths(
                            (5 - $siklus) * 2
                        );

                    $panen = PanenCycle::create([
                        'lahan_id' => $lahan->id,
                        'nomor_siklus' => $siklus,
                        'tanggal_panen' => $tanggalPanen,
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
                        'keterangan' =>
                        "Panen siklus ke-{$siklus} (dummy)",
                    ]);

                    AnakanRecord::create([
                        'panen_cycle_id' => $panen->id,
                        'jumlah_muncul' => rand(150, 250),
                        'jumlah_disisakan' => $jumlahPohon,
                        'jumlah_dijual' => rand(20, 50),
                        'jumlah_dipindah_lahan_lain' => rand(0, 10),
                        'jumlah_dibuang' => rand(10, 30),
                    ]);
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | ASSET
        |--------------------------------------------------------------------------
        */

        $assets = [
            [
                'nama' => 'Pompa Air Contoh',
                'jenis' => 'Pengairan',
                'months' => 5,
                'harga' => 1500000,
                'kondisi' => 'baik',
            ],
            [
                'nama' => 'Alat Semprot Contoh',
                'jenis' => 'Perawatan',
                'months' => 2,
                'harga' => 350000,
                'kondisi' => 'perlu_servis',
            ],
            [
                'nama' => 'Mesin Potong Rumput',
                'jenis' => 'Perawatan',
                'months' => 8,
                'harga' => 2200000,
                'kondisi' => 'baik',
            ],
            [
                'nama' => 'Selang Pengairan',
                'jenis' => 'Pengairan',
                'months' => 4,
                'harga' => 450000,
                'kondisi' => 'baik',
            ],
        ];

        foreach ($assets as $assetData) {

            $asset = Asset::create([
                'nama' => $assetData['nama'],
                'jenis' => $assetData['jenis'],
                'tanggal_beli' => now()->subMonths(
                    $assetData['months']
                ),
                'harga_beli' => $assetData['harga'],
                'kondisi' => $assetData['kondisi'],
            ]);

            /*
            |--------------------------------------------------------------------------
            | Alokasi aset
            |--------------------------------------------------------------------------
            */

            if ($assetData['nama'] === 'Pompa Air Contoh') {

                $jumlahLahan = $lahans->count();

                foreach ($lahans as $lahan) {
                    AssetAllocation::create([
                        'asset_id' => $asset->id,
                        'lahan_id' => $lahan->id,
                        'porsi_persen' => round(
                            100 / $jumlahLahan,
                            2
                        ),
                    ]);
                }
            } else {

                $lahan = $lahans->random();

                AssetAllocation::create([
                    'asset_id' => $asset->id,
                    'lahan_id' => $lahan->id,
                    'porsi_persen' => 100,
                ]);
            }
        }

        $this->command->info(
            'Dummy data berhasil dibuat untuk ' .
                $lahans->count() .
                ' lahan.'
        );

        $this->command->info(
            'Transaksi: 12 bulan + Progress: 52 minggu + ' .
                'Trouble + Schedule + Panen + Asset.'
        );
    }
}
