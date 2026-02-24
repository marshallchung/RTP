<?php

namespace App\Nfa\Repositories;

use App\DpCourse;
use App\DpScore;
use App\DpStudent;
use App\DpWaiver;
use App\User;
use Auth;
use Illuminate\Database\Eloquent\Builder;

class DpWaiverRepository implements DpWaiverRepositoryInterface
{
    public function getCourses()
    {
        $q = DpCourse::with('author', 'county')
            ->orderBy('county_id', 'asc');

        if ($county_id = $this->getPermmitedCountyId()) {
            $q->where('county_id', $county_id);
        }

        $rawData = $q->get();
        $parsed = [];
        foreach ($rawData as $data) {
            $parsed[$data->id] = $data->county->name . ' - ' .
                $data->name .
                ' (' . date('Y-m-d', strtotime($data->date_from)) . '-' . date('Y-m-d', strtotime($data->date_to)) . ')';
        }

        return $parsed;
    }

    private function getPermmitedCountyId()
    {
        /** @var User $user */
        $user = Auth::user();
        if ($user->type !== null) {
            if ($user->county_id === null) {
                $county_id = $user->id;
            } else {
                $county_id = $user->user;
            }

            return $county_id;
        } else {
            return null;
        }
    }

    public function getWaivers($course_id)
    {
        return DpWaiver::with('dpScore.dpStudent.county', 'dpScore.author')
            ->whereHas('dpScore', function ($query) use ($course_id) {
                /** @var Builder|DpScore $query */
                $query->where('dp_course_id', $course_id);
            })->get();
    }

    public function getStudent($TID)
    {
        $q = DpStudent::with('dpWaivers.dpScore.author', 'dpWaivers.dpScore.dpCourse', 'dpWaivers.files', 'county')
            ->where('TID', $TID);

        if ($county_id = $this->getPermmitedCountyId()) {
            $q->where('county_id', $county_id);
        }

        return $q->first();
    }

    public function getDashboardData()
    {
        return DpCourse::with('author', 'dp_students')
            ->where('active', true)
            ->latest('created_at')
            ->simplePaginate(5);
    }

    public function postData($data)
    {
        $dpWaivers = [];

        foreach ($data['dp_course_id'] as $key => $item_course_id) {
            if (strlen(str_replace(' ', '', $data['waiverName'][$key])) === 0) {
                continue;
            }

            $dpScore = DpScore::create([
                'dp_course_id'  => $item_course_id,
                'dp_student_id' => $data['dp_student_id'],
                'user_id'       => Auth::user()->id,
                'score'         => -1,
            ]);

            $waiver = [
                'name' => $data['waiverName'][$key],
            ];
            $dpWaivers[$key] = $dpScore->dpWaiver()->create($waiver);
        }

        return $dpWaivers;
    }
}
