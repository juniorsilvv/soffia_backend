<?php
namespace App\Http\Middleware;

use Closure;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use App\ResponseTrait;

class AuthMiddleware
{
    use ResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            // Tenta obter o token do header Authorization
            $token = JWTAuth::parseToken();

            // Verifica se o token é válido
            $user = JWTAuth::authenticate($token);

            // Se o token não for válido, retorna um erro
            if (!$user)
                return $this->responseJSON(false, 'Token inválido', 401);

        } catch (JWTException $e) {
            return $this->responseJSON(false, 'Token inválido', 401);
            // Se ocorrer um erro ao tentar analisar o token
        }

        return $next($request);
    }
}

