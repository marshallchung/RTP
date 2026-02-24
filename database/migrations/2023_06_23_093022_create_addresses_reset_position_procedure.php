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
        $procedure = "DROP PROCEDURE IF EXISTS `addresses_reset_position`;
            CREATE PROCEDURE `addresses_reset_position` ()
            BEGIN
                DECLARE bDone INT;
                DECLARE countyId INT;
                DECLARE curs CURSOR FOR SELECT county_id FROM addresses GROUP BY county_id;
                DECLARE CONTINUE HANDLER FOR NOT FOUND SET bDone = 1;
                OPEN curs;
                SET bDone = 0;
                REPEAT
                    FETCH curs INTO countyId;
                    SET @a = 0;
                    UPDATE addresses SET position = @a:=@a+1 WHERE county_id=countyId ORDER BY position,id DESC;
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
        Schema::dropIfExists('addresses_reset_position');
    }
};
