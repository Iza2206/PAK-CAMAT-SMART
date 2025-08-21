<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasQueueNumber;
use App\Traits\HasNikStatusFilter;
use App\Traits\HasPenilaian;

class IumkSubmission extends Model
{
    use HasFactory, HasQueueNumber, HasNikStatusFilter,HasPenilaian;

    protected $table = 'iumk_submissions';
    // Menentukan nama field NIK yang digunakan untuk filtering
    protected $nikField = 'nik_pemohon';

    protected $fillable = [
        'queue_number',
        'nama_pemohon',
        'nik_pemohon',
        'jenis_kelamin',
        'pendidikan',
        'saran_kritik',
        'alamat_usaha',
        'surat_keterangan_usaha',
        'foto_tempat_usaha',
        'file_kk',
        'file_ktp',
        'pasphoto_3x4_1',
        'file_pbb',
        'file_surat',
        'surat_final',

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
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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
