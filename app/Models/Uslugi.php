<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\belongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Uslugi extends Model
{
    use HasFactory;

    protected $fillable = ['user_id'];

    protected $casts = [
        'created_at'  => 'date:d.m.Y',
    ];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('usl_name', 'like', '%'.$search.'%')
                    ->orWhere('usl_desc', 'like', '%'.$search.'%')
                    ->orWhere('longdescription', 'like', '%'.$search.'%')
                    ->orWhere('id', 'like', '%'.$search.'%');
            });
        });
    }

    public function firstlawyer(): belongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->select(['id', 'avatar_path', 'name']);
    }

    public function HasArticles(): HasMany
    {
        return $this->HasMany(Article::class, 'usluga_id', 'id');
    }
}
