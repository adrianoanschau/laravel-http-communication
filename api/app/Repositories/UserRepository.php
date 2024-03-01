<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;

use App\Repositories\Contracts\RepositoryInterface;
use App\Models\User;

class UserRepository implements RepositoryInterface
{
    /**
     * Find and return a collection of User Model
     *
     * @return Collection<User>
     */
    public function all()
    {
        return User::all();
    }

    /**
     * Find and retuirn a paginated collection of User Model
     *
     * @param  int $per_page
     * @return Collection<User>
     */
    public function paginate(int $per_page = null)
    {
        return User::paginate($per_page);
    }

    /**
     * Find and return a User Model
     *
     * @param  string $id
     * @return User
     */
    public function find(string $id)
    {
        return User::findOrFail($id);
    }

    /**
     * Select and return a User Model
     *
     * @param  string $username
     * @return User
     */
    public function findByUsername(string $username)
    {
        return User::where("username", $username)->first();
    }

    /**
     * Create and return a User Model
     *
     * @param  array $data
     * @return User
     */
    public function create(array $data)
    {
        return User::create($data);
    }

    /**
     * Update and return a User Model
     *
     * @param  array $data
     * @param  string $id
     * @return User
     */
    public function update(array $data, string $id)
    {
        $user = User::findOrFail($id);
        $user->update($data);

        return $user;
    }

    /**
     * Delete and return a User Model
     *
     * @param  string $id
     * @return User
     */
    public function delete(string $id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return $user;
    }

    /**
     * Delete and return a collection of User Model
     *
     * @param  array<string> $ids
     * @return Collection<User>
     */
    public function bulkDelete(array $ids)
    {
        $query = User::whereIn('id', $ids);

        $users = $query->get();

        $query->delete();

        return $users;
    }
}
