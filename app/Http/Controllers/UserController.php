<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\ResponseTrait;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserCollection;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Repositories\UserRepository;

class UserController extends Controller
{

    use ResponseTrait;
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    /**
     * Retorna todos os usuários
     * @author Junior <hjuniorbsilva@gmail.com>
     */
    public function users()
    {
        $users = $this->userRepository->all();
        return new UserCollection($users);
    }

    /**
     * Cadastra um novo usuário
     *
     * @param UsersRequest $request
     * @return void
     * @author Junior <hjuniorbsilva@gmail.com>
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

    public function update(UpdateUserRequest $request)
    {

        try {
            // Encontrar o usuário pelo ID
            $user = $this->userRepository->find($request->id);

            // Verificar se o usuário foi encontrado
            if (!$user) {
                return $this->responseJSON(false, 'Usuário não encontrado', 404);
            }


            /**
             * Montando itens que serão atualizados
             */
            $updated = [];
            foreach($request->all() as $key => $value){
                if($value === "" || $value === null) continue;
                $updated[$key] = $value;
            }

            // Atualizar o usuário com os dados fornecidos
            $this->userRepository->update($request->id, $updated);

            $user = $this->userRepository->find($request->id);
            // Retornar a resposta com o usuário atualizado
            return $this->responseJSON(true, 'Usuário atualizado com sucesso', 200, [
                'user' => $user, // Retorna o usuário atualizado
            ]);
        } catch (\Exception $e) {
            print_r($e->getMessage());exit;
            return $this->responseJSON(false, 'Erro ao editar o usuário. Verifique os dados fornecidos.', 500);
        }
    }

    /**
     * Remove usuário por ID
     *
     * @param [type] $id
     * @return object
     * @author Junior <hjuniorbsilva@gmail.com>
     */
    public function delete($id) : object
    {
        try {
            $user = $this->userRepository->find($id);

            if (!$user) {
                return $this->responseJSON(false, 'Usuário não encontrado', 404);
            }

            // Deletar o usuário
            $this->userRepository->delete($id);

            return $this->responseJSON(true, 'Usuário deletado com sucesso', 200);
        } catch (\Exception $e) {
            return $this->responseJSON(false, 'Erro ao deletar o usuário.', 500);
        }
    }
}
