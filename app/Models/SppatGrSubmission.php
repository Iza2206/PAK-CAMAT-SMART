<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasQueueNumber;
use App\Traits\HasNikStatusFilter;

class SppatGrSubmission extends Model
{
    use HasFactory, HasQueueNumber, HasNikStatusFilter;

    protected $nikField = 'nik_pemohon';
    protected $table = 'sppat_gr_submissions';

    protected $fillable = [
        'nama_pemohon',
        'nik_pemohon',
        'jenis_kelamin',
        'pendidikan',
        'file_permohonan',       // Surat Permohonan Registrasi bermaterai
        'file_formulir',         // Blangko / formulir
        'file_alas_hak_tanah',   // Alas hak tanah
        'file_ktp',              // Fotokopi KTP
        'file_kk',               // Fotokopi KK
        'file_pbb',              // Tanda Lunas PBB (1 file saja)
        'status',
        'verified_at',
        'approved_sekcam_at',
        'approved_camat_at',
        'rejected_reason',
        'rejected_sekcam_reason',
        'queue_number',
        'penilaian',
        'diambil_at',
        'saran_kritik'
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'approved_sekcam_at' => 'datetime',
        'approved_camat_at' => 'datetime',
    ];
}
