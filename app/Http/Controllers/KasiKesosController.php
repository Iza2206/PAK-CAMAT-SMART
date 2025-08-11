<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BpjsSubmission;
use App\Models\CatinSubmission;
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

    // ---------------- BPJS ----------------
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

    // ---------------- DISPENSASI CATIN NIKAH ----------------

    public function dispencatinIndex()
    {
        $pengajuan = CatinSubmission::where('status', 'diajukan')->get();

        return view('kasi_kesos.dispencatin.index', compact('pengajuan'));
    }

    public function dispencatinApprove($id)
    {
        $item = CatinSubmission::findOrFail($id);
        $item->status = 'checked_by_kasi';
        $item->verified_at = now();
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan berhasil diverifikasi oleh Kasi Kesos.');
    }

    public function dispencatinReject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        $item = CatinSubmission::findOrFail($id);
        $item->status = 'rejected_by_kasi';
        $item->rejected_reason = $request->reason;
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan berhasil ditolak oleh Kasi Kesos.');
    }

    public function dispencatinProses()
    {
        $jumlahPengajuan      = CatinSubmission::count();
        $pengajuanDiajukan    = CatinSubmission::where('status', 'diajukan')->count();
        $pengajuanDisetujui   = CatinSubmission::where('status', 'checked_by_kasi')->count();
        $pengajuanDitolak     = CatinSubmission::where('status', 'rejected_by_kasi')->count();

        $pengajuan = CatinSubmission::whereIn('status', ['checked_by_kasi', 'rejected_by_kasi'])
            ->latest()
            ->paginate(10);

        return view('kasi_kesos.dispencatin.proses', compact(
            'pengajuan',
            'jumlahPengajuan',
            'pengajuanDiajukan',
            'pengajuanDisetujui',
            'pengajuanDitolak'
        ));
    }

    public function uploadSuratDispensasi(Request $request, $id)
    {
        try {
            $request->validate([
                'file_surat' => 'required|max:2048',
            ]);

            $pengajuan = CatinSubmission::findOrFail($id);

            // Simpan file
            $path = $request->file('file_surat')->store('dispensasi/surat', 'public');

            // Update ke database
            $pengajuan->file_surat = $path;
            $pengajuan->save();

            return back()->with('success', 'Surat berhasil diupload.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Menangani error validasi
            return back()->withErrors($e->errors())->withInput();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Menangani jika data tidak ditemukan
            return back()->with('error', 'Pengajuan tidak ditemukan.');
        } catch (\Exception $e) {
            // Menangani error umum lainnya
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function dispencatinApproveByCamatIndex()
    {
        $pengajuan = CatinSubmission::where('status', 'approved_by_camat')->get();

        return view('kasi_kesos.dispencatin.approveByCamat', compact('pengajuan'));
    }

    public function proses($id)
    {
        $item = CatinSubmission::findOrFail($id);

        // Pastikan pengajuan sudah disetujui Camat
        if ($item->status !== 'approved_by_camat') {
            return redirect()->back()->with('error', 'Pengajuan belum disetujui oleh Camat.');
        }

        return view('kasi_kesos.dispencatin.formapprovebyCamat', compact('item'));
    }

    public function prosesStore(Request $request, $id)
    {
        $item = CatinSubmission::findOrFail($id);

        // Validasi input (misal tanda tangan digital)
        $request->validate([
            'ttd' => 'required', // bisa file atau data base64 tanda tangan
        ]);

        // Simpan tanda tangan
        $item->file_surat = $request->ttd; // kalau base64 / nama file
        $item->status = 'approved_by_kasi_kesos';
        $item->approved_kasi_kesos_at = now();
        $item->save();

        return redirect()->route('kasi_kesos.dashboard')->with('success', 'Surat berhasil ditandatangani dan pengajuan diproses.');
    }

    // ---------------- SKTM ----------------
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
