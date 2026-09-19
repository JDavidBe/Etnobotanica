<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name'              => 'Admin Principal',
                'email'             => 'admin@etnobotanica.co',
                'password'          => Hash::make('admin123'),
                'email_verified_at' => now(),
                'activo'            => true,
                'role'              => 'admin',
            ],
            [
                'name'              => 'Moderador Ejemplo',
                'email'             => 'moderador@etnobotanica.co',
                'password'          => Hash::make('moderador123'),
                'email_verified_at' => now(),
                'activo'            => true,
                'role'              => 'moderador',
            ],
            [
                'name'              => 'Lector Ejemplo',
                'email'             => 'lector@etnobotanica.co',
                'password'          => Hash::make('lector123'),
                'email_verified_at' => now(),
                'activo'            => true,
                'role'              => 'lector',
            ],
        ];

        foreach ($users as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name'              => $data['name'],
                    'password'          => $data['password'],
                    'email_verified_at' => $data['email_verified_at'],
                    'activo'            => $data['activo'],
                ]
            );

            $user->syncRoles([]);
            $user->assignRole($data['role']);
        }
    }
}
