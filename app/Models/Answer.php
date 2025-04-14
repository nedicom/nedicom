<?php

namespace App\Models;

use App\Casts\humandate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\belongsTo;
use Illuminate\Database\Eloquent\Relations\belongsToMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Answer extends Model
{
    use HasFactory;

    public function UserAns(): belongsTo
    {
        return $this->belongsTo(User::class, 'users_id', 'id')->select(['id', 'name', 'avatar_path', 'lawyer']);
    }

    public function Question(): belongsTo
    {
        return $this->belongsTo(Questions::class, 'questions_id', 'id')->select(['id', 'title', 'url']);
    }

    public function subcomments(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'answers', 'parent_comment_id', 'users_id')
        ->using(PivotDate::class)
        ->withPivot('id', 'created_at', 'body')
        ->select(['users.id', 'users.name', 'users.avatar_path', 'users.lawyer']);
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
