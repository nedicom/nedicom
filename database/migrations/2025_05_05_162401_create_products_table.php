<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
        Schema::create('Pension', function (Blueprint $table) {
            $table->id();            
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade'); // Удаление записей при удалении пользователя
            $table->timestamps();
            $table->smallInteger('pay')->unsigned();
            $table->smallInteger('gender')->unsigned();
            $table->decimal('sk', 3, 2);
            $table->smallInteger('zp')->unsigned();
            $table->smallInteger('szp')->unsigned();
            $table->smallInteger('punkt')->unsigned();
            $table->smallInteger('pns')->unsigned();
            $table->smallInteger('t')->unsigned();
            $table->smallInteger('kv')->unsigned();
            $table->integer('pk')->unsigned();
            $table->integer('pk1')->unsigned();
            $table->integer('pk2')->unsigned();
            $table->decimal('spk', 5, 2);
            $table->decimal('ipkn', 5, 2);
            $table->decimal('ipks', 8, 4);
            $table->decimal('ipk', 8, 4);
            $table->decimal('spst', 8, 2);
            $table->decimal('propzp', 7, 5);
            $table->decimal('rp', 7, 2);
            $table->decimal('sv', 8, 2);
            $table->decimal('p', 7, 2);    
            $table->decimal('nadb', 7, 2);       
            $table->decimal('pension', 8, 2);
        });
        */

        Schema::table('users', function (Blueprint $table) {
            $table->string('yandex_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Pension');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('yandex_id');
        });
    }
};
