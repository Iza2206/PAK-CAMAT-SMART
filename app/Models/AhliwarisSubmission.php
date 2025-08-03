<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasQueueNumber;

class AhliwarisSubmission extends Model
{
    use HasFactory, HasQueueNumber;
    
    protected $fillable = [
        'queue_number',
        'nama_pemohon',
        'nik_pemohon',
        'jenis_kelamin',
        'pendidikan',
        'file_permohonan',
        'file_pernyataan_ahliwaris',
        'file_akta_kematian',
        'file_ktp',
        'file_kk',
        'file_pbb',
        'status',
        'verified_at',
        'approved_sekcam_at',
        'approved_camat_at',
        'rejected_reason',
    ];

    protected $dates = [
        'verified_at',
        'approved_sekcam_at',
        'approved_camat_at',
    ];
}
