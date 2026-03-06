<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $procedure = "DROP PROCEDURE IF EXISTS `public_news_reset_position`;
            CREATE PROCEDURE `public_news_reset_position` ()
            BEGIN
            SET @a = 0;
	        UPDATE public_news SET position = @a:=@a+1 ORDER BY position,id DESC;
            END;";

        DB::unprepared($procedure);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('public_news_reset_position_procedure');
    }
};
