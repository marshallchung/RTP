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
        $procedure = "DROP PROCEDURE IF EXISTS `dp_training_institutions_exange_position`;
            CREATE PROCEDURE `dp_training_institutions_exange_position` (IN fromId int,IN fromPosition int,IN toPosition int)
            BEGIN
                IF fromPosition > toPosition THEN
                    UPDATE dp_training_institutions SET position = position+1 WHERE position < fromPosition AND position >= toPosition;
                    UPDATE dp_training_institutions SET position = toPosition WHERE id = fromId;
                ELSE
                    UPDATE dp_training_institutions SET position = position-1 WHERE position > fromPosition AND position <= toPosition;
                    UPDATE dp_training_institutions SET position = toPosition WHERE id = fromId;
                END IF;
                CALL dp_training_institutions_reset_position();
            END;";

        DB::unprepared($procedure);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dp_training_institutions_exange_position');
    }
};
