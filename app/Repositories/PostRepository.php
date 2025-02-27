<?php

namespace App\Repositories;

use App\Models\Post;

class PostRepository extends Repository
{
    public function __construct()
    {
        // Definindo o modelo (Model) a ser usado no repositório
        $this->model = Post::class;
    }
}
