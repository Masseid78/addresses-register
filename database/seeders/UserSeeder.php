<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'João Silva',
                'email' => 'joao@email.com',
                'zipcode' => '01310-100',
                'street' => 'Avenida Paulista',
                'district' => 'Bela Vista',
                'city' => 'São Paulo',
                'state' => 'SP',
                'number' => '1578',
                'complement' => 'Andar 15',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Maria Santos',
                'email' => 'maria@email.com',
                'zipcode' => '22071-900',
                'street' => 'Avenida Atlântica',
                'district' => 'Copacabana',
                'city' => 'Rio de Janeiro',
                'state' => 'RJ',
                'number' => '1702',
                'complement' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pedro Costa',
                'email' => 'pedro@email.com',
                'zipcode' => '40140-130',
                'street' => 'Rua da Bahia',
                'district' => 'Barra',
                'city' => 'Salvador',
                'state' => 'BA',
                'number' => '1148',
                'complement' => 'Sala 201',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
} 