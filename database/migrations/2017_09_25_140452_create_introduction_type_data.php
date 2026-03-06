<?php

use Illuminate\Database\Migrations\Migration;

class CreateIntroductionTypeData extends Migration
{
    private $introductionTypeNames = [
        '細說深耕',
        '深耕心曲',
        '深耕亮點',
        '深耕集錦',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach ($this->introductionTypeNames as $introductionTypeName) {
            \App\IntroductionType::create([
                'name' => $introductionTypeName,
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
        \App\IntroductionType::whereIn('name', $this->introductionTypeNames)->delete();
    }
}
