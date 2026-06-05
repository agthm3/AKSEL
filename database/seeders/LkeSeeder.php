<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LkeComponent;
use App\Models\LkeSubComponent;
use App\Models\LkeCriteria;

class LkeSeeder extends Seeder
{
    public function run()
    {
        // 1. Buat Komponen Utama
        $komponen1 = LkeComponent::create([
            'component_number' => 1,
            'name' => 'PERENCANAAN KINERJA',
            'weight' => 30.00
        ]);

        // 2. Buat Sub-Komponen
        $sub1a = LkeSubComponent::create([
            'lke_component_id' => $komponen1->id,
            'code' => '1.a',
            'name' => 'Dokumen Perencanaan kinerja telah tersedia',
            'weight' => 6.00
        ]);

        // 3. Buat Kriteria dari Excel Anda
        LkeCriteria::create([
            'lke_sub_component_id' => $sub1a->id,
            'criteria_number' => 1,
            'description' => 'Terdapat pedoman teknis perencanaan kinerja.',
            'expected_evidence' => 'Surat Edaran Walikota Makassar terkait Penyusunan Rencana Strategis, Rencana Kerja, Indikator Kinerja Utama, dan Perjanjian Kinerja Perangkat Daerah.'
        ]);

        LkeCriteria::create([
            'lke_sub_component_id' => $sub1a->id,
            'criteria_number' => 2,
            'description' => 'Terdapat dokumen perencanaan kinerja jangka panjang.',
            'expected_evidence' => 'RPJPD Kota Makassar Tahun 2005-2025.'
        ]);

        LkeCriteria::create([
            'lke_sub_component_id' => $sub1a->id,
            'criteria_number' => 3,
            'description' => 'Terdapat dokumen perencanaan kinerja jangka menengah.',
            'expected_evidence' => 'RPJMD Kota Makassar dan RENSTRA Perangkat Daerah Tahun 2021-2026.'
        ]);
    }
}