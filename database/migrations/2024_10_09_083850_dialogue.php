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
        Schema::table('dialogues', function (Blueprint $table) {
            $table->unsignedBigInteger('lawyer_id')->after('user_id')->nullable();
            $table->string('phone_num')->nullable();
            $table->boolean('is_success')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dialogues', function (Blueprint $table) {
            $table->dropColumn('lawyer_id');
            $table->dropColumn('phone_num');
            $table->dropColumn('is_success');
        });
    }
};
