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
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com as tags (belongsToMany).
     * Cada post pode ter muitas tags.
     */
    public function tags()
    {
        return $this->belongsToMany(PostsTags::class, 'posts_tags');
    }

}
