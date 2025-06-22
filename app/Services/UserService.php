<?php


namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Repositories\UserRepository;
use App\Models\User;

class UserService
{
    public function __construct(protected UserRepository $repo) {}

    public function createUserWithAddress(array $data): User
    {
        $cep  = preg_replace('/\D/','',$data['zipcode'] ?? '');
        $resp = Http::get("https://viacep.com.br/ws/{$cep}/json/");

        if ($resp->failed() || isset($resp['erro'])) {
            throw new \Exception('CEP inválido ou não encontrado.');
        }

        $address = $resp->json();

        // só sobrescreve se o ViaCEP realmente retornou algo não vazio
        if (!empty($address['logradouro'])) {
            $data['street'] = $address['logradouro'];
        }
        if (!empty($address['bairro'])) {
            $data['district'] = $address['bairro'];
        }
        if (!empty($address['localidade'])) {
            $data['city'] = $address['localidade'];
        }
        if (!empty($address['uf'])) {
            $data['state'] = $address['uf'];
        }

        return $this->repo->create($data);
    }
}
