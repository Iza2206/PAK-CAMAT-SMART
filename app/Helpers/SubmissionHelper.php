<?php

namespace App\Helpers;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class SubmissionHelper
{
    public static function paginateAllSubmissions($perPage = 10)
    {
        // Ambil semua submission dari tabel-tabel yang ada
        $submissions = collect([
            'agunan' => \App\Models\AgunanSubmission::all(),
            'ahliwaris' => \App\Models\AhliwarisSubmission::all(),
            'bpjs' => \App\Models\BpjsSubmission::all(),
            'catin' => \App\Models\CatinSubmission::all(),
            'catinTniPolri' => \App\Models\CatinTniPolriSubmission::all(),
            'iumk' => \App\Models\IumkSubmission::all(),
            'sengketa' => \App\Models\SengketaSubmission::all(),
            'skbd' => \App\Models\SkbdSubmission::all(),
            'skrisetKkn' => \App\Models\SkrisetKknSubmission::all(),
            'skt' => \App\Models\SktSubmission::all(),
            'sktmDispensasi' => \App\Models\SktmDispensasiSubmission::all(),
            'sppatGr' => \App\Models\SppatGrSubmission::all(),
        ]);

        // Gabungkan semua data submissions ke dalam satu collection
        $allSubmissions = $submissions->flatten();

        // Paginasi collection
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $collection = new Collection($allSubmissions);
        $perPage = $perPage;
        $currentPageItems = $collection->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginatedItems = new LengthAwarePaginator(
            $currentPageItems,
            $collection->count(),
            $perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );

        return $paginatedItems;
    }
}
