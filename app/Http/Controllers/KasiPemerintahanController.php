<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KasiPemerintahanController extends Controller
{
    public function index()
    {
        return view('dashboard.kasi-pemerintahan');
    }
}
