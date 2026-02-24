<?php

use Illuminate\Database\Migrations\Migration;

class AddDataInDpSubjects extends Migration
{
    private static $subjects = [
        1  => '防災士職責與任務、我國災防體系與運作',
        2  => '我國近年災害經驗及災害特性',
        3  => '資訊掌握、運用與社區防災計畫',
        4  => '基礎急救訓練',
        5  => '急救措施實作(含急救術科測驗)',
        6  => '個人與居家防護措施',
        7  => '個人與居家防護措施（含情境練習）',
        8  => '社區防災工作推動與運作',
        9  => '社區避難收容場所開設與運作',
        10 => '防災計畫實作與驗證',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (self::$subjects as $position => $subject) {
            \App\DpSubject::create([
                'name'     => $subject,
                'position' => $position,
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
        \App\DpSubject::whereIn('name', self::$subjects)->delete();
    }
}
