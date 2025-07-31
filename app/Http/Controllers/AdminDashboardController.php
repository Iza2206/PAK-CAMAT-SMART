<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IkmSubmission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\IkmExport;

class AdminDashboardController extends Controller
{
    /**
     * Tampilkan dashboard super admin
     */
    public function index()
    {
        $submissions = IkmSubmission::with('user')->latest()->paginate(10);
        $totalNilai = IkmSubmission::avg('nilai');
        $jumlahResponden = IkmSubmission::count();
        $avgDuration = IkmSubmission::avg('duration_seconds');

        return view('admin.dashboard', compact(
            'submissions',
            'totalNilai',
            'jumlahResponden',
            'avgDuration'
        ));
    }

    /**
     * Hapus data IKM berdasarkan ID
     */
    public function destroy($id)
    {
        IkmSubmission::destroy($id);
        return back()->with('success', 'Data berhasil dihapus.');
    }

    /**
     * Reset password user dengan ID
     */
    public function resetPassword($id)
    {
        $user = User::findOrFail($id);
        $newPassword = Str::random(8);

        $user->password = Hash::make($newPassword);
        $user->save();

        return back()->with('success', "Password baru untuk {$user->name}: $newPassword");
    }

    /**
     * Export semua data IKM ke Excel
     */
    public function export()
    {
        return Excel::download(new IkmExport, 'laporan_ikm.xlsx');
    }
}
