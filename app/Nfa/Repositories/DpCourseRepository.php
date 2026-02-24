<?php

namespace App\Nfa\Repositories;

use App\DpCourse;
use App\User;
use Auth;

class DpCourseRepository implements DpCourseRepositoryInterface
{
    public function getData()
    {
        $q = DpCourse::with(['author', 'county']);

        /** @var User $user */
        $user = Auth::user();
        if ($user->type !== null && !in_array($user->type, ['civil', 'dp-training'])) { /*管理員及縣市*/
            if ($user->county_id === null) {
                $county_id = $user->id;
            } else {
                $county_id = $user->user;
            }

            $q->where(function ($q) use ($user, $county_id) {
                $q->where('county_id', $county_id)
                    ->orWhere('organizer', $user->name);
            });
        }
        if ($user->type !== null && in_array($user->type, ['dp-training'])) {  /*培訓機構*/
            $q->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->orWhere('organizer', $user->name);
            });
        }

        return $q->latest('created_at')->paginate(20);
    }

    public function getDashboardData()
    {
        return DpCourse::with('author')->where('active', true)->latest('created_at')->simplePaginate(5);
    }

    public function postData($data)
    {
        $user = Auth::user();

        return $user->dpCourses()->create($data);
    }
}
