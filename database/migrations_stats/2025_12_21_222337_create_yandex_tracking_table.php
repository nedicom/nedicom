<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'pgsql_stats';
    
    public function up(): void
    {
        Schema::create('yandex_tracking', function (Blueprint $table) {
            $table->id();
            $table->string('_ym_uid')->nullable();
            $table->string('yandexuid')->nullable();
            $table->string('yandex_login')->nullable();
            $table->string('url')->nullable();
            $table->string('ip')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('yandex_tracking');
    }
};