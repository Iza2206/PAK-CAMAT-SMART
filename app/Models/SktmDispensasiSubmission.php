<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasQueueNumber;

class SktmDispensasiSubmission extends Model
{
    use HasFactory, HasQueueNumber;

     protected $fillable = [
        'nama_pemohon',
        'jenis_kelamin',
        'pendidikan',
        'nik_pemohon',
        'sktm_desa',
        'kk',
        'ktp',
        'buku_nikah',
        'tanda_lunas_pbb',
        'status',
        'verified_at',
        'approved_sekcam_at',
        'approved_camat_at',
        'rejected_reason',
        'rejected_sekcam_reason',
        'rejected_camat_reason',
        'queue_number', // ⬅️ Tambahkan ini
    ];
}
