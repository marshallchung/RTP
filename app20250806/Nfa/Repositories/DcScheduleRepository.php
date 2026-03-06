<?php

namespace App\Nfa\Repositories;

use App\DcSchedule;
use Auth;

class DcScheduleRepository implements DcScheduleRepositoryInterface
{
    public function getSchedules()
    {
        return DcSchedule::with('author')->latest('created_at')->paginate(20);
    }

    public function getDashboardSchedules()
    {
        return DcSchedule::with('author')->where('active', true)->latest('created_at')->simplePaginate(5);
    }

    public function postSchedule($data)
    {
        $user = Auth::user();

        return $user->dcSchedules()->create($data);
    }
}
