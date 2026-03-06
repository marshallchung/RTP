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
        $procedure = "DROP PROCEDURE IF EXISTS `dc_downloads_exange_position`;
            CREATE PROCEDURE `dc_downloads_exange_position` (IN fromId int,IN fromPosition int,IN toPosition int)
            BEGIN
                IF fromPosition > toPosition THEN
                    UPDATE dc_downloads SET position = position+1 WHERE position < fromPosition AND position >= toPosition;
                    UPDATE dc_downloads SET position = toPosition WHERE id = fromId;
                ELSE
                    UPDATE dc_downloads SET position = position-1 WHERE position > fromPosition AND position <= toPosition;
                    UPDATE dc_downloads SET position = toPosition WHERE id = fromId;
                END IF;
                CALL dc_downloads_reset_position();
            END;";

        DB::unprepared($procedure);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dc_downloads_exange_position');
    }
};
