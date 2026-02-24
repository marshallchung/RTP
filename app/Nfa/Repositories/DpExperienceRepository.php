<?php

namespace App\Nfa\Repositories;

use App\DpCourse;
use App\DpStudent;
use App\User;
use Auth;

class DpExperienceRepository implements DpExperienceRepositoryInterface
{
    public function getCourses()
    {
        $rawData = DpCourse::with('author', 'county', 'files')
            ->orderBy('county_id', 'asc')
            ->get();
        $parsed = [];
        foreach ($rawData as $data) {
            $parsed[$data->id] = $data->county->name . ' - ' .
                $data->name .
                ' (' . date('Y-m-d', strtotime($data->date_from)) . '-' . date('Y-m-d', strtotime($data->date_to)) . ')';
        }

        return $parsed;
    }

    public function getStudent($TID)
    {
        $q = DpStudent::with('dpExperiences.files', 'county')
            ->where('TID', $TID);

        if ($county_id = $this->getPermmitedCountyId()) {
            $q->where('county_id', $county_id);
        }

        return $q->first();
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

    public function getDashboardData()
    {
        return DpCourse::with('author', 'dpStudents')
            ->where('active', true)
            ->latest('created_at')
            ->simplePaginate(5);
    }

    public function postData($data)
    {
        $dpExperiences = [];

        foreach ($data['name'] as $key => $item_course_id) {
            if (strlen(str_replace(' ', '', $item_course_id)) === 0) {
                continue;
            }

            $dpStudent = DpStudent::find($data['dp_student_id']);
            $experience = [
                'name'          => $data['name'][$key],
                'date'          => $data['date'][$key],
                'work_hours'          => $data['work_hours'][$key],
                'unit'          => $data['unit'][$key],
                'document_code' => $data['document_code'][$key],
            ];
            if (isset($data['id'])) {
                $dpStudent->dpExperiences()->where('id', '=', $data['id'][$key])->update($experience);
                $dpExperiences[$key] = $dpStudent->dpExperiences()->find($data['id'][$key]);
            } else {
                $dpExperiences[$key] = $dpStudent->dpExperiences()->create($experience);
            }
        }

        return $dpExperiences;
    }
}
