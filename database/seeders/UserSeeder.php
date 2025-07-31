<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'role' => 'admin',
            ],
            [
                'name' => 'Meja Layanan',
                'email' => 'meja@example.com',
                'role' => 'meja_layanan',
            ],
            [
                'name' => 'Kasi Kesos',
                'email' => 'kasi@example.com',
                'role' => 'kasi_kesos',
            ],
            [
                'name' => 'Sekcam',
                'email' => 'sekcam@example.com',
                'role' => 'sekcam',
            ],
            [
                'name' => 'Camat',
                'email' => 'camat@example.com',
                'role' => 'camat',
            ],
            [
                'name' => 'Kasubbag Umpeg',
                'email' => 'umpeg@example.com',
                'role' => 'kasubbag_umpeg',
            ],
            [
                'name' => 'Kasi Pemerintahan',
                'email' => 'pemerintahan@example.com',
                'role' => 'kasi_pemerintahan',
            ],
            [
                'name' => 'Kasi Trantib',
                'email' => 'trantib@example.com',
                'role' => 'kasi_trantib',
            ],
        ];

        foreach ($users as $data) {
            User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'role' => $data['role'],
                    'password' => Hash::make('password'),
                ]
            );
        }
    }
}
