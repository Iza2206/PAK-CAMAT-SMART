<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasQueueNumber;
use App\Traits\HasNikStatusFilter;

class SengketaSubmission extends Model
{
    use HasFactory, HasQueueNumber, HasNikStatusFilter;

    protected $nikField = 'nik_pemohon';

    protected $fillable = [
        'user_id',
        'queue_number',
        'nama_pemohon',
        'jenis_kelamin',
        'pendidikan',
        'nik_pemohon',
        'surat_silang_sengketa',
        'foto_copy_surat_tanah',
        'bukti_lunas_pbb',
        'status',
        'verified_at',
        'approved_sekcam_at',
        'approved_camat_at',
        'rejected_reason',
        'rejected_sekcam_reason',
        'rejected_camat_reason',
        'penilaian',
        'diambil_at'
    ];

    protected $dates = [
        'verified_at',
        'approved_sekcam_at',
        'approved_camat_at',
    ];
}
