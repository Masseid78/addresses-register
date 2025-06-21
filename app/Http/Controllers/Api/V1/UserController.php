<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return response()->json(User::paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        $zipcode = preg_replace('/[^0-9]/', '', $validatedData['zipcode']);

        $response = Http::get("https://viacep.com.br/ws/{$zipcode}/json/")->json();

        if (isset($response['erro']) && $response['erro'] === true) {
            return response()->json(['message' => 'Invalid zipcode.'], 400);
        }

        $userData = array_merge($validatedData, [
            'street'   => $response['logradouro'],
            'district' => $response['bairro'],
            'city'     => $response['localidade'],
            'state'    => $response['uf'],
        ]);

        $user = User::create($userData);

        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): JsonResponse
    {
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $user->update($request->validated());

        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return response()->json(null, 204);
    }
}
