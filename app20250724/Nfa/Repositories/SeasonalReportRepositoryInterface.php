<?php

namespace App\Nfa\Repositories;

interface SeasonalReportRepositoryInterface
{
    public function getPublicDateByYear($year);

    public function getPublicDates();

    public function updatePublicDate($id, $year, $date, $expire_soon_date, $expire_date);

    public function findOrCreateSeasonalReportTopic($reportId, $topicId);

    public function generateUsersCurrentSeasonalReport($year);

    public function generateSeasonalReportByUserIdAndYear($id, $year);

    public function generateSeasonalReportByUserId($id);

    public function generateSeasonalReportByUserIdAndTopic($id, $topic);

    public function getSeasonalReportFilesByUserIdAndYear($id, $year);

    public function findUsersCurrentTopicSeasonalReportByTopicId($id);

    public function sortTopicsIntoCategories($topics, $year, $filter = true);
}
