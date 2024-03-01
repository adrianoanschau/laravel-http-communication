<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

use App\Repositories\UserRepository;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;

class UserService
{
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    public function all(bool $paginate = false, int $per_page = null)
    {
        if ($paginate) {
            $users = $this->userRepository->paginate($per_page);
        } else {
            $users = $this->userRepository->all();
        }

        return new UserCollection($users);
    }

    public function find(string $id)
    {
        $user = $this->userRepository->find($id);

        return new UserResource($user);
    }

    public function findByUsername(string $username)
    {
        $user = $this->userRepository->findByUsername($username);

        return new UserResource($user);
    }

    public function create(array $data)
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data["password"]);
        }

        $user = $this->userRepository->create($data);

        return new UserResource($user);
    }

    public function update(array $data, string $id)
    {
        if (isset($data['reset_password']) && !!$data['reset_password']) {
            unset($data['reset_password']);

            $data['password'] = null;
        }

        $user = $this->userRepository->update($data, $id);

        return new UserResource($user);
    }

    public function delete(string $id)
    {
        $user = $this->userRepository->delete($id);

        return new UserResource($user);
    }

    public function bulkDelete(string $ids)
    {
        $ids = Str::of($ids)->explode(';')->toArray();

        $users = $this->userRepository->bulkDelete($ids);

        return new UserCollection($users);
    }
}
