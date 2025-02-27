<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserCollection;
use App\ResponseTrait;
use App\Repositories\UserRepository;

class UserController extends Controller
{

    use ResponseTrait;
    private $userRespository;

    public function __construct()
    {
        $this->userRespository = new UserRepository;
    }
    /**
     * Retorna todos os usuários
     * @author Junior <hjuniorbsilva@gmail.com>
     */
    public function users()
    {
        $users = $this->userRespository->all();
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
            $user = $this->userRespository->create([
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
            $user = $this->userRespository->find($request->id);

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
            $this->userRespository->update($request->id, $updated);

            // Retornar a resposta com o usuário atualizado
            return $this->responseJSON(true, 'Usuário atualizado com sucesso', 200, [
                'user' => $user, // Retorna o usuário atualizado
            ]);
        } catch (\Exception $e) {
            print_r($e->getMessage());exit;
            return $this->responseJSON(false, 'Erro ao editar o usuário. Verifique os dados fornecidos.', 500);
        }
    }
}
