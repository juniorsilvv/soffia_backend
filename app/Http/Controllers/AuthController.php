<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\ResponseTrait;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    use ResponseTrait;

    /**
     * Realiza login do usuário no sistema
     *
     * @param LoginRequest $request
     * @return object
     * @author Junior <hjuniorbsilva@gmail.com>
     */
    public function login(LoginRequest $request): object
    {

        $credentials = $request->only('email', 'password');
        
        if (!$token = Auth::attempt($credentials)) 
            return $this->responseJSON(false, 'Credenciais inválidas. Verifique seu email e senha.', 401);

        $user = Auth::user();
        $token = JWTAuth::fromUser($user);
        return $this->responseJSON(true, 'Login realizado com sucesso.', 200, [
            'token'         => $token,
            'user'          => $user,
        ]);
    }


    /**
     * Cadastra um novo usuário
     *
     * @param RegisterRequest $request
     * @return object
     * @author Junior <hjuniorbsilva@gmail.com>
     */
    public function register(RegisterRequest $request): object
    {
    
        try {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'phone'    => $request->phone,
                'password' => $request->password, 
            ]);
    
            $token = JWTAuth::fromUser($user);
    
            return $this->responseJSON(true, 'Usuário cadastrado com sucesso', 201, [
                'token' => $token,
                'user'  => $user,
            ]);            
        } catch (\Exception $e) {
            return $this->responseJSON(false, 'Erro ao cadastrar o usuário. Verifique os dados fornecidos.', 500);
        }
    }

    /**
     * Realiza logout do usuário
     *
     * @return object
     * @author Junior <hjuniorbsilva@gmail.com>
     */
    public function logout(): object
    {
        try {
            // Invalida o token do usuário atual
            JWTAuth::invalidate(JWTAuth::getToken());

            return $this->responseJSON(true, 'Logout realizado com sucesso.', 200);
        } catch (\Exception $e) {
            return $this->responseJSON(false, 'Falha ao tentar realizar logout.', 500);
        }
    }
}
