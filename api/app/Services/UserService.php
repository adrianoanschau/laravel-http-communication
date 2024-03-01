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

    /**
     * Select a collection of Users
     *
     * @param  bool $paginate
     * @param  int $per_page
     * @return UserCollection
     */
    public function all(bool $paginate = false, int $per_page = null)
    {
        if ($paginate) {
            $users = $this->userRepository->paginate($per_page);
        } else {
            $users = $this->userRepository->all();
        }

        return new UserCollection($users);
    }

    /**
     * Select one User as Resource
     *
     * @param  string $id
     * @return UserResource
     */
    public function find(string $id)
    {
        $user = $this->userRepository->find($id);

        return new UserResource($user);
    }

    /**
     * Select one User as Resource by the username field
     *
     * @param  string $username
     * @return UserResource
     */
    public function findByUsername(string $username)
    {
        $user = $this->userRepository->findByUsername($username);

        return new UserResource($user);
    }

    /**
     * Create one User as Resource
     *
     * @param  array $data
     * @return UserResource
     */
    public function create(array $data)
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data["password"]);
        }

        $user = $this->userRepository->create($data);

        return new UserResource($user);
    }

    /**
     * Update one User as Resource
     *
     * @param  array $data
     * @param  string $id
     * @return UserResource
     */
    public function update(array $data, string $id)
    {
        if (isset($data['reset_password']) && !!$data['reset_password']) {
            unset($data['reset_password']);

            $data['password'] = null;
        }

        $user = $this->userRepository->update($data, $id);

        return new UserResource($user);
    }

    /**
     * Delete one User as Resource
     *
     * @param  string $id
     * @return UserResource
     */
    public function delete(string $id)
    {
        $user = $this->userRepository->delete($id);

        return new UserResource($user);
    }

    /**
     * Delete a collection of Users
     *
     * @param  string $ids //separated by semicolon
     * @return UserCollection
     */
    public function bulkDelete(string $ids)
    {
        $ids = Str::of($ids)->explode(';')->toArray();

        $users = $this->userRepository->bulkDelete($ids);

        return new UserCollection($users);
    }
}
