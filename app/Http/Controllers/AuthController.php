<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\ResponseTrait;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use ResponseTrait;

    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     summary="Realiza login do usuário",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login realizado com sucesso.",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="your-jwt-token"),
     *             @OA\Property(property="user", type="object", ref="#/components/schemas/User")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Credenciais inválidas.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Credenciais inválidas. Verifique seu email e senha.")
     *         )
     *     )
     * )
     */
    public function login(LoginRequest $request): object
    {
        $credentials = $request->only('email', 'password');
        
        if (!$token = Auth::attempt($credentials)) 
            return $this->responseJSON(false, 'Credenciais inválidas. Verifique seu email e senha.', 401);

        $user = Auth::user();
        $token = JWTAuth::fromUser($user);
        return $this->responseJSON(true, 'Login realizado com sucesso.', 200, [
            'token' => $token,
            'user'  => $user,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/register",
     *     summary="Cadastra um novo usuário",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "phone", "password"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="phone", type="string", example="123456789"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuário cadastrado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="your-jwt-token"),
     *             @OA\Property(property="user", type="object", ref="#/components/schemas/User")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao cadastrar o usuário",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Erro ao cadastrar o usuário. Verifique os dados fornecidos.")
     *         )
     *     )
     * )
     */
    public function register(RegisterRequest $request): object
    {
        $userRepository = new UserRepository;
        try {
            $user = $userRepository->create([
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
     * @OA\Get(
     *     path="/api/auth/logout",
     *     summary="Realiza logout do usuário",
     *     tags={"Auth"},
     *     @OA\Response(
     *         response=200,
     *         description="Logout realizado com sucesso.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Logout realizado com sucesso.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Falha ao tentar realizar logout.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Falha ao tentar realizar logout.")
     *         )
     *     )
     * )
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
