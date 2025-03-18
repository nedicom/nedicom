<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'lawyer',
        'name',
        'email',
        'password',
        'about',
        'lawyer',
        'phone',
        'city',
        'speciality_one_id',
        'speciality_two_id',
        'speciality_three_id',
        'address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at'  => 'date:d.m.Y',
    ];

    /**
     * Relation User
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function lawyerSpecOne(): HasOne
    {
        return $this->hasOne(Uslugi::class, 'id', 'speciality_one_id');
    }

    public function lawyerSpecTwo(): HasOne
    {
        return $this->hasOne(Uslugi::class, 'id', 'speciality_two_id');
    }

    public function lawyerSpecThree(): HasOne
    {
        return $this->hasOne(Uslugi::class, 'id', 'speciality_three_id');
    }


    public function reviews(): HasMany
    {
        return $this->HasMany(Review::class, 'lawyer_id', 'id')->select(['id as revieid', 'lawyer_id', 'rating', 'fio', 'description'])->orderBy('created_at', 'desc');
    }

    public function scopeFilter($query, array $filters)
    {
        $query
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhere('id', 'like', '%' . $search . '%');
                });
            })->when($filters['lawyer'] ?? null, function ($query, $lawyer) {
                if ($lawyer == "true") {
                    $query->where('lawyer', 1);
                }
            });
    }

    public function HasArticles(): HasMany
    {
        return $this->HasMany(Article::class, 'userid', 'id');
    }

    public function HasUslugi(): HasMany
    {
        return $this->HasMany(Uslugi::class, 'user_id', 'id');
    }

    public function HasPractice(): HasMany
    {
        return $this->HasMany(Article::class, 'userid', 'id')->where('practice_file_path', '!=', null);
    }

    public function HasQuestion(): HasMany
    {
        return $this->HasMany(Questions::class, 'user_id', 'id');
    }

    public function HasAnwers(): HasMany
    {
        return $this->HasMany(Answer::class, 'users_id', 'id');
    }

    public function cities(): HasOne
    {
        return $this->HasOne(cities::class, 'id', 'city_id')->select(['id', 'title', 'url', 'region', 'regionId', 'postalcode']);
    }


    public function review(): HasMany
    {
        return $this->HasMany(Review::class, 'user_id', 'id')
            ->select(['id as revieid', 'user_id', 'rating']);
    }

    public function main(): HasOne
    {
        return $this->HasOne(Uslugi::class, 'id', 'main_usluga_id')->select(['id', 'url', 'usl_name as name']);
    }


    public function second(): HasOne
    {
        return $this->HasOne(Uslugi::class, 'id', 'second_usluga_id')->select(['id', 'url', 'usl_name as name']);
    }
}
