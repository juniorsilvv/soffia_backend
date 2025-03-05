<?php
 /**
  * @OA\Items(ref="#/components/schemas/Post")
  */
namespace App\Http\Controllers;

use App\ResponseTrait;
use Illuminate\Http\Request;
use App\Repositories\PostRepository;
use App\Http\Resources\PostColletion;
use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Repositories\PostsTagsRepository;

/**
 * @OA\Tag(
 *     name="Posts",
 *     description="Endpoints relacionados a Posts"
 * )
 */
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
     * @OA\Get(
     *     path="/api/posts",
     *     summary="Retorna todos os posts",
     *     tags={"Posts"},
     *     @OA\Parameter(
     *         name="tag",
     *         in="query",
     *         description="Filtra posts por tag",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="query",
     *         in="query",
     *         description="Filtra posts por título ou conteúdo",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de posts",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Post")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao recuperar posts",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Erro ao recuperar posts.")
     *         )
     *     )
     * )
     */
    public function posts(Request $request)
    {
        if ($request->has('tag')) {
            $tag = $request->query('tag');
            $posts = $this->postRepository->searchByTag($tag, ['tags', 'author']);
            return new PostColletion($posts);
        }

        if ($request->has('query')) {
            $query = $request->query('query');
            $posts = $this->postRepository->searchTerms($query, ['tags', 'author']);
            return new PostColletion($posts);
        }

        $posts = $this->postRepository->paginate(10, ['*'], ['tags', 'author']);
        return new PostColletion($posts);
    }

    /**
     * @OA\Post(
     *     path="/api/posts/create",
     *     summary="Cria um novo post",
     *     tags={"Posts"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "content", "author_id"},
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="content", type="string"),
     *             @OA\Property(property="author_id", type="integer"),
     *             @OA\Property(property="tags", type="array", @OA\Items(type="string"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Post criado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Post")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao criar post",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Erro ao criar post.")
     *         )
     *     )
     * )
     */
    public function create(CreatePostRequest $request)
    {
        try {
            $post = $this->postRepository->create([
                'title'     => $request->title,
                'content'   => $request->content,
                'author_id' => $request->author_id
            ]);

            if ($request->has('tags')) {
                $tags = $request->tags;
                foreach ($tags as $tag) {
                    $this->tagsRepository->create(['tag_name' => $tag, 'post_id' => $post->id]);
                }
            }

            $post = $this->postRepository->find($post->id, ['*'], ['tags', 'author']);
            return $this->responseJSON(true, 'Post cadastrado com sucesso', 201, ['post' => $post]);
        } catch (\Exception $e) {
            return $this->responseJSON(false, 'Erro ao cadastrar o Post. Verifique os dados fornecidos.', 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/posts/update/{id}",
     *     summary="Atualiza um post",
     *     tags={"Posts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do post a ser atualizado",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="content", type="string"),
     *             @OA\Property(property="author_id", type="integer"),
     *             @OA\Property(property="tags", type="array", @OA\Items(type="string"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Post atualizado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Post")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Post não encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Post não encontrado")
     *         )
     *     )
     * )
     */
    public function update($id, UpdatePostRequest $request) :object
    {
        try {
            $post = $this->postRepository->find($id);

            if (!$post) {
                return $this->responseJSON(false, 'Post não encontrado', 404);
            }

            $updated = [];
            foreach($request->all() as $key => $value){
                if($value === "" || $value === null) continue;
                $updated[$key] = $value;
            }

            $this->postRepository->update($id, $updated);

            if ($request->has('tags')) {
                $this->tagsRepository->delete($post->id, 'post_id');
                $tags = $request->tags;
                foreach ($tags as $tag) {
                    $this->tagsRepository->create(['tag_name' => $tag, 'post_id' => $post->id]);
                }
            }

            $post = $this->postRepository->find($post->id, ['*'], ['tags', 'author']);
            return $this->responseJSON(true, 'Post atualizado com sucesso', 200, ['post' => $post]);
        } catch (\Exception $e) {
            return $this->responseJSON(false, 'Erro ao editar o post. Verifique os dados fornecidos.', 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/posts/delete/{id}",
     *     summary="Deleta um post",
     *     tags={"Posts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do post a ser deletado",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Post deletado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Post não encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Post não encontrado")
     *         )
     *     )
     * )
     */
    public function delete($id): object
    {
        try {
            $post = $this->postRepository->find($id);

            if (!$post) {
                return $this->responseJSON(false, 'Post não encontrado', 404);
            }

            $this->postRepository->delete($id);
            return $this->responseJSON(true, 'Post deletado com sucesso', 200);
        } catch (\Exception $e) {
            return $this->responseJSON(false, 'Erro ao deletar o post.', 500);
        }
    }
}