<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\ResponseTrait;

class UsersRequest extends FormRequest
{

    use ResponseTrait;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email'             => 'required|email|unique:users,email',
            'password'          => 'required|min:6',
            'name'              => 'required|string',
            'phone'             => 'required'
        ];
    }

    public function messages()
    {
        return [
            'email.required'    => "E-mail não informado.",
            'email.email'       => "E-mail inválido",
            'email.unique'      => "E-mail já cadastrado",
            'password.required' => "Senha não informada",
            "password.min"      => "Senha precisa ter pelo menos 6 digitos",
            "name.required"     => "Nome não informado",
            "phone"             => "Telefone não informado"
        ];
    }
}
