<?php

namespace App\Http\Requests;
use App\ResponseTrait;
use App\ValidatePostsTrait;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class CreatePostRequest extends FormRequest
{
    use ResponseTrait;
    use ValidatePostsTrait;

    public function prepareForValidation()
    {

        /**
         * Se o autor não for informado, pega o usuário atual como autor
         */
        if (!$this->has('author')) { 
            $this->merge([
                'author' => Auth::user()->id
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
        return array_merge(
            $this->titleValidation(),
            $this->autorValidation(),
            $this->contentValidation()
        );
    }

    public function messages(): array
    {
        return $this->messagesValidation();
    }
}
