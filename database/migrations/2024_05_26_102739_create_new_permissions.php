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
        $permission_data = [
            [
                'name' => 'admin-permissions',
                'display_name' => 'Admin Operations',
                'description' => '系統管理員權限',
                'role' => 1,
            ],
            [
                'name' => 'NFA-permissions',
                'display_name' => 'NFA Operations',
                'description' => '消防署權限',
                'role' => 2,
            ],
            [
                'name' => 'County-permissions',
                'display_name' => 'County Operations',
                'description' => '縣市權限',
                'role' => 4,
            ],
            [
                'name' => 'DEP-permissions',
                'display_name' => 'DEP Operations',
                'description' => '社團法人臺灣防災教育訓練學會權限',
                'role' => 6,
            ],
            [
                'name' => 'DP-Training-permissions',
                'display_name' => 'DP Training Operations',
                'description' => '防災士培訓機構權限',
                'role' => 7,
            ],
        ];
        foreach ($permission_data as $permission) {
            $role = $permission['role'];
            unset($permission['role']);
            $data = Permission::create($permission);
            DB::table('permission_role')->insert(['permission_id' => $data->id, 'role_id' => $role]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
