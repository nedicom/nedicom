<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YandexTracking extends Model
{
    use HasFactory;

    protected $connection = 'pgsql_stats';

    protected $table = 'yandex_tracking';

    protected $fillable = [
        '_ym_uid',
        'url',
        'ip',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_term',
        'utm_content',
        'interests',
        'city',
        'article_id',
        'question_id',
        'lawyer_id',
        'usluga_id',
        'is_engaged',
        'phone_click_at',
        'page_views',
    ];

    /**
     * Типы полей для автоматического преобразования
     */
    protected $casts = [
        'is_engaged' => 'boolean',
        'phone_click_at' => 'datetime',
        'usluga_id' => 'array', // Автоматически преобразует JSON в массив PHP
        'page_views' => 'integer',
        'article_id' => 'integer',
        'question_id' => 'integer',
        'lawyer_id' => 'integer',
        'interests' => 'integer',
    ];

    /**
     * Значения по умолчанию
     */
    protected $attributes = [
        'is_engaged' => false,
        'page_views' => 1,
    ];

    /**
     * Связь с таблицей статей (если есть модель Article)
     */
    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id');
    }

    /**
     * Связь с таблицей вопросов (если есть модель Question)
     */
    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }

    /**
     * Связь с таблицей юристов (если есть модель Lawyer/User)
     */
    public function lawyer()
    {
        // Предполагаем, что юристы в таблице users с role=lawyer
        return $this->belongsTo(User::class, 'lawyer_id');
    }

    /**
     * Scope для фильтрации по UTM источнику
     */
    public function scopeByUtmSource($query, $source)
    {
        return $query->where('utm_source', $source);
    }

    /**
     * Scope для фильтрации по городу
     */
    public function scopeByCity($query, $city)
    {
        return $query->where('city', $city);
    }

    /**
     * Scope для вовлечённых пользователей
     */
    public function scopeEngaged($query)
    {
        return $query->where('is_engaged', true);
    }

    /**
     * Scope за сегодня
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Scope с кликами по телефону
     */
    public function scopeWithPhoneClicks($query)
    {
        return $query->whereNotNull('phone_click_at');
    }
}