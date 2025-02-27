<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\ResponseTrait;
use App\ValidateUserTrait;

class RegisterRequest extends FormRequest
{
    use ResponseTrait;
    use ValidateUserTrait;
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

        return array_merge(
            $this->emailValidation(),
            $this->nameValidation(),
            $this->phoneValidation(),
            $this->passwordValidation()
        );
    }

    public function messages() : array
    {
        return $this->messagesRequest();
    }
}
