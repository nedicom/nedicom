<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class cities extends Model
{
    use HasFactory;
    public function offers(): HasMany
    {
        return $this->HasMany(Offer::class,  
        'sity',  'id')
        ->with('uslugi')
        ;
    }
}
