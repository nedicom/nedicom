<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;


use Illuminate\Support\Facades\Auth;


class Uslugi extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'views_count', 'slug'];

    protected $casts = [
        'created_at'  => 'date:d.m.Y',
        'popular_question' => 'array',
        'video' => 'array',
    ];

    /**
     * Увеличивает счетчик просмотров с защитой от накрутки
     */
    public function incrementViews()
    {
        $cookieName = $this->getViewCookieName();
        
        // Проверяем, не просматривался ли пост в последние 24 часа
        if (!$this->wasRecentlyViewed()) {
            $this->timestamps = false;
            $this->increment('views_count');
            $this->timestamps = true;
            
            $this->markAsViewed();
            
            return true;
        }
        
        return false;
    }
    
    /**
     * Проверяет, был ли пост просмотрен recently
     */
    public function wasRecentlyViewed($hours = 24)
    {
        $cookieName = $this->getViewCookieName();
        return Cookie::has($cookieName);
    }
    
    /**
     * Отмечает пост как просмотренный
     */
    protected function markAsViewed($hours = 24)
    {
        $cookieName = $this->getViewCookieName();
        Cookie::queue($cookieName, true, $hours * 60);
    }
    
    /**
     * Генерирует уникальное имя куки для поста
     */
    protected function getViewCookieName()
    {
        return 'post_viewed_' . $this->id;
    }
    
    /**
     * Получает количество просмотров с форматированием
     */
    public function getViewsCountFormattedAttribute()
    {
        return number_format($this->views_count, 0, ',', ' ');
    }
    
    /**
     * Проверяет, является ли пользователь уникальным посетителем
     */
    public function isUniqueVisitor()
    {
        return !$this->wasRecentlyViewed();
    }

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
                if ($main == "true") {
                    $query->where('is_main', 1);
                }
            })
            ->when($filters['second'] ?? null, function ($query, $second) {

                if ($second == "true") {
                    $query->where('is_second', 1);
                }
            })
            ->when($filters['feed']  ?? null, function ($query, $feed) {
                if ($feed == "true") {
                    $query->where('is_feed', 1);
                }
            })

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

    public function firstlawyer(): HasOne
    {
        return $this->HasOne(User::class, 'id', 'user_id');
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
            ->withSum('review', 'rating')
            ->with('cities')
        ;
    }

    public function mainhassecond(): HasMany
    {
        return $this->HasMany(Uslugi::class, 'main_usluga_id', 'id')
            ->where('is_second', 1)
            ->where('is_feed', 1)
            ->select(['id', 'main_usluga_id', 'is_second', 'usl_name', 'url', 'updated_at'])
        ;
    }

    public function sets(): HasMany
    {
        return $this->HasMany(Offer::class, 'mainusl_id', 'main_usluga_id');
    }

    public function cities(): HasOne
    {
        return $this->HasOne(cities::class, 'id', 'sity')->select(['id', 'title', 'url', 'regionId', 'region']);
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

    public function userreview(): HasMany
    {
        return $this->HasMany(Review::class, 'lawyer_id', 'user_id')
            ->select(['id as revieid', 'usl_id', 'rating', 'lawyer_id', 'user_id']);
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
