<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * База данных, с которой работает эта миграция
     */
    protected $connection = 'pgsql_stats';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('article_views', function (Blueprint $table) {
            $table->id();
            
            // Основные поля
            $table->unsignedBigInteger('article_id');
            $table->string('session_id');
            
            // Гео и сетевые данные
            $table->string('ip_address', 45)->nullable(); // IPv6 поддерживает до 45 символов
            $table->string('user_agent')->nullable();
            
            // Яндекс метрика идентификаторы
            $table->string('yandex_uid')->nullable();     // Яндекс User ID из куки _ym_uid
            $table->string('yandex_client_id')->nullable(); // Client ID из метрики
            
            // Информация об устройстве
            $table->string('device_type')->nullable();    // desktop, mobile, tablet
            $table->string('browser')->nullable();        // chrome, firefox, safari, etc
            $table->string('platform')->nullable();       // Windows, macOS, iOS, Android
            
            // Дополнительная аналитика
            $table->string('referer')->nullable();        // Откуда пришел пользователь
            $table->string('utm_source')->nullable();     // UTM метки
            $table->string('utm_medium')->nullable();
            $table->string('utm_campaign')->nullable();
            $table->string('utm_content')->nullable();
            $table->string('utm_term')->nullable();
            
            // Время просмотра
            $table->timestamp('viewed_at')->useCurrent();
            
            // Индексы
            $table->index(['article_id', 'session_id']);
            $table->index(['article_id', 'ip_address']);
            $table->index(['article_id', 'yandex_uid']);
            $table->index('article_id');
            $table->index('viewed_at');
            $table->index('device_type');
            $table->index('browser');
            $table->index('ip_address');
            $table->index('yandex_uid');
            $table->index('utm_source');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_views');
    }
};