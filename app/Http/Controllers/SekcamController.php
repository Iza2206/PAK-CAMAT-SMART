<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BpjsSubmission;
use App\Models\SktmDispensasiSubmission;
use App\Models\SktSubmission;
use App\Models\SkbdSubmission;
use App\Models\CatinTniPolriSubmission;
use App\Models\SengketaSubmission;

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

    // ================= BPJS =================
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

    // ================= SKTM =================
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

    // ================= SKT (Surat Keterangan Tanah) =================
    public function sktIndex()
    {
        $pengajuan = SktSubmission::where('status', 'checked_by_kasi')->latest()->get();
        return view('sekcam.skt.index', compact('pengajuan'));
    }


    public function sktApprove($id)
    {
        $item = SktSubmission::findOrFail($id);
        $item->status = 'approved_by_sekcam';
        $item->approved_sekcam_at = now();
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan SKT berhasil disetujui oleh Sekcam.');
    }


    public function sktReject(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string|max:255']);

        $item = SktSubmission::findOrFail($id);
        $item->status = 'rejected_by_sekcam';
        $item->rejected_sekcam_reason = $request->reason;
        $item->approved_sekcam_at = now();
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan SKT berhasil ditolak oleh Sekcam.');
    }

    public function sktProses()
    {

        $pengajuanDiterima  = SktSubmission::whereIn('status', ['checked_by_kasi', 'approved_by_sekcam', 'rejected_by_sekcam'])->count();
        $pengajuanDisetujui = SktSubmission::where('status', 'approved_by_sekcam')->count();
        $pengajuanDitolak   = SktSubmission::where('status', 'rejected_by_sekcam')->count();

        $pengajuan = SktSubmission::whereIn('status', ['approved_by_sekcam', 'rejected_by_sekcam'])
                        ->latest()
                        ->paginate(10);

        return view('sekcam.skt.proses', compact(
            'pengajuan',
            'pengajuanDiterima',
            'pengajuanDisetujui',
            'pengajuanDitolak'
        ));
    }

     // ================= Silang Sengketa  =================
    public function sengketaIndex()
    {
        $pengajuan = SengketaSubmission::where('status', 'checked_by_kasi')->latest()->get();
        return view('sekcam.silang_sengketa.index', compact('pengajuan'));
    }


    public function sengketadApprove($id)
    {
        $item = SengketaSubmission::findOrFail($id);
        $item->status = 'approved_by_sekcam';
        $item->approved_sekcam_at = now();
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan Silang Sengketa berhasil disetujui oleh Sekcam.');
    }


    public function sengketadReject(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string|max:255']);

        $item = SengketaSubmission::findOrFail($id);
        $item->status = 'rejected_by_sekcam';
        $item->rejected_sekcam_reason = $request->reason;
        $item->approved_sekcam_at = now();
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan Silang Sengketa berhasil ditolak oleh Sekcam.');
    }

    public function sengketaProsesX()
    {                                
        $pengajuanDiterima  = SengketaSubmission::whereIn('status', ['checked_by_kasi', 'approved_by_sekcam', 'rejected_by_sekcam'])->count();
        $pengajuanmasuk  = SengketaSubmission::whereIn('status', ['checked_by_kasi'])->count();
        $pengajuanDisetujui = SengketaSubmission::where('status', 'approved_by_sekcam')->count();
        $pengajuanDitolak   = SengketaSubmission::where('status', 'rejected_by_sekcam')->count();

        $pengajuan = SengketaSubmission::whereIn('status', ['approved_by_sekcam', 'rejected_by_sekcam'])
                        ->latest()
                        ->paginate(10);

        return view('sekcam.silang_sengketa.proses', compact(
            'pengajuan',
            'pengajuanmasuk',
            'pengajuanDiterima',
            'pengajuanDisetujui',
            'pengajuanDitolak'
        ));
    }

    // ================= Catin TNI/POLRI  =================
    public function catintniIndex()
    {
        $pengajuan = CatinTniPolriSubmission::where('status', 'checked_by_kasi')->latest()->get();
        return view('sekcam.catin-tni.index', compact('pengajuan'));
    }


    public function catintnidApprove($id)
    {
        $item = CatinTniPolriSubmission::findOrFail($id);
        $item->status = 'approved_by_sekcam';
        $item->approved_sekcam_at = now();
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan catin TNI/Polri berhasil disetujui oleh Sekcam.');
    }


    public function catintnidReject(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string|max:255']);

        $item = CatinTniPolriSubmission::findOrFail($id);
        $item->status = 'rejected_by_sekcam';
        $item->rejected_sekcam_reason = $request->reason;
        $item->approved_sekcam_at = now();
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan catin TNI/Polri berhasil ditolak oleh Sekcam.');
    }

    public function catintniProses()
    {

        $pengajuanDiterima  = CatinTniPolriSubmission::whereIn('status', ['checked_by_kasi', 'approved_by_sekcam', 'rejected_by_sekcam'])->count();
        $pengajuanmasuk  = CatinTniPolriSubmission::whereIn('status', ['checked_by_kasi'])->count();
        $pengajuanDisetujui = CatinTniPolriSubmission::where('status', 'approved_by_sekcam')->count();
        $pengajuanDitolak   = CatinTniPolriSubmission::where('status', 'rejected_by_sekcam')->count();

        $pengajuan = CatinTniPolriSubmission::whereIn('status', ['approved_by_sekcam', 'rejected_by_sekcam'])
                        ->latest()
                        ->paginate(10);

        return view('sekcam.catin-tni.proses', compact(
            'pengajuan',
            'pengajuanmasuk',
            'pengajuanDiterima',
            'pengajuanDisetujui',
            'pengajuanDitolak'
        ));
    }

    // ================= SKBD =================
    public function skbdIndex()
    {
        $pengajuan = SkbdSubmission::where('status', 'checked_by_kasi')->latest()->get();
        return view('sekcam.skbd.index', compact('pengajuan'));
    }


    public function skbdApprove($id)
    {
        $item = SkbdSubmission::findOrFail($id);
        $item->status = 'approved_by_sekcam';
        $item->approved_sekcam_at = now();
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan SKBD berhasil disetujui oleh Sekcam.');
    }


    public function skbdReject(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string|max:255']);

        $item = SkbdSubmission::findOrFail($id);
        $item->status = 'rejected_by_sekcam';
        $item->rejected_sekcam_reason = $request->reason;
        $item->approved_sekcam_at = now();
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan SKBD berhasil ditolak oleh Sekcam.');
    }

    public function skbdProses()
    {

        $pengajuanDiterima  = SkbdSubmission::whereIn('status', ['checked_by_kasi', 'approved_by_sekcam', 'rejected_by_sekcam'])->count();
        $pengajuanDisetujui = SkbdSubmission::where('status', 'approved_by_sekcam')->count();
        $pengajuanDitolak   = SkbdSubmission::where('status', 'rejected_by_sekcam')->count();

        $pengajuan = SkbdSubmission::whereIn('status', ['approved_by_sekcam', 'rejected_by_sekcam'])
                        ->latest()
                        ->paginate(10);

        return view('sekcam.skbd.proses', compact(
            'pengajuan',
            'pengajuanDiterima',
            'pengajuanDisetujui',
            'pengajuanDitolak'
        ));
    }


}
