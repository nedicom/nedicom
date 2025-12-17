<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * База данных, с которой работает эта миграция[citation:1].
     */
    protected $connection = 'pgsql_stats'; // Указываем ваше подключение к PostgreSQL

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('conversions', function (Blueprint $table) {
            $table->id();
            $table->string('conversion_type'); // lead, purchase, signup и т.д.
            $table->decimal('conversion_value', 10, 2)->nullable();

            // Информация о посетителе
            $table->ipAddress('ip_address');
            $table->string('user_agent')->nullable();
            $table->string('session_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();

            // Информация о странице
            $table->text('page_url');
            $table->text('page_referrer')->nullable();
            $table->string('page_title')->nullable();

            // UTM-метки
            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->string('utm_campaign')->nullable();
            $table->string('utm_term')->nullable();
            $table->string('utm_content')->nullable();

            // Дополнительные данные
            $table->json('extra_data')->nullable();

            // Временные метки
            $table->timestamp('converted_at')->useCurrent();
            $table->timestamps(); // created_at, updated_at

            // Индексы для ускорения запросов
            $table->index('session_id');
            $table->index('user_id');
            $table->index('converted_at');
            $table->index(['utm_source', 'utm_campaign']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversions');
    }
};
