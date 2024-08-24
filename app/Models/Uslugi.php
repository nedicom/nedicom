<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\belongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Uslugi extends Model
{
    use HasFactory;

    protected $fillable = ['user_id'];

    protected $casts = [
        'created_at'  => 'date:d.m.Y',
        'popular_question' => 'array',
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
        return $this->belongsTo(User::class, 'user_id', 'id')//->select(['id', 'avatar_path', 'name'])
        ;
    }

    public function HasArticles(): HasMany
    {
        return $this->HasMany(Article::class, 'usluga_id', 'id');
    }

    public function hasuslugi(): HasMany
    {
        return $this->HasMany(Uslugi::class, 'main_usluga_id', 'id')
        ->where('is_main', 0)
        ->select(['id', 'usl_name', 'main_usluga_id']);
    }

    public function sets(): HasMany
    {
        return $this->HasMany(Offer::class, 'mainusl_id', 'main_usluga_id')
        //->select(['id', 'title'])
        ;
        /*return $this->HasMany(Uslugi::class, 'main_usluga_id', 'main_usluga_id')
        ->select(['id', 'usl_name', 'main_usluga_id'])
        ;*/
    }

    public function cities(): HasOne
    {
        return $this->HasOne(cities::class, 'id', 'sity')
        ;
        /*return $this->HasMany(Uslugi::class, 'main_usluga_id', 'main_usluga_id')
        ->select(['id', 'usl_name', 'main_usluga_id'])
        ;*/
    }
        
}
