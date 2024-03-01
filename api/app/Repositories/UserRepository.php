<?php

namespace App\Repositories;

use App\Repositories\Contracts\RepositoryInterface;
use App\Models\User;

class UserRepository implements RepositoryInterface
{
    public function all()
    {
        return User::all();
    }

    public function paginate(int $per_page = null)
    {
        return User::paginate($per_page);
    }

    public function find(string $id)
    {
        return User::findOrFail($id);
    }

    public function findByUsername(string $username)
    {
        return User::where("username", $username)->first();
    }

    public function create(array $data)
    {
        return User::create($data);
    }

    public function update(array $data, string $id)
    {
        $user = User::findOrFail($id);
        $user->update($data);

        return $user;
    }

    public function delete(string $id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return $user;
    }

    public function bulkDelete(array $ids)
    {
        $query = User::whereIn('id', $ids);

        $users = $query->get();

        $query->delete();

        return $users;
    }
}
