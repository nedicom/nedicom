<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class cities extends Model
{
    use HasFactory;

    public function offers(): HasMany
    {
        return $this->HasMany(
            Uslugi::class,
            'sity',
            'id'
        )
            ->select(['id', 'usl_name', 'usl_desc', 'sity', 'main_usluga_id', 'second_usluga_id', 'url'])
        ;
    }


    public function uslugies(): HasMany
    {
        return $this->HasMany(
            Uslugi::class,
            'sity',
            'id'
        )
            ->with('cities')
            ->with('main')
            ->with('second')
            ->select(['id', 'usl_name', 'usl_desc', 'sity', 'main_usluga_id', 'second_usluga_id', 'url'])
        ;
    }
}
