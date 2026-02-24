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
        $procedure = "ALTER TABLE dc_users_password_history DROP FOREIGN KEY dc_users_password_history_dc_users_id_foreign;";
        DB::unprepared($procedure);
        $procedure = "ALTER TABLE dp_students_password_history DROP FOREIGN KEY dp_students_password_history_dp_students_id_foreign;";
        DB::unprepared($procedure);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
