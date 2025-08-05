<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasQueueNumber;
use App\Traits\HasNikStatusFilter;

class SktSubmission extends Model
{
    use HasFactory, HasQueueNumber, HasNikStatusFilter;
    
     protected $nikField = 'nik_pemohon';
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
        'rejected_sekcam_reason',
        'approved_camat_at',
        'rejected_reason',
        'queue_number',
        'penilaian',
        'diambil_at'
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'approved_sekcam_at' => 'datetime',
        'approved_camat_at' => 'datetime',
    ];
}
