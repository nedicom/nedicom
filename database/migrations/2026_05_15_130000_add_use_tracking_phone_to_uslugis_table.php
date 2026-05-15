<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('uslugis', function (Blueprint $table) {
            $table->boolean('use_tracking_phone')->default(false)->after('phone');
        });
    }

    public function down(): void
    {
        Schema::table('uslugis', function (Blueprint $table) {
            $table->dropColumn('use_tracking_phone');
        });
    }
};
