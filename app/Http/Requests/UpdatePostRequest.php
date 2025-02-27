<?php

namespace App\Http\Requests;
use App\ResponseTrait;
use App\ValidatePostsTrait;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
{
    use ResponseTrait;
    use ValidatePostsTrait;

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
            $this->titleValidation($this->route('id')),
            $this->autorValidation($this->route('id')),
            $this->contentValidation($this->route('id')),
        );
    }

    public function messages()
    {
        return $this->messagesValidation();
    }
}
