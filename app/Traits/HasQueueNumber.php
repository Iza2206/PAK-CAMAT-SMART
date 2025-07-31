<?php

namespace App\Traits;

use App\Models\QueueNumber;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

trait HasQueueNumber
{
    public static function bootHasQueueNumber()
    {
        static::creating(function ($model) {
            if ($model->isFillable('queue_number') || array_key_exists('queue_number', $model->getAttributes())) {
                $today = Carbon::today()->toDateString();

                $queueNumber = DB::transaction(function () use ($today) {
                    $record = QueueNumber::lockForUpdate()->firstOrCreate(
                        ['tanggal' => $today],
                        ['last_number' => 0]
                    );

                    $record->last_number += 1;
                    $record->save();

                    return $record->last_number;
                });

                $model->queue_number = $queueNumber;
            }
        });
    }
}
