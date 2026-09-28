<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ApiResponse;

    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 15);
        $users = $this->userService->list($perPage);

        return $this->success(
            new UserCollection($users),
            'Users retrieved successfully'
        );
    }

    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->create($request->validated());

        return $this->created(
            new UserResource($user->load('roles')),
            'User created successfully'
        );
    }

    public function show(int $id)
    {
        $user = $this->userService->find($id);

        return $this->success(
            new UserResource($user->load('roles')),
            'User retrieved successfully'
        );
    }

    public function update(UpdateUserRequest $request, int $id)
    {
        $user = $this->userService->update($id, $request->validated());

        return $this->success(
            new UserResource($user->load('roles')),
            'User updated successfully'
        );
    }

    public function destroy(int $id)
    {
        $this->userService->delete($id);

        return $this->success(null, 'User deleted successfully');
    }
}
