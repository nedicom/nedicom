<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversion extends Model
{
    protected $connection = 'pgsql_stats';
    protected $table = 'conversions';
    
    protected $fillable = [
        'conversion_type',
        'conversion_value',
        'ip_address',
        'user_agent',
        'session_id',
        'user_id',
        'page_url',
        'page_referrer',
        'page_title',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_term',
        'utm_content',
        'extra_data'
    ];
    
    protected $casts = [
        'conversion_value' => 'decimal:2',
        'extra_data' => 'array',
        'converted_at' => 'datetime'
    ];
}