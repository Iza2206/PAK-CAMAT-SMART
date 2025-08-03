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
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::delete('/delete/{id}', [AdminDashboardController::class, 'destroy'])->name('admin.delete');
    Route::post('/reset-password/{id}', [AdminDashboardController::class, 'resetPassword'])->name('admin.resetPassword');
    Route::get('/export', [AdminDashboardController::class, 'export'])->name('admin.export');
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
    Route::patch('/layanan/bpjs/{id}/kirim-kasi', [MejaLayananController::class, 'kirimKeKasi'])->name('bpjs.kirimkasi');
    Route::post('/bpjs/{id}/penilaian', [MejaLayananController::class, 'simpanPenilaianBpjs'])->name('bpjs.penilaian');
    Route::get('/bpjs/cari', [MejaLayananController::class, 'cariPengajuanBpjsByNik'])->name('bpjs.cari');

    // SKTM
    Route::get('/layanan/SKTMs', [MejaLayananController::class, 'SKTMsList'])->name('SKTMs.list'); // List Data
    Route::get('/layanan/SKTMs/create', [MejaLayananController::class, 'SKTMsCreate'])->name('SKTMs.create'); // Tambah
    Route::post('/layanan/SKTMs/store', [MejaLayananController::class, 'SKTMsStore'])->name('SKTMs.store');  // Simpan
    Route::patch('/layanan/SKTMs/{id}/kirim-kasi', [MejaLayananController::class, 'SKTMkirimKeKasi'])->name('SKTMs.kirimkasi');
    // SKT (Surat Keterangan Tanah)
    Route::get('/layanan/skt', [MejaLayananController::class, 'sktList'])->name('skt.list'); // List Data
    Route::get('/layanan/skt/create', [MejaLayananController::class, 'sktCreate'])->name('skt.create'); // Tambah
    Route::post('/layanan/skt/store', [MejaLayananController::class, 'sktStore'])->name('skt.store'); // Simpan
    Route::patch('/layanan/skt/{id}/kirim-kasi', [MejaLayananController::class, 'sktKirimKeKasi'])->name('skt.kirimkasi'); // Kirim ke Kasi
    // SPPAT-GR (Surat Penyerahan Penguasaan Tanah Ganti Rugi)
    Route::get('/layanan/sppat-gr', [MejaLayananController::class, 'sppatGrList'])->name('sppat_gr.list'); // List Data
    Route::get('/layanan/sppat-gr/create', [MejaLayananController::class, 'sppatGrCreate'])->name('sppat_gr.create'); // Tambah
    Route::post('/layanan/sppat-gr/store', [MejaLayananController::class, 'sppatGrStore'])->name('sppat_gr.store'); // Simpan
    Route::patch('/layanan/sppat-gr/{id}/kirim-kasi', [MejaLayananController::class, 'sppatGrKirimKeKasi'])->name('sppat_gr.kirimkasi'); // Kirim ke Kasi
    // ---------------- Ahli Waris ----------------
    Route::get('/meja/ahliwaris', [MejaLayananController::class, 'ahliWarisList'])->name('ahliwaris.list');
    Route::get('/meja/ahliwaris/create', [MejaLayananController::class, 'ahliWarisCreate'])->name('ahliwaris.create');
    Route::post('/meja/ahliwaris/store', [MejaLayananController::class, 'ahliWarisStore'])->name('ahliwaris.store');
    Route::post('/meja/ahliwaris/{id}/kirim-kasi', [MejaLayananController::class, 'ahliWarisKirimKasi'])->name('ahliwaris.kirimkasi');
    // Agunan ke Bank
    Route::get('/meja/agunan', [MejaLayananController::class, 'agunanList'])->name('agunan.list');
    Route::get('/meja/agunan/create', [MejaLayananController::class, 'agunanCreate'])->name('agunan.create');
    Route::post('/meja/agunan/store', [MejaLayananController::class, 'agunanStore'])->name('agunan.store');
    Route::post('/meja/agunan/{id}/kirim-kasi', [MejaLayananController::class, 'agunanKirimKasi'])->name('agunan.kirimkasi');
    // Sengketa
    Route::get('/meja/sengketa', [MejaLayananController::class, 'sengketaList'])->name('sengketa.list');
    Route::get('/meja/sengketa/create', [MejaLayananController::class, 'sengketaCreate'])->name('sengketa.create');
    Route::post('/meja/sengketa/store', [MejaLayananController::class, 'sengketaStore'])->name('sengketa.store');
    Route::post('/meja/sengketa/{id}/kirim-kasi', [MejaLayananController::class, 'sengketaKirimKasi'])->name('sengketa.kirimkasi');
    // CATIN TNI/Polri
    Route::get('/layanan/catin-tni-polri', [MejaLayananController::class, 'catinTniList'])->name('catin.tni.list');
    Route::get('/layanan/catin-tni-polri/create', [MejaLayananController::class, 'catinTniCreate'])->name('catin.tni.create');
    Route::post('/layanan/catin-tni-polri/store', [MejaLayananController::class, 'catinTniStore'])->name('catin.tni.store');
    Route::patch('/layanan/catin-tni-polri/{id}/kirim-kasi', [MejaLayananController::class, 'catinTniKirimKasi'])->name('catin.tni.kirimkasi');
    // SKBD (Surat Keterangan Bersih Diri)
    Route::get('/layanan/SKBDs', [MejaLayananController::class, 'SKBDsList'])->name('SKBDs.list'); // List Data
    Route::get('/layanan/SKBDs/create', [MejaLayananController::class, 'SKBDsCreate'])->name('SKBDs.create'); // Tambah
    Route::post('/layanan/SKBDs/store', [MejaLayananController::class, 'SKBDsStore'])->name('SKBDs.store');  // Simpan
    Route::patch('/layanan/SKBDs/{id}/kirim-kasi', [MejaLayananController::class, 'SKBDkirimKeKasi'])->name('SKBDs.kirimkasi');

    // === Dispensasi ===
    Route::get('/layanan/dispensasi', [MejaLayananController::class, 'dispensasi'])->name('layanan.dispensasi');

    // === IUMK ===
    Route::get('/layanan/iumk', [MejaLayananController::class, 'iumk'])->name('layanan.iumk');

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

    // ===== Layanan SKBD =====
    Route::get('/skbd', [SekcamController::class, 'skbdIndex'])->name('sekcam.skbd.index');
    Route::post('/skbd/{id}/approve', [SekcamController::class, 'skbdApprove'])->name('sekcam.skbd.approve');
    Route::post('/skbd/{id}/reject', [SekcamController::class, 'skbdReject'])->name('sekcam.skbd.reject');
    Route::get('/skbd/proses', [SekcamController::class, 'skbdProses'])->name('sekcam.skbd.proses');

    // ===== Layanan dari Kasi Pemerintahan =====
    // Route::get('/silang-sengketa', [KasiPemerintahanController::class, 'silangSengketaIndex'])->name('kasi_pemerintahan.silang_sengketa.index');
    // Route::get('/silang-sengketa/proses', [KasiPemerintahanController::class, 'silangSengketaProses'])->name('kasi_pemerintahan.silang_sengketa.proses');
    // Route::post('/silang-sengketa/{id}/approve', [KasiPemerintahanController::class, 'silangSengketaApprove'])->name('kasi_pemerintahan.silang_sengketa.approve');
    // Route::post('/silang-sengketa/{id}/reject', [KasiPemerintahanController::class, 'silangSengketaReject'])->name('kasi_pemerintahan.silang_sengketa.reject');

    // Route::get('/agunan-bank', [KasiPemerintahanController::class, 'agunanBankIndex'])->name('kasi_pemerintahan.agunan_bank.index');
    // Route::get('/agunan-bank/proses', [KasiPemerintahanController::class, 'agunanBankProses'])->name('kasi_pemerintahan.agunan_bank.proses');
    // Route::post('/agunan-bank/{id}/approve', [KasiPemerintahanController::class, 'agunanBankApprove'])->name('kasi_pemerintahan.agunan_bank.approve');
    // Route::post('/agunan-bank/{id}/reject', [KasiPemerintahanController::class, 'agunanBankReject'])->name('kasi_pemerintahan.agunan_bank.reject');

    // Route::get('/ahli-waris', [KasiPemerintahanController::class, 'ahliWarisIndex'])->name('kasi_pemerintahan.ahli_waris.index');
    // Route::get('/ahli-waris/proses', [KasiPemerintahanController::class, 'ahliWarisProses'])->name('kasi_pemerintahan.ahli_waris.proses');
    // Route::post('/ahli-waris/{id}/approve', [KasiPemerintahanController::class, 'ahliWarisApprove'])->name('kasi_pemerintahan.ahli_waris.approve');
    // Route::post('/ahli-waris/{id}/reject', [KasiPemerintahanController::class, 'ahliWarisReject'])->name('kasi_pemerintahan.ahli_waris.reject');

    // Route::get('/sppat-gr', [KasiPemerintahanController::class, 'sppatGrIndex'])->name('kasi_pemerintahan.sppat_gr.index');
    // Route::get('/sppat-gr/proses', [KasiPemerintahanController::class, 'sppatGrProses'])->name('kasi_pemerintahan.sppat_gr.proses');
    // Route::post('/sppat-gr/{id}/approve', [KasiPemerintahanController::class, 'sppatGrApprove'])->name('kasi_pemerintahan.sppat_gr.approve');
    // Route::post('/sppat-gr/{id}/reject', [KasiPemerintahanController::class, 'sppatGrReject'])->name('kasi_pemerintahan.sppat_gr.reject');

    // Route::get('/skt', [KasiPemerintahanController::class, 'sktIndex'])->name('kasi_pemerintahan.skt.index');
    // Route::get('/skt/proses', [KasiPemerintahanController::class, 'sktProses'])->name('kasi_pemerintahan.skt.proses');
    // Route::post('/skt/{id}/approve', [KasiPemerintahanController::class, 'sktApprove'])->name('kasi_pemerintahan.skt.approve');
    // Route::post('/skt/{id}/reject', [KasiPemerintahanController::class, 'sktReject'])->name('kasi_pemerintahan.skt.reject');

    // ===== Layanan dari Kasi Trantib =====
    // Route::get('/catin-tni', [KasiTrantibController::class, 'catinTniIndex'])->name('kasi_trantib.catin_tni.index');
    // Route::get('/catin-tni/proses', [KasiTrantibController::class, 'catinTniProses'])->name('kasi_trantib.catin_tni.proses');
    // Route::post('/catin-tni/{id}/approve', [KasiTrantibController::class, 'catinTniApprove'])->name('kasi_trantib.catin_tni.approve');
    // Route::post('/catin-tni/{id}/reject', [KasiTrantibController::class, 'catinTniReject'])->name('kasi_trantib.catin_tni.reject');
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
});

// ---------------- Kasubbag Umpeg ----------------
Route::middleware(['auth', 'role:kasubbag_umpeg'])->prefix('kasubbag-umpeg')->group(function () {
    Route::get('/dashboard', [KasubbagUmpegController::class, 'index'])->name('kasubbag_umpeg.dashboard');
});



