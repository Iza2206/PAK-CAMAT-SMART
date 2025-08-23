<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BpjsSubmission;
use App\Models\CatinSubmission;
use App\Models\IumkSubmission;
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
        $item->status = 'rejected';
        $item->rejected_reason = $request->reason;
        $item->verified_at = now();  // <-- tambah ini supaya waktu tolak tercatat
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan berhasil ditolak oleh Kasi Kesos.');
    }


  public function dispencatinProses()
{
    $jumlahPengajuan      = CatinSubmission::count();
    $pengajuanDiajukan    = CatinSubmission::where('status', 'diajukan')->count();
    $pengajuanDisetujui   = CatinSubmission::where('status', 'checked_by_kasi')->count();
    $pengajuanDitolak     = CatinSubmission::where('status', 'rejected')->count();

    $pengajuan = CatinSubmission::latest()->paginate(10);

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

    // public function dispencatinApproveByCamatIndex()
    // {
    //     $pengajuan = CatinSubmission::with('camat')
    //         ->where('status', 'approved_by_camat')
    //         ->get();

    //     return view('kasi_kesos.dispencatin.approveByCamat', compact('pengajuan'));
    // }

    // public function proses($id)
    // {
    //     $item = CatinSubmission::with('camat')->findOrFail($id);

    //     // Pastikan pengajuan sudah disetujui Camat
    //     if ($item->status !== 'approved_by_camat') {
    //         return redirect()->back()->with('error', 'Pengajuan belum disetujui oleh Camat.');
    //     }

    //     return view('kasi_kesos.dispencatin.formapprovebyCamat', compact('item'));
    // }

    // public function prosesStore(Request $request, $id)
    // {
    //     $item = CatinSubmission::findOrFail($id);

    //     // Validasi input file surat_final
    //     $request->validate([
    //         'surat_final' => 'required|file|mimes:pdf,doc,docx|max:5120', // max 5MB
    //     ]);

    //     // Simpan file ke storage (misal folder 'surat_final')
    //     if ($request->hasFile('surat_final')) {
    //         $file = $request->file('surat_final');
    //         $filename = time() . '_' . $file->getClientOriginalName();
    //         $path = $file->storeAs('surat_final', $filename, 'public'); // simpan di storage/app/public/surat_final
    //         $item->surat_final = $path; // simpan path relatif
    //     }

    //     $item->save();

    //     return redirect()->route('kasi_kesos.dispencatin.approveByCamat')->with('success', 'Surat final berhasil diunggah.');
    // }

    // ----------------  IUMK ----------------

    public function iumkIndex()
    {
        $pengajuan = IumkSubmission::where('status', 'diajukan')->get();

        return view('kasi_kesos.iumk.index', compact('pengajuan'));
    }

    public function iumkApprove($id)
    {
        $item = IumkSubmission::findOrFail($id);
        $item->status = 'checked_by_kasi';
        $item->verified_at = now();
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan berhasil diverifikasi oleh Kasi Kesos.');
    }

    public function iumkReject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        $item = IumkSubmission::findOrFail($id);
        $item->status = 'rejected_by_kasi';
        $item->rejected_reason = $request->reason;
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan berhasil ditolak oleh Kasi Kesos.');
    }

    public function iumkProses()
    {
        $jumlahPengajuan      = IumkSubmission::count();
        $pengajuanDiajukan    = IumkSubmission::where('status', 'diajukan')->count();
        $pengajuanDisetujui   = IumkSubmission::where('status', 'checked_by_kasi')->count();
        $pengajuanDitolak     = IumkSubmission::where('status', 'rejected_by_kasi')->count();

        $pengajuan = IumkSubmission::latest()->paginate(10);

        return view('kasi_kesos.iumk.proses', compact(
            'pengajuan',
            'jumlahPengajuan',
            'pengajuanDiajukan',
            'pengajuanDisetujui',
            'pengajuanDitolak'
        ));
    }

    public function uploadSuratIumk(Request $request, $id)
    {
        try {
            $request->validate([
                'file_surat' => 'required|max:2048',
            ]);

            $pengajuan = IumkSubmission::findOrFail($id);

            // Simpan file
            $path = $request->file('file_surat')->store('Iumk/surat', 'public');

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

    public function iumkApproveByCamatIndex()
    {
        $pengajuan = IumkSubmission::with('camat')
            ->where('status', 'approved_by_camat')
            ->get();

        return view('kasi_kesos.iumk.approveByCamat', compact('pengajuan'));
    }

    public function prosesiumk($id)
    {
        $item = IumkSubmission::with('camat')->findOrFail($id);

        // Pastikan pengajuan sudah disetujui Camat
        if ($item->status !== 'approved_by_camat') {
            return redirect()->back()->with('error', 'Pengajuan belum disetujui oleh Camat.');
        }

        return view('kasi_kesos.iumk.formapprovebyCamat', compact('item'));
    }

    public function prosesStoreIumk(Request $request, $id)
    {
        $item = IumkSubmission::findOrFail($id);

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

        return redirect()->route('kasi_kesos.iumk.approveByCamat')->with('success', 'Surat final berhasil diunggah.');
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
