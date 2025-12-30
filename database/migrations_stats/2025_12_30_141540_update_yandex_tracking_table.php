<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    protected $connection = 'pgsql_stats';

    public function up(): void
    {
        Schema::table('yandex_tracking', function (Blueprint $table) {
            $table->dropColumn(['yandexuid', 'yandex_login']);

            $table->string('utm_source')->nullable()->comment('yandex, google, vk, email');
            $table->string('utm_medium')->nullable()->comment('cpc, organic, email, banner');
            $table->string('utm_campaign')->nullable()->comment('black_friday_2026, pension_calc');
            $table->string('utm_term')->nullable()->comment('пенсионный+калькулятор, рассчитать+пенсию');
            $table->string('utm_content')->nullable()->comment('button_red, banner_top, textlink');

            $table->boolean('is_engaged')->default(false)->comment('Флаг вовлечённости (время > 30 сек)');
            $table->timestamp('phone_click_at')->nullable()->comment('Время клика по телефону');

            $table->integer('interests')->nullable()->comment('1 - пенсионный юрист');
            $table->integer('city')->nullable()->comment('1 - Moscow etc');

            $table->integer('article_id')->nullable()->comment('1 - Как оформить завещание');
            $table->integer('question_id')->nullable()->comment('1 - Вопрос как повысить пенсию');
            $table->integer('lawyer_id')->nullable()->comment('1 - юрист Мина М. А.');
            $table->json('usluga_data')->nullable()
                ->comment('{
                    "usluga_id": 1,
                    "usluga_name": "Пенсионный юрист",
                    "city": 1,
                    "lawyer_id": 42,
                }');

            $table->index('utm_source');
            $table->index('interests');
            $table->index('city');
            $table->index('article_id');
            $table->index('lawyer_id');
        });
    }

    public function down(): void
    {
        Schema::table('yandex_tracking', function (Blueprint $table) {
            // Восстанавливаем удалённые столбцы
            $table->string('yandexuid')->nullable();
            $table->string('yandex_login')->nullable();

            // Удаляем новые столбцы
            $table->dropColumn([
                'utm_source',
                'utm_medium',
                'utm_campaign',
                'utm_term',
                'utm_content',
                'interests',
                'city',
                'lawyer',
                'is_engaged',
                'phone_click_at',
                'article_id',
                'question_id',
                'lawyer_id',
                'usluga_data',
            ]);

            // Удаляем индексы
            DB::statement('DROP INDEX IF EXISTS utm_source');
            DB::statement('DROP INDEX IF EXISTS city');
            DB::statement('DROP INDEX IF EXISTS interests');
            DB::statement('DROP INDEX IF EXISTS article_id');
            DB::statement('DROP INDEX IF EXISTS lawyer_id');
        });
    }
};
