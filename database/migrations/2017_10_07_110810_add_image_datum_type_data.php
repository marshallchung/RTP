<?php

use Illuminate\Database\Migrations\Migration;

class AddImageDatumTypeData extends Migration
{
    private $imageDatumTypeNames = [
        '水災',
        '土石流',
        '地震',
        '其他',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach ($this->imageDatumTypeNames as $imageDatumTypeName) {
            \App\ImageDatumType::create([
                'name' => $imageDatumTypeName,
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
        \App\ImageDatumType::whereIn('name', $this->imageDatumTypeNames)->delete();
    }
}
