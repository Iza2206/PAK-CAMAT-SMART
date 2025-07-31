<?php

if (!function_exists('redirectToDashboard')) {
    function redirectToDashboard($role)
    {
        return match ($role) {
            'admin'             => route('admin.dashboard'),
            'camat'             => route('camat.dashboard'),
            'kasi_kesos'        => route('kasi_kesos.dashboard'),
            'kasi_pemerintahan' => route('kasi_pemerintahan.dashboard'),
            'kasi_trantib'      => route('kasi_trantib.dashboard'),
            'kasubbag_umpeg'    => route('kasubbag_umpeg.dashboard'),
            'meja_layanan'      => route('meja.dashboard'),
            'sekcam'            => route('sekcam.dashboard'),
            default             => null,
        };
    }
}

if (!function_exists('getRoleName')) {
    function getRoleName($role)
    {
        return match ($role) {
            'admin'             => 'Admin',
            'meja_layanan'      => 'Meja Layanan',
            'kasi_kesos'        => 'Kasi Kesos',
            'sekcam'            => 'Sekcam',
            'camat'             => 'Camat',
            'kasubbag_umpeg'    => 'Kasubbag Umpeg',
            'kasi_pemerintahan' => 'Kasi Pemerintahan',
            'kasi_trantib'      => 'Kasi Trantib',
            default             => 'Tidak Dikenal',
        };
    }
}

if (!function_exists('getRoleBadgeColor')) {
    function getRoleBadgeColor($role)
    {
        return match ($role) {
            'admin'             => 'slate',
            'meja_layanan'      => 'blue',
            'kasi_kesos'        => 'green',
            'sekcam'            => 'blue',
            'camat'             => 'red',
            'kasubbag_umpeg'    => 'amber',
            'kasi_pemerintahan' => 'cyan',
            'kasi_trantib'      => 'blue',
            default             => 'gray',
        };
    }
}
