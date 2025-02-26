<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserCollection;

class UserController extends Controller
{
    /**
     * Retorna todos os usuários
     * @author Junior <hjuniorbsilva@gmail.com>
     */
    public function users()
    {
        $users = User::all();
        return new UserCollection($users);
    }
}
