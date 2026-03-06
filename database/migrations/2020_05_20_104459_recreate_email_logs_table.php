<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class RecreateEmailLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('recipient_email')->nullable()->comment('收件者信箱');
            $table->string('subject')->nullable()->comment('標題');
            $table->longText('content')->nullable()->comment('內文');
            $table->timestamp('sent_at')->nullable()->comment('寄信時間');
            $table->unsignedInteger('failed_time')->default(0)->comment('失敗次數');
            $table->unsignedInteger('recipient_id')->nullable()->comment('收件者ID');
            $table->string('recipient_type')->nullable()->comment('收件者類型');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_logs');
    }
}
