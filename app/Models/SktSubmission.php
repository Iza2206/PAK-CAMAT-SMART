<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasQueueNumber;

class SktSubmission extends Model
{
    use HasFactory, HasQueueNumber;

    protected $table = 'skt_submissions';

    protected $fillable = [
        'nama_pemohon',
        'nik_pemohon',
        'jenis_kelamin',
        'pendidikan',
        'file_permohonan',
        'file_alas_hak',
        'file_kk',
        'file_ktp',
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
