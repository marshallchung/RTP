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
        $procedure = "DROP PROCEDURE IF EXISTS `news_exange_position`;
            CREATE PROCEDURE `news_exange_position` (IN fromId int,IN fromPosition int,IN toPosition int)
            BEGIN
                IF fromPosition > toPosition THEN
                    UPDATE news SET position = position+1 WHERE position < fromPosition AND position >= toPosition;
                    UPDATE news SET position = toPosition WHERE id = fromId;
                ELSE
                    UPDATE news SET position = position-1 WHERE position > fromPosition AND position <= toPosition;
                    UPDATE news SET position = toPosition WHERE id = fromId;
                END IF;
                CALL news_reset_position();
            END;";

        DB::unprepared($procedure);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_exange_position');
    }
};
