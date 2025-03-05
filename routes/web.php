<?php
/**
 * @OA\Info(
 *     title="API Documentation",
 *     version="1.0.0",
 *     description="Documentação da API para autenticação, gerenciamento de usuários e posts."
 * )
 */

/**
 * @OA\Tag(
 *     name="Auth",
 *     description="Endpoints relacionados à autenticação"
 * )
 */

/**
 * @OA\Tag(
 *     name="Users",
 *     description="Endpoints relacionados ao gerenciamento de usuários"
 * )
 */

/**
 * @OA\Tag(
 *     name="Posts",
 *     description="Endpoints relacionados ao gerenciamento de posts"
 * )
 */
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AuthMiddleware;
use Illuminate\Support\Facades\Route;



Route::prefix('api')->middleware('throttle:20,1')->group(function () {

    /**
     * @OA\PathItem(
     *     path="/api/auth"
     * )
     */
    Route::prefix('auth')->group(function () {
        /**
         * @OA\Post(
         *     path="/api/auth/login",
         *     summary="Realiza login do usuário",
         *     tags={"Auth"},
         *     @OA\RequestBody(
         *         required=true,
         *         @OA\JsonContent(
         *             required={"email", "password"},
         *             @OA\Property(property="email", type="string", example="user@example.com"),
         *             @OA\Property(property="password", type="string", example="password123")
         *         )
         *     ),
         *     @OA\Response(
         *         response=200,
         *         description="Login bem-sucedido",
         *         @OA\JsonContent(
         *             @OA\Property(property="message", type="string", example="Login bem-sucedido")
         *         )
         *     ),
         *     @OA\Response(
         *         response=401,
         *         description="Credenciais inválidas",
         *         @OA\JsonContent(
         *             @OA\Property(property="message", type="string", example="Credenciais inválidas")
         *         )
         *     )
         * )
         */
        Route::post('login', [AuthController::class, 'login']);

        /**
         * @OA\Post(
         *     path="/api/auth/register",
         *     summary="Registra um novo usuário",
         *     tags={"Auth"},
         *     @OA\RequestBody(
         *         required=true,
         *         @OA\JsonContent(
         *             required={"name", "email", "password"},
         *             @OA\Property(property="name", type="string", example="John Doe"),
         *             @OA\Property(property="email", type="string", example="user@example.com"),
         *             @OA\Property(property="password", type="string", example="password123")
         *         )
         *     ),
         *     @OA\Response(
         *         response=201,
         *         description="Usuário registrado com sucesso",
         *         @OA\JsonContent(
         *             @OA\Property(property="message", type="string", example="Usuário registrado com sucesso")
         *         )
         *     ),
         *     @OA\Response(
         *         response=400,
         *         description="Erro ao registrar o usuário",
         *         @OA\JsonContent(
         *             @OA\Property(property="message", type="string", example="Erro ao registrar o usuário")
         *         )
         *     )
         * )
         */
        Route::post('register', [AuthController::class, 'register']);
    });

    Route::middleware(AuthMiddleware::class)->group(function () {
        Route::get('logout', [AuthController::class, 'logout']);

        /**
         * @OA\PathItem(
         *     path="/api/users"
         * )
         */
        Route::prefix('users')->controller(UserController::class)->group(function () {
            /**
             * @OA\Get(
             *     path="/api/users",
             *     summary="Retorna todos os usuários",
             *     tags={"Users"},
             *     @OA\Response(
             *         response=200,
             *         description="Lista de usuários",
             *         @OA\JsonContent(
             *             type="array",
             *             @OA\Items(ref="#/components/schemas/User")
             *         )
             *     )
             * )
             */
            Route::get('/', 'users');

            /**
             * @OA\Post(
             *     path="/api/users/create",
             *     summary="Cadastra um novo usuário",
             *     tags={"Users"},
             *     @OA\RequestBody(
             *         required=true,
             *         @OA\JsonContent(
             *             required={"name", "email", "phone", "password"},
             *             @OA\Property(property="name", type="string"),
             *             @OA\Property(property="email", type="string"),
             *             @OA\Property(property="phone", type="string"),
             *             @OA\Property(property="password", type="string")
             *         )
             *     ),
             *     @OA\Response(
             *         response=201,
             *         description="Usuário cadastrado com sucesso",
             *         @OA\JsonContent(ref="#/components/schemas/User")
             *     ),
             *     @OA\Response(
             *         response=500,
             *         description="Erro ao cadastrar usuário",
             *         @OA\JsonContent(
             *             @OA\Property(property="message", type="string", example="Erro ao cadastrar o usuário.")
             *         )
             *     )
             * )
             */
            Route::post('create', 'create');

            /**
             * @OA\Put(
             *     path="/api/users/update/{id}",
             *     summary="Atualiza dados de um usuário",
             *     tags={"Users"},
             *     @OA\Parameter(
             *         name="id",
             *         in="path",
             *         required=true,
             *         description="ID do usuário a ser atualizado",
             *         @OA\Schema(type="integer")
             *     ),
             *     @OA\RequestBody(
             *         required=true,
             *         @OA\JsonContent(
             *             @OA\Property(property="name", type="string"),
             *             @OA\Property(property="email", type="string"),
             *             @OA\Property(property="phone", type="string"),
             *             @OA\Property(property="password", type="string")
             *         )
             *     ),
             *     @OA\Response(
             *         response=200,
             *         description="Usuário atualizado com sucesso",
             *         @OA\JsonContent(ref="#/components/schemas/User")
             *     ),
             *     @OA\Response(
             *         response=404,
             *         description="Usuário não encontrado",
             *         @OA\JsonContent(
             *             @OA\Property(property="message", type="string", example="Usuário não encontrado")
             *         )
             *     )
             * )
             */
            route::put('update/{id}', 'update');

            /**
             * @OA\Delete(
             *     path="/api/users/delete/{id}",
             *     summary="Remove um usuário",
             *     tags={"Users"},
             *     @OA\Parameter(
             *         name="id",
             *         in="path",
             *         required=true,
             *         description="ID do usuário a ser removido",
             *         @OA\Schema(type="integer")
             *     ),
             *     @OA\Response(
             *         response=200,
             *         description="Usuário deletado com sucesso"
             *     ),
             *     @OA\Response(
             *         response=404,
             *         description="Usuário não encontrado",
             *         @OA\JsonContent(
             *             @OA\Property(property="message", type="string", example="Usuário não encontrado")
             *         )
             *     )
             * )
             */
            Route::delete('delete/{id}', 'delete');
        });

        /**
         * @OA\PathItem(
         *     path="/api/posts"
         * )
         */
        Route::prefix('posts')->controller(PostController::class)->group(function () {
            /**
             * @OA\Get(
             *     path="/api/posts",
             *     summary="Retorna todos os posts",
             *     tags={"Posts"},
             *     @OA\Response(
             *         response=200,
             *         description="Lista de posts",
             *         @OA\JsonContent(
             *             type="array",
             *             @OA\Items(ref="#/components/schemas/Post")
             *         )
             *     )
             * )
             */
            Route::get('/', 'posts');

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
             *             @OA\Property(property="author_id", type="integer")
             *         )
             *     ),
             *     @OA\Response(
             *         response=201,
             *         description="Post criado com sucesso",
             *         @OA\JsonContent(ref="#/components/schemas/Post")
             *     )
             * )
             */
            Route::post('create', 'create');

            /**
             * @OA\Put(
             *     path="/api/posts/update/{id}",
             *     summary="Atualiza dados de um post",
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
             *             @OA\Property(property="author_id", type="integer")
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
            Route::put('update/{id}', 'update');

            /**
             * @OA\Delete(
             *     path="/api/posts/delete/{id}",
             *     summary="Remove um post",
             *     tags={"Posts"},
             *     @OA\Parameter(
             *         name="id",
             *         in="path",
             *         required=true,
             *         description="ID do post a ser removido",
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
            Route::delete('delete/{id}', 'delete');
        });
    });
});