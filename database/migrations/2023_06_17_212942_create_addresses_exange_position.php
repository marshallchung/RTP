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
        $procedure = "DROP PROCEDURE IF EXISTS `addresses_exange_position`;
            CREATE PROCEDURE `addresses_exange_position` (IN countyId int,IN fromId int,IN fromPosition int,IN toPosition int)
            BEGIN
                IF fromPosition > toPosition THEN
                    UPDATE addresses SET position = position+1 WHERE position < fromPosition AND position >= toPosition AND county_id = countyId;
                    UPDATE addresses SET position = toPosition WHERE id = fromId;
                ELSE
                    UPDATE addresses SET position = position-1 WHERE position > fromPosition AND position <= toPosition AND county_id = countyId;
                    UPDATE addresses SET position = toPosition WHERE id = fromId;
                END IF;
                CALL addresses_reset_position();
            END;";

        DB::unprepared($procedure);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses_exange_position');
    }
};
