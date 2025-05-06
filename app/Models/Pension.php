<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pension extends Model
{
    use HasFactory;

    protected $table = 'Pension'; // если таблица с заглавной буквы, как в миграции

    protected $fillable = [
        'user_id',
        'pay',
        'gender',
        'sk',
        'zp',
        'szp',
        'punkt',
        'pns',
        't',
        'kv',
        'pk',
        'pk1',
        'pk2',
        'spk',
        'ipkn',
        'ipks',
        'ipk',
        'spst',
        'propzp',
        'rp',
        'sv',
        'p',
        'nadb',
        'pension',
    ];

    // Связь с пользователем (опционально)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
