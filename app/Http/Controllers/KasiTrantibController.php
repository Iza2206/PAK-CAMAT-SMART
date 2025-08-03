<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CatinTniPolriSubmission;
use App\Models\SkbdSubmission;

class KasiTrantibController extends Controller
{
    // Dashboard Kasi Trantib
    public function index()
    {
        $jumlahPengajuan     = SkbdSubmission::count();
        $pengajuanDiajukan   = SkbdSubmission::where('status', 'diajukan')->count();
        $pengajuanDisetujui  = SkbdSubmission::where('status', 'approved_by_sekcam')->count();
        $pengajuanDitolak    = SkbdSubmission::where('status', 'rejected')->count();

        return view('kasi_trantib.dashboard', compact(
            'jumlahPengajuan',
            'pengajuanDiajukan',
            'pengajuanDisetujui',
            'pengajuanDitolak'
        ));
    }

    // -------- CATIN TNI POLRI -----------

    // tampikllkan daftar catin dari meja pelayanan (CATIN)
    public function catinTniIndex()
    {
        $pengajuan = CatinTniPolriSubmission::where('status', 'diajukan')->latest()->get();
        return view('kasi_trantib.catin_tni.index', compact('pengajuan'));
    }
    // Verifikasi pengajuan (approve) (CATIN)
    public function catinTniApprove($id)
    {
        $item = CatinTniPolriSubmission::findOrFail($id);
        $item->status = 'checked_by_kasi';
        $item->verified_at = now();
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan Catin TNI/Polri berhasil diverifikasi oleh Kasi Trantib.');
    }
    // Tolak pengajuan (CATIN)
    public function catinTniReject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        $item = CatinTniPolriSubmission::findOrFail($id);
        $item->status = 'rejected';
        $item->rejected_reason = $request->reason;
        $item->verified_at = now();
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan Catin TNI/Polri ditolak oleh Kasi Trantib.');
    }
    // Riwayat pengajuan yang diproses oleh Kasi Trantib (CATIN)
    public function catinTniProses()
    {
        $jumlahPengajuan     = CatinTniPolriSubmission::count();
        $pengajuanDiajukan   = CatinTniPolriSubmission::where('status', 'diajukan')->count();
        $pengajuanDisetujui  = CatinTniPolriSubmission::where('status', 'checked_by_kasi')->count();
        $pengajuanDitolak    = CatinTniPolriSubmission::where('status', 'rejected')->count();

        $pengajuan = CatinTniPolriSubmission::whereIn('status', ['checked_by_kasi', 'rejected'])
                                ->latest()
                                ->paginate(10);

        return view('kasi_trantib.catin_tni.proses', compact(
            'pengajuan',
            'jumlahPengajuan',
            'pengajuanDiajukan',
            'pengajuanDisetujui',
            'pengajuanDitolak'
        ));
    }

    // -------- SKBD -----------

    // Tampilkan daftar pengajuan SKBD dari Meja Layanan
    public function skbdIndex()
    {
        $pengajuan = SkbdSubmission::where('status', 'diajukan')->latest()->get();
        return view('kasi_trantib.skbd.index', compact('pengajuan'));
    }
    // Setujui pengajuan SKBD
    public function skbdApprove($id)
    {
        $item = SkbdSubmission::findOrFail($id);
        $item->status = 'checked_by_kasi';
        $item->verified_at = now();
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan SKBD berhasil diverifikasi oleh Kasi Trantib.');
    }
    // Tolak pengajuan SKBD
    public function skbdReject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        $item = SkbdSubmission::findOrFail($id);
        $item->status = 'rejected';
        $item->rejected_reason = $request->reason;
        $item->verified_at = now();
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan SKBD ditolak oleh Kasi Trantib.');
    }
    // Riwayat pengajuan yang diproses oleh Kasi Trantib
    public function skbdProses()
    {
        $jumlahPengajuan     = SkbdSubmission::count();
        $pengajuanDiajukan   = SkbdSubmission::where('status', 'diajukan')->count();
        $pengajuanDisetujui  = SkbdSubmission::where('status', 'checked_by_kasi')->count();
        $pengajuanDitolak    = SkbdSubmission::where('status', 'rejected')->count();

        $pengajuan = SkbdSubmission::whereIn('status', ['checked_by_kasi', 'rejected'])
                            ->latest()
                            ->paginate(10);

        return view('kasi_trantib.skbd.proses', compact(
            'pengajuan',
            'jumlahPengajuan',
            'pengajuanDiajukan',
            'pengajuanDisetujui',
            'pengajuanDitolak'
        ));
    }
}
