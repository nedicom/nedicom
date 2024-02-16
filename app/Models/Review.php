<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{

    public $timestamps = FALSE;
    
    use HasFactory;

    protected $fillable = [

        'mainusl_id',

        'usl_id',

        'rating',

        'fio',

        'description',
];

}
