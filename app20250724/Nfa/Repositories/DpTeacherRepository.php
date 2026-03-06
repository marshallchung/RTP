<?php

namespace App\Nfa\Repositories;

use App\DpSubject;
use App\DpTeacher;
use Auth;
use Illuminate\Database\Eloquent\Builder;

class DpTeacherRepository implements DpTeacherRepositoryInterface
{
    protected function getDataQuery()
    {
        return DpTeacher::with('author')->latest('created_at');
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
        $dpTeacherCount = DpTeacher::with('author', 'dpTeacherSubjects.dpSubject');

        $filterableFields = ['name', 'location'];
        foreach ($filterableFields as $filterableField) {
            $searchKeyword = request()->get($filterableField);
            if ($searchKeyword) {
                $dpTeacherCount->where($filterableField, 'like', "%{$searchKeyword}%");
            }
        }
        if (request()->has('expired') && request('expired') === '1') {
            $dpTeacherCount->whereHas('dpTeacherSubjects.DpSubject', function ($q) {
                $time = strtotime("-3 year", time());
                $date = date("Y-m-d", $time);
                $q->where('type', '=', '種子師資')
                    ->where('pass_date', '<', $date);
            });
        } elseif (request()->has('expired') && request('expired') === '0') {
            $dpTeacherCount->whereHas('dpTeacherSubjects.DpSubject', function ($q) {
                $time = strtotime("-3 year", time());
                $date = date("Y-m-d", $time);
                $q->where('type', '=', '種子師資')
                    ->where('pass_date', '>=', $date);
            });
        }

        if ($filteredDpSubject = DpSubject::find(request('dp_subject'))) {
            $dpTeacherCount->whereHas('dpTeacherSubjects.DpSubject', function ($q) use ($filteredDpSubject) {
                /** @var Builder|DpSubject $q */
                $q->where('id', $filteredDpSubject->id);
            });
        }

        /*return $q->latest('created_at');*/
        return $dpTeacherCount->orderBy('created_at')->orderBy('user_id');
    }

    public function getFilteredData()
    {
        return $this->getFilteredDataQuery()->paginate(20);
    }

    public function getAllFilteredData()
    {
        return $this->getFilteredDataQuery()->get();
    }


    public function getDashboardData()
    {
        return DpTeacher::with('author')->where('active', true)->latest('created_at')->simplePaginate(5);
    }

    /**
     * @param $data
     * @return DpTeacher
     * @throws \Exception
     */
    public function postData($data)
    {
        $user = Auth::user();

        /** @var DpTeacher $dpTeacher */
        $dpTeacher = $user->dpTeachers()->create($data);

        foreach ($data['dp_subjects'] as $subjectId => $teacherType) {
            if ($teacherType) {
                $passDate = ($teacherType == '種子師資') ? $data['pass_date'][$subjectId] : null;
                $dpTeacher->dpTeacherSubjects()->where('dp_subject_id', $subjectId)->create([
                    'dp_subject_id' => $subjectId,
                    'type'          => $teacherType,
                    'pass_date'     => $passDate,
                ]);
            }
        }

        return $dpTeacher;
    }
}
