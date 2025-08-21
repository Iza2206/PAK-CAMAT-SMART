<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\CamatController;
use App\Http\Controllers\KasiKesosController;
use App\Http\Controllers\KasiPemerintahanController;
use App\Http\Controllers\KasiTrantibController;
use App\Http\Controllers\KasubbagUmpegController;
use App\Http\Controllers\MejaLayananController;
use App\Http\Controllers\SekcamController;
use App\Http\Controllers\AntrianController;
use App\Http\Controllers\AdminAccountController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => redirect()->route('login'));

// ===================== AUTH =====================
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');



// ===================== SUPER ADMIN DASHBOARD =====================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    

    // // Manajemen Akun
    Route::get('/akun', [AdminAccountController::class, 'index'])->name('admin.accounts.index'); // list akun
    Route::get('/akun/create', [AdminAccountController::class, 'create'])->name('admin.accounts.create'); // form tambah
    Route::post('/akun', [AdminAccountController::class, 'store'])->name('admin.accounts.store'); // proses simpan akun

    // // Ubah Password Akun
    Route::get('/akun/{id}/ubah-password', [AdminAccountController::class, 'editPassword'])->name('admin.accounts.editPassword');
    Route::post('/akun/{id}/ubah-password', [AdminAccountController::class, 'updatePassword'])->name('admin.accounts.updatePassword');

    // // Hapus Akun
    Route::delete('/akun/{id}', [AdminAccountController::class, 'destroy'])->name('admin.accounts.destroy');

    // // Export Akun
    Route::get('/akun-export', [AdminAccountController::class, 'export'])->name('admin.accounts.export');
});
// antrian tv
Route::get('/antrian-tv', [AntrianController::class, 'index']);


// ---------------- Meja Layanan ----------------
Route::middleware(['auth', 'role:meja_layanan'])->prefix('meja-layanan')->group(function () {
    Route::get('/dashboard', [MejaLayananController::class, 'index'])->name('meja.dashboard');

    // BPJS
    Route::get('/layanan/bpjs', [MejaLayananController::class, 'bpjsList'])->name('bpjs.list'); // List Data
    Route::get('/layanan/bpjs/create', [MejaLayananController::class, 'bpjsCreate'])->name('bpjs.create'); // Tambah
    Route::post('/layanan/bpjs/store', [MejaLayananController::class, 'bpjsStore'])->name('bpjs.store');  // Simpan
    Route::patch('/layanan/bpjs/{id}/kirim-kasi', [MejaLayananController::class, 'kirimKeKasi'])->name('bpjs.kirimkasi'); //kirim ke kasi kesos
    Route::post('/bpjs/{id}/penilaian', [MejaLayananController::class, 'simpanPenilaianBpjs'])->name('bpjs.penilaian'); // simpan penilaian ikm bpjs
    Route::get('/bpjs/penilaian', [MejaLayananController::class, 'penilaianIndex'])->name('bpjs.penilaian.index'); // list penilaian bpjs
    Route::get('/bpjs/penilaian/pdf', [MejaLayananController::class, 'penilaianPdf'])->name('bpjs.penilaian.pdf');

    // Dispensasi Nikah
    Route::get('/layanan/dispencatin', [MejaLayananController::class, 'DispencatinList'])->name('dispencatin.list'); // List Data
    Route::get('/layanan/dispencatin/create', [MejaLayananController::class, 'DispencatinCreate'])->name('dispencatin.create'); // Tambah
    Route::post('/layanan/dispencatin/store', [MejaLayananController::class, 'DispencatinStore'])->name('dispencatin.store');  // Simpan
    Route::patch('/layanan/dispencatin/{id}/kirim-kasi', [MejaLayananController::class, 'kirimKeKasidispen'])->name('dispencatin.kirimkasi'); //kirim ke kasi kesos
    Route::post('/dispencatin/{id}/penilaian', [MejaLayananController::class, 'simpanPenilaianDispencatin'])->name('dispencatin.penilaian'); // simpan penilaian ikm Dispencatin
    Route::get('/dispencatin/penilaian', [MejaLayananController::class, 'DispencatinpenilaianIndex'])->name('dispencatin.penilaian.index'); // list penilaian Dispencatin

    // === IUMK ===
    Route::get('/layanan/iumk', [MejaLayananController::class, 'iumkList'])->name('iumk.list'); // List Data
    Route::get('/layanan/iumk/create', [MejaLayananController::class, 'iumkCreate'])->name('iumk.create'); // Tambah
    Route::post('/layanan/iumk/store', [MejaLayananController::class, 'iumkStore'])->name('iumk.store');  // Simpan
    Route::patch('/layanan/iumk/{id}/kirim-kasi', [MejaLayananController::class, 'kirimKeKasidispen'])->name('iumk.kirimkasi');
    Route::post('/iumk/{id}/penilaian', [MejaLayananController::class, 'simpanPenilaianiumk'])->name('iumk.penilaian');
    Route::get('/iumk/penilaian', [MejaLayananController::class, 'iumkpenilaianIndex'])->name('iumk.penilaian.index');
    Route::get('/iumk/penilaian/pdf', [MejaLayananController::class, 'penilaianPdf'])->name('iumk.penilaian.pdf');

    // SKTM
    Route::get('/layanan/SKTMs', [MejaLayananController::class, 'SKTMsList'])->name('SKTMs.list'); // List Data
    Route::get('/layanan/SKTMs/create', [MejaLayananController::class, 'SKTMsCreate'])->name('SKTMs.create'); // Tambah
    Route::post('/layanan/SKTMs/store', [MejaLayananController::class, 'SKTMsStore'])->name('SKTMs.store');  // Simpan
    Route::patch('/layanan/SKTMs/{id}/kirim-kasi', [MejaLayananController::class, 'SKTMkirimKeKasi'])->name('SKTMs.kirimkasi'); // kirim ke kasi kesos
    Route::post('/sktm/{id}/penilaian', [MejaLayananController::class, 'simpanPenilaianSktm'])->name('sktm.penilaian'); // simpan penilaian ikm sktm
    Route::get('/sktm/penilaian', [MejaLayananController::class, 'sktmPenilaianIndex'])->name('sktm.penilaian.index'); // list penilaian sktm

    // SKT (Surat Keterangan Tanah)
    Route::get('/layanan/skt', [MejaLayananController::class, 'sktList'])->name('skt.list'); // List Data
    Route::get('/layanan/skt/create', [MejaLayananController::class, 'sktCreate'])->name('skt.create'); // Tambah
    Route::post('/layanan/skt/store', [MejaLayananController::class, 'sktStore'])->name('skt.store'); // Simpan
    Route::patch('/layanan/skt/{id}/kirim-kasi', [MejaLayananController::class, 'sktKirimKeKasi'])->name('skt.kirimkasi'); // Kirim ke Kasi
    Route::post('/skt/{id}/penilaian', [MejaLayananController::class, 'simpanPenilaianSkt'])->name('skt.penilaian');
    Route::get('/skt/penilaian', [MejaLayananController::class, 'sktPenilaianIndex'])->name('skt.penilaian.index'); // list penilaian skt

    // SPPAT-GR (Surat Penyerahan Penguasaan Tanah Ganti Rugi)
    Route::get('/layanan/sppat-gr', [MejaLayananController::class, 'sppatGrList'])->name('sppat_gr.list'); // List Data
    Route::get('/layanan/sppat-gr/create', [MejaLayananController::class, 'sppatGrCreate'])->name('sppat_gr.create'); // Tambah
    Route::post('/layanan/sppat-gr/store', [MejaLayananController::class, 'sppatGrStore'])->name('sppat_gr.store'); // Simpan
    Route::patch('/layanan/sppat-gr/{id}/kirim-kasi', [MejaLayananController::class, 'sppatGrKirimKeKasi'])->name('sppat_gr.kirimkasi'); // Kirim ke Kasi
    Route::post('/sppat_gr/{id}/penilaian', [MejaLayananController::class, 'simpanPenilaiansppatgr'])->name('sppat_gr.penilaian');
    Route::get('/sppat_gr/penilaian', [MejaLayananController::class, 'sppatgrPenilaianIndex'])->name('sppat_gr.penilaian.index'); // list penilaian skt

    // ---------------- Ahli Waris ----------------
    Route::get('/meja/ahliwaris', [MejaLayananController::class, 'ahliWarisList'])->name('ahliwaris.list');
    Route::get('/meja/ahliwaris/create', [MejaLayananController::class, 'ahliWarisCreate'])->name('ahliwaris.create');
    Route::post('/meja/ahliwaris/store', [MejaLayananController::class, 'ahliWarisStore'])->name('ahliwaris.store');
    Route::post('/meja/ahliwaris/{id}/kirim-kasi', [MejaLayananController::class, 'ahliWarisKirimKasi'])->name('ahliwaris.kirimkasi');
    Route::post('/ahliwaris/{id}/penilaian', [MejaLayananController::class, 'simpanPenilaianahliwaris'])->name('ahliwaris.penilaian');
    Route::get('/ahliwaris/penilaian', [MejaLayananController::class, 'ahliwarisPenilaianIndex'])->name('ahliwaris.penilaian.index'); // list penilaian skt

    // Agunan ke Bank
    Route::get('/meja/agunan', [MejaLayananController::class, 'agunanList'])->name('agunan.list');
    Route::get('/meja/agunan/create', [MejaLayananController::class, 'agunanCreate'])->name('agunan.create');
    Route::post('/meja/agunan/store', [MejaLayananController::class, 'agunanStore'])->name('agunan.store');
    Route::post('/meja/agunan/{id}/kirim-kasi', [MejaLayananController::class, 'agunanKirimKasi'])->name('agunan.kirimkasi');
    Route::post('/agunan/{id}/penilaian', [MejaLayananController::class, 'simpanPenilaianagunan'])->name('agunan.penilaian'); // simpan penilaian ikm agunan
    Route::get('/agunan/penilaian', [MejaLayananController::class, 'penilaianIndexagunan'])->name('agunan.penilaian.index'); // list penilaian agunan

    // Sengketa
    Route::get('/meja/sengketa', [MejaLayananController::class, 'sengketaList'])->name('sengketa.list');
    Route::get('/meja/sengketa/create', [MejaLayananController::class, 'sengketaCreate'])->name('sengketa.create');
    Route::post('/meja/sengketa/store', [MejaLayananController::class, 'sengketaStore'])->name('sengketa.store');
    Route::post('/meja/sengketa/{id}/kirim-kasi', [MejaLayananController::class, 'sengketaKirimKasi'])->name('sengketa.kirimkasi');
    Route::post('/sengketa/{id}/penilaian', [MejaLayananController::class, 'simpanPenilaiansengketa'])->name('sengketa.penilaian'); // simpan penilaian ikm sengketa
    Route::get('/sengketa/penilaian', [MejaLayananController::class, 'penilaianIndexSENGKETA'])->name('sengketa.penilaian.index'); // list penilaian bpjs

    // CATIN TNI/Polri
    Route::get('/layanan/catin-tni-polri', [MejaLayananController::class, 'catinTniList'])->name('catin.tni.list');
    Route::get('/layanan/catin-tni-polri/create', [MejaLayananController::class, 'catinTniCreate'])->name('catin.tni.create');
    Route::post('/layanan/catin-tni-polri/store', [MejaLayananController::class, 'catinTniStore'])->name('catin.tni.store');
    Route::patch('/layanan/catin-tni-polri/{id}/kirim-kasi', [MejaLayananController::class, 'catinTniKirimKasi'])->name('catin.tni.kirimkasi');
    Route::post('/catin/{id}/penilaian', [MejaLayananController::class, 'simpanPenilaiancatin'])->name('catin.penilaian'); // simpan penilaian ikm catin
    Route::get('/catin/penilaian', [MejaLayananController::class, 'penilaianIndexcatin'])->name('catins.penilaian.index'); // list penilaian catin

    // SKBD (Surat Keterangan Bersih Diri)
    Route::get('/layanan/SKBDs', [MejaLayananController::class, 'SKBDsList'])->name('SKBDs.list'); // List Data
    Route::get('/layanan/SKBDs/create', [MejaLayananController::class, 'SKBDsCreate'])->name('SKBDs.create'); // Tambah
    Route::post('/layanan/SKBDs/store', [MejaLayananController::class, 'SKBDsStore'])->name('SKBDs.store');  // Simpan
    Route::patch('/layanan/SKBDs/{id}/kirim-kasi', [MejaLayananController::class, 'SKBDkirimKeKasi'])->name('SKBDs.kirimkasi');
    Route::post('/skbd/{id}/penilaian', [MejaLayananController::class, 'simpanPenilaianskbd'])->name('skbd.penilaian'); // simpan penilaian ikm skbd
    Route::get('/skbd/penilaian', [MejaLayananController::class, 'penilaianIndexSKBD'])->name('SKBDs.penilaian.index'); // list penilaian skbd

    // === Dispensasi ===
    Route::get('/layanan/dispensasi', [MejaLayananController::class, 'dispensasi'])->name('layanan.dispensasi');

    // === SK Riset KKN ===
    Route::get('/layanan/skrisetKKN', [MejaLayananController::class, 'skrisetKKNList'])->name('skrisetKKN.list'); // List Data
    Route::get('/layanan/skrisetKKN/create', [MejaLayananController::class, 'skrisetKKNCreate'])->name('skrisetKKN.create'); // Tambah
    Route::post('/layanan/skrisetKKN/store', [MejaLayananController::class, 'skrisetKKNStore'])->name('skrisetKKN.store');  // Simpan
    Route::patch('/layanan/skrisetKKN/{id}/kirim-kasi', [MejaLayananController::class, 'kirimKeKasidispen'])->name('skrisetKKN.kirimkasi');
    Route::post('/skrisetKKN/{id}/penilaian', [MejaLayananController::class, 'simpanPenilaianskrisetKKN'])->name('skrisetKKN.penilaian');
    Route::get('/skrisetKKN/penilaian', [MejaLayananController::class, 'skrisetKKNpenilaianIndex'])->name('skrisetKKN.penilaian.index');

    // === Export ===
    Route::get('/layanan/export', [MejaLayananController::class, 'export'])->name('mejalayanan.export');
});


// ---------------- Kasi Kesos ----------------
Route::middleware(['auth', 'role:kasi_kesos'])->prefix('kasi-kesos')->group(function () {
    Route::get('/dashboard', [KasiKesosController::class, 'index'])->name('kasi_kesos.dashboard');

    // Layanan BPJS
    Route::get('/bpjs', [KasiKesosController::class, 'bpjsIndex'])->name('kasi_kesos.bpjs.index');
    Route::post('/bpjs/{id}/approve', [KasiKesosController::class, 'bpjsApprove'])->name('kasi_kesos.bpjs.approve');
    Route::post('/bpjs/{id}/reject', [KasiKesosController::class, 'bpjsReject'])->name('kasi_kesos.bpjs.reject');
    Route::get('/bpjs/proses', [KasiKesosController::class, 'bpjsProses'])->name('kasi_kesos.bpjs.proses');

    // Layanan dispensasi nikah
    Route::get('/dispencatin', [KasiKesosController::class, 'dispencatinIndex'])->name('kasi_kesos.dispencatin.index');
    Route::post('/dispencatin/{id}/approve', [KasiKesosController::class, 'dispencatinApprove'])->name('kasi_kesos.dispencatin.approve');
    Route::post('/dispencatin/{id}/reject', [KasiKesosController::class, 'dispencatinReject'])->name('kasi_kesos.dispencatin.reject');
    Route::get('/dispencatin/proses', [KasiKesosController::class, 'dispencatinProses'])->name('kasi_kesos.dispencatin.proses');
    Route::post('/dispensasi-nikah/{id}/upload-surat', [KasiKesosController::class, 'uploadSuratDispensasi'])
    ->name('dispensasi.uploadSurat');
     Route::get('/dispencatin/approveByCamat', [KasiKesosController::class, 'dispencatinApproveByCamatIndex'])->name('kasi_kesos.dispencatin.approveByCamat');
     Route::get('/kasi-kesos/dispencatin/approve/{id}/proses', [KasiKesosController::class, 'proses'])
    ->name('kasi_kesos.dispencatin.prosesTTD');
    Route::post('/kasi-kesos/dispencatin/{id}/proses', [KasiKesosController::class, 'prosesStore'])
    ->name('kasi_kesos.dispencatin.proses.storeFinal');

        // Layanan IUMK
    Route::get('/iumk', [KasiKesosController::class, 'iumkIndex'])->name('kasi_kesos.iumk.index');
    Route::post('/iumk/{id}/approve', [KasiKesosController::class, 'iumkApprove'])->name('kasi_kesos.iumk.approve');
    Route::post('/iumk/{id}/reject', [KasiKesosController::class, 'iumkReject'])->name('kasi_kesos.iumk.reject');
    Route::get('/iumk/proses', [KasiKesosController::class, 'iumkProses'])->name('kasi_kesos.iumk.proses');
    Route::post('/iumk-nikah/{id}/upload-surat', [KasiKesosController::class, 'uploadSuratIumk'])
    ->name('iumk.uploadSurat');
     Route::get('/iumk/approveByCamat', [KasiKesosController::class, 'iumkApproveByCamatIndex'])->name('kasi_kesos.iumk.approveByCamat');
     Route::get('/kasi-kesos/iumk/approve/{id}/proses', [KasiKesosController::class, 'prosesiumk'])
    ->name('kasi_kesos.iumk.prosesTTD');
    Route::post('/kasi-kesos/iumk/{id}/proses', [KasiKesosController::class, 'prosesStoreIumk'])
    ->name('kasi_kesos.iumk.proses.storeFinal');


    // Layanan SKTM
    Route::get('/sktm', [KasiKesosController::class, 'sktmIndex'])->name('kasi_kesos.sktm.index');
    Route::post('/sktm/{id}/approve', [KasiKesosController::class, 'sktmApprove'])->name('kasi_kesos.sktm.approve');
    Route::post('/sktm/{id}/reject', [KasiKesosController::class, 'sktmReject'])->name('kasi_kesos.sktm.reject');
    Route::get('/sktm/proses', [KasiKesosController::class, 'sktmProses'])->name('kasi_kesos.sktm.proses');



});

// ---------------- Kasi Trantib ----------------
Route::middleware(['auth', 'role:kasi_trantib'])->prefix('kasi-trantib')->group(function () {
    Route::get('/dashboard', [KasiTrantibController::class, 'index'])->name('kasi_trantib.dashboard');
    // CATIN TNI/Polri - Kasi Trantib
    Route::get('/catin-tni', [KasiTrantibController::class, 'catinTniIndex'])->name('kasi_trantib.catin_tni.index');
    Route::get('/catin-tni/proses', [KasiTrantibController::class, 'catinTniProses'])->name('kasi_trantib.catin_tni.proses');
    Route::post('/catin-tni/{id}/approve', [KasiTrantibController::class, 'catinTniApprove'])->name('kasi_trantib.catin_tni.approve');
    Route::post('/catin-tni/{id}/reject', [KasiTrantibController::class, 'catinTniReject'])->name('kasi_trantib.catin_tni.reject');
    // Layanan SKBD
    Route::get('/skbd', [KasiTrantibController::class, 'skbdIndex'])->name('kasi_trantib.skbd.index');
    Route::post('/skbd/{id}/approve', [KasiTrantibController::class, 'skbdApprove'])->name('kasi_trantib.skbd.approve');
    Route::post('/skbd/{id}/reject', [KasiTrantibController::class, 'skbdReject'])->name('kasi_trantib.skbd.reject');
    Route::get('/skbd/proses', [KasiTrantibController::class, 'skbdProses'])->name('kasi_trantib.skbd.proses');
});

// ---------------- Kasi Pemerintahan ----------------
Route::middleware(['auth', 'role:kasi_pemerintahan'])->prefix('kasi-pemerintahan')->group(function () {
    Route::get('/dashboard', [KasiPemerintahanController::class, 'index'])->name('kasi_pemerintahan.dashboard');

    // Silang Sengketa - Kasi Pemerintahan
    Route::get('/silang-sengketa', [KasiPemerintahanController::class, 'silangSengketaIndex'])->name('kasi_pemerintahan.silang_sengketa.index');
    Route::get('/silang-sengketa/proses', [KasiPemerintahanController::class, 'silangSengketaProses'])->name('kasi_pemerintahan.silang_sengketa.proses');
    Route::post('/silang-sengketa/{id}/approve', [KasiPemerintahanController::class, 'silangSengketaApprove'])->name('kasi_pemerintahan.silang_sengketa.approve');
    Route::post('/silang-sengketa/{id}/reject', [KasiPemerintahanController::class, 'silangSengketaReject'])->name('kasi_pemerintahan.silang_sengketa.reject');

    // Agunan ke Bank - Kasi Pemerintahan
    Route::get('/agunan-bank', [KasiPemerintahanController::class, 'agunanBankIndex'])->name('kasi_pemerintahan.agunan_bank.index');
    Route::get('/agunan-bank/proses', [KasiPemerintahanController::class, 'agunanBankProses'])->name('kasi_pemerintahan.agunan_bank.proses');
    Route::post('/agunan-bank/{id}/approve', [KasiPemerintahanController::class, 'agunanBankApprove'])->name('kasi_pemerintahan.agunan_bank.approve');
    Route::post('/agunan-bank/{id}/reject', [KasiPemerintahanController::class, 'agunanBankReject'])->name('kasi_pemerintahan.agunan_bank.reject');

    // Surat Pernyataan Ahli Waris - Kasi Pemerintahan
    Route::get('/ahli-waris', [KasiPemerintahanController::class, 'ahliWarisIndex'])->name('kasi_pemerintahan.ahli_waris.index');
    Route::get('/ahli-waris/proses', [KasiPemerintahanController::class, 'ahliWarisProses'])->name('kasi_pemerintahan.ahli_waris.proses');
    Route::post('/ahli-waris/{id}/approve', [KasiPemerintahanController::class, 'ahliWarisApprove'])->name('kasi_pemerintahan.ahli_waris.approve');
    Route::post('/ahli-waris/{id}/reject', [KasiPemerintahanController::class, 'ahliWarisReject'])->name('kasi_pemerintahan.ahli_waris.reject');

    // Surat Penyerahan Penguasaan Tanah Ganti Rugi (SPPAT-GR) - Kasi Pemerintahan
    Route::get('/sppat-gr', [KasiPemerintahanController::class, 'sppatGrIndex'])->name('kasi_pemerintahan.sppat_gr.index');
    Route::get('/sppat-gr/proses', [KasiPemerintahanController::class, 'sppatGrProses'])->name('kasi_pemerintahan.sppat_gr.proses');
    Route::post('/sppat-gr/{id}/approve', [KasiPemerintahanController::class, 'sppatGrApprove'])->name('kasi_pemerintahan.sppat_gr.approve');
    Route::post('/sppat-gr/{id}/reject', [KasiPemerintahanController::class, 'sppatGrReject'])->name('kasi_pemerintahan.sppat_gr.reject');

    // Surat Keterangan Tanah (SKT) - Kasi Pemerintahan
    Route::get('/skt', [KasiPemerintahanController::class, 'sktIndex'])->name('kasi_pemerintahan.skt.index');
    Route::get('/skt/proses', [KasiPemerintahanController::class, 'sktProses'])->name('kasi_pemerintahan.skt.proses');
    Route::post('/skt/{id}/approve', [KasiPemerintahanController::class, 'sktApprove'])->name('kasi_pemerintahan.skt.approve');
    Route::post('/skt/{id}/reject', [KasiPemerintahanController::class, 'sktReject'])->name('kasi_pemerintahan.skt.reject');

});


// ---------------- Sekcam ----------------
Route::middleware(['auth', 'role:sekcam'])->prefix('sekcam')->group(function () {
    Route::get('/dashboard', [SekcamController::class, 'index'])->name('sekcam.dashboard');

    // ===== Layanan BPJS =====
    Route::get('/bpjs', [SekcamController::class, 'bpjsIndex'])->name('sekcam.bpjs.index');
    Route::post('/bpjs/{id}/approve', [SekcamController::class, 'bpjsApprove'])->name('sekcam.bpjs.approve');
    Route::post('/bpjs/{id}/reject', [SekcamController::class, 'bpjsReject'])->name('sekcam.bpjs.reject');
    Route::get('/bpjs/proses', [SekcamController::class, 'bpjsProses'])->name('sekcam.bpjs.proses');

    // ===== Layanan SKTM =====
    Route::get('/sktm', [SekcamController::class, 'sktmIndex'])->name('sekcam.sktm.index');
    Route::post('/sktm/{id}/approve', [SekcamController::class, 'sktmApprove'])->name('sekcam.sktm.approve');
    Route::post('/sktm/{id}/reject', [SekcamController::class, 'sktmReject'])->name('sekcam.sktm.reject');
    Route::get('/sktm/proses', [SekcamController::class, 'sktmProses'])->name('sekcam.sktm.proses');

    // ===== Layanan SK TANAH =====
    Route::get('/skt', [SekcamController::class, 'sktIndex'])->name('sekcam.skt.index');
    Route::post('/skt/{id}/approve', [SekcamController::class, 'sktApprove'])->name('sekcam.skt.approve');
    Route::post('/skt/{id}/reject', [SekcamController::class, 'sktReject'])->name('sekcam.skt.reject');
    Route::get('/skt/proses', [SekcamController::class, 'sktProses'])->name('sekcam.skt.proses');

    // =====  SPPAT-GR =====
    Route::get('/sppatgr', [SekcamController::class, 'sppatgrIndex'])->name('sekcam.sppatgr.index');
    Route::post('/sppatgr/{id}/approve', [SekcamController::class, 'sppatgrApprove'])->name('sekcam.sppatgr.approve');
    Route::post('/sppatgr/{id}/reject', [SekcamController::class, 'sppatgrReject'])->name('sekcam.sppatgr.reject');
    Route::get('/sppatgr/proses', [SekcamController::class, 'sppatgrProses'])->name('sekcam.sppatgr.proses');

    // ===== Layanan Registrasi Ahli Waris  =====
    Route::get('/ahliwaris', [SekcamController::class, 'ahliwarisIndex'])->name('sekcam.ahliwaris.index');
    Route::post('/ahliwaris/{id}/approve', [SekcamController::class, 'ahliwarisdApprove'])->name('sekcam.ahliwaris.approve');
    Route::post('/ahliwaris/{id}/reject', [SekcamController::class, 'ahliwarisdReject'])->name('sekcam.ahliwaris.reject');
    Route::get('/ahliwaris/proses', [SekcamController::class, 'ahliwarisProses'])->name('sekcam.ahliwaris.proses');

    // ===== Layanan Registrasi Agunan ke Bank  =====
    Route::get('/agunan', [SekcamController::class, 'agunanIndex'])->name('sekcam.agunan.index');
    Route::post('/agunan/{id}/approve', [SekcamController::class, 'agunandApprove'])->name('sekcam.agunan.approve');
    Route::post('/agunan/{id}/reject', [SekcamController::class, 'agunandReject'])->name('sekcam.agunan.reject');
    Route::get('/agunan/proses', [SekcamController::class, 'agunanProses'])->name('sekcam.agunan.proses');

    // ===== Layanan Silang Sengketa =====
    Route::get('/silang_sengketa', [SekcamController::class, 'sengketaIndex'])->name('sekcam.silang_sengketa.index');
    Route::post('/silang_sengketa/{id}/approve', [SekcamController::class, 'sengketadApprove'])->name('sekcam.silang_sengketa.approve');
    Route::post('/silang_sengketa/{id}/reject', [SekcamController::class, 'sengketadReject'])->name('sekcam.silang_sengketa.reject');
    Route::get('/silang_sengketa/proses', [SekcamController::class, 'sengketaProsesX'])->name('sekcam.silang_sengketa.proses');

    // ===== Layanan Catin TNI/POLRI =====
    Route::get('/catin-tni', [SekcamController::class, 'catintniIndex'])->name('sekcam.catin-tni.index');
    Route::post('/catin-tni/{id}/approve', [SekcamController::class, 'catintnidApprove'])->name('sekcam.catin-tni.approve');
    Route::post('/catin-tni/{id}/reject', [SekcamController::class, 'catintnidReject'])->name('sekcam.catin-tni.reject');
    Route::get('/catin-tni/proses', [SekcamController::class, 'catintniProses'])->name('sekcam.catin-tni.proses');

    // ===== Layanan SKBD =====
    Route::get('/skbd', [SekcamController::class, 'skbdIndex'])->name('sekcam.skbd.index');
    Route::post('/skbd/{id}/approve', [SekcamController::class, 'skbdApprove'])->name('sekcam.skbd.approve');
    Route::post('/skbd/{id}/reject', [SekcamController::class, 'skbdReject'])->name('sekcam.skbd.reject');
    Route::get('/skbd/proses', [SekcamController::class, 'skbdProses'])->name('sekcam.skbd.proses');

    // Layanan dispensasi nikah
    Route::get('/dispencatin', [SekcamController::class, 'dispencatinIndex'])->name('sekcam.dispencatin.index');
    Route::post('/dispencatin/{id}/approve', [SekcamController::class, 'dispencatinApprove'])->name('sekcam.dispencatin.approve');
    Route::post('/dispencatin/{id}/reject', [SekcamController::class, 'dispencatinReject'])->name('sekcam.dispencatin.reject');
    Route::get('/dispencatin/proses', [SekcamController::class, 'dispencatinProses'])->name('sekcam.dispencatin.proses');

    // Layanan izin usaha mikro
    Route::get('/iumk', [SekcamController::class, 'iumkIndex'])->name('sekcam.iumk.index');
    Route::post('/iumk/{id}/approve', [SekcamController::class, 'iumkApprove'])->name('sekcam.iumk.approve');
    Route::post('/iumk/{id}/reject', [SekcamController::class, 'iumkReject'])->name('sekcam.iumk.reject');
    Route::get('/iumk/proses', [SekcamController::class, 'iumkProses'])->name('sekcam.iumk.proses');

    // Layanan SK Riset KKN
    Route::get('/skrisetKKN', [SekcamController::class, 'skrisetKKNIndex'])->name('sekcam.skrisetKKN.index');
    Route::post('/skrisetKKN/{id}/approve', [SekcamController::class, 'skrisetKKNApprove'])->name('sekcam.skrisetKKN.approve');
    Route::post('/skrisetKKN/{id}/reject', [SekcamController::class, 'skrisetKKNReject'])->name('sekcam.skrisetKKN.reject');
    Route::get('/skrisetKKN/proses', [SekcamController::class, 'skrisetKKNProses'])->name('sekcam.skrisetKKN.proses');

});


// ---------------- Camat ----------------
Route::middleware(['auth', 'role:camat'])->prefix('camat')->group(function () {
    Route::get('/dashboard', [CamatController::class, 'index'])->name('camat.dashboard');

    // Layanan BPJS
    Route::get('/bpjs', [CamatController::class, 'bpjsIndex'])->name('camat.bpjs.index');
    Route::post('/bpjs/{id}/approve', [CamatController::class, 'bpjsApprove'])->name('camat.bpjs.approve');
    Route::post('/bpjs/{id}/reject', [CamatController::class, 'bpjsReject'])->name('camat.bpjs.reject');
    Route::get('/bpjs/proses', [CamatController::class, 'bpjsProses'])->name('camat.bpjs.proses');

    // Layanan SKTM
    Route::get('/sktm', [CamatController::class, 'sktmIndex'])->name('camat.sktm.index');
    Route::post('/sktm/{id}/approve', [CamatController::class, 'sktmApprove'])->name('camat.sktm.approve');
    Route::post('/sktm/{id}/reject', [CamatController::class, 'sktmReject'])->name('camat.sktm.reject');
    Route::get('/sktm/proses', [CamatController::class, 'sktmProses'])->name('camat.sktm.proses');

    // ===== Layanan SK TANAH =====
    Route::get('/skt', [CamatController::class, 'sktIndex'])->name('camat.skt.index');
    Route::post('/skt/{id}/approve', [CamatController::class, 'sktApprove'])->name('camat.skt.approve');
    Route::post('/skt/{id}/reject', [CamatController::class, 'sktReject'])->name('camat.skt.reject');
    Route::get('/skt/proses', [CamatController::class, 'sktProses'])->name('camat.skt.proses');

     // =====  SPPAT-GR =====
    Route::get('/sppatgr', [CamatController::class, 'sppatgrIndex'])->name('camat.sppatgr.index');
    Route::post('/sppatgr/{id}/approve', [CamatController::class, 'sppatgrApprove'])->name('camat.sppatgr.approve');
    Route::post('/sppatgr/{id}/reject', [CamatController::class, 'sppatgrReject'])->name('camat.sppatgr.reject');
    Route::get('/sppatgr/proses', [CamatController::class, 'sppatgrProses'])->name('camat.sppatgr.proses');

    // ===== Layanan Ahli Waris =====
    Route::get('/ahliwaris', [CamatController::class, 'ahliwarisIndex'])->name('camat.ahliwaris.index');
    Route::post('/ahliwaris/{id}/approve', [CamatController::class, 'ahliwarisApprove'])->name('camat.ahliwaris.approve');
    Route::post('/ahliwaris/{id}/reject', [CamatController::class, 'ahliwarisReject'])->name('camat.ahliwaris.reject');
    Route::get('/ahliwaris/proses', [CamatController::class, 'ahliwarisProses'])->name('camat.ahliwaris.proses');

    // ===== Layanan agunan bank =====
    Route::get('/agunan', [CamatController::class, 'agunanIndex'])->name('camat.agunan.index');
    Route::post('/agunan/{id}/approve', [CamatController::class, 'agunanApprove'])->name('camat.agunan.approve');
    Route::post('/agunan/{id}/reject', [CamatController::class, 'agunanReject'])->name('camat.agunan.reject');
    Route::get('/agunan/proses', [CamatController::class, 'agunanProses'])->name('camat.agunan.proses');

     // ===== Layanan Silang Sengketa =====
    Route::get('/silang_sengketa', [CamatController::class, 'sengketaIndex'])->name('camat.silang_sengketa.index');
    Route::post('/silang_sengketa/{id}/approve', [CamatController::class, 'sengketaApprove'])->name('camat.silang_sengketa.approve');
    Route::post('/silang_sengketa/{id}/reject', [CamatController::class, 'sengketaReject'])->name('camat.silang_sengketa.reject');
    Route::get('/silang_sengketa/proses', [CamatController::class, 'sengketaProses'])->name('camat.silang_sengketa.proses');

     // ===== Layanan CATIN TNI/POLRI =====
    Route::get('/catin', [CamatController::class, 'catinIndex'])->name('camat.catin.index');
    Route::post('/catin/{id}/approve', [CamatController::class, 'catinApprove'])->name('camat.catin.approve');
    Route::post('/catin/{id}/reject', [CamatController::class, 'catinReject'])->name('camat.catin.reject');
    Route::get('/catin/proses', [CamatController::class, 'catinProses'])->name('camat.catin.proses');

    // ===== Layanan SKBD =====
    Route::get('/skbd', [CamatController::class, 'skbdIndex'])->name('camat.skbd.index');
    Route::post('/skbd/{id}/approve', [CamatController::class, 'skbdApprove'])->name('camat.skbd.approve');
    Route::post('/skbd/{id}/reject', [CamatController::class, 'skbdReject'])->name('camat.skbd.reject');
    Route::get('/skbd/proses', [CamatController::class, 'skbdProses'])->name('camat.skbd.proses');

    // Layanan dispensasi nikah
    Route::get('/dispencatin', [CamatController::class, 'dispencatinIndex'])->name('camat.dispencatin.index');
    Route::post('/dispencatin/{id}/approve', [CamatController::class, 'dispencatinApprove'])->name('camat.dispencatin.approve');
    Route::post('/dispencatin/{id}/reject', [CamatController::class, 'dispencatinReject'])->name('camat.dispencatin.reject');
    Route::get('/dispencatin/proses', [CamatController::class, 'dispencatinProses'])->name('camat.dispencatin.proses');

    // Layanan Izin Usaha Mikro
    Route::get('/iumk', [CamatController::class, 'iumkIndex'])->name('camat.iumk.index');
    Route::post('/iumk/{id}/approve', [CamatController::class, 'iumkApprove'])->name('camat.iumk.approve');
    Route::post('/iumk/{id}/reject', [CamatController::class, 'iumkReject'])->name('camat.iumk.reject');
    Route::get('/iumk/proses', [CamatController::class, 'iumkProses'])->name('camat.iumk.proses');

    // Layanan SK Riset KKN
    Route::get('/skrisetKKN', [CamatController::class, 'skrisetKKNIndex'])->name('camat.skrisetKKN.index');
    Route::post('/skrisetKKN/{id}/approve', [CamatController::class, 'skrisetKKNApprove'])->name('camat.skrisetKKN.approve');
    Route::post('/skrisetKKN/{id}/reject', [CamatController::class, 'skrisetKKNReject'])->name('camat.skrisetKKN.reject');
    Route::get('/skrisetKKN/proses', [CamatController::class, 'skrisetKKNProses'])->name('camat.skrisetKKN.proses');

    // Account (Manajemen Akun)
    Route::get('/account', [CamatController::class, 'edit'])->name('camat.account.edit');
    Route::put('/account', [CamatController::class, 'update'])->name('camat.account.update');
    Route::delete('/account/ttd', [CamatController::class, 'deleteTtd'])->name('camat.account.ttd.delete');
    Route::put('/account/password', [CamatController::class, 'updatePassword'])->name('camat.account.password.update');

});

// ---------------- Kasubbag Umpeg ----------------
Route::middleware(['auth', 'role:kasubbag_umpeg'])->prefix('kasubbag-umpeg')->group(function () {
    Route::get('/dashboard', [KasubbagUmpegController::class, 'index'])->name('kasubbag_umpeg.dashboard');

    // Layanan SK Riset KKN
    Route::get('/skrisetKKN', [KasubbagUmpegController::class, 'skrisetKKNIndex'])->name('kasubbag_umpeg.skrisetKKN.index');
    Route::post('/skrisetKKN/{id}/approve', [KasubbagUmpegController::class, 'skrisetKKNApprove'])->name('kasubbag_umpeg.skrisetKKN.approve');
    Route::post('/skrisetKKN/{id}/reject', [KasubbagUmpegController::class, 'skrisetKKNReject'])->name('kasubbag_umpeg.skrisetKKN.reject');
    Route::get('/skrisetKKN/proses', [KasubbagUmpegController::class, 'skrisetKKNProses'])->name('kasubbag_umpeg.skrisetKKN.proses');
    Route::post('/skrisetKKN-nikah/{id}/upload-surat', [KasubbagUmpegController::class, 'uploadSuratskrisetKKN'])
    ->name('skrisetKKN.uploadSurat');
     Route::get('/skrisetKKN/approveByCamat', [KasubbagUmpegController::class, 'skrisetKKNApproveByCamatIndex'])->name('kasubbag_umpeg.skrisetKKN.approveByCamat');
     Route::get('/kasi-kesos/skrisetKKN/approve/{id}/proses', [KasubbagUmpegController::class, 'prosesskrisetKKN'])
    ->name('kasubbag_umpeg.skrisetKKN.prosesTTD');
    Route::post('/kasi-kesos/skrisetKKN/{id}/proses', [KasubbagUmpegController::class, 'prosesStoreskrisetKKN'])
    ->name('kasubbag_umpeg.skrisetKKN.proses.storeFinal');
});


