<?php

namespace App;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
trait ResponseTrait
{
    /**
     * Retorna em JSON os erros nos ocorridos nos REQUETS
     *
     * @param Validator $validator
     * @author Junior <hjuniorbsilva@gmail.com>
     */
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], 422));
    }

    /**
     * Retorna os dados
     *
     * @param array $data
     * @param string $message
     * @param int $httpStatusCode
     * @author Junior <hjuniorbsilva@gmail.com>
     */
    public function responseJSON(bool $success = true, string $message = "", int $httpStatusCode = 200, array $data = []) {
        return response()->json([
            'success' => $success, 
            'message' => $message, 
            'data' => $data
        ], $httpStatusCode);
    }
}
