<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PostsTags extends Model
{
    use HasFactory;

    protected $fillable = ['post_id', 'tag_name'];
    protected $table = 'posts_tags';
}
