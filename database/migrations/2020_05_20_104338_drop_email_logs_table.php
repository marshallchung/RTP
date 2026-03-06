<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class DropEmailLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('email_logs');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('email_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('subject')->comment('信件標題');
            $table->unsignedInteger('recipient_id')->nullable()->comment('收件者ID');
            $table->string('recipient_type')->nullable()->comment('收件者類型');
            $table->string('blade_name')->nullable()->comment('樣板名稱');
            $table->timestamp('sent_at')->nullable()->comment('寄信時間');

            $table->timestamps();
        });
    }
}
