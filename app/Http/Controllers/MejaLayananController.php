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
    use App\Models\CatinSubmission;
use App\Models\IumkSubmission;
use App\Models\SkrisetKKNSubmission;
use App\Models\SppatGrSubmission;
use App\Models\SktSubmission;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Helpers\PenilaianHelper;


use App\Traits\HasNikStatusFilter;

class MejaLayananController extends Controller
{
    use HasNikStatusFilter;

    public function index(Request $request)
    {
         $tables = [
        'agunan_submissions',
        'ahliwaris_submissions',
        'bpjs_submissions',
        'catin_submissions',
        'catin_tni_polri_submissions',
        'iumk_submissions',
        'sengketa_submissions',
        'skbd_submissions',
        'skriset_kkn_submissions',
        'skt_submissions',
        'sppat_gr_submissions',
        'sktm_dispensasi_submissions',
    ];

    $nilaiMapping = [
        'sangat_puas' => 4,
        'puas'        => 3,
        'cukup'       => 2,
        'tidak_puas'  => 1,
    ];

    $allData = collect();
    $totalResponden = 0;
    $totalPenilaian = 0;
    $totalDurasiSekcam = 0;
    $totalDurasiCamat = 0;

    foreach ($tables as $table) {
        $records = DB::table($table)
            ->select('id', 'nama_pemohon', 'penilaian', 'verified_at', 'approved_sekcam_at', 'approved_camat_at', 'status', 'created_at')
            ->whereNotNull('approved_camat_at')
            ->whereNotNull('penilaian')
            ->get();

        foreach ($records as $r) {
            $nilai = $nilaiMapping[strtolower(trim($r->penilaian))] ?? 0;
            if ($nilai <= 0) continue;

            $durasiSekcam = $r->verified_at && $r->approved_sekcam_at
                ? Carbon::parse($r->verified_at)->diffInMinutes(Carbon::parse($r->approved_sekcam_at))
                : 0;

            $durasiCamat = $r->approved_sekcam_at && $r->approved_camat_at
                ? Carbon::parse($r->approved_sekcam_at)->diffInMinutes(Carbon::parse($r->approved_camat_at))
                : 0;

            // emoji dari helper
            $emoji = \App\Helpers\PenilaianHelper::numericWithEmoji($nilai);

            $allData->push((object) [
                'id' => $r->id,
                'nama_pemohon' => $r->nama_pemohon ?? '-',
                'layanan' => $table,
                'penilaian' => $r->penilaian,
                'emoji' => $emoji,
                'nilai' => $nilai,
                'status' => $r->status ?? '-',
                'verified_at' => $r->verified_at,
                'approved_sekcam_at' => $r->approved_sekcam_at,
                'approved_camat_at' => $r->approved_camat_at,
                'durasi_sekcam' => $durasiSekcam,
                'durasi_camat' => $durasiCamat,
                'created_at' => $r->created_at,
            ]);

            $totalResponden++;
            $totalPenilaian += $nilai;
            $totalDurasiSekcam += $durasiSekcam;
            $totalDurasiCamat += $durasiCamat;
        }
    }

    $nrr = $totalResponden > 0 ? $totalPenilaian / $totalResponden : 0;
    $nilaiunsur = $totalPenilaian;
    $nrrtertbg = ($nrr * 0.111) * 9;
    $ikm = $nrrtertbg * 25;

    $statistik = [
        'total_responden' => $totalResponden,
        'avg_durasi_sekcam' => $totalResponden > 0 ? round($totalDurasiSekcam / $totalResponden, 2) : 0,
        'avg_durasi_camat' => $totalResponden > 0 ? round($totalDurasiCamat / $totalResponden, 2) : 0,
        'nilaiunsur' => round($nilaiunsur, 2),
        'nrr' => round($nrr, 2),
        'nrrtertbg' => round($nrrtertbg, 2),
        'ikm' => round($ikm, 2),
    ];

    // Pagination manual
    $perPage = 10;
    $page = $request->get('page', 1);
    $paginated = new LengthAwarePaginator(
        $allData->forPage($page, $perPage),
        $allData->count(),
        $perPage,
        $page,
        ['path' => $request->url(), 'query' => $request->query()]
    );

    return view('mejalayanan.meja-layanan', [
        'statistik' => $statistik,
        'submissions' => $paginated,
    ]);
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
    // 1. Validasi request
    $request->validate([
        'penilaian' => PenilaianHelper::validationRule(),
        'saran_kritik' => 'nullable|string|max:1000',
    ]);

    // 2. Ambil data submission
    $submission = BpjsSubmission::findOrFail($id);

    // 3. Cek apakah boleh dinilai
    if ($submission->status !== 'approved_by_camat' || !is_null($submission->penilaian)) {
        return back()->with('error', 'Pengajuan tidak valid atau sudah pernah dinilai.');
    }

    // 4. Simpan penilaian
    $submission->penilaian    = PenilaianHelper::labelToNumeric($request->penilaian);
    $submission->saran_kritik = $request->saran_kritik;
    $submission->diambil_at   = now();
    $submission->save();

    return back()->with('success', 'Penilaian berhasil dikirim.');
}

    // penilaian bpjs ikm
    public function penilaianIndex(Request $request)
    {
        $filters = $request->only(['nik', 'penilaian']);

        $query = \App\Models\BpjsSubmission::where('status', 'approved_by_camat')
            ->filterNikStatus($filters);  // ✅ menggunakan trait

        // -------------------------------
        // Filter tanggal (created_at)
        // -------------------------------
        if ($request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        // -------------------------------

        $data = $query->latest('updated_at')
            ->paginate(10)
            ->withQueryString(); // agar pagination tetap bawa filter

        return view('mejalayanan.bpjs.penilaian', compact('data'));
    }

    // print out pdf
    public function penilaianPdf(Request $request)
    {
        $query = \App\Models\BpjsSubmission::where('status','approved_by_camat')
            ->when($request->nik, function($q) use ($request){
                $q->where('nik_pemohon','like','%'.$request->nik.'%');
            })
            ->when($request->penilaian, function($q) use ($request){
                $q->where('penilaian', $request->penilaian);   // filter angka langsung
            });

        if ($request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $data = $query->latest('created_at')->get();

        $pdf = Pdf::loadView('mejalayanan.bpjs.penilaianPdf', compact('data'))
                ->setPaper('a4', 'landscape');

        return $pdf->download('Laporan-Penilaian-BPJS.pdf');
    }


    // ---------------- Dispensasi ----------------

     // list dispensasi catin
    public function DispencatinList()
    {
        $data = CatinSubmission::latest()->paginate(10); // urut dari terbaru + paginate
        return view('mejalayanan.dispencatin.index', compact('data'));
    }

    // tambah dispensasi catin
    public function DispencatinCreate()
    {
        return view('mejalayanan.dispencatin.create');
    }

    // simpan dispensasi catin
    public function DispencatinStore(Request $request)
    {
            $validated = $request->validate([
            'nama_pemohon' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'pendidikan' => 'required|in:SD,SMP,SMA,D1,D2,D3,S1,S2,S3',
            'nik_pemohon' => 'required|digits:16',

            // Semua file wajib PDF, kecuali akta cerai bisa optional
            'file_na_pria' => 'required|file|mimetypes:application/pdf',
            'file_na_wanita' => 'required|file|mimetypes:application/pdf',
            'file_kk_pria' => 'required|file|mimetypes:application/pdf',
            'file_kk_wanita' => 'required|file|mimetypes:application/pdf',
            'file_ktp_pria' => 'required|file|mimetypes:application/pdf',
            'file_ktp_wanita' => 'required|file|mimetypes:application/pdf',
            'file_akte_cerai_pria' => 'nullable|file|mimetypes:application/pdf',
            'file_akte_cerai_wanita' => 'nullable|file|mimetypes:application/pdf',
            'file_pbb' => 'required|file|mimetypes:application/pdf',
        ]);

        $folder = 'catin';

        $paths = [
            'file_na_pria' => $request->file('file_na_pria')->storeAs($folder, Str::uuid() . '.pdf', 'public'),
            'file_na_wanita' => $request->file('file_na_wanita')->storeAs($folder, Str::uuid() . '.pdf', 'public'),
            'file_kk_pria' => $request->file('file_kk_pria')->storeAs($folder, Str::uuid() . '.pdf', 'public'),
            'file_kk_wanita' => $request->file('file_kk_wanita')->storeAs($folder, Str::uuid() . '.pdf', 'public'),
            'file_ktp_pria' => $request->file('file_ktp_pria')->storeAs($folder, Str::uuid() . '.pdf', 'public'),
            'file_ktp_wanita' => $request->file('file_ktp_wanita')->storeAs($folder, Str::uuid() . '.pdf', 'public'),
            'file_akte_cerai_pria' => $request->hasFile('file_akte_cerai_pria') 
                ? $request->file('file_akte_cerai_pria')->storeAs($folder, Str::uuid() . '.pdf', 'public') 
                : null,
            'file_akte_cerai_wanita' => $request->hasFile('file_akte_cerai_wanita') 
                ? $request->file('file_akte_cerai_wanita')->storeAs($folder, Str::uuid() . '.pdf', 'public') 
                : null,
            'file_pbb' => $request->file('file_pbb')->storeAs($folder, Str::uuid() . '.pdf', 'public'),
        ];

        $submission = CatinSubmission::create([
            'nama_pemohon' => $validated['nama_pemohon'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'pendidikan' => $validated['pendidikan'],
            'nik_pemohon' => $validated['nik_pemohon'],

            'file_na_pria' => $paths['file_na_pria'],
            'file_na_wanita' => $paths['file_na_wanita'],
            'file_kk_pria' => $paths['file_kk_pria'],
            'file_kk_wanita' => $paths['file_kk_wanita'],
            'file_ktp_pria' => $paths['file_ktp_pria'],
            'file_ktp_wanita' => $paths['file_ktp_wanita'],
            'file_akte_cerai_pria' => $paths['file_akte_cerai_pria'],
            'file_akte_cerai_wanita' => $paths['file_akte_cerai_wanita'],
            'file_pbb' => $paths['file_pbb'],

            'status' => 'diajukan',
        ]);

        return redirect()
            ->route('dispencatin.list') // ganti sesuai nama route untuk list pengajuan catin
            ->with('success', 'Pengajuan Catin berhasil disimpan.')
            ->with('antrian', $submission->queue_number);
    }

    // kirim ke kasi kesos (DISPENSASI)
    public function kirimKeKasidispen($id)
    {
        $data = CatinSubmission::findOrFail($id);

        if ($data->status !== 'diajukan') {
            return back()->with('error', 'Data sudah diproses.');
        }

        $data->update([
            'status' => 'diproses',
            'verified_at' => now(),
        ]);

        return back()->with('success', 'Pengajuan berhasil dikirim ke Kasi Kesos.');
    }

    // Verifikasi oleh Kasi (DISPENSASI)
    public function dispensasiverifikasiKasi($id) {
        $data = CatinSubmission::findOrFail($id);
        if ($data->status === 'diajukan') {
            $data->update(['status' => 'verifikasi_kasi', 'verified_at' => now()]);
        }
        return back();
    }

    // Persetujuan Sekcam (DISPENSASI)
    public function dispensasiapproveSekcam($id) {
        $data = CatinSubmission::findOrFail($id);
        if ($data->status === 'verifikasi_kasi') {
            $data->update(['status' => 'disetujui_sekcam', 'approved_sekcam_at' => now()]);
        }
        return back();
    }

    // Persetujuan Camat (DISPENSASI)
    public function dispensasiapproveCamat($id) {
        $data = CatinSubmission::findOrFail($id);
        if ($data->status === 'disetujui_sekcam') {
            $data->update(['status' => 'disetujui_camat', 'approved_camat_at' => now()]);
        }
        return back();
    }

    // penilaian DISPENSAI IKM
    public function DispencatinpenilaianIndex(Request $request)
    {
        $filters = $request->only(['nik', 'penilaian']);

        $data = \App\Models\CatinSubmission::where('status', 'approved_by_camat')
            ->filterNikStatus($filters) // ✅ menggunakan trait
            ->latest('updated_at')
            ->paginate(10)
            ->withQueryString(); // agar pagination tetap bawa filter

        return view('mejalayanan.dispencatin.penilaian', compact('data'));
    }

    // SIMPAN PENILAIAN DISPENSASI
    public function simpanPenilaianDispencatin(Request $request, $id)
    {
        $request->validate([
            'penilaian' => 'required|in:tidak_puas,cukup,puas,sangat_puas',
        ]);

        $data = \App\Models\CatinSubmission::findOrFail($id);

        if ($data->status !== 'approved_by_camat' || $data->penilaian) {
            return back()->with('error', 'Pengajuan tidak valid untuk dinilai.');
        }

        $data->update([
            'penilaian' => $request->penilaian,
            'diambil_at' => now(),
        ]);

        return back()->with('success', 'Penilaian berhasil dikirim.');
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
        $data = SktmDispensasiSubmission::findOrFail($id);
        if ($data->status === 'diajukan') {
            $data->update(['status' => 'verifikasi_kasi', 'verified_at' => now()]);
        }
        return back();
    }

    // Persetujuan Sekcam (SKTM)
    public function SKTMapproveSekcam($id) {
        $data = SktmDispensasiSubmission::findOrFail($id);
        if ($data->status === 'verifikasi_kasi') {
            $data->update(['status' => 'disetujui_sekcam', 'approved_sekcam_at' => now()]);
        }
        return back();
    }

    // Persetujuan Camat (SKTM)
    public function SKTMapproveCamat($id) {
        $data = SktmDispensasiSubmission::findOrFail($id);
        if ($data->status === 'disetujui_sekcam') {
            $data->update(['status' => 'disetujui_camat', 'approved_camat_at' => now()]);
        }
        return back();
    }

    // penilaian sktm ikm
    public function sktmPenilaianIndex(Request $request)
    {
        $filters = $request->only(['nik', 'penilaian']);

        $data = \App\Models\SktmDispensasiSubmission::where('status', 'approved_by_camat')
            ->filterNikStatus($filters) // ✅ menggunakan trait
            ->latest('updated_at')
            ->paginate(10)
            ->withQueryString(); // agar pagination tetap bawa filter

        return view('mejalayanan.sktm.penilaian', compact('data'));
    }

    public function simpanPenilaianSktm(Request $request, $id)
    {
        $request->validate([
            'penilaian'    => 'required|in:tidak_puas,cukup,puas,sangat_puas',
            'saran_kritik' => 'nullable|string|max:1000',
        ]);

        $data = \App\Models\SktmDispensasiSubmission::findOrFail($id);

        if ($data->status !== 'approved_by_camat' || $data->penilaian) {
            return back()->with('error', 'Pengajuan tidak valid untuk dinilai.');
        }

        $data->update([
            'penilaian'    => $request->penilaian,
            'saran_kritik' => $request->saran_kritik,
            'diambil_at'   => now(),
        ]);

        return back()->with('success', 'Penilaian berhasil dikirim.');
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

    // penilaian skt ikm
    public function sktPenilaianIndex(Request $request)
    {
        $filters = $request->only(['nik', 'penilaian']);

        $data = \App\Models\SktSubmission::where('status', 'approved_by_camat')
            ->filterNikStatus($filters) // ✅ menggunakan trait
            ->latest('updated_at')
            ->paginate(10)
            ->withQueryString(); // agar pagination tetap bawa filter

        return view('mejalayanan.skt.penilaian', compact('data'));
    }

    public function simpanPenilaianSkt(Request $request, $id)
    {
        $request->validate([
            'penilaian' => 'required|in:tidak_puas,cukup,puas,sangat_puas',
            'saran_kritik' => 'nullable|string|max:1000',
        ]);

        $data = \App\Models\SktSubmission::findOrFail($id);

        if ($data->status !== 'approved_by_camat' || $data->penilaian) {
            return back()->with('error', 'Pengajuan tidak valid untuk dinilai.');
        }

        $data->update([
            'penilaian' => $request->penilaian,
            'saran_kritik' => $request->saran_kritik,
            'diambil_at' => now(),
        ]);

        return back()->with('success', 'Penilaian berhasil dikirim.');
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
            'file_formulir' => 'required|file|mimes:pdf',
            'file_alas_hak_tanah' => 'required|file|mimes:pdf',
            'file_ktp' => 'required|file|mimes:pdf',
            'file_kk' => 'required|file|mimes:pdf',
            'file_pbb' => 'required|file|mimes:pdf',
        ]);

        // Simpan file
        $permohonan = $request->file('file_permohonan')->storeAs('sppat_gr', Str::uuid() . '.pdf', 'public');
        $pernyataan = $request->file('file_formulir')->storeAs('sppat_gr', Str::uuid() . '.pdf', 'public');
        $akta = $request->file('file_alas_hak_tanah')->storeAs('sppat_gr', Str::uuid() . '.pdf', 'public');
        $ktp = $request->file('file_ktp')->storeAs('sppat_gr', Str::uuid() . '.pdf', 'public');
        $kk = $request->file('file_kk')->storeAs('sppat_gr', Str::uuid() . '.pdf', 'public');
        $pbb = $request->file('file_pbb')->storeAs('sppat_gr', Str::uuid() . '.pdf', 'public');

        $submission = SppatGrSubmission::create([
            'nama_pemohon' => $validated['nama_pemohon'],
            'nik_pemohon' => $validated['nik_pemohon'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'pendidikan' => $validated['pendidikan'],

            'file_permohonan' => $permohonan,
            'file_formulir' => $pernyataan,
            'file_alas_hak_tanah' => $akta,
            'file_ktp' => $ktp,
            'file_kk' => $kk,
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

    // penilaian sppatgr 
    public function sppatgrPenilaianIndex(Request $request)
    {
        $filters = $request->only(['nik', 'penilaian']);

        $data = \App\Models\SppatGrSubmission::where('status', 'approved_by_camat')
            ->filterNikStatus($filters) // ✅ menggunakan trait
            ->latest('updated_at')
            ->paginate(10)
            ->withQueryString(); // agar pagination tetap bawa filter

        return view('mejalayanan.sppat_gr.penilaian', compact('data'));
    }

    // SIMPAN PENILAIAN SPPATGR
    public function simpanPenilaiansppatgr(Request $request, $id)
    {
        $request->validate([
            'penilaian' => 'required|in:tidak_puas,cukup,puas,sangat_puas',
            'saran_kritik' => 'nullable|string|max:1000',
        ]);

        $data = \App\Models\SppatGrSubmission::findOrFail($id);

        if ($data->status !== 'approved_by_camat' || $data->penilaian) {
            return back()->with('error', 'Pengajuan tidak valid untuk dinilai.');
        }

        $data->update([
            'penilaian' => $request->penilaian,
            'saran_kritik' => $request->saran_kritik,
            'diambil_at' => now(),
        ]);

        return back()->with('success', 'Penilaian berhasil dikirim.');
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

    public function simpanPenilaianahliwaris(Request $request, $id)
    {
        $request->validate([
            'penilaian' => 'required|in:tidak_puas,cukup,puas,sangat_puas',
        ]);

        $data = \App\Models\AhliwarisSubmission::findOrFail($id);

        if ($data->status !== 'approved_by_camat' || $data->penilaian) {
            return back()->with('error', 'Pengajuan tidak valid untuk dinilai.');
        }

        $data->update([
            'penilaian' => $request->penilaian,
            'saran_kritik' => $request->saran_kritik,
            'diambil_at' => now(),
        ]);

        return back()->with('success', 'Penilaian berhasil dikirim.');
    }

    // penilaian bpjs ikm
    public function ahliwarisPenilaianIndex(Request $request)
    {
        $filters = $request->only(['nik', 'penilaian']);

        $data = \App\Models\AhliwarisSubmission::where('status', 'approved_by_camat')
            ->filterNikStatus($filters) // ✅ menggunakan trait
            ->latest('updated_at')
            ->paginate(10)
            ->withQueryString(); // agar pagination tetap bawa filter

        return view('mejalayanan.ahliwaris.penilaian', compact('data'));
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

    // penilaian agunan ikm
    public function penilaianIndexagunan(Request $request)
    {
        $filters = $request->only(['nik', 'penilaian']);

        $data = \App\Models\AgunanSubmission::where('status', 'approved_by_camat')
            ->filterNikStatus($filters) // ✅ menggunakan trait
            ->latest('updated_at')
            ->paginate(10)
            ->withQueryString(); // agar pagination tetap bawa filter

        return view('mejalayanan.agunan.penilaian', compact('data'));
    }

    // SIMPAN PENILAIAN SKTM
    public function simpanPenilaianagunan(Request $request, $id)
    {
        $request->validate([
            'penilaian' => 'required|in:tidak_puas,cukup,puas,sangat_puas',
        ]);

        $data = \App\Models\AgunanSubmission::findOrFail($id);

        if ($data->status !== 'approved_by_camat' || $data->penilaian) {
            return back()->with('error', 'Pengajuan tidak valid untuk dinilai.');
        }

        $data->update([
            'penilaian' => $request->penilaian,
            'saran_kritik' => $request->saran_kritik,
            'diambil_at' => now(),
        ]);

        return back()->with('success', 'Penilaian berhasil dikirim.');
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

    // SIMPAN PENILAUAN SILANG SENGKETA
    public function simpanPenilaiansengketa(Request $request, $id)
    {
        $request->validate([
            'penilaian' => 'required|in:tidak_puas,cukup,puas,sangat_puas',
        ]);

        $data = \App\Models\SengketaSubmission::findOrFail($id);

        if ($data->status !== 'approved_by_camat' || $data->penilaian) {
            return back()->with('error', 'Pengajuan tidak valid untuk dinilai.');
        }

        $data->update([
            'penilaian' => $request->penilaian,
            'saran_kritik' => $request->saran_kritik,
            'diambil_at' => now(),
        ]);

        return back()->with('success', 'Penilaian berhasil dikirim.');
    }

    // penilaian silang sengketa ikm
    public function penilaianIndexSENGKETA (Request $request)
    {
        $filters = $request->only(['nik', 'penilaian']);

        $data = \App\Models\SengketaSubmission::where('status', 'approved_by_camat')
            ->filterNikStatus($filters) // ✅ menggunakan trait
            ->latest('updated_at')
            ->paginate(10)
            ->withQueryString(); // agar pagination tetap bawa filter

        return view('mejalayanan.sengketa.penilaian', compact('data'));
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

    // SIMPAN PENILAUAN catin
    public function simpanPenilaiancatin(Request $request, $id)
    {
        $request->validate([
            'penilaian' => 'required|in:tidak_puas,cukup,puas,sangat_puas',
        ]);

        $data = \App\Models\CatinTniPolriSubmission::findOrFail($id);

        if ($data->status !== 'approved_by_camat' || $data->penilaian) {
            return back()->with('error', 'Pengajuan tidak valid untuk dinilai.');
        }

        $data->update([
            'penilaian' => $request->penilaian,
            'diambil_at' => now(),
        ]);

        return back()->with('success', 'Penilaian berhasil dikirim.');
    }

    // penilaian catin ikm
    public function penilaianIndexcatin (Request $request)
    {
        $filters = $request->only(['nik', 'penilaian']);

        $data = \App\Models\CatinTniPolriSubmission::where('status', 'approved_by_camat')
            ->filterNikStatus($filters) // ✅ menggunakan trait
            ->latest('updated_at')
            ->paginate(10)
            ->withQueryString(); // agar pagination tetap bawa filter

        return view('mejalayanan.catin_tni.penilaian', compact('data'));
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

    // SIMPAN PENILAUAN SKBD
    public function simpanPenilaianskbd(Request $request, $id)
    {
        $request->validate([
            'penilaian' => 'required|in:tidak_puas,cukup,puas,sangat_puas',
        ]);

        $data = \App\Models\SkbdSubmission::findOrFail($id);

        if ($data->status !== 'approved_by_camat' || $data->penilaian) {
            return back()->with('error', 'Pengajuan tidak valid untuk dinilai.');
        }

        $data->update([
            'penilaian' => $request->penilaian,
            'diambil_at' => now(),
        ]);

        return back()->with('success', 'Penilaian berhasil dikirim.');
    }

    // penilaian SKBD ikm
    public function penilaianIndexSKBD (Request $request)
    {
        $filters = $request->only(['nik', 'penilaian']);

        $data = \App\Models\SkbdSubmission::where('status', 'approved_by_camat')
            ->filterNikStatus($filters) // ✅ menggunakan trait
            ->latest('updated_at')
            ->paginate(10)
            ->withQueryString(); // agar pagination tetap bawa filter

        return view('mejalayanan.skbd.penilaian', compact('data'));
    }


    public function dispensasi() {
        return view('mejalayanan.dispensasi');
    }

    // IUMK
     public function iumkList()
    {
        $data = IumkSubmission::latest()->paginate(10); // urut dari terbaru + paginate
        return view('mejalayanan.iumk.index', compact('data'));
    }

    // tambah IUMK
    public function iumkCreate()
    {
        return view('mejalayanan.iumk.create');
    }

    // simpan IUMK
    public function iumkStore(Request $request)
    {
        $validated = $request->validate([
            'nama_pemohon' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'pendidikan' => 'required|in:SD,SMP,SMA,D1,D2,D3,S1,S2,S3',
            'nik_pemohon' => 'required|digits:16',
            'alamat_usaha' => 'required|string|max:500',

            'surat_keterangan_usaha' => 'required|file|mimes:pdf',
            'foto_tempat_usaha' => 'required|file|image|mimes:jpeg,png,jpg',
            'file_kk' => 'required|file|mimes:pdf',
            'file_ktp' => 'required|file|mimes:pdf',
            'pasphoto_3x4_1' => 'required|file|image|mimes:jpeg,png,jpg',
            'pasphoto_3x4_2' => 'required|file|image|mimes:jpeg,png,jpg',
            'file_pbb' => 'required|file|mimes:pdf',
        ]);

        $folder = 'iumk';

        $paths = [
            'surat_keterangan_usaha' => $request->file('surat_keterangan_usaha')->storeAs($folder, Str::uuid() . '.pdf', 'public'),
            'foto_tempat_usaha' => $request->file('foto_tempat_usaha')->storeAs($folder, Str::uuid() . '.' . $request->file('foto_tempat_usaha')->extension(), 'public'),
            'file_kk' => $request->file('file_kk')->storeAs($folder, Str::uuid() . '.pdf', 'public'),
            'file_ktp' => $request->file('file_ktp')->storeAs($folder, Str::uuid() . '.pdf', 'public'),
            'pasphoto_3x4_1' => $request->file('pasphoto_3x4_1')->storeAs($folder, Str::uuid() . '.' . $request->file('pasphoto_3x4_1')->extension(), 'public'),
            'pasphoto_3x4_2' => $request->file('pasphoto_3x4_2')->storeAs($folder, Str::uuid() . '.' . $request->file('pasphoto_3x4_2')->extension(), 'public'),
            'file_pbb' => $request->file('file_pbb')->storeAs($folder, Str::uuid() . '.pdf', 'public'),
        ];

        $submission = IumkSubmission::create([
            'nama_pemohon' => $validated['nama_pemohon'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'pendidikan' => $validated['pendidikan'],
            'nik_pemohon' => $validated['nik_pemohon'],
            'alamat_usaha' => $validated['alamat_usaha'],

            'surat_keterangan_usaha' => $paths['surat_keterangan_usaha'],
            'foto_tempat_usaha' => $paths['foto_tempat_usaha'],
            'file_kk' => $paths['file_kk'],
            'file_ktp' => $paths['file_ktp'],
            'pasphoto_3x4_1' => $paths['pasphoto_3x4_1'],
            'pasphoto_3x4_2' => $paths['pasphoto_3x4_2'],
            'file_pbb' => $paths['file_pbb'],

            'status' => 'diajukan',
        ]);

        return redirect()
            ->route('dispencatin.list') // sesuaikan nama route list IUMK
            ->with('success', 'Pengajuan Izin Usaha Mikro berhasil disimpan.');
    }

    // kirim ke kasi kesos (DISPENSASI)
    public function kirimKeKasiIumk($id)
    {
        $data = IumkSubmission::findOrFail($id);

        if ($data->status !== 'diajukan') {
            return back()->with('error', 'Data sudah diproses.');
        }

        $data->update([
            'status' => 'diproses',
            'verified_at' => now(),
        ]);

        return back()->with('success', 'Pengajuan berhasil dikirim ke Kasi Kesos.');
    }

    // Verifikasi oleh Kasi (Iumk)
    public function IumkverifikasiKasi($id) {
        $data = IumkSubmission::findOrFail($id);
        if ($data->status === 'diajukan') {
            $data->update(['status' => 'verifikasi_kasi', 'verified_at' => now()]);
        }
        return back();
    }

    // Persetujuan Sekcam (Iumk)
    public function IumkapproveSekcam($id) {
        $data = IumkSubmission::findOrFail($id);
        if ($data->status === 'verifikasi_kasi') {
            $data->update(['status' => 'disetujui_sekcam', 'approved_sekcam_at' => now()]);
        }
        return back();
    }

    // Persetujuan Camat (Iumk)
    public function IumkapproveCamat($id) {
        $data = IumkSubmission::findOrFail($id);
        if ($data->status === 'disetujui_sekcam') {
            $data->update(['status' => 'disetujui_camat', 'approved_camat_at' => now()]);
        }
        return back();
    }

    // penilaian iumk IKM
    public function iumkpenilaianIndex(Request $request)
    {
        $filters = $request->only(['nik', 'penilaian']);

        $data = \App\Models\IumkSubmission::where('status', 'approved_by_camat')
            ->filterNikStatus($filters) // ✅ menggunakan trait
            ->latest('updated_at')
            ->paginate(10)
            ->withQueryString(); // agar pagination tetap bawa filter

        return view('mejalayanan.iumk.penilaian', compact('data'));
    }

    // SIMPAN PENILAIAN IUMK
    public function simpanPenilaianiumk(Request $request, $id)
    {
        $request->validate([
            'penilaian' => 'required|in:tidak_puas,cukup,puas,sangat_puas',
        ]);

        $data = \App\Models\IumkSubmission::findOrFail($id);

        if ($data->status !== 'approved_by_camat' || $data->penilaian) {
            return back()->with('error', 'Pengajuan tidak valid untuk dinilai.');
        }

        $data->update([
            'penilaian' => $request->penilaian,
            'diambil_at' => now(),
        ]);

        return back()->with('success', 'Penilaian berhasil dikirim.');
    }

    
    // SK RISET KKN
     public function skrisetKKNList()
    {
        $data = skrisetKKNSubmission::latest()->paginate(10); // urut dari terbaru + paginate
        return view('mejalayanan.skrisetKKN.index', compact('data'));
    }

    // tambah skrisetKKN
    public function skrisetKKNCreate()
    {
        return view('mejalayanan.skrisetKKN.create');
    }

    // simpan skrisetKKN
    public function skrisetKKNStore(Request $request)
    {
        $validated = $request->validate([
            'nama_pemohon' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'pendidikan' => 'required|in:SD,SMP,SMA,D1,D2,D3,S1,S2,S3',
            'nik_pemohon' => 'required|digits:16',
            
            'file_surat_sekolah' => 'required|file|mimes:pdf',
            'file_izin_pengambilan' => 'required|file|mimes:pdf',
        ]);

        $folder = 'skrisetKKN';

        $paths = [
            'file_surat_sekolah' => $request->file('file_surat_sekolah')->storeAs($folder, Str::uuid() . '.pdf', 'public'),
            'file_izin_pengambilan' => $request->file('file_izin_pengambilan')->storeAs($folder, Str::uuid() . '.pdf', 'public'),
        ];

        $submission = SkrisetKKNSubmission::create([
            'nama_pemohon' => $validated['nama_pemohon'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'pendidikan' => $validated['pendidikan'],
            'nik_pemohon' => $validated['nik_pemohon'],

            'file_surat_sekolah' => $paths['file_surat_sekolah'],
            'file_izin_pengambilan' => $paths['file_izin_pengambilan'],

            'status' => 'diajukan',
        ]);

        return redirect()
            ->route('skrisetKKN.list') // sesuaikan dengan route list pengajuan SkrisetKKN
            ->with('success', 'Pengajuan Surat Keterangan Riset KKN/PKL berhasil disimpan.');
    }

    // kirim ke kasi kesos (DISPENSASI)
    public function kirimKeKasiskrisetKKN($id)
    {
        $data = skrisetKKNSubmission::findOrFail($id);

        if ($data->status !== 'diajukan') {
            return back()->with('error', 'Data sudah diproses.');
        }

        $data->update([
            'status' => 'diproses',
            'verified_at' => now(),
        ]);

        return back()->with('success', 'Pengajuan berhasil dikirim ke Kasi Kesos.');
    }

    // Verifikasi oleh Kasi (skrisetKKN)
    public function skrisetKKNverifikasiKasi($id) {
        $data = skrisetKKNSubmission::findOrFail($id);
        if ($data->status === 'diajukan') {
            $data->update(['status' => 'verifikasi_kasi', 'verified_at' => now()]);
        }
        return back();
    }

    // Persetujuan Sekcam (skrisetKKN)
    public function skrisetKKNapproveSekcam($id) {
        $data = skrisetKKNSubmission::findOrFail($id);
        if ($data->status === 'verifikasi_kasi') {
            $data->update(['status' => 'disetujui_sekcam', 'approved_sekcam_at' => now()]);
        }
        return back();
    }

    // Persetujuan Camat (skrisetKKN)
    public function skrisetKKNapproveCamat($id) {
        $data = SkrisetKKNSubmission::findOrFail($id);
        if ($data->status === 'disetujui_sekcam') {
            $data->update(['status' => 'disetujui_camat', 'approved_camat_at' => now()]);
        }
        return back();
    }

    // penilaian skrisetKKN IKM
    public function skrisetKKNpenilaianIndex(Request $request)
    {
        $filters = $request->only(['nik', 'penilaian']);

        $data = \App\Models\skrisetKKNSubmission::where('status', 'approved_by_camat')
            ->filterNikStatus($filters) // ✅ menggunakan trait
            ->latest('updated_at')
            ->paginate(10)
            ->withQueryString(); // agar pagination tetap bawa filter

        return view('mejalayanan.skrisetKKN.penilaian', compact('data'));
    }

    // SIMPAN PENILAIAN skrisetKKN
    public function simpanPenilaianskrisetKKN(Request $request, $id)
    {
        $request->validate([
            'penilaian' => 'required|in:tidak_puas,cukup,puas,sangat_puas',
        ]);

        $data = \App\Models\skrisetKKNSubmission::findOrFail($id);

        if ($data->status !== 'approved_by_camat' || $data->penilaian) {
            return back()->with('error', 'Pengajuan tidak valid untuk dinilai.');
        }

        $data->update([
            'penilaian' => $request->penilaian,
            'diambil_at' => now(),
        ]);

        return back()->with('success', 'Penilaian berhasil dikirim.');
    }

    
}
