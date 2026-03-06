<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Collection;

class CleanDuplicateSeasonalReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     * @throws Exception
     */
    public function up()
    {
        $duplicatedItems = DB::table('seasonal_reports')
            ->select('user_id', 'topic_id')
            ->groupBy('user_id', 'topic_id')
            ->havingRaw('COUNT(*) > 1')->get();

        foreach ($duplicatedItems as $duplicatedItem) {
            $userId = $duplicatedItem->user_id;
            $topicId = $duplicatedItem->topic_id;
            //找出所有重複項目
            /** @var Collection|\App\SeasonalReport[] $reservedSeasonalReport */
            $seasonalReports = \App\SeasonalReport::where('user_id', $userId)
                ->where('topic_id', $topicId)->get();
            //將第一筆作為保留項目
            /** @var \App\SeasonalReport $reservedSeasonalReport */
            $reservedSeasonalReport = $seasonalReports->first();
            //將所有重複項目的檔案全部指向第一筆
            \App\File::where('post_type', 'App\SeasonalReport')
                ->whereIn('post_id', $seasonalReports->pluck('id'))->update([
                    'post_id' => $reservedSeasonalReport->id,
                ]);
            //移除重複項目，只保留第一筆
            \App\SeasonalReport::where('user_id', $userId)
                ->where('topic_id', $topicId)->where('id', '<>', $reservedSeasonalReport->id)
                ->delete();
        }
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
