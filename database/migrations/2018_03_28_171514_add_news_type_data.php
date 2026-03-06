<?php

use Illuminate\Database\Migrations\Migration;

class AddNewsTypeData extends Migration
{
    private $newsTypeNames = [
        '近期重點工作',
        '計畫規範',
        '相關資料',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach ($this->newsTypeNames as $typeName) {
            \App\NewsType::create([
                'name' => $typeName,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     * @throws Exception
     */
    public function down()
    {
        foreach ($this->newsTypeNames as $typeName) {
            \App\NewsType::where('name', $typeName)->delete();
        }
    }
}
