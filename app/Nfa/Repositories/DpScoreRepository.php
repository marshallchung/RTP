<?php

namespace App\Nfa\Repositories;

use App\DpCourse;
use App\DpStudent;
use App\User;
use Auth;

class DpScoreRepository implements DpScoreRepositoryInterface
{
    public function getCourses()
    {
        $q = DpCourse::with('author', 'county')
            ->orderBy('county_id', 'asc');

        /** @var User $user */
        $user = Auth::user();
        if ($user->type !== null) {
            if ($user->county_id === null) {
                $county_id = $user->id;
            } else {
                $county_id = $user->user;
            }

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

    public function getCourseStudents($id)
    {
        return DpCourse::with('dpScores.dpStudent')->where('id', $id)->first()->dpScores;
    }

    public function getDashboardData()
    {
        return DpCourse::with('author', 'dpStudents')->where('active', true)->latest('created_at')->simplePaginate(5);
    }

    public function postData($data)
    {
        $course = DpCourse::find($data['course_id']);

        $fails = [];
        foreach ($data['TID'] as $key => $item_TID) {
            if (strlen($item_TID) === 0) {
                continue;
            }

            $student = DpStudent::where('TID', $item_TID)->first();
            if ($student === null) {
                $fails[] = $item_TID;
                continue;
            }

            $course->dpStudents()->attach([$student->id => [
                'score'   => $data['score'][$key],
                'user_id' => Auth::user()->id,
            ]]);
        }

        return $fails;
    }
}
