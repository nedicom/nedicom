<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bundles_social extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['users_id', 'article_id', 'question_id', 'likes', 'shares', 'bookmarks', 'comments'];
    
}