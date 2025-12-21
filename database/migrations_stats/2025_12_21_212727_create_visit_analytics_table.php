<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    // Явно указываем подключение к PostgreSQL
    protected $connection = 'pgsql_stats';
    
    /**
     * Проверяем, что это PostgreSQL
     */
    public function __construct()
    {
        // Проверяем тип базы данных
        $connection = config('database.connections.' . $this->connection . '.driver');
        
        if ($connection !== 'pgsql') {
            throw new \Exception('Эта миграция предназначена только для PostgreSQL!');
        }
        
        parent::__construct();
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Дополнительная проверка при выполнении
        if (DB::connection()->getDriverName() !== 'pgsql') {
            throw new \Exception('Миграция может выполняться только в PostgreSQL!');
        }

        Schema::create('site_visits', function (Blueprint $table) {
            $table->id(); // Автоинкрементный ID
            $table->string('yandex_id')->nullable(); // Яндекс ID (если есть)
            $table->timestamp('visited_at')->useCurrent(); // Дата и время посещения
            $table->string('url'); // URL страницы
            $table->string('referer')->nullable(); // Откуда пришёл пользователь
            
            $table->timestamps(); // created_at, updated_at
        });
        
        // Добавляем индексы для быстрого поиска
        Schema::table('site_visits', function (Blueprint $table) {
            $table->index('yandex_id');
            $table->index('visited_at');
            $table->index('url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Проверка перед удалением
        if (DB::connection()->getDriverName() !== 'pgsql') {
            throw new \Exception('Миграция может откатываться только в PostgreSQL!');
        }
        
        Schema::dropIfExists('site_visits');
    }
};