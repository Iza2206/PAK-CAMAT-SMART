<?php
// app/Models/BpjsSubmission.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasQueueNumber;
use App\Traits\HasNikStatusFilter;
use App\Traits\HasPenilaian;

class BpjsSubmission extends Model
{
    use HasFactory, HasQueueNumber, HasNikStatusFilter,HasPenilaian;

    // Menentukan nama field NIK yang digunakan untuk filtering
    protected $nikField = 'nik_pemohon';

    protected $fillable = [
        'nama_pemohon', 
        'jenis_kelamin',
        'pendidikan',
        'nik_pemohon', 
        'surat_permohonan', 
        'sktm', 
        'kk',
        'ktp', 
        'tanda_lunas_pbb', 
        'status', 
        'verified_at', 
        'approved_sekcam_at', 
        'approved_camat_at',
        'queue_number', 
        'penilaian',
        'diambil_at',
        'saran_kritik'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'verified_at' => 'datetime',
        'approved_sekcam_at' => 'datetime',
        'approved_camat_at' => 'datetime',
    ];
}
