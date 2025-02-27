<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\ValidateUserTrait;
use App\ResponseTrait;

class UpdateUserRequest extends FormRequest
{
    use ValidateUserTrait;
    use ResponseTrait;


    public function prepareForValidation()
    {

        if ($this->has('is_valid')) { // Apenas processa se o campo foi enviado
            $this->merge([
                'is_valid' => filter_var($this->input('is_valid'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
            ]);
        }
    }

    
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
        /**
         * Passando o id em todas as função para ser possivel alterar somente alguns campos especificos
         */
        return array_merge(
            $this->emailValidation($this->route('id')),
            $this->nameValidation($this->route('id')),
            $this->phoneValidation($this->route('id')),
            $this->passwordValidation($this->route('id')),
            $this->isValidValidation($this->route('id')),
        );
    }

    public function messages()
    {
        return $this->messagesRequest();
    }
}
