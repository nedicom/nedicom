<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\belongsTo;
use Illuminate\Database\Eloquent\Relations\belongsToMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

use App\Casts\humandate;

class Article_comment extends Model
{
    use HasFactory;

    public function UserAns(): belongsTo
    {
        return $this->belongsTo(User::class, 'users_id', 'id')->select(['id', 'name', 'avatar_path']);
    }

    public function subcomments(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'article_comments', 'parent_comment_id', 'users_id')
            ->using(PivotDate::class)
            ->withPivot('id', 'created_at', 'body')
            ->select(['users.id', 'users.name', 'users.avatar_path']);
    }

    protected $casts = [
        'created_at' => humandate::class,
    ];
}

class PivotDate extends Pivot
{
    protected $casts = [
        'created_at' => humandate::class,
    ];
}
