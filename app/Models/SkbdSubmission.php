<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasQueueNumber;

class SkbdSubmission extends Model
{
    use HasFactory, HasQueueNumber;

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
    ];

    protected $dates = [
        'verified_at',
        'approved_sekcam_at',
        'approved_camat_at',
    ];
}
