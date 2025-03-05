<?php

namespace App\Swagger\Schemas;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Post",
 *     type="object",
 *     required={"id", "title", "content", "author_id"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Título do post"),
 *     @OA\Property(property="content", type="string", example="Conteúdo do post"),
 *     @OA\Property(property="author_id", type="integer", example=1)
 * )
 */
class Post
{
    // Este arquivo pode estar vazio, pois a anotação Swagger será suficiente para gerar o schema.
}
