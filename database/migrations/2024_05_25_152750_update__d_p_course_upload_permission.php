<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Permission;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $permission = [
            'name' => 'DP-course-plan-document-manage',
            'display_name' => 'Manage DP course document',
            'description' => 'CRUD DP course document',
        ];
        $data = Permission::create($permission);
        DB::table('permission_role')->insert(['permission_id' => $data->id, 'role_id' => 1]);
        DB::table('permission_role')->insert(['permission_id' => $data->id, 'role_id' => 2]);
        DB::table('permission_role')->insert(['permission_id' => $data->id, 'role_id' => 6]);
        DB::table('permission_role')->insert(['permission_id' => $data->id, 'role_id' => 7]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
