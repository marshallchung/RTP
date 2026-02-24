<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAddressIdInDpTrainingInstitutions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('dp_training_institutions', 'addressId')) {
            Schema::table('dp_training_institutions', function (Blueprint $table) {
                $table->string('addressId')->nullable()->comment('地址識別碼')->after('address');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('dp_training_institutions', 'addressId')) {
            Schema::table('dp_training_institutions', function (Blueprint $table) {
                $table->dropColumn('addressId');
            });
        }
    }
}
