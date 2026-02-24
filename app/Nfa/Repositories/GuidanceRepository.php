<?php

namespace App\Nfa\Repositories;

use App\Guidance;
use Auth;

class GuidanceRepository implements GuidanceRepositoryInterface
{
    public function getData()
    {
        if (Auth::user()->origin_role < 4) {
            return Guidance::with('author')->latest('created_at')->paginate(20);
        } else {
            return Guidance::with('author')->where('active', true)->latest('created_at')->paginate(20);
        }
    }

    public function getDashboardData()
    {
        return Guidance::with('author')->where('active', true)->latest('created_at')->simplePaginate(5);
    }

    public function postData($data)
    {
        $user = Auth::user();

        return $user->guidance()->create($data);
    }
}
