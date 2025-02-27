<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    private $model;

    public function __construct()
    {
        // Definindo o modelo (Model) a ser usado no repositório
        $this->model = User::class;
    }

    /**
     * Recupera todos os registros de usuários.
     *
     * @param array $columns
     * @return mixed
     */
    public function all($columns = ['*'])
    {
        return $this->model::select($columns)->get();
    }

    /**
     * Recupera um usuário por ID.
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
