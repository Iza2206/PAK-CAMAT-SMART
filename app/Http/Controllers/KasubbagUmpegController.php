<?php

namespace App\Http\Controllers;

use App\Models\BpjsSubmission;
use App\Models\SkrisetKKNSubmission;
use Illuminate\Http\Request;

class KasubbagUmpegController extends Controller
{
    public function index()
    {
          // Ambil data pengajuan BPJS yang masih diajukan
        $submissions = BpjsSubmission::where('status', 'diajukan')->latest()->paginate(10);

        // Data statistik
        $jumlahPengajuan      = BpjsSubmission::count();
        $pengajuanDiajukan    = BpjsSubmission::where('status', 'diajukan')->count();
        $pengajuanDisetujui   = BpjsSubmission::where('status', 'checked_by_kasi')->count();
        $pengajuanDitolak     = BpjsSubmission::where('status', 'rejected')->count();
        return view('kasubbag_umpeg.dashboard', compact(
            'submissions',
            'jumlahPengajuan',
            'pengajuanDiajukan',
            'pengajuanDisetujui',
            'pengajuanDitolak'
        ));
    }

     // ----------------  SK Riset KKN ----------------

    public function skrisetKKNIndex()
    {
        $pengajuan = SkrisetKKNSubmission::where('status', 'diajukan')->get();

        return view('kasubbag_umpeg.skrisetKKN.index', compact('pengajuan'));
    }

    public function skrisetKKNApprove($id)
    {
        $item = SkrisetKKNSubmission::findOrFail($id);
        $item->status = 'checked_by_kasi';
        $item->verified_at = now();
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan berhasil diverifikasi oleh Kasi Kesos.');
    }

    public function skrisetKKNReject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        $item = SkrisetKKNSubmission::findOrFail($id);
        $item->status = 'rejected_by_kasi';
        $item->rejected_reason = $request->reason;
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan berhasil ditolak oleh Kasi Kesos.');
    }

    public function skrisetKKNProses()
    {
        $jumlahPengajuan      = SkrisetKKNSubmission::count();
        $pengajuanDiajukan    = SkrisetKKNSubmission::where('status', 'diajukan')->count();
        $pengajuanDisetujui   = SkrisetKKNSubmission::where('status', 'checked_by_kasi')->count();
        $pengajuanDitolak     = SkrisetKKNSubmission::where('status', 'rejected_by_kasi')->count();

        $pengajuan = SkrisetKKNSubmission::whereIn('status', ['checked_by_kasi', 'rejected_by_kasi'])
            ->latest()
            ->paginate(10);

        return view('kasubbag_umpeg.skrisetKKN.proses', compact(
            'pengajuan',
            'jumlahPengajuan',
            'pengajuanDiajukan',
            'pengajuanDisetujui',
            'pengajuanDitolak'
        ));
    }

    public function uploadSuratskrisetKKN(Request $request, $id)
    {
        try {
            $request->validate([
                'file_surat' => 'required|max:2048',
            ]);

            $pengajuan = SkrisetKKNSubmission::findOrFail($id);

            // Simpan file
            $path = $request->file('file_surat')->store('skrisetKKN/surat', 'public');

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

    public function skrisetKKNApproveByCamatIndex()
    {
        $pengajuan = SkrisetKKNSubmission::with('camat')
            ->where('status', 'approved_by_camat')
            ->get();

        return view('kasubbag_umpeg.skrisetKKN.approveByCamat', compact('pengajuan'));
    }

    public function prosesskrisetKKN($id)
    {
        $item = SkrisetKKNSubmission::with('camat')->findOrFail($id);

        // Pastikan pengajuan sudah disetujui Camat
        if ($item->status !== 'approved_by_camat') {
            return redirect()->back()->with('error', 'Pengajuan belum disetujui oleh Camat.');
        }

        return view('kasubbag_umpeg.skrisetKKN.formapprovebyCamat', compact('item'));
    }

    public function prosesStoreskrisetKKN(Request $request, $id)
    {
        $item = SkrisetKKNSubmission::findOrFail($id);

        // Validasi input file surat_final
        $request->validate([
            'surat_final' => 'required|file|mimes:pdf,doc,docx|max:5120', // max 5MB
        ]);

        // Simpan file ke storage (misal folder 'surat_final')
        if ($request->hasFile('surat_final')) {
            $file = $request->file('surat_final');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('surat_final', $filename, 'public'); // simpan di storage/app/public/surat_final
            $item->surat_final = $path; // simpan path relatif
        }

        $item->save();

        return redirect()->route('kasubbag_umpeg.skrisetKKN.approveByCamat')->with('success', 'Surat final berhasil diunggah.');
    }
}
