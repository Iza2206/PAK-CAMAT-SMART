<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasQueueNumber;

class CatinTniPolriSubmission extends Model
{
    use HasFactory, HasQueueNumber;
    protected $fillable = [
        'user_id',
        'queue_number',
        'nama_pemohon',
        'jenis_kelamin',
        'pendidikan',
        'nik_pemohon',

        // Dokumen dari pihak sipil
        'file_ktp',
        'file_kk',
        'file_akta_kelahiran',
        'file_pas_foto_3x4',
        'file_pas_foto_4x6',
        'file_pengantar_rt_rw',
        'file_surat_n1',
        'file_surat_n2',
        'file_surat_n3',
        'file_surat_n4',
        'file_izin_orang_tua',
        'file_status_pernikahan',

        // Dokumen dari pihak TNI/Polri
        'file_surat_izin_kawin',
        'file_keterangan_belum_menikah_tni',
        'file_pernyataan_kesediaan',
        'file_kta',
        'file_sk_pangkat_terakhir',
        'file_pas_foto_berdampingan_dinas',
        'file_pas_foto_berdampingan_formal',

        // Tambahan
        'file_pbb',

        // Status & tracking
        'status',
        'verified_at',
        'approved_sekcam_at',
        'approved_camat_at',
        'rejected_reason',
        'rejected_sekcam_reason',
        'rejected_camat_reason',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'approved_sekcam_at' => 'datetime',
        'approved_camat_at' => 'datetime',
        'queue_number' => 'integer',
    ];
}
