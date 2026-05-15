<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement('ALTER TABLE reviews MODIFY COLUMN description TEXT NULL');
    }

    public function down()
    {
        DB::statement("ALTER TABLE reviews MODIFY COLUMN description VARCHAR(255) NULL DEFAULT 'Стандартный отзыв'");
    }
};
