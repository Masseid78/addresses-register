<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_user_with_address()
    {
        Http::fake([
            'viacep.com.br/*' => Http::response([
                'logradouro' => 'Rua Teste',
                'bairro' => 'Bairro Teste',
                'localidade' => 'Cidade Teste',
                'uf' => 'TS',
            ], 200),
        ]);

        $userData = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'zipcode' => '99999-999',
            'number' => '10A',
            'street' => 'Rua Teste',
            'district' => 'Bairro Teste',
            'city' => 'Cidade Teste',
            'state' => 'TS',
            'complement' => '',
        ];

        $response = $this->postJson('/api/v1/users', $userData);

        $response->assertStatus(201);

        $this->assertDatabaseHas('users', [
            'email' => 'john.doe@example.com',
            'street' => 'Rua Teste',
        ]);
    }
} 