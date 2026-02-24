<?php

namespace App\Nfa\Repositories;

interface DcScheduleRepositoryInterface
{
    public function getSchedules();

    public function getDashboardSchedules();

    public function postSchedule($data);
}
