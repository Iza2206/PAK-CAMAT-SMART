<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BpjsSubmission;
use App\Models\SktmDispensasiSubmission;

class SekcamController extends Controller
{
    // Dashboard Sekcam
    public function index()
    {
        $submissions = BpjsSubmission::where('status', 'checked_by_kasi')->latest()->paginate(10);

        $jumlahPengajuan       = BpjsSubmission::count();
        $pengajuanDiterima     = BpjsSubmission::where('status', 'checked_by_kasi')->count();
        $pengajuanDisetujui    = BpjsSubmission::where('status', 'approved_by_sekcam')->count();
        $pengajuanDitolak      = BpjsSubmission::where('status', 'rejected_by_sekcam')->count();

        return view('sekcam.dashboard', compact(
            'submissions',
            'jumlahPengajuan',
            'pengajuanDiterima',
            'pengajuanDisetujui',
            'pengajuanDitolak'
        ));
    }

    // Tampilkan daftar pengajuan dari Kasi Kesos
    public function bpjsIndex()
    {
        $pengajuan = BpjsSubmission::where('status', 'checked_by_kasi')->get();
        return view('sekcam.bpjs.index', compact('pengajuan'));
    }

    // Setujui pengajuan BPJS
    public function bpjsApprove($id)
    {
        $item = BpjsSubmission::findOrFail($id);
        $item->status = 'approved_by_sekcam';
        $item->approved_sekcam_at = now();
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan berhasil disetujui oleh Sekcam.');
    }

    // Tolak pengajuan BPJS oleh Sekcam
    public function bpjsReject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        $item = BpjsSubmission::findOrFail($id);
        $item->status = 'rejected_by_sekcam';
        $item->rejected_sekcam_reason = $request->reason;
        $item->approved_sekcam_at = now();
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan berhasil ditolak oleh Sekcam.');
    }

    // Riwayat pengajuan yang diproses oleh Sekcam
    public function bpjsProses()
    {
        $jumlahPengajuan       = BpjsSubmission::count();
        $pengajuanDiterima     = BpjsSubmission::where('status', 'checked_by_kasi')->count();
        $pengajuanDisetujui    = BpjsSubmission::where('status', 'approved_by_sekcam')->count();
        $pengajuanDitolak      = BpjsSubmission::where('status', 'rejected_by_sekcam')->count();

        $pengajuan = BpjsSubmission::whereIn('status', ['approved_by_sekcam', 'rejected_by_sekcam'])
                            ->latest()
                            ->paginate(10);

        return view('sekcam.bpjs.proses', compact(
            'pengajuan',
            'jumlahPengajuan',
            'pengajuanDiterima',
            'pengajuanDisetujui',
            'pengajuanDitolak'
        ));
    }

   // Tampilkan daftar pengajuan dari Kasi Kesos
    public function sktmIndex()
    {
        $pengajuan = SktmDispensasiSubmission::where('status', 'checked_by_kasi')->get();
        return view('sekcam.sktm.index', compact('pengajuan'));
    }

    // Setujui pengajuan SKTM oleh Sekcam
    public function sktmApprove($id)
    {
        $item = SktmDispensasiSubmission::findOrFail($id);
        $item->status = 'approved_by_sekcam';
        $item->approved_sekcam_at = now();
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan berhasil disetujui oleh Sekcam.');
    }

    // Tolak pengajuan SKTM oleh Sekcam
    public function sktmReject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        $item = SktmDispensasiSubmission::findOrFail($id);
        $item->status = 'rejected_by_sekcam';
        $item->rejected_sekcam_reason = $request->reason;
        $item->approved_sekcam_at = now();
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan berhasil ditolak oleh Sekcam.');
    }

    // Riwayat pengajuan yang diproses oleh Sekcam
    public function sktmProses()
    {
        $jumlahPengajuan    = SktmDispensasiSubmission::count();
        $pengajuanDiterima  = SktmDispensasiSubmission::where('status', 'checked_by_kasi')->count();
        $pengajuanDisetujui = SktmDispensasiSubmission::where('status', 'approved_by_sekcam')->count();
        $pengajuanDitolak   = SktmDispensasiSubmission::where('status', 'rejected_by_sekcam')->count();

        $pengajuan = SktmDispensasiSubmission::whereIn('status', ['approved_by_sekcam', 'rejected_by_sekcam'])
                        ->latest()
                        ->paginate(10);

        return view('sekcam.sktm.proses', compact(
            'pengajuan',
            'jumlahPengajuan',
            'pengajuanDiterima',
            'pengajuanDisetujui',
            'pengajuanDitolak'
        ));
    }

}
