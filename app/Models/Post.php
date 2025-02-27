<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content', 'user_id'];

    /**
     * Relacionamento com o usuário (belongsTo).
     * Cada post pertence a um usuário.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id')->select(['id', 'name', 'email', 'email', 'phone']);
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
