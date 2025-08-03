<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasQueueNumber;

class SppatGrSubmission extends Model
{
    use HasFactory, HasQueueNumber;

    protected $table = 'sppat_gr_submissions';

    protected $fillable = [
        'nama_pemohon',
        'nik_pemohon',
        'jenis_kelamin',
        'pendidikan',
        'file_permohonan',
        'file_pernyataan_ahli_waris',
        'file_akta_kematian',
        'file_ktp_ahli_waris',
        'file_kk_ahli_waris',
        'file_pbb',
        'status',
        'verified_at',
        'approved_sekcam_at',
        'approved_camat_at',
        'rejected_reason',
        'queue_number',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'approved_sekcam_at' => 'datetime',
        'approved_camat_at' => 'datetime',
    ];
}
