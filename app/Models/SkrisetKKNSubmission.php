<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkrisetKKNSubmission extends Model
{
    use HasFactory;

    protected $table = 'skriset_kkn_submissions';

    protected $fillable = [
        'nama_pemohon',
        'nik_pemohon',
        'jenis_kelamin',
        'pendidikan',

        'file_surat_sekolah',
        'file_izin_pengambilan',

        'status',
        'verified_at',
        'approved_sekcam_at',
        'approved_camat_at',

        'rejected_reason',
        'rejected_sekcam_reason',
        'rejected_camat_reason',

        'penilaian',
        'diambil_at',

        'camat_id',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'approved_sekcam_at' => 'datetime',
        'approved_camat_at' => 'datetime',
        'diambil_at' => 'datetime',
    ];

    // Relasi ke User sebagai Camat
    public function camat()
    {
        return $this->belongsTo(User::class, 'camat_id');
    }
}
