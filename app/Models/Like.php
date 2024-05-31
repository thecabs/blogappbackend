<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Post;


class Like extends Model
{
    use HasFactory;

    protected $fillable = [
         
        'user_id',
        'post_id'

    ];
}
