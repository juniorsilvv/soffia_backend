<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UsersRequest;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserCollection;
use App\ResponseTrait;

class UserController extends Controller
{

    use ResponseTrait;
    /**
     * Retorna todos os usuários
     * @author Junior <hjuniorbsilva@gmail.com>
     */
    public function users()
    {
        $users = User::all();
        return new UserCollection($users);
    }

    public function create(UsersRequest $request)
    {
        try {
            $user = User::create([
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
}
