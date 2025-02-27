<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Repositories\PostRepository;
use App\Http\Resources\PostColletion;
use App\Repositories\PostsTagsRepository;
use App\ResponseTrait;

class PostController extends Controller
{
    use ResponseTrait;
    protected $postRepository;
    protected $tagsRepository;
    public function __construct(PostRepository $postRepository, PostsTagsRepository $tagsRepository)
    {
        $this->postRepository = $postRepository;
        $this->tagsRepository = $tagsRepository;
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
                'title'         => $request->title,
                'content'       => $request->content,
                'author_id'     => $request->author_id
            ]);

            /**
             * Adicionando tags no post
             */
            if ($request->has('tags')) {
                $tags = $request->tags;
                foreach ($tags as $tag) {
                    $this->tagsRepository->create(['tag_name' => $tag, 'post_id' => $post->id]); 
                }
            }
            $post = $this->postRepository->find($post->id, ['*'], ['tags', 'author']);

            return $this->responseJSON(true, 'Post cadastrado com sucesso', 201, [
                'post'  => $post,
            ]);
        } catch (\Exception $e) {
            return $this->responseJSON(false, 'Erro ao cadastrar o Post. Verifique os dados fornecidos.', 500);
        }
    }

    public function update($id, UpdatePostRequest $request)
    {
        try {
            // Encontrar o usuário pelo ID
            $post = $this->postRepository->find($request->id);

            // Verificar se o usuário foi encontrado
            if (!$post) {
                return $this->responseJSON(false, 'Post não encontrado', 404);
            }


            /**
             * Montando itens que serão atualizados
             */
            $updated = [];
            foreach($request->all() as $key => $value){
                if($value === "" || $value === null) continue;
                $updated[$key] = $value;
            }

            // Atualizar o post com os dados fornecidos
            $this->postRepository->update($id, $updated);

            /**
             * Adicionando tags no post
             */
            if ($request->has('tags')) {
                $this->tagsRepository->delete($post->id, 'post_id'); 
                $tags = $request->tags;
                foreach ($tags as $tag) {
                    $this->tagsRepository->create(['tag_name' => $tag, 'post_id' => $post->id]); 
                }
            }

            $post = $this->postRepository->find($request->id);
            // Retornar a resposta com o post atualizado

            $post = $this->postRepository->find($post->id, ['*'], ['tags', 'author']);
            return $this->responseJSON(true, 'Post atualizado com sucesso', 200, [
                'post' => $post, // Retorna o post atualizado
            ]);
        } catch (\Exception $e) {
            print_r($e->getMessage());exit;
            return $this->responseJSON(false, 'Erro ao editar o post. Verifique os dados fornecidos.', 500);
        }
    }


    /**
     * Remove o post
     *
     * @param [type] $id
     * @return object
     * @author Junior <hjuniorbsilva@gmail.com>
     */
    public function delete($id): object
    {
        try {
            $user = $this->postRepository->find($id);

            if (!$user) {
                return $this->responseJSON(false, 'Post não encontrado', 404);
            }

            // Deletar o post
            $this->postRepository->delete($id);

            return $this->responseJSON(true, 'Post deletado com sucesso', 200);
        } catch (\Exception $e) {
            return $this->responseJSON(false, 'Erro ao deletar o post.', 500);
        }
    }
}
