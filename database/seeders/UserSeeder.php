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
        // Membuat pengguna Admin
        // firstOrCreate akan mencari user dengan email ini, jika tidak ada, baru akan dibuat.
        // Ini mencegah duplikasi jika seeder dijalankan berkali-kali.
        User::firstOrCreate(
            ['email' => 'admin@pemilu.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('Rahas1aosk4**//'), // Password default: "password"
                'role' => 'admin',
            ]
        );

        // Membuat pengguna Pemilih (Voter)
        User::firstOrCreate(
            ['email' => 'siswa@pemilu.com'],
            [
                'name' => 'Siswa Pemilih',
                'password' => Hash::make('Rahas1ajiva**//'), // Password default: "password"
                'role' => 'voter',
            ]
        );
    }
}
