<?php

namespace App\Exports;

use App\Models\IkmSubmission;
use Maatwebsite\Excel\Concerns\FromCollection;

class IkmExport implements FromCollection
{
    public function collection()
    {
        return IkmSubmission::with('user')->get();
    }
}
