<?php

namespace App;

use Illuminate\Validation\Rule;

trait ValidateUserTrait
{
    public function emailValidation($userId = null)
    {
        return [
            'email' => [
                (!is_null($userId) ? 'nullable' : 'required'),
                'email',
                Rule::unique('users')->ignore($userId),  // Ignore o e-mail do usuário atual para updates
            ],
        ];
    }

    public function nameValidation($userId = null)
    {
        return [
            'name' => [
                (!is_null($userId) ? 'nullable' : 'required'),
                'string',
                'min:3'
            ], 
        ];
    }

    public function phoneValidation($userId = null)
    {
        return [
            "phone" => [
                (!is_null($userId) ? 'nullable' : 'required'),
                'string'
            ]
        ];
    }

    public function passwordValidation($currentPassword = null)
    {
        return [
            'password' => $currentPassword ? 'nullable|string|min:6' : 'required|string|min:6',  // A senha é obrigatória apenas na criação
        ];
    }

    public function isValidValidation($userId = null)
    {
        return [
            'is_valid' => [
                (!is_null($userId) ? 'nullable' : 'required'),
                'boolean'
            ],
        ];
    }

    public function messagesRequest() : array
    {
        return [
            'email.required'    => "E-mail não informado.",
            'email.email'       => "E-mail inválido",
            'email.unique'      => "E-mail já cadastrado",
            'password.required' => "Senha não informada",
            "password.min"      => "Senha precisa ter pelo menos 6 digitos",
            "name.required"     => "Nome não informado",
            "phone.required"    => "Telefone não informado",
            "is_valid.required" => "Status de validade não informado",
            "is_valid.boolean"  => "Status de validade precisa ser um booleano"
        ];
    }
}
