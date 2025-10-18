<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{

    public function run(): void
    {
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@admin.com',
            'password' => Hash::make('35647982'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Administrador Auto',
            'email' => 'admin@autopecas.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Usuário Sênior',
            'email' => 'senior@autopecas.com',
            'password' => Hash::make('12345678'),
            'role' => 'senior',
        ]);

        User::create([
            'name' => 'Usuário Comum',
            'email' => 'user@autopecas.com',
            'password' => Hash::make('12345678'),
            'role' => 'user',
        ]);
    }
}
