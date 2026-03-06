<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHomePageCarouselImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home_page_carousel_images', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('position')->default(0)->comment('排序位置');
            $table->string('title')->comment('標題');
            $table->text('url')->nullable()->comment('連結網址');
            $table->boolean('active')->default(false)->comment('上線');
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
        Schema::dropIfExists('home_page_carousel_images');
    }
}
