<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Pengguna ADMIN
        User::updateOrCreate(
            ['email' => 'admin@pemilu.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin123'), // Ganti dengan password yang aman
                'role' => 'admin',
            ]
        );

        // 2. Pengguna VOTE OSIS SAJA
        User::updateOrCreate(
            ['email' => 'voter_osis@pemilu.com'],
            [
                'name' => 'Siswa Pemilih OSIS',
                'password' => Hash::make('osis123'), // Ganti dengan password yang aman
                'role' => 'voter_osis',
            ]
        );
        
        // 3. Pengguna VOTE MPK SAJA
        User::updateOrCreate(
            ['email' => 'voter_mpk@pemilu.com'],
            [
                'name' => 'Siswa Pemilih MPK',
                'password' => Hash::make('mpk123'), // Ganti dengan password yang aman
                'role' => 'voter_mpk',
            ]
        );

        // 4. (Opsional) Pengguna VOTE KEDUANYA (jika masih diperlukan)
        User::updateOrCreate(
            ['email' => 'voter@pemilu.com'],
            [
                'name' => 'Siswa Pemilih Umum',
                'password' => Hash::make('pemilih123'), // Ganti dengan password yang aman
                'role' => 'voter',
            ]
        );
    }
}