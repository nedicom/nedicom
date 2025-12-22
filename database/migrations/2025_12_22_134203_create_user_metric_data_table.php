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
        Schema::connection('mysql')->table('users', function (Blueprint $table) {
            $table->string('_ym_uid')->nullable()->after('email');
            $table->string('_ga')->nullable()->after('_ym_uid');
            $table->string('_nedicoo')->nullable()->after('_ga');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql')->table('users', function (Blueprint $table) {
            $table->dropColumn('_ym_uid');
            $table->dropColumn('_ga');
            $table->dropColumn('_nedicoo');
        });
    }
};
