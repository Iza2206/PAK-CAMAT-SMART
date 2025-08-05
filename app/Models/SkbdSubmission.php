<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasQueueNumber;
use App\Traits\HasNikStatusFilter;

class SkbdSubmission extends Model
{
    use HasFactory, HasQueueNumber, HasNikStatusFilter;
    
    protected $nikField = 'nik_pemohon';

    protected $fillable = [
        'user_id',
        'nama_pemohon',
        'jenis_kelamin',
        'pendidikan',
        'nik_pemohon',
        'skbd_desa',
        'ktp',
        'kk',
        'tanda_lunas_pbb',
        'status',
        'verified_at',
        'approved_sekcam_at',
        'approved_camat_at',
        'rejected_reason',
        'rejected_sekcam_reason',
        'rejected_camat_reason',
        'queue_number',
        'penilaian',
        'diambil_at'
    ];

    protected $dates = [
        'verified_at',
        'approved_sekcam_at',
        'approved_camat_at',
    ];
}
