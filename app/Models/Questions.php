<?php

namespace App\Models;

use App\Casts\humandate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\hasMany;

class Questions extends Model
{
    use HasFactory;

    protected $fillable = ['city'];

    public function User(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function Usluga(): HasOne
    {
        return $this->hasOne(Uslugi::class, 'id', 'usluga')->select(['id', 'usl_name', 'url']);;
    }

    public function QuantityAns(): hasMany
    {
        return $this->hasMany(Answer::class,'questions_id',  'id');
    }

    protected $casts = [
        'created_at' => humandate::class,
    ];
}
