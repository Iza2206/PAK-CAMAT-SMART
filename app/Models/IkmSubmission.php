<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IkmSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nilai',
        'status',
        'submitted_at',
        'duration_seconds',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
