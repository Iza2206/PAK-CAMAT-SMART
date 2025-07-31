<?php

namespace Database\Seeders;

use App\Models\BpjsSubmission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BpjsSubmissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BpjsSubmission::create([
            'nama_pemohon' => 'Budi Santoso',
            'surat_permohonan' => 'surat_permohonan_budi.pdf',
            'sktm' => 'sktm.pdf',
            'kk' => 'kk_budi.pdf',
            'ktp' => 'ktp_budi.pdf',
            'tanda_lunas_pbb' => 'pbb_budi.pdf',
            'status' => 'diajukan',
        ]);
    }
}
