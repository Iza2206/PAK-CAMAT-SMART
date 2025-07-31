<?php

namespace App\Http\Controllers;

use App\Models\BpjsSubmission;
use Illuminate\Http\Request;
use App\Models\IkmSubmission;
use App\Models\SktmDispensasiSubmission;
use App\Models\SkbdSubmission;
use Illuminate\Support\Str;
use App\Models\CatinTniPolriSubmission;

class MejaLayananController extends Controller
{
    public function index()
    {
        $submissions = IkmSubmission::with('user')->latest()->paginate(10);
        $totalNilai = IkmSubmission::avg('nilai');
        $jumlahResponden = IkmSubmission::count();
        $avgDuration = IkmSubmission::avg('duration_seconds');
        return view('mejalayanan.meja-layanan', compact(
            'submissions',
            'totalNilai',
            'jumlahResponden',
            'avgDuration'
        ));
    }

    // ---------------- BPJS ----------------

    // list bpjs 
    public function bpjsList()
    {
        $data = BpjsSubmission::latest()->paginate(10); // urut dari terbaru + paginate
        return view('mejalayanan.bpjs.index', compact('data'));
    }

    // tambah bpjs
    public function bpjsCreate()
    {
        return view('mejalayanan.bpjs.create');
    }
    
    // simpan bpjs
    public function bpjsStore(Request $request)
    {
    $validated = $request->validate([
        'nama_pemohon' => 'required|string|max:255',
        'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
        'pendidikan' => 'required|in:SD,SMP,SMA,D1,D2,D3,S1,S2,S3',
        // hanya 16 angka
        'nik_pemohon' => 'required|digits:16',
        // Validasi hanya file PDF
        'surat_permohonan' => 'required|file|mimetypes:application/pdf',
        'sktm' => 'required|file|mimetypes:application/pdf',
        'kk' => 'required|file|mimetypes:application/pdf',
        'ktp' => 'required|file|mimetypes:application/pdf',
        'tanda_lunas_pbb' => 'required|file|mimetypes:application/pdf',
    ]);

    $paths = [
        'surat_permohonan' => $request->file('surat_permohonan')->storeAs('bpjs', Str::uuid() . '.pdf', 'public'),
        'sktm' => $request->file('sktm')->storeAs('bpjs', Str::uuid() . '.pdf', 'public'),
        'kk' => $request->file('kk')->storeAs('bpjs', Str::uuid() . '.pdf', 'public'),
        'ktp' => $request->file('ktp')->storeAs('bpjs', Str::uuid() . '.pdf', 'public'),
        'tanda_lunas_pbb' => $request->file('tanda_lunas_pbb')->storeAs('bpjs', Str::uuid() . '.pdf', 'public'),
    ];

     $submission = BpjsSubmission::create([
        'nama_pemohon' => $validated['nama_pemohon'],
        'jenis_kelamin' => $validated['jenis_kelamin'],
        'pendidikan' => $validated['pendidikan'],
        'nik_pemohon' => $validated['nik_pemohon'],
        'surat_permohonan' => $paths['surat_permohonan'],
        'sktm' => $paths['sktm'],
        'kk' => $paths['kk'],
        'ktp' => $paths['ktp'],
        'tanda_lunas_pbb' => $paths['tanda_lunas_pbb'],
        'status' => 'diajukan',
    ]);
        return redirect()
            ->route('bpjs.list')
            ->with('success', 'Data berhasil disimpan.')
            ->with('antrian', $submission->queue_number);
    }

    // kirim ke kasi kesos
    public function kirimKeKasi($id)
    {
        $data = BpjsSubmission::findOrFail($id);
        
        if ($data->status !== 'diajukan') {
            return back()->with('error', 'Data sudah diproses.');
        }

        $data->update([
            'status' => 'diproses',
            'verified_at' => now(),
        ]);

        return back()->with('success', 'Pengajuan berhasil dikirim ke Kasi Kesos.');
    }

    // Verifikasi oleh Kasi
    public function verifikasiKasi($id) {
        $data = BpjsSubmission::findOrFail($id);
        if ($data->status === 'diajukan') {
            $data->update(['status' => 'verifikasi_kasi', 'verified_at' => now()]);
        }
        return back();
    }

    // Persetujuan Sekcam
    public function approveSekcam($id) {
        $data = BpjsSubmission::findOrFail($id);
        if ($data->status === 'verifikasi_kasi') {
            $data->update(['status' => 'disetujui_sekcam', 'approved_sekcam_at' => now()]);
        }
        return back();
    }

    // Persetujuan Camat
    public function approveCamat($id) {
        $data = BpjsSubmission::findOrFail($id);
        if ($data->status === 'disetujui_sekcam') {
            $data->update(['status' => 'disetujui_camat', 'approved_camat_at' => now()]);
        }
        return back();
    }


    // ---------------- SKTM ----------------

     // list SKTM 
    public function SKTMsList()
    {
        $data = SktmDispensasiSubmission::latest()->paginate(10); // urut dari terbaru + paginate
        return view('mejalayanan.sktm.index', compact('data'));
    }

    // tambah SKTM
    public function SKTMsCreate()
    {
        return view('mejalayanan.sktm.create');
    }
    
    // simpan SKTM
    public function SKTMsStore(Request $request)
    {
        $validated = $request->validate([
            'nama_pemohon' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'pendidikan' => 'required|in:SD,SMP,SMA,D1,D2,D3,S1,S2,S3',
            'nik_pemohon' => 'required|digits:16',
            

            // Semua file harus PDF
            'sktm_desa' => 'required|file|mimetypes:application/pdf',
            'kk' => 'required|file|mimetypes:application/pdf',
            'ktp' => 'required|file|mimetypes:application/pdf',
            'buku_nikah' => 'required|file|mimetypes:application/pdf',
            'tanda_lunas_pbb' => 'required|file|mimetypes:application/pdf',
        ]);

        // Simpan file ke folder 'sktm_dispensasi' di storage
        $paths = [
            'sktm_desa' => $request->file('sktm_desa')->storeAs('sktm_dispensasi', Str::uuid() . '.pdf', 'public'),
            'kk' => $request->file('kk')->storeAs('sktm_dispensasi', Str::uuid() . '.pdf', 'public'),
            'ktp' => $request->file('ktp')->storeAs('sktm_dispensasi', Str::uuid() . '.pdf', 'public'),
            'buku_nikah' => $request->file('buku_nikah')->storeAs('sktm_dispensasi', Str::uuid() . '.pdf', 'public'),
            'tanda_lunas_pbb' => $request->file('tanda_lunas_pbb')->storeAs('sktm_dispensasi', Str::uuid() . '.pdf', 'public'),
        ];

        $submission = SktmDispensasiSubmission::create([
            'nama_pemohon' => $validated['nama_pemohon'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'pendidikan' => $validated['pendidikan'],
            'nik_pemohon' => $validated['nik_pemohon'],
            'sktm_desa' => $paths['sktm_desa'],
            'kk' => $paths['kk'],
            'ktp' => $paths['ktp'],
            'buku_nikah' => $paths['buku_nikah'],
            'tanda_lunas_pbb' => $paths['tanda_lunas_pbb'],
            'status' => 'diajukan',
        ]);
        return redirect()
        ->route('SKTMs.list') // ganti sesuai route kamu
        ->with('success', 'Pengajuan SKTM berhasil disimpan.')
        ->with('antrian', $submission->queue_number);
    }

    // kirim ke kasi kesos (SKTM)
    public function SKTMkirimKeKasi($id)
    {
        $data = SktmDispensasiSubmission::findOrFail($id);
        
        if ($data->status !== 'diajukan') {
            return back()->with('error', 'Data sudah diproses.');
        }

        $data->update([
            'status' => 'diproses',
            'verified_at' => now(),
        ]);

        return back()->with('success', 'Pengajuan berhasil dikirim ke Kasi Kesos.');
    }

    // Verifikasi oleh Kasi (SKTM)
    public function SKTMverifikasiKasi($id) {
        $data = BpjsSubmission::findOrFail($id);
        if ($data->status === 'diajukan') {
            $data->update(['status' => 'verifikasi_kasi', 'verified_at' => now()]);
        }
        return back();
    }

    // Persetujuan Sekcam (SKTM)
    public function SKTMapproveSekcam($id) {
        $data = BpjsSubmission::findOrFail($id);
        if ($data->status === 'verifikasi_kasi') {
            $data->update(['status' => 'disetujui_sekcam', 'approved_sekcam_at' => now()]);
        }
        return back();
    }

    // Persetujuan Camat (SKTM)
    public function SKTMapproveCamat($id) {
        $data = BpjsSubmission::findOrFail($id);
        if ($data->status === 'disetujui_sekcam') {
            $data->update(['status' => 'disetujui_camat', 'approved_camat_at' => now()]);
        }
        return back();
    }

    // ---------------- SKBD ----------------

    // List SKBD
    public function SKBDsList()
    {
        $data = SkbdSubmission::latest()->paginate(10);
        return view('mejalayanan.skbd.index', compact('data'));
    }

    // Form tambah SKBD
    public function SKBDsCreate()
    {
        return view('mejalayanan.skbd.create');
    }

    // Simpan SKBD
    public function SKBDsStore(Request $request)
    {
        $validated = $request->validate([
        'nama_pemohon' => 'required|string|max:255',
        'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
        'pendidikan' => 'required|in:SD,SMP,SMA,D1,D2,D3,S1,S2,S3',
        'nik_pemohon' => 'required|digits:16',

        'skbd_desa' => 'required|file|mimetypes:application/pdf',
        'ktp' => 'required|file|mimetypes:application/pdf',
        'kk' => 'required|file|mimetypes:application/pdf',
        'tanda_lunas_pbb' => 'required|file|mimetypes:application/pdf',
    ]);

    // Simpan file PDF ke storage/app/public/skbd/ dengan nama UUID
    $paths = [
        'skbd_desa' => $request->file('skbd_desa')->storeAs('skbd', Str::uuid() . '.pdf', 'public'),
        'ktp' => $request->file('ktp')->storeAs('skbd', Str::uuid() . '.pdf', 'public'),
        'kk' => $request->file('kk')->storeAs('skbd', Str::uuid() . '.pdf', 'public'),
        'tanda_lunas_pbb' => $request->file('tanda_lunas_pbb')->storeAs('skbd', Str::uuid() . '.pdf', 'public'),
    ];

    $submission = SkbdSubmission::create([
        'user_id' => auth()->id(),
        'nama_pemohon' => $validated['nama_pemohon'],
        'jenis_kelamin' => $validated['jenis_kelamin'],
        'pendidikan' => $validated['pendidikan'],
        'nik_pemohon' => $validated['nik_pemohon'],
        'skbd_desa' => $paths['skbd_desa'],
        'ktp' => $paths['ktp'],
        'kk' => $paths['kk'],
        'tanda_lunas_pbb' => $paths['tanda_lunas_pbb'],
        'status' => 'diajukan',
    ]);
    return redirect()
        ->route('SKBDs.list') // ganti sesuai route kamu
        ->with('success', 'Pengajuan SKBD berhasil disimpan.')
        ->with('antrian', $submission->queue_number);
    }

    // Kirim ke Kasi Trantib
    public function SKBDkirimKeKasi($id)
    {
        $data = SkbdSubmission::findOrFail($id);

        if ($data->status !== 'diajukan') {
            return back()->with('error', 'Data sudah diproses.');
        }

        $data->update([
            'status' => 'checked_by_kasi',
            'verified_at' => now(),
        ]);

        return back()->with('success', 'Pengajuan berhasil dikirim ke Kasi Trantib.');
    }

      // ---------------- Catin TNI POLRI ----------------

    // list catin
    public function catinTniList()
    {
        $data = CatinTniPolriSubmission::latest()->paginate(10);
        return view('mejalayanan.catin_tni.index', compact('data'));
    }

    // tambah data catin 
    public function catinTniCreate()
    {
        return view('mejalayanan.catin_tni.create');
    }

    public function catinTniStore(Request $request)
    {
        $validated = $request->validate([
            'nama_pemohon' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'pendidikan' => 'required|in:SD,SMP,SMA,D1,D2,D3,S1,S2,S3',
            'nik_pemohon' => 'required|digits:16',

            // Dokumen Sipil (required jika dokumen TNI tidak diisi)
            'file_ktp' => 'nullable|file|mimes:pdf',
            'file_kk' => 'nullable|file|mimes:pdf',
            'file_akta_kelahiran' => 'nullable|file|mimes:pdf',
            'file_pas_foto_3x4' => 'nullable|file|mimes:pdf',
            'file_pas_foto_4x6' => 'nullable|file|mimes:pdf',
            'file_pengantar_rt_rw' => 'nullable|file|mimes:pdf',
            'file_surat_n1' => 'nullable|file|mimes:pdf',
            'file_surat_n2' => 'nullable|file|mimes:pdf',
            'file_surat_n3' => 'nullable|file|mimes:pdf',
            'file_surat_n4' => 'nullable|file|mimes:pdf',
            'file_izin_orang_tua' => 'nullable|file|mimes:pdf',
            'file_status_pernikahan' => 'nullable|file|mimes:pdf',

            // Dokumen TNI/POLRI (required jika dokumen sipil tidak diisi)
            'file_surat_izin_kawin' => 'nullable|file|mimes:pdf',
            'file_keterangan_belum_menikah_tni' => 'nullable|file|mimes:pdf',
            'file_pernyataan_kesediaan' => 'nullable|file|mimes:pdf',
            'file_kta' => 'nullable|file|mimes:pdf',
            'file_sk_pangkat_terakhir' => 'nullable|file|mimes:pdf',
            'file_pas_foto_berdampingan_dinas' => 'nullable|file|mimes:pdf',
            'file_pas_foto_berdampingan_formal' => 'nullable|file|mimes:pdf',

            // Tambahan
            'file_pbb' => 'nullable|file|mimes:pdf',
        ]);

        // Daftar semua file
        $fileFields = [
            'file_ktp', 'file_kk', 'file_akta_kelahiran', 'file_pas_foto_3x4',
            'file_pas_foto_4x6', 'file_pengantar_rt_rw', 'file_surat_n1',
            'file_surat_n2', 'file_surat_n3', 'file_surat_n4', 'file_izin_orang_tua',
            'file_status_pernikahan', 'file_surat_izin_kawin', 'file_keterangan_belum_menikah_tni',
            'file_pernyataan_kesediaan', 'file_kta', 'file_sk_pangkat_terakhir',
            'file_pas_foto_berdampingan_dinas', 'file_pas_foto_berdampingan_formal',
            'file_pbb',
        ];

        $paths = [];
        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $paths[$field] = $request->file($field)->storeAs('catin_tni', Str::uuid() . '.pdf', 'public');
            }
        }

        $submission = CatinTniPolriSubmission::create([
            'user_id' => auth()->id(),
            'nama_pemohon' => $validated['nama_pemohon'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'pendidikan' => $validated['pendidikan'],
            'nik_pemohon' => $validated['nik_pemohon'],
            'status' => 'diajukan',
            ...$paths,
        ]);

        return redirect()
            ->route('catin.tni.list')
            ->with('success', 'Pengajuan Catin TNI/Polri berhasil disimpan.')
            ->with('antrian', $submission->queue_number);
    }


    // kirim ke kasi trantib 
    public function catinTniKirimKasi($id)
    {
        $data = CatinTniPolriSubmission::findOrFail($id);

        if ($data->status !== 'diajukan') {
            return back()->with('error', 'Data sudah diproses.');
        }

        $data->update([
            'status' => 'checked_by_kasi',
            'verified_at' => now(),
        ]);

        return back()->with('success', 'Pengajuan berhasil dikirim ke Kasi Trantib.');
    }





    
    public function dispensasi() {
        return view('mejalayanan.dispensasi');
    }

    public function iumk() {
        return view('mejalayanan.iumk');
    }
}
