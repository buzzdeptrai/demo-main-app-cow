<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserService
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function list(int $perPage = 15)
    {
        return $this->userRepository->paginate($perPage);
    }

    public function find(int $id): User
    {
        return $this->userRepository->findOrFail($id);
    }

    public function findByEmail(string $email)
    {
        return $this->userRepository->findByEmail($email);
    }

    public function create(array $data): User
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user = $this->userRepository->create($data);

        if (isset($data['roles'])) {
            $user->syncRoles($data['roles']);
        }

        return $user;
    }

    public function update(int $id, array $data): User
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user = $this->userRepository->update($id, $data);

        if (isset($data['roles'])) {
            $user->syncRoles($data['roles']);
        }

        return $user;
    }

    public function delete(int $id): bool
    {
        return $this->userRepository->delete($id);
    }

    public function getActiveUsers(int $perPage = 15)
    {
        return $this->userRepository->getActiveUsers($perPage);
    }
}
