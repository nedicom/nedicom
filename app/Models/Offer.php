<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Offer extends Model
{
    use HasFactory;

    public function city(): HasOne
    {
        return $this->hasOne(cities::class,'id', 'sity');
    }

    public function uslugi(): HasMany
    {
        return $this->HasMany(Uslugi::class,
        'main_usluga_id', 'mainusl_id')
        ->select(['main_usluga_id', 'usl_name', 'url'])
        ->where('is_main', 0)
        ;
    }
}
