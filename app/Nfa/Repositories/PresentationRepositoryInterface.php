<?php

namespace App\Nfa\Repositories;

interface PresentationRepositoryInterface
{
    public function getPublicDateByYear($year);

    public function getPublicDates();

    public function updatePublicDate($id, $year, $date, $expire_soon_date, $expire_date);

    public function findOrCreateReportTopic($reportId, $topicId);

    public function findOrCreateCentralReportTopic($reportId, $topicId);

    public function generateUsersCurrentReport($year);

    public function generateCentralReport($year);

    public function generateReportByUserIdAndYear($id, $year);

    public function generateReportByUserId($id);

    public function generateReportByUserIdAndTopic($id, $topic);

    public function getReportFilesByUserIdAndYear($id, $year);

    public function findUsersCurrentTopicReportByTopicId($id);
}
