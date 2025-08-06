<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasQueueNumber;
use App\Traits\HasNikStatusFilter;

class AgunanSubmission extends Model
{
    use HasFactory, HasQueueNumber, HasNikStatusFilter;

    protected $nikField = 'nik';

    protected $fillable = [
        'user_id',
        'nama_pemohon',
        'jenis_kelamin',
        'pendidikan',
        'nik',
        'file_surat_tanah_asli',
        'file_ktp',
        'file_pengantar_desa',
        'file_surat_tidak_sengketa',
        'file_pbb',
        'status',
        'verified_at',
        'approved_sekcam_at',
        'approved_camat_at',
        'rejected_reason',
        'rejected_sekcam_reason',
        'rejected_camat_reason',
        'queue_number',
        'rejected_reason',
        'penilaian',
        'diambil_at'
    ];

    protected $dates = [
        'verified_at',
        'approved_sekcam_at',
        'approved_camat_at',
    ];
}
