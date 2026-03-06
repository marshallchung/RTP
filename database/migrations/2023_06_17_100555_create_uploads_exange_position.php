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
        $procedure = "DROP PROCEDURE IF EXISTS `uploads_exange_position`;
            CREATE PROCEDURE `uploads_exange_position` (IN fromId int,IN fromPosition int,IN toPosition int)
            BEGIN
                IF fromPosition > toPosition THEN
                    UPDATE uploads SET position = position+1 WHERE position < fromPosition AND position >= toPosition;
                    UPDATE uploads SET position = toPosition WHERE id = fromId;
                ELSE
                    UPDATE uploads SET position = position-1 WHERE position > fromPosition AND position <= toPosition;
                    UPDATE uploads SET position = toPosition WHERE id = fromId;
                END IF;
                CALL uploads_reset_position();
            END;";

        DB::unprepared($procedure);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uploads_exange_position');
    }
};
