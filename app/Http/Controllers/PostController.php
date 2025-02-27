<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Repositories\PostRepository;
use App\Http\Resources\PostColletion;
use App\ResponseTrait;

class PostController extends Controller
{
    use ResponseTrait;
    protected $postRepository;
    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * Retorna os posts
     *
     * @author Junior <hjuniorbsilva@gmail.com>
     */
    public function posts()
    {
        $posts = $this->postRepository->all(['*'], ['tags', 'author']);
        return new PostColletion($posts);
    }

    public function create(CreatePostRequest $request)
    {
        try {
            $post = $this->postRepository->create([
                'title'     => $request->title,
                'content'   => $request->content,
                'user_id'   => $request->author
            ]);

            return $this->responseJSON(true, 'Post cadastrado com sucesso', 201, [
                'post'  => $post,
            ]);
        } catch (\Exception $e) {
            return $this->responseJSON(false, 'Erro ao cadastrar o Post. Verifique os dados fornecidos.', 500);
        }
    }
}
