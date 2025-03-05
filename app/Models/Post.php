<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
/**
 * @OA\Schema(
 *     schema="Post",
 *     type="object",
 *     required={"id", "title", "content", "author_id"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Post Title"),
 *     @OA\Property(property="content", type="string", example="This is the content of the post"),
 *     @OA\Property(property="author_id", type="integer", example=1)
 * )
 */

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content', 'author_id'];

    /**
     * Relacionamento com o usuário (belongsTo).
     * Cada post pertence a um usuário.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', 'id')->select(['id', 'name', 'email', 'phone']);
    }

    /**
     * Relacionamento com as tags (hasMany).
     * Cada post pode ter muitas tags.
     */
    public function tags()
    {
        return $this->hasMany(PostsTags::class, 'post_id')->select(['tag_name', 'post_id']);
    }

}
