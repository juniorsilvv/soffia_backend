<?php

namespace App\Repositories;

interface PostsRepositoryInterface
{
    public function all($columns = ['*']);
    public function find($id, $columns = ['*']);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}
