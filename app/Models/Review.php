<?php

namespace App\Models;

use App\Casts\humandate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

        'created_at',
    ];

    protected $casts = [
        'created_at' => humandate::class,
    ];
    public function usluga(): HasOne
    {
        return $this->hasOne(Uslugi::class, 'id', 'mainusl_id')->select(['id', 'url', 'usl_name', 'file_path']);
    }

}

