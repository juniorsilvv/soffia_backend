<?php

namespace App\Repositories;

use App\Models\PostsTags;

class PostsTagsRepository extends Repository
{
    public function __construct()
    {
        // Definindo o modelo (Model) a ser usado no repositório
        $this->model = PostsTags::class;
    }
}
