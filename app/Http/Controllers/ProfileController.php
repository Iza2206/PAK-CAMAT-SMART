<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileController extends Controller
{
    public function edit()
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (! $user) {
            abort(403, 'User tidak ditemukan atau belum login.');
        }

        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (! $user) {
            abort(403, 'User tidak ditemukan atau belum login.');
        }

        $request->validate([
            'name'     => 'required|string|max:191',
            'email'    => 'required|email|max:191|unique:users,email,' . $user->id,
            'ttd'      => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'password' => 'nullable|confirmed|min:6',
        ]);

        // Update nama & email
        $user->name = $request->name;
        $user->email = $request->email;

        // Update password kalau diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Upload tanda tangan
        if ($request->hasFile('ttd')) {
            // Hapus file lama
            if ($user->ttd) {
                Storage::delete('public/' . $user->ttd);
            }

            // Buat nama file rapi
            $filename = 'ttd_' . $user->id . '_' . time() . '.' . $request->file('ttd')->getClientOriginalExtension();

            // Simpan file
            $request->file('ttd')->storeAs('ttd', $filename, 'public');

            // Simpan path ke DB
            $user->ttd = 'ttd/' . $filename;
        }

        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui.');
    }
}
