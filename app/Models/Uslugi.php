<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\belongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

use Illuminate\Support\Facades\Auth;


class Uslugi extends Model
{
    use HasFactory;

    protected $fillable = ['user_id'];

    protected $casts = [
        'created_at'  => 'date:d.m.Y',
        'popular_question' => 'array',
        'video' => 'array',
    ];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('usl_name', 'like', '%' . $search . '%')
                    ->orWhere('usl_desc', 'like', '%' . $search . '%')
                    ->orWhere('longdescription', 'like', '%' . $search . '%')
                    ->orWhere('id', 'like', '%' . $search . '%');
            });
        });
    }

    public function scopeUslfilter($query, array $filters)
    {
        $query
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('usl_name', 'like', '%' . $search . '%');
                });
            })
            ->when($filters['author'] ?? null, function ($query, $author) {
                $query->where(function ($query) use ($author) {
                    $query->where('user_id', $author);
                });
            })
            ->when($filters['city'] ?? null, function ($query, $city) {
                $query->where(function ($query) use ($city) {
                    $query->where('sity', $city);
                });
            })
            ->when($filters['main'] ?? null, function ($query, $main) {
               if ( $main == "true") {$query->where('is_main', 1);}
            })
            ->when($filters['second'] ?? null, function ($query, $second) {
               
                if ( $second == "true") {$query->where('is_second', 1);}
            })
            ->when($filters['feed']  ?? null, function ($query, $feed) {
                if ( $feed == "true") {$query->where('is_feed', 1);}
            })
                
        ;
    }


    public function firstlawyer(): belongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id') //->select(['id', 'avatar_path', 'name'])
        ;
    }

    public function HasArticles(): HasMany
    {
        return $this->HasMany(Article::class, 'usluga_id', 'id');
    }

    public function hasuslugi(): HasMany
    {
        return $this->HasMany(Uslugi::class, 'main_usluga_id', 'id')
            ->where('is_second', 1)
            ->select(['id', 'usl_name', 'main_usluga_id', 'url', 'sity', 'url']);
    }

   /* тестим услугу без категории. Первичную выбирать можно всегда
   public function doesntHaveoffersbymain(): HasMany
    {
        return $this->HasMany(Uslugi::class, 'main_usluga_id', 'id')
            ->where('user_id', Auth::user()->id)            
            ->where('second_usluga_id', null)
            ->where('is_feed', 1)
            ;
    }*/

    public function zerocategory(): HasOne
    {
        return $this->HasOne(Uslugi::class, 'main_usluga_id', 'id')
            ->where('user_id', Auth::user()->id)            
            ->where('second_usluga_id', 0)
            ->where('is_feed', 1)
            ->select(['id', 'usl_name', 'main_usluga_id', 'second_usluga_id', 'is_feed', 'user_id'])
            ;
    }

    public function doesntHaveoffersbysecond(): HasMany
    {
        return $this->HasMany(Uslugi::class, 'second_usluga_id', 'id')
            ->where('user_id', Auth::user()->id)
            ->where('is_feed', 1);
    }

    public function mainhasoffer(): HasMany
    {
        return $this->HasMany(Uslugi::class, 'main_usluga_id', 'id')
            ->where('is_second', null)
            ->where('is_main', "!=", 1)
            ->where('is_feed', 1)
            ->select(['id', 'usl_name', 'main_usluga_id', 'sity', 'url', 'file_path', 'price'])
            ->withCount('review')
            ->withSum( 'review', 'rating')
            ->with('cities')
            ;
    }

    public function mainhassecond(): HasMany
    {
        return $this->HasMany(Uslugi::class, 'main_usluga_id', 'id')
            ->where('is_second', 1)
            ->where('is_feed', 1)
            ->select(['id', 'main_usluga_id', 'is_second','usl_name', 'url', 'updated_at'])
            ;
    }

    public function sets(): HasMany
    {
        return $this->HasMany(Offer::class, 'mainusl_id', 'main_usluga_id');
    }

    public function cities(): HasOne
    {
        return $this->HasOne(cities::class, 'id', 'sity')->select(['id', 'title', 'url']);
    }

    public function main(): HasOne
    {
        return $this->HasOne(Uslugi::class, 'id', 'main_usluga_id')->select(['id', 'url', 'usl_name as name']);
    }

    public function mainwithsecond(): HasMany
    {
        return $this->HasMany(Uslugi::class, 'main_usluga_id', 'main_usluga_id')
            ->where('is_second', 1)
            ->select(['id', 'main_usluga_id', 'is_second']);
    }

    public function second(): HasOne
    {
        return $this->HasOne(Uslugi::class, 'id', 'second_usluga_id')->select(['id', 'url', 'usl_name as name']);
    }

    public function review(): HasMany
    {
        return $this->HasMany(Review::class, 'usl_id', 'id')
            ->select(['id as revieid', 'usl_id', 'rating']);
    }

    public function mainreview(): HasMany
    {
        return $this->HasMany(Review::class, 'mainusl_id', 'id');
    }

    public function user(): HasOne
    {
        return $this->HasOne(User::class, 'id', 'user_id')->select(['id as user_id', 'name', 'avatar_path']);
    }
}
