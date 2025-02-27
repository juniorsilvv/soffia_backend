<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends Repository
{

    public function __construct()
    {
        // Definindo o modelo (Model) a ser usado no repositório
        $this->model = User::class;
    }
}
