<?php
namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function allPaginated(int $perPage = 10)
    {
        // ordena pelo id ascendente
        return User::orderBy('id','asc')->paginate($perPage);
    }

    public function find(int $id): User
    {
        return User::findOrFail($id);
    }

    public function create(array $data): User
    {
        return User::create($data);
    }
}
