<?php

namespace App;

trait ValidatePostsTrait
{
    public function titleValidation()
    {
        return [
            'title' => ['required', 'string']
        ];
    }

    public function autorValidation()
    {
        return [
            'author' => ['nullable', 'integer', 'exists:users,id']
        ];
    }

    public function contentValidation()
    {
        return [
           'content' => ['required', 'string']
        ];
    }


    public function messagesValidation()
    {
        return [
            'title.required'    => 'O título é obrigatório',
            'title.string'      => 'O título precisa ser uma string',
            'author.nullable'   => 'O autor é opcional',
            'author.integer'    => 'O autor precisa ser um número inteiro',
            'author.exists'     => 'O autor informado não existe',
            'content.required'  => 'O conteúdo é obrigatório',
            'content.string'    => 'O conteúdo precisa ser uma string'
         ];
    }
}
