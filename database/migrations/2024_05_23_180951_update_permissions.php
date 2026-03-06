<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Permission;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $permission_data = [
            [
                'name' => 'create-sample-report-public-date',
                'display_name' => 'Create Sample Report Public Date',
                'description' => 'CRUD Sample Report public date',
            ],
            [
                'name' => 'create-plans-public-date',
                'display_name' => 'Create Plans Public Date',
                'description' => 'CRUD Plans public date',
            ],
            [
                'name' => 'create-presentation-public-date',
                'display_name' => 'Create Presentation Public Date',
                'description' => 'CRUD Presentation public date',
            ],
            [
                'name' => 'create-seasonal-report-public-date',
                'display_name' => 'Create Seasonal Report Public Date',
                'description' => 'CRUD Seasonal Report public date',
            ],
        ];
        foreach ($permission_data as $permission) {
            $data = Permission::create($permission);
            DB::table('permission_role')->insert(['permission_id' => $data->id, 'role_id' => 1]);
            DB::table('permission_role')->insert(['permission_id' => $data->id, 'role_id' => 2]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
