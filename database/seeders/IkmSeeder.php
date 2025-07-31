<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\IkmSubmission;
use App\Models\User;
use Illuminate\Support\Carbon;

class IkmSeeder extends Seeder
{
    public function run(): void
    {
        // Buat 10 user dummy (jika belum ada)
        User::factory(10)->create()->each(function ($user) {
            IkmSubmission::create([
                'user_id' => $user->id,
                'nilai' => rand(60, 95),
                'status' => 'terkirim',
                'submitted_at' => Carbon::now()->subMinutes(rand(1, 1000)),
                'duration_seconds' => rand(60, 300),
            ]);
        });
    }
}
