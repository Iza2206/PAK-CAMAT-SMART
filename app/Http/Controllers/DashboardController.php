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
            'puas'        => 3,
            'cukup'       => 2,
            'tidak puas'  => 1,
        ];

        $totalResponden = 0;
        $totalPenilaian = 0;
        $allSubmissions = collect();

        $durations = [
            'rejected' => [],
            'sekcam'   => [],
            'camat'    => [],
        ];

        foreach ($tables as $table) {
            $records = DB::table($table)
                ->select(
                    'id',
                    'user_id',
                    'penilaian',
                    'status',
                    'verified_at',
                    'approved_sekcam_at',
                    'approved_camat_at',
                    'rejected_at',
                    'created_at'
                )
                ->get();

            foreach ($records as $record) {
                $totalResponden++;

                $penilaianStr = strtolower(trim($record->penilaian ?? ''));
                $nilai = $nilaiMapping[$penilaianStr] ?? 0;
                $totalPenilaian += $nilai;

                $user = $record->user_id ? User::find($record->user_id) : null;

                $start = $record->verified_at ? strtotime($record->verified_at) : null;
                $end = null;
                $duration = 0;
                $statusKategori = '-';

                if ($start) {
                    if ($record->status === 'rejected' && $record->rejected_at) {
                        $end = strtotime($record->rejected_at);
                        $statusKategori = 'rejected';
                    } elseif ($record->approved_camat_at) {
                        $end = strtotime($record->approved_camat_at);
                        $statusKategori = 'camat';
                    } elseif ($record->approved_sekcam_at) {
                        $end = strtotime($record->approved_sekcam_at);
                        $statusKategori = 'sekcam';
                    }

                    if ($end) {
                        $duration = $end - $start;
                        $durations[$statusKategori][] = $duration;
                    }
                }

                $allSubmissions->push((object)[
                    'id' => $record->id,
                    'user' => $user,
                    'penilaian' => $record->penilaian ?? '-',
                    'nilai' => $nilai,
                    'status' => $record->status ?? '-',
                    'submitted_at' => $record->verified_at,
                    'duration_seconds' => $duration,
                    'created_at' => $record->created_at,
                    'layanan' => $table,
                ]);
            }
        }

        $avgRejected = count($durations['rejected']) > 0
            ? intval(array_sum($durations['rejected']) / count($durations['rejected']))
            : 0;

        $avgSekcam = count($durations['sekcam']) > 0
            ? intval(array_sum($durations['sekcam']) / count($durations['sekcam']))
            : 0;

        $avgCamat = count($durations['camat']) > 0
            ? intval(array_sum($durations['camat']) / count($durations['camat']))
            : 0;

        $totalNilai = $totalResponden > 0
            ? $totalPenilaian / $totalResponden
            : 0;

        // Pagination manual
        $perPage = 10;
        $page = $request->get('page', 1);
        $paginated = new LengthAwarePaginator(
            $allSubmissions->forPage($page, $perPage),
            $allSubmissions->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('mejalayanan.meja-layanan', [
            'ikm' => $totalNilai,
            'jumlahRespondenTotal' => $totalResponden,
            'avgRejected' => $avgRejected,
            'avgSekcam' => $avgSekcam,
            'avgCamat' => $avgCamat,
            'avgDurasi' => $avgCamat,
            'submissions' => $paginated,
        ]);
    }
}
