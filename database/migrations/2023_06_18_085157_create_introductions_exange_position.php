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
        $procedure = "DROP PROCEDURE IF EXISTS `introductions_exange_position`;
            CREATE PROCEDURE `introductions_exange_position` (IN introductionTypeId int,IN fromId int,IN fromPosition int,IN toPosition int)
            BEGIN
                IF fromPosition > toPosition THEN
                    UPDATE introductions SET position = position+1 WHERE position < fromPosition AND position >= toPosition AND introduction_type_id = introductionTypeId;
                    UPDATE introductions SET position = toPosition WHERE id = fromId;
                ELSE
                    UPDATE introductions SET position = position-1 WHERE position > fromPosition AND position <= toPosition AND introduction_type_id = introductionTypeId;
                    UPDATE introductions SET position = toPosition WHERE id = fromId;
                END IF;
                CALL introductions_reset_position();
            END;";

        DB::unprepared($procedure);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('introductions_exange_position');
    }
};
