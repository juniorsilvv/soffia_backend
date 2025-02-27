<?php
namespace App\Repositories;

abstract class Repository implements RepositoryInterface
{
    protected $model;

    public function __construct()
    {

    }

    /**
     * Recupera todos os registros de usuários.
     *
     * @param array $columns
     * @return mixed
     */
    public function all($columns = ['*'], $relations = [])
    {
        $query = $this->model::select($columns);

        if (!empty($relations)) {
            $query->with($relations);  // Carregar os relacionamentos, se fornecidos
        }

        return $query->get();
    }

    /**
     * Recupera um usuário por ID.
     *
     * @param int $id
     * @param array $columns
     * @return mixed
     */
    public function find($id, $columns = ['*'], $relations = [])
    {
        $query = $this->model::select($columns);

        if (!empty($relations)) {
            $query->with($relations);  // Carregar os relacionamentos, se fornecidos
        }

        return $query->find($id);
    }

    /**
     * Cria um novo usuário.
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->model::create($data);
    }

    /**
     * Atualiza um usuário existente.
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
     * Deleta um usuário.
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