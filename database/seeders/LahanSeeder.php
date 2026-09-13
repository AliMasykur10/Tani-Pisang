<?php

namespace Database\Seeders;

use App\Models\Lahan;
use App\Models\Partner;
use App\Models\PartnerAgreement;
use Illuminate\Database\Seeder;

class LahanSeeder extends Seeder
{
    public function run(): void
    {
        $lahan1 = Lahan::create([
            'nama' => 'Lahan Contoh',
            'luas_panjang_m' => 25,
            'luas_lebar_m' => 30,
            'jarak_tanam_m' => 2,
            'jarak_pagar_m' => 1,
            'estimasi_jumlah_pohon' => 160,
            'fase_saat_ini' => 'perawatan',
        ]);

        $partner1 = Partner::create([
            'tipe' => 'penyedia_pembeli',
            'nama' => 'Partner Contoh (Penyedia & Pembeli)',
        ]);

        $partner2 = Partner::create([
            'tipe' => 'pemilik_lahan',
            'nama' => 'Partner Contoh (Pemilik Lahan)',
        ]);

        PartnerAgreement::create([
            'partner_id' => $partner2->id,
            'lahan_id' => $lahan1->id,
            'skema' => 'sewa',
            'nominal_sewa' => 5000000,
            'tanggal_mulai' => now(),
            'is_active' => true,
        ]);
    }
}
