<?php

/**
 * @OA\Property(property="user", type="object", ref="#/components/schemas/User")
 */
namespace App\Http\Controllers;

use App\Models\User;
use App\ResponseTrait;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserCollection;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Repositories\UserRepository;

/**
 * @OA\Tag(
 *     name="Users",
 *     description="Endpoints relacionados a usuários"
 * )
 */
class UserController extends Controller
{
    use ResponseTrait;
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

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
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao recuperar usuários",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Erro ao recuperar usuários.")
     *         )
     *     )
     * )
     */
    public function users()
    {
        $users = $this->userRepository->paginate();
        return new UserCollection($users);
    }

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
    public function create(CreateUserRequest $request)
    {
        try {
            $user = $this->userRepository->create([
                'name'     => $request->name,
                'email'    => $request->email,
                'phone'    => $request->phone,
                'password' => Hash::make($request->password),
            ]);

            return $this->responseJSON(true, 'Usuário cadastrado com sucesso', 201, [
                'user'  => $user,
            ]);
        } catch (\Exception $e) {
            return $this->responseJSON(false, 'Erro ao cadastrar o usuário. Verifique os dados fornecidos.', 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/users/update/{id}",
     *     summary="Atualiza os dados de um usuário",
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
    public function update(UpdateUserRequest $request) : object
    {
        try {
            $user = $this->userRepository->find($request->id);

            if (!$user) {
                return $this->responseJSON(false, 'Usuário não encontrado', 404);
            }

            $updated = [];
            foreach($request->all() as $key => $value){
                if($value === "" || $value === null) continue;
                $updated[$key] = $value;
            }

            $this->userRepository->update($request->id, $updated);

            $user = $this->userRepository->find($request->id);
            return $this->responseJSON(true, 'Usuário atualizado com sucesso', 200, [
                'user' => $user, 
            ]);
        } catch (\Exception $e) {
            return $this->responseJSON(false, 'Erro ao editar o usuário. Verifique os dados fornecidos.', 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/users/delete/{id}",
     *     summary="Deleta um usuário",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do usuário a ser deletado",
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
    public function delete($id) : object
    {
        try {
            $user = $this->userRepository->find($id);

            if (!$user) {
                return $this->responseJSON(false, 'Usuário não encontrado', 404);
            }

            $this->userRepository->delete($id);
            return $this->responseJSON(true, 'Usuário deletado com sucesso', 200);
        } catch (\Exception $e) {
            return $this->responseJSON(false, 'Erro ao deletar o usuário.', 500);
        }
    }
}
