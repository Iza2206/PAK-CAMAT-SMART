<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;

class AdminAccountController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Filter pencarian
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Filter tanggal
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $accounts = $query->latest()->paginate(10)->appends(request()->query());

        return view('admin.accounts.index', compact('accounts'));
    }

    public function create()
    {
        return view('admin.accounts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:191',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role'     => 'required|in:admin,user'
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role
        ]);

        return redirect()->route('admin.accounts.index')->with('success', 'Akun berhasil ditambahkan.');
    }

    public function editPassword($id)
    {
        $account = User::findOrFail($id);
        return view('admin.accounts.edit-password', compact('account'));
    }

    public function updatePassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        $account = User::findOrFail($id);
        $account->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('admin.accounts.index')->with('success', 'Password berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $account = User::findOrFail($id);
        $account->delete();

        return redirect()->route('admin.accounts.index')->with('success', 'Akun berhasil dihapus.');
    }

    public function export()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }
}
