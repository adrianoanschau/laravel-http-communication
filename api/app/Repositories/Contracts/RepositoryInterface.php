<?php

namespace App\Repositories\Contracts;

interface RepositoryInterface
{
    public function all();

    public function paginate(int $per_page = null);

    public function find(string $id);

    public function create(array $data);

    public function update(array $data, string $id);

    public function delete(string $id);

    public function bulkDelete(array $ids);
}
