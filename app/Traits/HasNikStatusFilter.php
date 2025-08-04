<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasNikStatusFilter
{
    public function scopeFilterNikStatus(Builder $query, $filters)
    {
        if (!empty($filters['nik'])) {
            $field = $this->nikField ?? 'nik_pemohon';
            $query->where($field, 'like', '%' . $filters['nik'] . '%');
        }

        if (!empty($filters['penilaian'])) {
            if ($filters['penilaian'] === 'belum') {
                $query->whereNull('penilaian');
            } else {
                $query->where('penilaian', $filters['penilaian']);
            }
        }

        return $query;
    }

}
