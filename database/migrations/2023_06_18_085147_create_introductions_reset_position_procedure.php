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
        $procedure = "DROP PROCEDURE IF EXISTS `introductions_reset_position`;
            CREATE PROCEDURE `introductions_reset_position` ()
            BEGIN
                DECLARE bDone INT;
                DECLARE introductionTypeId INT;
                DECLARE curs CURSOR FOR SELECT introduction_type_id FROM introductions GROUP BY introduction_type_id;
                DECLARE CONTINUE HANDLER FOR NOT FOUND SET bDone = 1;
                OPEN curs;
                SET bDone = 0;
                REPEAT
                    FETCH curs INTO introductionTypeId;
                    SET @a = 0;
                    UPDATE introductions SET position = @a:=@a+1 WHERE introduction_type_id=introductionTypeId ORDER BY position,id DESC;
                UNTIL bDone END REPEAT;
                CLOSE curs;
            END;";
        DB::unprepared($procedure);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('introductions_reset_position');
    }
};
