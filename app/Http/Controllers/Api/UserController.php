<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\DeleteUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ApiResponseTrait;

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $users = $this->userService->listUsers($request);

        return $this->success('Users fetched successfully', UserResource::collection($users)->response()->getData(true));
    }

    public function store(CreateUserRequest $request)
    {
        $user = $this->userService->createUser($request->validated());

        return $this->success(
            'User created successfully',
            new UserResource($user)
        );
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user = $this->userService->updateUser(
            $request->validated(),
            $user
        );

        return $this->success(
            'User updated successfully',
            new UserResource($user)
        );
    }

    public function delete(DeleteUserRequest $request, User $user)
    {
        try {
            $this->userService->deleteUser(
                $user
            );
            return $this->success('User deleted successfully', $user);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 403);
        }
    }
}
