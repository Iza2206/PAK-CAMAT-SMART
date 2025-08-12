<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\User;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $tables = [
            'agunan_submissions',
            'ahliwaris_submissions',
            'bpjs_submissions',
            'catin_submissions',
            'catin_tni_polri_submissions',
            'iumk_submissions',
            'sengketa_submissions',
            'skbd_submissions',
            'skriset_kkn_submissions',
            'skt_submissions',
            'sppat_gr_submissions',
            'sktm_dispensasi_submissions',
        ];

        $nilaiMapping = [
            'sangat puas' => 4,
            'puas'       => 3,
            'cukup'      => 2,
            'tidak puas' => 1,
        ];

        $totalResponden = 0;
        $totalPenilaian = 0;
        $allSubmissions = collect();

        foreach ($tables as $table) {
            $records = DB::table($table)
                ->select('id', 'user_id', 'penilaian', 'status', 'submitted_at', 'duration_seconds')
                ->get();

            foreach ($records as $record) {
                $totalResponden++;
                $penilaianStr = strtolower(trim($record->penilaian ?? ''));
                $nilai = $nilaiMapping[$penilaianStr] ?? 0;

                $totalPenilaian += $nilai;

                $user = null;
                if ($record->user_id) {
                    $user = User::find($record->user_id);
                }

                $allSubmissions->push((object)[
                    'id' => $record->id,
                    'user' => $user,
                    'nilai' => $nilai,
                    'status' => $record->status ?? '-',
                    'submitted_at' => $record->submitted_at,
                    'duration_seconds' => $record->duration_seconds ?? 0,
                ]);
            }
        }

        $totalNilai = $totalResponden > 0 ? $totalPenilaian / $totalResponden : 0;
        $avgDuration = $totalResponden > 0 ? intval($allSubmissions->avg('duration_seconds')) : 0;

        // Manual pagination for collection
        $perPage = 10;
        $page = $request->get('page', 1);
        $paginated = new LengthAwarePaginator(
            $allSubmissions->forPage($page, $perPage),
            $allSubmissions->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('dashboard.index', [
            'totalNilai' => $totalNilai,
            'jumlahResponden' => $totalResponden,
            'avgDuration' => $avgDuration,
            'submissions' => $paginated,
        ]);
    }
}
