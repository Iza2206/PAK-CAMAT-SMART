<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BpjsSubmission;
use App\Models\SktmDispensasiSubmission;
use App\Models\SktSubmission;
use App\Models\SkbdSubmission;

class CamatController extends Controller
{
    // Dashboard Camat
    public function index()
    {
        $submissions = BpjsSubmission::where('status', 'approved_by_sekcam')->latest()->paginate(10);

        $jumlahPengajuan       = BpjsSubmission::count();
        $pengajuanDiterima     = BpjsSubmission::where('status', 'approved_by_sekcam')->count();
        $pengajuanDisetujui    = BpjsSubmission::where('status', 'approved_by_camat')->count();
        $pengajuanDitolak      = BpjsSubmission::where('status', 'rejected_by_camat')->count();

        return view('camat.dashboard', compact(
            'submissions',
            'jumlahPengajuan',
            'pengajuanDiterima',
            'pengajuanDisetujui',
            'pengajuanDitolak'
        ));
    }

    // ================= BPJS =================
    // Tampilkan daftar pengajuan dari Sekcam
    public function bpjsIndex()
    {
        $pengajuan = BpjsSubmission::where('status', 'approved_by_sekcam')->get();
        return view('camat.bpjs.index', compact('pengajuan'));
    }

    // Setujui pengajuan BPJS oleh Camat
    public function bpjsApprove($id)
    {
        $item = BpjsSubmission::findOrFail($id);
        $item->status = 'approved_by_camat';
        $item->approved_camat_at = now();
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan disetujui oleh Camat.');
    }

    // Tolak pengajuan BPJS oleh Camat
    public function bpjsReject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        $item = BpjsSubmission::findOrFail($id);
        $item->status = 'rejected_by_camat';
        $item->rejected_camat_reason = $request->reason;
        $item->approved_camat_at = now(); // catat waktu meskipun ditolak
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan ditolak oleh Camat.');
    }

    // Riwayat pengajuan yang diproses oleh Camat
    public function bpjsProses()
    {
        $jumlahPengajuan       = BpjsSubmission::count();
        $pengajuanDiterima     = BpjsSubmission::where('status', 'approved_by_sekcam')->count();
        $pengajuanDisetujui    = BpjsSubmission::where('status', 'approved_by_camat')->count();
        $pengajuanDitolak      = BpjsSubmission::where('status', 'rejected_by_camat')->count();

        $pengajuan = BpjsSubmission::whereIn('status', ['approved_by_camat', 'rejected_by_camat'])
                            ->latest()
                            ->paginate(10);

        return view('camat.bpjs.proses', compact(
            'pengajuan',
            'jumlahPengajuan',
            'pengajuanDiterima',
            'pengajuanDisetujui',
            'pengajuanDitolak'
        ));
    }

    // ================= SKTM =================
    // Tampilkan daftar pengajuan dari Sekcam
    public function sktmIndex()
    {
        $pengajuan = SktmDispensasiSubmission::where('status', 'approved_by_sekcam')->get();
        return view('camat.sktm.index', compact('pengajuan'));
    }

    // Setujui pengajuan SKTM oleh Camat
    public function sktmApprove($id)
    {
        $item = SktmDispensasiSubmission::findOrFail($id);
        $item->status = 'approved_by_camat';
        $item->approved_camat_at = now();
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan disetujui oleh Camat.');
    }

    // Tolak pengajuan SKTM oleh Camat
    public function sktmReject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        $item = SktmDispensasiSubmission::findOrFail($id);
        $item->status = 'rejected_by_camat';
        $item->rejected_camat_reason = $request->reason;
        $item->approved_camat_at = now();
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan ditolak oleh Camat.');
    }

    // Riwayat pengajuan yang diproses oleh Camat
    public function sktmProses()
    {
        $jumlahPengajuan       = SktmDispensasiSubmission::count();
        $pengajuanDiterima     = SktmDispensasiSubmission::where('status', 'approved_by_sekcam')->count();
        $pengajuanDisetujui    = SktmDispensasiSubmission::where('status', 'approved_by_camat')->count();
        $pengajuanDitolak      = SktmDispensasiSubmission::where('status', 'rejected_by_camat')->count();

        $pengajuan = SktmDispensasiSubmission::whereIn('status', ['approved_by_camat', 'rejected_by_camat'])
                            ->latest()
                            ->paginate(10);

        return view('camat.sktm.proses', compact(
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
        $pengajuan = SktSubmission::where('status', 'approved_by_sekcam')->latest()->get();
        return view('camat.skt.index', compact('pengajuan'));
    }

    public function sktApprove($id)
    {
        $item = SktSubmission::findOrFail($id);
        $item->status = 'approved_by_camat';
        $item->approved_camat_at = now();
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan SKT disetujui oleh Camat.');
    }

    public function sktReject(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string|max:255']);

        $item = SktSubmission::findOrFail($id);
        $item->status = 'rejected_by_camat';
        $item->rejected_camat_reason = $request->reason;
        $item->approved_camat_at = now();
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan SKT ditolak oleh Camat.');
    }

    public function sktProses()
    {
        $jumlahPengajuan     = SktSubmission::count();
        $pengajuanDiterima   = SktSubmission::where('status', 'approved_by_sekcam')->count();
        $pengajuanDisetujui  = SktSubmission::where('status', 'approved_by_camat')->count();
        $pengajuanDitolak    = SktSubmission::where('status', 'rejected_by_camat')->count();

        $pengajuan = SktSubmission::whereIn('status', ['approved_by_camat', 'rejected_by_camat'])
                            ->latest()->paginate(10);

        return view('camat.skt.proses', compact(
            'pengajuan',
            'jumlahPengajuan',
            'pengajuanDiterima',
            'pengajuanDisetujui',
            'pengajuanDitolak'
        ));
    }

    // ================= SKBD =================
    public function skbdIndex()
    {
        $pengajuan = SkbdSubmission::where('status', 'approved_by_sekcam')->latest()->get();
        return view('camat.skbd.index', compact('pengajuan'));
    }

    public function skbdApprove($id)
    {
        $item = SkbdSubmission::findOrFail($id);
        $item->status = 'approved_by_camat';
        $item->approved_camat_at = now();
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan skbd disetujui oleh Camat.');
    }

    public function skbdReject(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string|max:255']);

        $item = SkbdSubmission::findOrFail($id);
        $item->status = 'rejected_by_camat';
        $item->rejected_camat_reason = $request->reason;
        $item->approved_camat_at = now();
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan skbd ditolak oleh Camat.');
    }

    public function skbdProses()
    {
        $jumlahPengajuan     = SkbdSubmission::count();
        $pengajuanDiterima   = SkbdSubmission::where('status', 'approved_by_sekcam')->count();
        $pengajuanDisetujui  = SkbdSubmission::where('status', 'approved_by_camat')->count();
        $pengajuanDitolak    = SkbdSubmission::where('status', 'rejected_by_camat')->count();

        $pengajuan = SkbdSubmission::whereIn('status', ['approved_by_camat', 'rejected_by_camat'])
                            ->latest()->paginate(10);

        return view('camat.skbd.proses', compact(
            'pengajuan',
            'jumlahPengajuan',
            'pengajuanDiterima',
            'pengajuanDisetujui',
            'pengajuanDitolak'
        ));
    }
}
