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
            'email' => 'admin@autopecas.com',
            'password' => Hash::make('123456'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Usuário Sênior',
            'email' => 'senior@autopecas.com',
            'password' => Hash::make('123456'),
            'role' => 'senior',
        ]);

        User::create([
            'name' => 'Usuário Comum',
            'email' => 'user@autopecas.com',
            'password' => Hash::make('123456'),
            'role' => 'user',
        ]);
    }
}
