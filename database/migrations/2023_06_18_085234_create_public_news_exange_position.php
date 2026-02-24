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
        $procedure = "DROP PROCEDURE IF EXISTS `public_news_exange_position`;
            CREATE PROCEDURE `public_news_exange_position` (IN fromId int,IN fromPosition int,IN toPosition int)
            BEGIN
                IF fromPosition > toPosition THEN
                    UPDATE public_news SET position = position+1 WHERE position < fromPosition AND position >= toPosition;
                    UPDATE public_news SET position = toPosition WHERE id = fromId;
                ELSE
                    UPDATE public_news SET position = position-1 WHERE position > fromPosition AND position <= toPosition;
                    UPDATE public_news SET position = toPosition WHERE id = fromId;
                END IF;
                CALL public_news_reset_position();
            END;";

        DB::unprepared($procedure);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('public_news_exange_position');
    }
};
