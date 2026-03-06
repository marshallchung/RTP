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
        $procedure = "DROP PROCEDURE IF EXISTS `home_page_carousel_images_exange_position`;
            CREATE PROCEDURE `home_page_carousel_images_exange_position` (IN fromId int,IN fromPosition int,IN toPosition int)
            BEGIN
                IF fromPosition > toPosition THEN
                    UPDATE home_page_carousel_images SET position = position+1 WHERE position < fromPosition AND position >= toPosition;
                    UPDATE home_page_carousel_images SET position = toPosition WHERE id = fromId;
                ELSE
                    UPDATE home_page_carousel_images SET position = position-1 WHERE position > fromPosition AND position <= toPosition;
                    UPDATE home_page_carousel_images SET position = toPosition WHERE id = fromId;
                END IF;
                CALL home_page_carousel_images_reset_position();
            END;";

        DB::unprepared($procedure);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_page_carousel_images_exange_position');
    }
};
