<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dialogue extends Model
{
    use HasFactory;

    protected $fillable = ['user_id'];

    protected $casts = [
        'created_at'  => 'date:d.m.Y',
    ];
}
