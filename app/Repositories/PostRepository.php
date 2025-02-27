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


    /**
     * Busca posts por tags
     *
     * @param string $tag
     * @author Junior <hjuniorbsilva@gmail.com>
     */
    public function searchByTag($tag, $relations = [])
    {
        return $this->model::whereHas('tags', function ($query) use ($tag) {
            $query->where('tag_name', $tag);  // Ajuste o nome do campo conforme seu banco de dados
        })
        ->with($relations)
        ->paginate(10);  
    }

    /**
     * Retorna o post pelo title ou pelo contant
     *
     * @param string $searchTerm
     * @author Junior <hjuniorbsilva@gmail.com>
     */
    public function searchTerms(string $searchTerm, $relations = [])
    {
        return $this->model::where(function ($query) use ($searchTerm) {
            $query->where('title', 'like', '%' . $searchTerm . '%')
                ->orWhere('content', 'like', '%' . $searchTerm . '%');
        })
        ->with($relations)
        ->paginate(10);  
    }
}
