<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use App\Models\AgunanSubmission;
use App\Models\AhliwarisSubmission;
use Illuminate\Http\Request;
use App\Models\BpjsSubmission;
use App\Models\CatinSubmission;
use App\Models\SktmDispensasiSubmission;
use App\Models\SktSubmission;
use App\Models\SkbdSubmission;
use App\Models\CatinTniPolriSubmission;
use App\Models\IumkSubmission;
use App\Models\SengketaSubmission;
use App\Models\SkrisetKKNSubmission;
use App\Models\SppatGrSubmission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;  // pastikan import model User


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

    // ================= SPPAT-GR =================
    public function sppatgrIndex()
    {
        $pengajuan = SppatGrSubmission::where('status', 'approved_by_sekcam')->latest()->get();
        return view('camat.sppatgr.index', compact('pengajuan'));
    }

    public function sppatgrApprove($id)
    {
        $item = SppatGrSubmission::findOrFail($id);
        $item->status = 'approved_by_camat';
        $item->approved_camat_at = now();
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan SPPAT-GR disetujui oleh Camat.');
    }

    public function sppatgrReject(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string|max:255']);

        $item = SppatGrSubmission::findOrFail($id);
        $item->status = 'rejected_by_camat';
        $item->rejected_camat_reason = $request->reason;
        $item->approved_camat_at = now();
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan SPPAT-GR ditolak oleh Camat.');
    }

    public function sppatgrProses()
    {
        $jumlahPengajuan     = SppatGrSubmission::count();
        $pengajuanDiterima   = SppatGrSubmission::where('status', 'approved_by_sekcam')->count();
        $pengajuanDisetujui  = SppatGrSubmission::where('status', 'approved_by_camat')->count();
        $pengajuanDitolak    = SppatGrSubmission::where('status', 'rejected_by_camat')->count();

        $pengajuan = SppatGrSubmission::whereIn('status', ['approved_by_camat', 'rejected_by_camat'])
                            ->latest()->paginate(10);

        return view('camat.sppatgr.proses', compact(
            'pengajuan',
            'jumlahPengajuan',
            'pengajuanDiterima',
            'pengajuanDisetujui',
            'pengajuanDitolak'
        ));
    }

     // ================= Ahli Waris =================
    public function ahliwarisIndex()
    {
        $pengajuan = AhliwarisSubmission::where('status', 'approved_by_sekcam')->latest()->get();
        return view('camat.ahliwaris.index', compact('pengajuan'));
    }

    public function ahliwarisApprove($id)
    {
        $item = AhliwarisSubmission::findOrFail($id);
        $item->status = 'approved_by_camat';
        $item->approved_camat_at = now();
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan ahli waris disetujui oleh Camat.');
    }

    public function ahliwarisReject(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string|max:255']);

        $item = AhliwarisSubmission::findOrFail($id);
        $item->status = 'rejected_by_camat';
        $item->rejected_camat_reason = $request->reason;
        $item->approved_camat_at = now();
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan ahli waris ditolak oleh Camat.');
    }

    public function ahliwarisProses()
    {
        $jumlahPengajuan     = AhliwarisSubmission::count();
        $pengajuanDiterima   = AhliwarisSubmission::where('status', 'approved_by_sekcam')->count();
        $pengajuanDisetujui  = AhliwarisSubmission::where('status', 'approved_by_camat')->count();
        $pengajuanDitolak    = AhliwarisSubmission::where('status', 'rejected_by_camat')->count();

        $pengajuan = AhliwarisSubmission::whereIn('status', ['approved_by_camat', 'rejected_by_camat'])
                            ->latest()->paginate(10);

        return view('camat.ahliwaris.proses', compact(
            'pengajuan',
            'jumlahPengajuan',
            'pengajuanDiterima',
            'pengajuanDisetujui',
            'pengajuanDitolak'
        ));
    }


    // ================= Agunan Bank =================
    public function agunanIndex()
    {
        $pengajuan = AgunanSubmission::where('status', 'approved_by_sekcam')->latest()->get();
        return view('camat.agunan.index', compact('pengajuan'));
    }

    public function agunanApprove($id)
    {
        $item = AgunanSubmission::findOrFail($id);
        $item->status = 'approved_by_camat';
        $item->approved_camat_at = now();
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan SKT disetujui oleh Camat.');
    }

    public function agunanReject(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string|max:255']);

        $item = AgunanSubmission::findOrFail($id);
        $item->status = 'rejected_by_camat';
        $item->rejected_camat_reason = $request->reason;
        $item->approved_camat_at = now();
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan SKT ditolak oleh Camat.');
    }

    public function agunanProses()
    {
        $jumlahPengajuan     = AgunanSubmission::count();
        $pengajuanDiterima   = AgunanSubmission::where('status', 'approved_by_sekcam')->count();
        $pengajuanDisetujui  = AgunanSubmission::where('status', 'approved_by_camat')->count();
        $pengajuanDitolak    = AgunanSubmission::where('status', 'rejected_by_camat')->count();

        $pengajuan = AgunanSubmission::whereIn('status', ['approved_by_camat', 'rejected_by_camat'])
                            ->latest()->paginate(10);

        return view('camat.agunan.proses', compact(
            'pengajuan',
            'jumlahPengajuan',
            'pengajuanDiterima',
            'pengajuanDisetujui',
            'pengajuanDitolak'
        ));
    }

    // ================= Silang Sengketa =================
    public function sengketaIndex()
    {
        $pengajuan = SengketaSubmission::where('status', 'approved_by_sekcam')->latest()->get();
        return view('camat.silang_sengketa.index', compact('pengajuan'));
    }

    public function sengketaApprove($id)
    {
        $item = SengketaSubmission::findOrFail($id);
        $item->status = 'approved_by_camat';
        $item->approved_camat_at = now();
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan sengketa disetujui oleh Camat.');
    }

    public function sengketaReject(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string|max:255']);

        $item = SengketaSubmission::findOrFail($id);
        $item->status = 'rejected_by_camat';
        $item->rejected_camat_reason = $request->reason;
        $item->approved_camat_at = now();
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan sengketa ditolak oleh Camat.');
    }

    public function sengketaProses()
    {
        $jumlahPengajuan     = SengketaSubmission::count();
        $pengajuanDiterima   = SengketaSubmission::where('status', 'approved_by_sekcam')->count();
        $pengajuanDisetujui  = SengketaSubmission::where('status', 'approved_by_camat')->count();
        $pengajuanDitolak    = SengketaSubmission::where('status', 'rejected_by_camat')->count();

        $pengajuan = SengketaSubmission::whereIn('status', ['approved_by_camat', 'rejected_by_camat'])
                            ->latest()->paginate(10);

        return view('camat.silang_sengketa.proses', compact(
            'pengajuan',
            'jumlahPengajuan',
            'pengajuanDiterima',
            'pengajuanDisetujui',
            'pengajuanDitolak'
        ));
    }

    // ================= Catin TNI/POLRI =================
    public function catinIndex()
    {
        $pengajuan = CatinTniPolriSubmission::where('status', 'approved_by_sekcam')->latest()->get();
        return view('camat.catin.index', compact('pengajuan'));
    }

    public function catinApprove($id)
    {
        $item = CatinTniPolriSubmission::findOrFail($id);
        $item->status = 'approved_by_camat';
        $item->approved_camat_at = now();
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan catin disetujui oleh Camat.');
    }

    public function catinReject(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string|max:255']);

        $item = CatinTniPolriSubmission::findOrFail($id);
        $item->status = 'rejected_by_camat';
        $item->rejected_camat_reason = $request->reason;
        $item->approved_camat_at = now();
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan catin ditolak oleh Camat.');
    }

    public function catinProses()
    {
        $jumlahPengajuan     = CatinTniPolriSubmission::count();
        $pengajuanDiterima   = CatinTniPolriSubmission::where('status', 'approved_by_sekcam')->count();
        $pengajuanDisetujui  = CatinTniPolriSubmission::where('status', 'approved_by_camat')->count();
        $pengajuanDitolak    = CatinTniPolriSubmission::where('status', 'rejected_by_camat')->count();

        $pengajuan = CatinTniPolriSubmission::whereIn('status', ['approved_by_camat', 'rejected_by_camat'])
                            ->latest()->paginate(10);

        return view('camat.catin.proses', compact(
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

    // ---------------- DISPENSASI CATIN NIKAH ----------------
    public function dispencatinIndex()
    {
        $pengajuan = CatinSubmission::where('status', 'approved_by_sekcam')->latest()->get();
        return view('camat.dispencatin.index', compact('pengajuan'));
    }

    public function dispencatinApprove($id)
    {
        $item = CatinSubmission::findOrFail($id);
        $item->status = 'approved_by_camat';
        $item->approved_camat_at = now();
        $item->camat_id = Auth::id();  // Simpan ID user login sebagai camat_id
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan dispencatin disetujui oleh Camat.');
    }

    public function dispencatinReject(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string|max:255']);

        $item = CatinSubmission::findOrFail($id);
        $item->status = 'rejected_by_camat';
        $item->rejected_camat_reason = $request->reason;
        $item->approved_camat_at = now();
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan dispencatin ditolak oleh Camat.');
    }

    public function dispencatinProses()
    {
        $jumlahPengajuan     = CatinSubmission::count();
        $pengajuanDiterima   = CatinSubmission::where('status', 'approved_by_sekcam')->count();
        $pengajuanDisetujui  = CatinSubmission::where('status', 'approved_by_camat')->count();
        $pengajuanDitolak    = CatinSubmission::where('status', 'rejected_by_camat')->count();

        $pengajuan = CatinSubmission::whereIn('status', ['approved_by_camat', 'rejected_by_camat'])
                            ->latest()->paginate(10);

        return view('camat.dispencatin.proses', compact(
            'pengajuan',
            'jumlahPengajuan',
            'pengajuanDiterima',
            'pengajuanDisetujui',
            'pengajuanDitolak'
        ));
    }

    // ---------------- Izin Usaha Mikro ----------------
    public function iumkIndex()
    {
        $pengajuan = IumkSubmission::where('status', 'approved_by_sekcam')->latest()->get();
        return view('camat.iumk.index', compact('pengajuan'));
    }

    public function iumkApprove($id)
    {
        $item = IumkSubmission::findOrFail($id);
        $item->status = 'approved_by_camat';
        $item->approved_camat_at = now();
        $item->camat_id = Auth::id();  // Simpan ID user login sebagai camat_id
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan iumk disetujui oleh Camat.');
    }

    public function iumkReject(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string|max:255']);

        $item = IumkSubmission::findOrFail($id);
        $item->status = 'rejected_by_camat';
        $item->rejected_camat_reason = $request->reason;
        $item->approved_camat_at = now();
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan iumk ditolak oleh Camat.');
    }

    public function iumkProses()
    {
        $jumlahPengajuan     = IumkSubmission::count();
        $pengajuanDiterima   = IumkSubmission::where('status', 'approved_by_sekcam')->count();
        $pengajuanDisetujui  = IumkSubmission::where('status', 'approved_by_camat')->count();
        $pengajuanDitolak    = IumkSubmission::where('status', 'rejected_by_camat')->count();

        $pengajuan = IumkSubmission::whereIn('status', ['approved_by_camat', 'rejected_by_camat'])
                            ->latest()->paginate(10);

        return view('camat.iumk.proses', compact(
            'pengajuan',
            'jumlahPengajuan',
            'pengajuanDiterima',
            'pengajuanDisetujui',
            'pengajuanDitolak'
        ));
    }


     // ---------------- SK Riset KKN ----------------
    public function skrisetKKNIndex()
    {
        $pengajuan = skrisetKKNSubmission::where('status', 'approved_by_sekcam')->latest()->get();
        return view('camat.skrisetKKN.index', compact('pengajuan'));
    }

    public function skrisetKKNApprove($id)
    {
        $item = skrisetKKNSubmission::findOrFail($id);
        $item->status = 'approved_by_camat';
        $item->approved_camat_at = now();
        $item->camat_id = Auth::id();  // Simpan ID user login sebagai camat_id
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan skrisetKKN disetujui oleh Camat.');
    }

    public function skrisetKKNReject(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string|max:255']);

        $item = skrisetKKNSubmission::findOrFail($id);
        $item->status = 'rejected_by_camat';
        $item->rejected_camat_reason = $request->reason;
        $item->approved_camat_at = now();
        $item->save();

        return redirect()->back()->with('success', 'Pengajuan skrisetKKN ditolak oleh Camat.');
    }

    public function skrisetKKNProses()
    {
        $jumlahPengajuan     = SkrisetKKNSubmission::count();
        $pengajuanDiterima   = skrisetKKNSubmission::where('status', 'approved_by_sekcam')->count();
        $pengajuanDisetujui  = skrisetKKNSubmission::where('status', 'approved_by_camat')->count();
        $pengajuanDitolak    = skrisetKKNSubmission::where('status', 'rejected_by_camat')->count();

        $pengajuan = skrisetKKNSubmission::whereIn('status', ['approved_by_camat', 'rejected_by_camat'])
                            ->latest()->paginate(10);

        return view('camat.skrisetKKN.proses', compact(
            'pengajuan',
            'jumlahPengajuan',
            'pengajuanDiterima',
            'pengajuanDisetujui',
            'pengajuanDitolak'
        ));
    }

   // ==================== ACCOUNT ====================

    public function edit()
    {
        /** @var User $user */
        $user = Auth::user();
        return view('camat.account.edit', compact('user'));
    }

    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'ttd' => 'nullable|image|max:2048',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->hasFile('ttd')) {
            if ($user->ttd && Storage::exists('public/ttd/' . $user->ttd)) {
                Storage::delete('public/ttd/' . $user->ttd);
            }

            $file = $request->file('ttd');
            $filename = 'ttd_' . $user->id . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/ttd', $filename);
            $user->ttd = $filename;
        }

        $user->save();

        return redirect()->route('camat.account.edit')->with('success', 'Profil berhasil diperbarui.');
    }

    public function deleteTtd()
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->ttd && Storage::exists('public/ttd/' . $user->ttd)) {
            Storage::delete('public/ttd/' . $user->ttd);
        }

        $user->ttd = null;
        $user->save();

        return redirect()->route('camat.account.edit')->with('success', 'Tanda tangan berhasil dihapus.');
    }
    public function updatePassword(Request $request)
{
     /** @var \App\Models\User $user */
    $user = Auth::user();

    $request->validate([
        'current_password' => ['required', 'string'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
    ]);

    // Cek password lama
    if (!Hash::check($request->current_password, $user->password)) {
        return redirect()->back()->withErrors(['current_password' => 'Password lama salah']);
    }

    // Update password baru
    $user->password = Hash::make($request->password);
    $user->save();

    return redirect()->back()->with('success', 'Password berhasil diubah.');
}
}
