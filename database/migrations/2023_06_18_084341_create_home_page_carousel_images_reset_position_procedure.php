<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $procedure = "DROP PROCEDURE IF EXISTS `home_page_carousel_images_reset_position`;
            CREATE PROCEDURE `home_page_carousel_images_reset_position` ()
            BEGIN
            SET @a = 0;
	        UPDATE home_page_carousel_images SET position = @a:=@a+1 ORDER BY position,id;
            END;";

        DB::unprepared($procedure);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_page_carousel_images_reset_position');
    }
};
