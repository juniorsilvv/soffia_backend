<?php

namespace App\Repositories;

use App\Models\Post;

class PostRepository implements PostsRepositoryInterface
{
    private $model;

    public function __construct()
    {
        // Definindo o modelo (Model) a ser usado no repositório
        $this->model = Post::class;
    }

    /**
     * Recupera todos os registros de posts.
     *
     * @param array $columns
     * @return mixed
     */
    public function all($columns = ['*'])
    {
        return $this->model::select($columns)->get();
    }

    /**
     * Recupera um post por ID.
     *
     * @param int $id
     * @param array $columns
     * @return mixed
     */
    public function find($id, $columns = ['*'])
    {
        return $this->model::select($columns)->find($id);
    }

    /**
     * Cria um novo post.
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->model::create($data);
    }

    /**
     * Atualiza um post existente.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update($id, array $data)
    {
        $user = $this->model::find($id);
        if ($user) {
            $user->update($data);
            return $user;
        }
        return null;
    }

    /**
     * Deleta um post.
     *
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $user = $this->model::find($id);
        if ($user) {
            return $user->delete();
        }
        return false;
    }
}
