<?php

use Illuminate\Database\Migrations\Migration;

class FixRepublicEraYearInDpStudents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\DpStudent::where('birth', '<=', '10000000')->chunk(100, function ($dpStudents) {
            /** @var \App\DpStudent $dpStudent */
            foreach ($dpStudents as $dpStudent) {
                $birth = new Carbon\Carbon($dpStudent->birth);
                if ($birth->lt(\Carbon\Carbon::createFromDate(1000, 1, 1))) {
                    $birth->addYear(1911);
                    $dpStudent->birth = $birth->format('Ymd');
                    $dpStudent->save();
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
