<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Offer extends Model
{
    use HasFactory;

    public function city(): HasOne
{
    return $this->hasOne(cities::class,'id', 'id');
}
}
