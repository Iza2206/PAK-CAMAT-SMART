<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AntrianController extends Controller
{
    public function index()
    {
        $today = Carbon::today()->toDateString();

        $bpjs = DB::table('bpjs_submissions')
            ->select('queue_number', 'nama_pemohon', 'created_at', DB::raw("'BPJS' as layanan"))
            ->whereDate('created_at', $today);

        $sktm = DB::table('sktm_dispensasi_submissions')
            ->select('queue_number', 'nama_pemohon', 'created_at', DB::raw("'SKTM Dispensasi' as layanan"))
            ->whereDate('created_at', $today);

        $skbd = DB::table('skbd_submissions')
            ->select('queue_number', 'nama_pemohon', 'created_at', DB::raw("'SKBD' as layanan"))
            ->whereDate('created_at', $today);

        $catin = DB::table('catin_tni_polri_submissions')
            ->select('queue_number', 'nama_pemohon', 'created_at', DB::raw("'Catin TNI/Polri' as layanan"))
            ->whereDate('created_at', $today);

        $sengketa = DB::table('sengketa_submissions')
            ->select('queue_number', 'nama_pemohon', 'created_at', DB::raw("'Surat Sengketa' as layanan"))
            ->whereDate('created_at', $today);

        $agunan = DB::table('agunan_submissions')
            ->select('queue_number', 'nama_pemohon', 'created_at', DB::raw("'Agunan ke Bank' as layanan"))
            ->whereDate('created_at', $today);

        $ahliwaris = DB::table('ahliwaris_submissions')
            ->select('queue_number', 'nama_pemohon', 'created_at', DB::raw("'Ahli Waris' as layanan"))
            ->whereDate('created_at', $today);

        $antrianHariIni = $bpjs
            ->unionAll($sktm)
            ->unionAll($skbd)
            ->unionAll($catin)
            ->unionAll($sengketa)
            ->unionAll($agunan)
            ->unionAll($ahliwaris)
            ->orderBy('queue_number')
            ->get();

        return view('antrian.tv', compact('antrianHariIni'));
    }
}
