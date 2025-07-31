<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
