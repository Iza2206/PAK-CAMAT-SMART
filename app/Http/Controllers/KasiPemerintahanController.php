<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SengketaSubmission;
use App\Models\AgunanSubmission;
use App\Models\AhliWarisSubmission;
use App\Models\SppatGrSubmission;
use App\Models\SktSubmission;

class KasiPemerintahanController extends Controller
{
    public function index()
    {
        $jumlahSkt       = SktSubmission::count();
        $jumlahSppatGr   = SppatGrSubmission::count();
        $jumlahAhliWaris = AhliWarisSubmission::count();
        $jumlahAgunan    = AgunanSubmission::count();
        $jumlahSengketa  = SengketaSubmission::count();

        return view('kasi_pemerintahan.dashboard', compact(
            'jumlahSkt',
            'jumlahSppatGr',
            'jumlahAhliWaris',
            'jumlahAgunan',
            'jumlahSengketa'
        ));
    }

    // ------------------- SILANG SENGKETA -------------------
    public function silangSengketaIndex()
    {
        $pengajuan = SengketaSubmission::where('status', 'diajukan')->latest()->get();
        return view('kasi_pemerintahan.silang_sengketa.index', compact('pengajuan'));
    }

    public function silangSengketaApprove($id)
    {
        $item = SengketaSubmission::findOrFail($id);
        $item->status = 'checked_by_kasi';
        $item->verified_at = now();
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan Silang Sengketa berhasil diverifikasi.');
    }

    public function silangSengketaReject(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string|max:255']);

        $item = SengketaSubmission::findOrFail($id);
        $item->status = 'rejected';
        $item->rejected_reason = $request->reason;
        $item->verified_at = now();
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan Silang Sengketa ditolak.');
    }

    public function silangSengketaProses()
    {
        $jumlahPengajuan     = SengketaSubmission::count();
        $pengajuanDiajukan   = SengketaSubmission::where('status', 'diajukan')->count();
        $pengajuanDisetujui  = SengketaSubmission::where('status', 'checked_by_kasi')->count();
        $pengajuanDitolak    = SengketaSubmission::where('status', 'rejected')->count();

        $pengajuan = SengketaSubmission::whereIn('status', ['checked_by_kasi', 'rejected'])
                            ->latest()->paginate(10);

        return view('kasi_pemerintahan.silang_sengketa.proses', compact(
            'pengajuan',
            'jumlahPengajuan',
            'pengajuanDiajukan',
            'pengajuanDisetujui',
            'pengajuanDitolak'
        ));
    }

    // ------------------- AGUNAN BANK -------------------
    public function agunanBankIndex()
    {
        $pengajuan = AgunanSubmission::where('status', 'diajukan')->latest()->get();
        return view('kasi_pemerintahan.agunan_bank.index', compact('pengajuan'));
    }

    public function agunanBankApprove($id)
    {
        $item = AgunanSubmission::findOrFail($id);
        $item->status = 'checked_by_kasi';
        $item->verified_at = now();
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan Agunan Bank berhasil diverifikasi.');
    }

    public function agunanBankReject(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string|max:255']);

        $item = AgunanSubmission::findOrFail($id);
        $item->status = 'rejected';
        $item->rejected_reason = $request->reason;
        $item->verified_at = now();
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan Agunan Bank ditolak.');
    }

    public function agunanBankProses()
    {
        $jumlahPengajuan     = AgunanSubmission::count();
        $pengajuanDiajukan   = AgunanSubmission::where('status', 'diajukan')->count();
        $pengajuanDisetujui  = AgunanSubmission::where('status', 'checked_by_kasi')->count();
        $pengajuanDitolak    = AgunanSubmission::where('status', 'rejected')->count();

        $pengajuan = AgunanSubmission::whereIn('status', ['checked_by_kasi', 'rejected'])
                            ->latest()->paginate(10);

        return view('kasi_pemerintahan.agunan_bank.proses', compact(
            'pengajuan',
            'jumlahPengajuan',
            'pengajuanDiajukan',
            'pengajuanDisetujui',
            'pengajuanDitolak'
        ));
    }

    // ------------------- AHLI WARIS -------------------
    public function ahliWarisIndex()
    {
        $pengajuan = AhliWarisSubmission::where('status', 'diajukan')->latest()->get();
        return view('kasi_pemerintahan.ahli_waris.index', compact('pengajuan'));
    }

    public function ahliWarisApprove($id)
    {
        $item = AhliWarisSubmission::findOrFail($id);
        $item->status = 'checked_by_kasi';
        $item->verified_at = now();
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan Ahli Waris berhasil diverifikasi.');
    }

    public function ahliWarisReject(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string|max:255']);

        $item = AhliWarisSubmission::findOrFail($id);
        $item->status = 'rejected';
        $item->rejected_reason = $request->reason;
        $item->verified_at = now();
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan Ahli Waris ditolak.');
    }

    public function ahliWarisProses()
    {
        $jumlahPengajuan     = AhliWarisSubmission::count();
        $pengajuanDiajukan   = AhliWarisSubmission::where('status', 'diajukan')->count();
        $pengajuanDisetujui  = AhliWarisSubmission::where('status', 'checked_by_kasi')->count();
        $pengajuanDitolak    = AhliWarisSubmission::where('status', 'rejected')->count();

        $pengajuan = AhliWarisSubmission::whereIn('status', ['checked_by_kasi', 'rejected'])
                            ->latest()->paginate(10);

        return view('kasi_pemerintahan.ahli_waris.proses', compact(
            'pengajuan',
            'jumlahPengajuan',
            'pengajuanDiajukan',
            'pengajuanDisetujui',
            'pengajuanDitolak'
        ));
    }

    // ------------------- SPPAT GR -------------------
    public function sppatGrIndex()
    {
        $pengajuan = SppatGrSubmission::where('status', 'diajukan')->latest()->get();
        return view('kasi_pemerintahan.sppat_gr.index', compact('pengajuan'));
    }

    public function sppatGrApprove($id)
    {
        $item = SppatGrSubmission::findOrFail($id);
        $item->status = 'checked_by_kasi';
        $item->verified_at = now();
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan SPPAT-GR berhasil diverifikasi.');
    }

    public function sppatGrReject(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string|max:255']);

        $item = SppatGrSubmission::findOrFail($id);
        $item->status = 'rejected';
        $item->rejected_reason = $request->reason;
        $item->verified_at = now();
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan SPPAT-GR ditolak.');
    }

    public function sppatGrProses()
    {
        $jumlahPengajuan     = SppatGrSubmission::count();
        $pengajuanDiajukan   = SppatGrSubmission::where('status', 'diajukan')->count();
        $pengajuanDisetujui  = SppatGrSubmission::where('status', 'checked_by_kasi')->count();
        $pengajuanDitolak    = SppatGrSubmission::where('status', 'rejected')->count();

        $pengajuan = SppatGrSubmission::whereIn('status', ['checked_by_kasi', 'rejected'])
                            ->latest()->paginate(10);

        return view('kasi_pemerintahan.sppat_gr.proses', compact(
            'pengajuan',
            'jumlahPengajuan',
            'pengajuanDiajukan',
            'pengajuanDisetujui',
            'pengajuanDitolak'
        ));
    }

    // ------------------- SKT -------------------
    public function sktIndex()
    {
        $pengajuan = SktSubmission::where('status', 'diajukan')->latest()->get();
        return view('kasi_pemerintahan.skt.index', compact('pengajuan'));
    }

    public function sktApprove($id)
    {
        $item = SktSubmission::findOrFail($id);
        $item->status = 'checked_by_kasi';
        $item->verified_at = now();
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan SKT berhasil diverifikasi.');
    }

    public function sktReject(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string|max:255']);

        $item = SktSubmission::findOrFail($id);
        $item->status = 'rejected';
        $item->rejected_reason = $request->reason;
        $item->verified_at = now();
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan SKT ditolak.');
    }

    public function sktProses()
    {
        $jumlahPengajuan     = SktSubmission::count();
        $pengajuanDiajukan   = SktSubmission::where('status', 'diajukan')->count();
        $pengajuanDisetujui  = SktSubmission::where('status', 'checked_by_kasi')->count();
        $pengajuanDitolak    = SktSubmission::where('status', 'rejected')->count();

        $pengajuan = SktSubmission::whereIn('status', ['checked_by_kasi', 'rejected'])
                            ->latest()->paginate(10);

        return view('kasi_pemerintahan.skt.proses', compact(
            'pengajuan',
            'jumlahPengajuan',
            'pengajuanDiajukan',
            'pengajuanDisetujui',
            'pengajuanDitolak'
        ));
    }
}
