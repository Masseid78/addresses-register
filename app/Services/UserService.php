<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\User;

class UserService
{
    public function createUserWithAddress(array $data): User
    {
        $cep = preg_replace('/\D/', '', $data['zipcode'] ?? '');
        $response = Http::get("https://viacep.com.br/ws/{$cep}/json/");
        if ($response->failed() || isset($response['erro'])) {
            throw new \Exception('CEP inválido ou não encontrado.');
        }
        $address = $response->json();
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'zipcode' => $data['zipcode'],
            'street' => $address['logradouro'] ?? '',
            'district' => $address['bairro'] ?? '',
            'city' => $address['localidade'] ?? '',
            'state' => $address['uf'] ?? '',
            'number' => $data['number'] ?? '',
            'complement' => $data['complement'] ?? '',
        ]);
        return $user;
    }
} 