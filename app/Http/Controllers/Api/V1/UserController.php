<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Repositories\UserRepository;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct(
        protected UserRepository $users,
        protected UserService    $service
    ){}

    public function index(): JsonResponse
    {
        return response()->json($this->users->allPaginated(10));
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = $this->service->createUserWithAddress($request->validated());
        return response()->json($user, 201);
    }

    public function show($id): JsonResponse
    {
        return response()->json($this->users->find($id));
    }

    public function update(UpdateUserRequest $request, $id): JsonResponse
    {
        $user = $this->users->find($id);
        $user->update($request->validated());
        return response()->json($user);
    }

    public function destroy($id): JsonResponse
    {
        $user = $this->users->find($id);
        $user->delete();
        return response()->json(null, 204);
    }
}
