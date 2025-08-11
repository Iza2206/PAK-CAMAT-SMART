<?php

namespace App\Models;

use App\Traits\HasNikStatusFilter;
use App\Traits\HasQueueNumber;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatinSubmission extends Model
{
    use HasFactory, HasQueueNumber, HasNikStatusFilter;

    protected $fillable = [
        'queue_number', 
        'nama_pemohon', 
        'nik_pemohon', 
        'jenis_kelamin', 
        'pendidikan',

        // dokumen
        'file_na_pria', 
        'file_na_wanita', 
        'file_kk_pria', 
        'file_kk_wanita',
        'file_ktp_pria', 
        'file_ktp_wanita', 
        'file_akte_cerai_pria', 
        'file_akte_cerai_wanita',
        'file_pbb', 
        
        // status & tracking 
        'status', 
        'verified_at', 
        'approved_sekcam_at', 
        'approved_camat_at',
        'rejected_reason', 
        'rejected_sekcam_reason', 
        'rejected_camat_reason',
        'penilaian', 
        'diambil_at',

        'file_surat'
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'approved_sekcam_at' => 'datetime',
        'approved_camat_at' => 'datetime',
        'diambil_at' => 'datetime',
    ];
}
