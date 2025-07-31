<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BpjsSubmission;
use App\Models\SktmDispensasiSubmission;

class KasiKesosController extends Controller
{
    // Dashboard Kasi Kesos
    public function index()
    {
        // Ambil data pengajuan BPJS yang masih diajukan
        $submissions = BpjsSubmission::where('status', 'diajukan')->latest()->paginate(10);

        // Data statistik
        $jumlahPengajuan      = BpjsSubmission::count();
        $pengajuanDiajukan    = BpjsSubmission::where('status', 'diajukan')->count();
        $pengajuanDisetujui   = BpjsSubmission::where('status', 'checked_by_kasi')->count();
        $pengajuanDitolak     = BpjsSubmission::where('status', 'rejected')->count();

        return view('kasi_kesos.dashboard', compact(
            'submissions',
            'jumlahPengajuan',
            'pengajuanDiajukan',
            'pengajuanDisetujui',
            'pengajuanDitolak'
        ));
    }

    // Tampilkan daftar pengajuan BPJS untuk diverifikasi
    public function bpjsIndex()
    {
        $pengajuan = BpjsSubmission::where('status', 'diajukan')->get();
        return view('kasi_kesos.bpjs.index', compact('pengajuan'));
    }

    // Setujui/verifikasi pengajuan BPJS
    public function bpjsApprove($id)
    {
        $item = BpjsSubmission::findOrFail($id);
        $item->status = 'checked_by_kasi';
        $item->verified_at = now();
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan berhasil disetujui.');
    }

    // Tolak pengajuan BPJS dengan alasan
    public function bpjsReject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        $item = BpjsSubmission::findOrFail($id);
        $item->status = 'rejected';
        $item->rejected_reason = $request->reason;
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan berhasil ditolak.');
    }

    public function bpjsProses()
    {
         $jumlahPengajuan      = BpjsSubmission::count();
        $pengajuanDiajukan    = BpjsSubmission::where('status', 'diajukan')->count();
        $pengajuanDisetujui   = BpjsSubmission::where('status', 'checked_by_kasi')->count();
        $pengajuanDitolak     = BpjsSubmission::where('status', 'rejected')->count();

         $pengajuan = BpjsSubmission::whereIn('status', ['checked_by_kasi', 'rejected'])
                    ->latest()
                    ->paginate(10); // tampilkan 9 per halaman
    return view('kasi_kesos.bpjs.proses', compact('pengajuan',
            'jumlahPengajuan',
            'pengajuanDiajukan',
            'pengajuanDisetujui',
            'pengajuanDitolak'));
    }

      // Tampilkan daftar pengajuan SKTM untuk diverifikasi
    public function sktmIndex()
    {
        $pengajuan = SktmDispensasiSubmission::where('status', 'diajukan')->get();

        return view('kasi_kesos.sktm.index', compact('pengajuan'));
    }

    public function sktmApprove($id)
    {
        $item = SktmDispensasiSubmission::findOrFail($id);
        $item->status = 'checked_by_kasi';
        $item->verified_at = now();
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan berhasil diverifikasi oleh Kasi Kesos.');
    }

    public function sktmReject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        $item = SktmDispensasiSubmission::findOrFail($id);
        $item->status = 'rejected_by_kasi';
        $item->rejected_reason = $request->reason;
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan berhasil ditolak oleh Kasi Kesos.');
    }

    public function sktmProses()
    {
        $jumlahPengajuan      = SktmDispensasiSubmission::count();
        $pengajuanDiajukan    = SktmDispensasiSubmission::where('status', 'diajukan')->count();
        $pengajuanDisetujui   = SktmDispensasiSubmission::where('status', 'checked_by_kasi')->count();
        $pengajuanDitolak     = SktmDispensasiSubmission::where('status', 'rejected_by_kasi')->count();

        $pengajuan = SktmDispensasiSubmission::whereIn('status', ['checked_by_kasi', 'rejected_by_kasi'])
            ->latest()
            ->paginate(10);

        return view('kasi_kesos.sktm.proses', compact(
            'pengajuan',
            'jumlahPengajuan',
            'pengajuanDiajukan',
            'pengajuanDisetujui',
            'pengajuanDitolak'
        ));
    }
}
