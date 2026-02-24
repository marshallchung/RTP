<?php

namespace App\Nfa\Repositories;

use App\DpStudent;
use App\User;
use Auth;

class DpStudentRepository implements DpStudentRepositoryInterface
{
    protected function getDataQuery()
    {
        $q = DpStudent::with('author', 'county')->where('advance', false);

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

        return $q->orderBy('certificate', 'DESC')->latest('created_at');
    }

    public function getData()
    {
        return $this->getDataQuery()->paginate(20);
    }

    public function getAllData()
    {
        return $this->getDataQuery()->get();
    }

    protected function getFilteredDataQuery()
    {
        ini_set('memory_limit', '2048M');
        set_time_limit(300);
        $q = DpStudent::with('author', 'county')->where('advance', false);

        /** @var User $user */
        $user = Auth::user();
        if ($user->type !== null && !in_array($user->type, ['civil', 'dp-training'])) {
            if ($user->county_id === null) {
                $county_id = $user->id;
            } else {
                $county_id = $user->county_id;
            }

            $q->where('county_id', $county_id);
        }
        if ($user->type !== null && in_array($user->type, ['dp-training'])) {
            $q->where('unit_first_course', $user->name);
        }

        $filterableFields = ['name', 'unit_first_course', 'certificate', 'date_first_finish'];
        foreach ($filterableFields as $filterableField) {
            $searchKeyword = request()->get($filterableField);
            if ($searchKeyword) {
                $q->where($filterableField, 'like', "%{$searchKeyword}%");
            }
        }

        if ($gender = request()->get('gender')) {
            $q->where('gender', $gender);
        }

        if ($countyId = request()->get('county_id')) {
            $q->where('county_id', $countyId);
        }

        if (request()->has('checkbox_willingness')) {
            $q->where('willingness', request()->get('checkbox_willingness'));
        }

        if ($startAt = request()->get('start_at')) {
            $q->where('date_first_finish', '>=', $startAt);
        }

        if ($endAt = request()->get('end_at')) {
            $endAt = date("Y-m-d", strtotime($endAt . '+1 day'));
            $q->where('date_first_finish', '<=', $endAt);
        }

        if (request()->has('pass')) {
            $pass = request()->get('pass');
            if ($pass !== null) {
                $q->where('pass', $pass);
            }
        }

        return $q->orderBy('certificate', 'DESC')->latest('created_at');
    }

    public function getFilteredData()
    {
        return $this->getFilteredDataQuery()->paginate(20);
    }

    public function getAllFilteredData()
    {
        return $this->getFilteredDataQuery()->get();
    }

    public function getPassCount()
    {
        return $this->getFilteredDataQuery()->select(['pass', \DB::raw('count(*) as count')])
            ->groupBy('pass')->pluck('count', 'pass');
    }

    public function getDashboardData()
    {
        return DpStudent::with('author')->where('active', true)->where('advance', false)->latest('created_at')->simplePaginate(5);
    }

    /**
     * @param $data
     * @return DpStudent
     * @throws \Exception
     */
    public function postData($data)
    {
        $user = Auth::user();
        $data['TID'] = strtoupper($data['TID']);
        $data['advance'] = false;
        $data['username'] = $data['TID'];
        $data['password'] = bcrypt($data['birth']); //md5($data['mobile']);

        if (empty($data['date_first_finish'])) {
            $data['date_first_finish'] = null;
        }

        if (empty($data['date_second_finish'])) {
            $data['date_second_finish'] = null;
        }

        /** @var DpStudent $dpStudent */
        $dpStudent = $user->dpStudents()->create($data);

        foreach ($data['dp_subjects'] ?? [] as $subjectId) {
            $dpStudent->dpStudentSubjects()->updateOrCreate(['dp_subject_id' => $subjectId]);
        }

        return $dpStudent;
    }
}
