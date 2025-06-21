<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function allPaginated($perPage = 10)
    {
        return User::orderByDesc('created_at')->paginate($perPage);
    }

    public function find($id)
    {
        return User::findOrFail($id);
    }

    public function create(array $data)
    {
        return User::create($data);
    }
} 