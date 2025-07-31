<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class QueueNumberHelper
{
    public static function generateForCurrentUser(string $modelClass, string $roleField = null): int
    {
        $instance = new $modelClass;
        $table = $instance->getTable();

        $query = DB::table($table)->whereDate('created_at', Carbon::today());

        if ($roleField) {
            $userRole = Auth::user()->role ?? null;
            if ($userRole) {
                $query->where($roleField, $userRole);
            }
        }

        return $query->count() + 1;
    }
}
