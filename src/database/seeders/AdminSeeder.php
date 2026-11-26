<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = config('layanan.admin.email');
        $password = config('layanan.admin.password');

        if (! is_string($email) || $email === '' || ! is_string($password) || $password === '') {
            $this->command?->warn('ADMIN_EMAIL dan ADMIN_PASSWORD belum diisi; akun admin dilewati.');

            return;
        }

        User::updateOrCreate(
            ['email' => Str::lower(trim($email))],
            [
                'name' => config('layanan.admin.nama', 'Administrator'),
                'password' => $password,
                'role' => UserRole::Admin,
                'aktif' => true,
            ],
        );
    }
}
