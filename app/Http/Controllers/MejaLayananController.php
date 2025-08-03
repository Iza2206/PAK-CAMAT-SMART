<?php

namespace App\Http\Controllers;

use App\Models\BpjsSubmission;
use Illuminate\Http\Request;
use App\Models\IkmSubmission;
use App\Models\SktmDispensasiSubmission;
use App\Models\SkbdSubmission;
use Illuminate\Support\Str;
use App\Models\CatinTniPolriSubmission;
use App\Models\SengketaSubmission;
use App\Models\AgunanSubmission;
use App\Models\AhliwarisSubmission;
use App\Models\SppatGrSubmission;
use App\Models\SktSubmission;
use Illuminate\Pagination\LengthAwarePaginator;

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

public function bpjsList(Request $request)
{
    $query = BpjsSubmission::query();

    if ($request->filled('nik')) {
        $query->where('nik_pemohon', 'like', '%' . $request->nik . '%');
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    /** @var LengthAwarePaginator $data */
    $data = $query->latest()->paginate(10);
    $data = $data->withQueryString();


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

    public function simpanPenilaianBpjs(Request $request, $id)
    {
        $request->validate([
            'penilaian' => 'required|in:tidak_puas,cukup,puas,sangat_puas',
        ]);

        $data = \App\Models\BpjsSubmission::findOrFail($id);

        if ($data->status !== 'approved_by_camat' || $data->penilaian) {
            return back()->with('error', 'Pengajuan tidak valid untuk dinilai.');
        }

        $data->update([
            'penilaian' => $request->penilaian,
            'diambil_at' => now(),
        ]);

        return back()->with('success', 'Penilaian berhasil dikirim.');
    }


   public function cariPengajuanBpjsByNik(Request $request)
    {
        $request->validate([
            'nik' => 'required',
        ]);

        $data = \App\Models\BpjsSubmission::where('nik_pemohon', $request->nik)->paginate(10);

        return view('mejalayanan.bpjs.index', compact('data'));
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

    // ------------------- SKT -------------------

    // LIST
    public function sktList()
    {
        $data = SktSubmission::latest()->paginate(10);
        return view('mejalayanan.skt.index', compact('data'));
    }

    // FORM INPUT
    public function sktCreate()
    {
        return view('mejalayanan.skt.create');
    }

    // SIMPAN
    public function sktStore(Request $request)
    {
        $validated = $request->validate([
            'nama_pemohon'   => 'required|string|max:255',
            'nik_pemohon'    => 'required|digits:16',
            'jenis_kelamin'  => 'required|in:Laki-laki,Perempuan',
            'pendidikan'     => 'required|in:SD,SMP,SMA,D1,D2,D3,S1,S2,S3',

            'file_permohonan' => 'required|file|mimes:pdf',
            'file_alas_hak'   => 'required|file|mimes:pdf',
            'file_kk'         => 'required|file|mimes:pdf',
            'file_ktp'        => 'required|file|mimes:pdf',
            'file_pbb'        => 'required|file|mimes:pdf',
        ]);

        // Simpan file
        $permohonan = $request->file('file_permohonan')->storeAs('skt', Str::uuid() . '.pdf', 'public');
        $alasHak    = $request->file('file_alas_hak')->storeAs('skt', Str::uuid() . '.pdf', 'public');
        $kk         = $request->file('file_kk')->storeAs('skt', Str::uuid() . '.pdf', 'public');
        $ktp        = $request->file('file_ktp')->storeAs('skt', Str::uuid() . '.pdf', 'public');
        $pbb        = $request->file('file_pbb')->storeAs('skt', Str::uuid() . '.pdf', 'public');

        $submission = SktSubmission::create([
            'nama_pemohon'     => $validated['nama_pemohon'],
            'nik_pemohon'      => $validated['nik_pemohon'],
            'jenis_kelamin'    => $validated['jenis_kelamin'],
            'pendidikan'       => $validated['pendidikan'],

            'file_permohonan'  => $permohonan,
            'file_alas_hak'    => $alasHak,
            'file_kk'          => $kk,
            'file_ktp'         => $ktp,
            'file_pbb'         => $pbb,

            'status' => 'diajukan',
        ]);

        return redirect()
            ->route('skt.list')
            ->with('success', 'Pengajuan SKT berhasil disimpan.')
            ->with('antrian', $submission->queue_number);
    }

    // KIRIM KE KASI
    public function sktKirimKeKasi($id)
    {
        $data = SktSubmission::findOrFail($id);

        if ($data->status !== 'diajukan') {
            return back()->with('error', 'Data sudah diproses.');
        }

        $data->update([
            'status' => 'checked_by_kasi',
            'verified_at' => now(),
        ]);

        return back()->with('success', 'Pengajuan SKT berhasil dikirim ke Kasi Pemerintahan.');
    }


     // ---------------- SPPAT GR ----------------

    // LIST
    public function sppatGrList()
    {
        $data = SppatGrSubmission::latest()->paginate(10);
        return view('mejalayanan.sppat_gr.index', compact('data'));
    }

    // FORM INPUT
    public function sppatGrCreate()
    {
        return view('mejalayanan.sppat_gr.create');
    }

    // SIMPAN
    public function sppatGrStore(Request $request)
    {
        $validated = $request->validate([
            'nama_pemohon' => 'required|string|max:255',
            'nik_pemohon' => 'required|digits:16',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'pendidikan' => 'required|in:SD,SMP,SMA,D1,D2,D3,S1,S2,S3',

            'file_permohonan' => 'required|file|mimes:pdf',
            'file_pernyataan_ahli_waris' => 'required|file|mimes:pdf',
            'file_akta_kematian' => 'required|file|mimes:pdf',
            'file_ktp_ahli_waris' => 'required|file|mimes:pdf',
            'file_kk_ahli_waris' => 'required|file|mimes:pdf',
            'file_pbb' => 'required|file|mimes:pdf',
        ]);

        // Simpan file
        $permohonan = $request->file('file_permohonan')->storeAs('sppat_gr', Str::uuid() . '.pdf', 'public');
        $pernyataan = $request->file('file_pernyataan_ahli_waris')->storeAs('sppat_gr', Str::uuid() . '.pdf', 'public');
        $akta = $request->file('file_akta_kematian')->storeAs('sppat_gr', Str::uuid() . '.pdf', 'public');
        $ktp = $request->file('file_ktp_ahli_waris')->storeAs('sppat_gr', Str::uuid() . '.pdf', 'public');
        $kk = $request->file('file_kk_ahli_waris')->storeAs('sppat_gr', Str::uuid() . '.pdf', 'public');
        $pbb = $request->file('file_pbb')->storeAs('sppat_gr', Str::uuid() . '.pdf', 'public');

        $submission = SppatGrSubmission::create([
            'nama_pemohon' => $validated['nama_pemohon'],
            'nik_pemohon' => $validated['nik_pemohon'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'pendidikan' => $validated['pendidikan'],

            'file_permohonan' => $permohonan,
            'file_pernyataan_ahli_waris' => $pernyataan,
            'file_akta_kematian' => $akta,
            'file_ktp_ahli_waris' => $ktp,
            'file_kk_ahli_waris' => $kk,
            'file_pbb' => $pbb,

            'status' => 'diajukan',
        ]);

        return redirect()
            ->route('sppat_gr.list')
            ->with('success', 'Pengajuan SPPAT-GR berhasil disimpan.')
            ->with('antrian', $submission->queue_number);
    }

    // KIRIM KE KASI
    public function sppatGrKirimKeKasi($id)
    {
        $data = SppatGrSubmission::findOrFail($id);

        if ($data->status !== 'diajukan') {
            return back()->with('error', 'Data sudah diproses.');
        }

        $data->update([
            'status' => 'checked_by_kasi',
            'verified_at' => now(),
        ]);

        return back()->with('success', 'Pengajuan berhasil dikirim ke Kasi Pemerintahan.');
    }

    // ---------------- Ahli Waris ----------------

   // List pengajuan Ahli Waris
    public function ahliwarisList()
    {
        $data = AhliwarisSubmission::latest()->paginate(10);
        return view('mejalayanan.ahliwaris.index', compact('data'));
    }

    // Form input pengajuan Ahli Waris
    public function ahliwarisCreate()
    {
        return view('mejalayanan.ahliwaris.create');
    }

    // Simpan pengajuan Ahli Waris
    public function ahliwarisStore(Request $request)
    {
        $validated = $request->validate([
            'nama_pemohon' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'pendidikan' => 'required|in:SD,SMP,SMA,D1,D2,D3,S1,S2,S3',
            'nik_pemohon' => 'required|digits:16',

            'file_permohonan' => 'required|file|mimes:pdf',
            'file_pernyataan_ahliwaris' => 'required|file|mimes:pdf',
            'file_akta_kematian' => 'required|file|mimes:pdf',
            'file_ktp' => 'required|file|mimes:pdf',
            'file_kk' => 'required|file|mimes:pdf',
            'file_pbb' => 'required|file|mimes:pdf',
        ]);

        // Simpan file PDF ke storage/app/public/ahliwaris
        $folder = 'ahliwaris';
        $file_permohonan            = $request->file('file_permohonan')->storeAs($folder, Str::uuid() . '.pdf', 'public');
        $file_pernyataan_ahliwaris  = $request->file('file_pernyataan_ahliwaris')->storeAs($folder, Str::uuid() . '.pdf', 'public');
        $file_akta_kematian         = $request->file('file_akta_kematian')->storeAs($folder, Str::uuid() . '.pdf', 'public');
        $file_ktp                   = $request->file('file_ktp')->storeAs($folder, Str::uuid() . '.pdf', 'public');
        $file_kk                    = $request->file('file_kk')->storeAs($folder, Str::uuid() . '.pdf', 'public');
        $file_pbb                   = $request->file('file_pbb')->storeAs($folder, Str::uuid() . '.pdf', 'public');

        $submission = AhliwarisSubmission::create([
            'nama_pemohon' => $validated['nama_pemohon'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'pendidikan' => $validated['pendidikan'],
            'nik_pemohon' => $validated['nik_pemohon'],
            'file_permohonan' => $file_permohonan,
            'file_pernyataan_ahliwaris' => $file_pernyataan_ahliwaris,
            'file_akta_kematian' => $file_akta_kematian,
            'file_ktp' => $file_ktp,
            'file_kk' => $file_kk,
            'file_pbb' => $file_pbb,
            'status' => 'diajukan',
        ]);

        return redirect()
            ->route('ahliwaris.list')
            ->with('success', 'Pengajuan Surat Pernyataan Ahli Waris berhasil disimpan.')
            ->with('antrian', $submission->queue_number);
    }

    
    // ---------------- Angunan Ke Bank----------------

    // List pengajuan Agunan ke Bank
    public function agunanList()
    {
        $data = AgunanSubmission::latest()->paginate(10);
        return view('mejalayanan.agunan.index', compact('data'));
    }
    // Form input pengajuan Agunan ke Bank
    public function agunanCreate()
    {
        return view('mejalayanan.agunan.create');
    }
    // Simpan pengajuan Agunan ke Bank
    public function agunanStore(Request $request)
    {
        $validated = $request->validate([
            'nama_pemohon' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'pendidikan' => 'required|in:SD,SMP,SMA,D1,D2,D3,S1,S2,S3',
            'nik' => 'required|digits:16',

            'file_surat_tanah_asli' => 'required|file|mimes:pdf',
            'file_ktp' => 'required|file|mimes:pdf',
            'file_pengantar_desa' => 'required|file|mimes:pdf',
            'file_surat_tidak_sengketa' => 'required|file|mimes:pdf',
            'file_pbb' => 'required|file|mimes:pdf',
        ]);

        // Simpan file PDF ke storage/app/public/agunan
        $file_surat_tanah_asli     = $request->file('file_surat_tanah_asli')->storeAs('agunan', Str::uuid() . '.pdf', 'public');
        $file_ktp                  = $request->file('file_ktp')->storeAs('agunan', Str::uuid() . '.pdf', 'public');
        $file_pengantar_desa       = $request->file('file_pengantar_desa')->storeAs('agunan', Str::uuid() . '.pdf', 'public');
        $file_surat_tidak_sengketa = $request->file('file_surat_tidak_sengketa')->storeAs('agunan', Str::uuid() . '.pdf', 'public');
        $file_pbb                  = $request->file('file_pbb')->storeAs('agunan', Str::uuid() . '.pdf', 'public');

        $submission = AgunanSubmission::create([
            'nama_pemohon' => $validated['nama_pemohon'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'pendidikan' => $validated['pendidikan'],
            'nik' => $validated['nik'],
            'file_surat_tanah_asli' => $file_surat_tanah_asli,
            'file_ktp' => $file_ktp,
            'file_pengantar_desa' => $file_pengantar_desa,
            'file_surat_tidak_sengketa' => $file_surat_tidak_sengketa,
            'file_pbb' => $file_pbb,
            'status' => 'diajukan',
        ]);

        return redirect()
            ->route('agunan.list')
            ->with('success', 'Pengajuan Agunan ke Bank berhasil disimpan.')
            ->with('antrian', $submission->queue_number);
    }


    // ---------------- Silang Sengketa ----------------

    // List pengajuan sengketa
    public function sengketaList()
    {
        $data = SengketaSubmission::latest()->paginate(10);
        return view('mejalayanan.sengketa.index', compact('data'));
    }

    // Form input pengajuan sengketa
    public function sengketaCreate()
    {
        return view('mejalayanan.sengketa.create');
    }


    // simpan sengketa
    public function sengketaStore(Request $request)
    {
        $validated = $request->validate([
            'nama_pemohon' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'pendidikan' => 'required|in:SD,SMP,SMA,D1,D2,D3,S1,S2,S3',
            'nik_pemohon' => 'required|digits:16',

            'surat_silang_sengketa' => 'required|file|mimes:pdf',
            'foto_copy_surat_tanah' => 'required|file|mimes:pdf',
            'bukti_lunas_pbb' => 'required|file|mimes:pdf',
        ]);

        // Simpan file PDF ke storage/app/public/sengketa
        $surat = $request->file('surat_silang_sengketa')->storeAs('sengketa', Str::uuid() . '.pdf', 'public');
        $tanah = $request->file('foto_copy_surat_tanah')->storeAs('sengketa', Str::uuid() . '.pdf', 'public');
        $pbb   = $request->file('bukti_lunas_pbb')->storeAs('sengketa', Str::uuid() . '.pdf', 'public');

        $submission = SengketaSubmission::create([
            'nama_pemohon' => $validated['nama_pemohon'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'pendidikan' => $validated['pendidikan'],
            'nik_pemohon' => $validated['nik_pemohon'],
            'surat_silang_sengketa' => $surat,
            'foto_copy_surat_tanah' => $tanah,
            'bukti_lunas_pbb' => $pbb,
            'status' => 'diajukan',
        ]);

        return redirect()
            ->route('sengketa.list')
            ->with('success', 'Pengajuan Surat Silang Sengketa berhasil disimpan.')
            ->with('antrian', $submission->queue_number);
    }

    // Kirim ke Kasi Pemerintahan
    public function sengketaKirimKasi($id)
    {
        $data = SengketaSubmission::findOrFail($id);

        if ($data->status !== 'diajukan') {
            return back()->with('error', 'Data sudah diproses.');
        }

        $data->update([
            'status' => 'checked_by_kasi',
            'verified_at' => now(),
        ]);

        return back()->with('success', 'Pengajuan berhasil dikirim ke Kasi Pemerintahan.');
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

    
    public function dispensasi() {
        return view('mejalayanan.dispensasi');
    }

    public function iumk() {
        return view('mejalayanan.iumk');
    }
}
