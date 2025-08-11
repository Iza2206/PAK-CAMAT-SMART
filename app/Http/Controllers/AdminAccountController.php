<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminAccountController extends Controller
{
    public function index()
    {
        // tampilkan daftar akun
    }

    public function create()
    {
        // form tambah akun
    }

    public function store(Request $request)
    {
        // simpan akun baru
    }

    public function editPassword($id)
    {
        // tampilkan form ubah password
    }

    public function updatePassword(Request $request, $id)
    {
        // proses ubah password
    }

    public function destroy($id)
    {
        // hapus akun
    }

    public function export()
    {
        // export akun ke Excel
    }
}
