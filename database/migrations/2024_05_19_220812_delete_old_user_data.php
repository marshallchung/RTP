<?php

use App\DcUser;
use App\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Laratrust\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('level');
            $table->dropColumn('class');
        });
        User::whereType('district')->delete();
        DB::table('roles')->where('id', '5')->delete();
        DB::table('role_user')->where('role_id', '5')->delete();
        DB::table('dc_users')->where('id', '>', 0)->delete();
        DB::table('dc_users_password_history')->where('id', '>', 0)->delete();
        DcUser::where('id', '>', 0)->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
