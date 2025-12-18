<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'pgsql_stats';

    public function up(): void
    {
        Schema::create('tracked_visitors', function (Blueprint $table) {
            $table->id();
            
            // Уровень 1: Email (если зарегистрирован)
            $table->string('email')->nullable()->unique();
            
            // Уровень 2: Яндекс идентификаторы
            $table->string('yandex_uid')->nullable()->unique();
            $table->string('yandex_client_id')->nullable();
            
            // Уровень 3: Наш идентификатор
            $table->string('visitor_id')->unique();
            
            // Метаданные
            $table->timestamp('first_seen')->useCurrent();
            $table->timestamp('last_seen')->useCurrent();
            $table->integer('total_visits')->default(1);
            
            // История посещений в JSON
            $table->jsonb('visit_history')->default('[]');
            
            // Дополнительные поля для аналитики
            $table->string('first_ip')->nullable();
            $table->string('first_user_agent', 500)->nullable();
            $table->string('last_ip')->nullable();
            $table->string('last_user_agent', 500)->nullable();
            $table->string('first_referer', 1000)->nullable();
            $table->string('first_device_type')->nullable();
            $table->string('first_browser')->nullable();
            
            // Таймстампы
            $table->timestamps();
            
            // Индексы
            $table->index('email');
            $table->index('yandex_uid');
            $table->index('visitor_id');
            $table->index('first_seen');
            $table->index('last_seen');
            $table->index('total_visits');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tracked_visitors');
    }
};