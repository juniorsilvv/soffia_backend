<?php

namespace App\Repositories;

abstract class Repository implements RepositoryInterface
{
    protected $model;

    public function __construct() {}

    /**
     * Recupera todos os registros de registros.
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
     * Recupera um registro por ID.
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
     * Recupera registros paginados.
     *
     * @param int $perPage
     * @param array $columns
     * @param array $relations
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function paginate($perPage = 10, $columns = ['*'], $relations = [])
    {
        $query = $this->model::select($columns);

        if (!empty($relations)) {
            $query->with($relations); 
        }

        return $query->paginate($perPage);
    }

    /**
     * Cria um novo registro.
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->model::create($data);
    }

    /**
     * Atualiza um registro existente.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update($id, array $data)
    {
        $query = $this->model::find($id);
        if ($query) {
            $query->update($data);
            return $query;
        }
        return null;
    }

    /**
     * Deleta um registro.
     *
     * @param int $value
     * @param  $column
     * @return bool
     */
    public function delete($value, $column = 'id')
    {
        $query = $this->model::where($column, $value);
        if ($query) {
            return $query->delete();
        }
        return false;
    }
}
