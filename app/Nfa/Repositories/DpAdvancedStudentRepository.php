<?php

namespace App\Nfa\Repositories;

use App\DpStudent;
use App\DpSubject;
use App\Http\Controllers\Admin\DpAdvancedStudentController;
use App\User;
use Auth;
use Carbon\Carbon;

class DpAdvancedStudentRepository implements DpAdvancedStudentRepositoryInterface
{
    protected function getDataQuery()
    {
        $q = DpStudent::with('author', 'county')->where('advance', true);

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

    protected function getFilteredDataQuery($user = null)
    {
        ini_set('memory_limit', '2048M');
        set_time_limit(300);
        //證書有效年份
        $valid_year = DpAdvancedStudentController::DP_STUDENT_VALID_YEAR;
        //效期警示月份
        $warning_month = DpAdvancedStudentController::DP_STUDENT_WARNING_MONTH;
        $sql = "dp_students.*,DATE_SUB(NOW(), INTERVAL {$valid_year} YEAR) AS expire_date,DATE_ADD(DATE_SUB(NOW(), INTERVAL {$valid_year} YEAR),INTERVAL {$warning_month} MONTH) AS soon_expire_date," .
            "IF(date_first_finish IS NULL,IF(pass=1,'合格','受訓中')," .
            "(IF(date_first_finish IS NOT NULL AND date_first_finish<=DATE_ADD(DATE_SUB(NOW(), INTERVAL {$valid_year} YEAR),INTERVAL {$warning_month} MONTH) AND date_first_finish >= DATE_SUB(NOW(), INTERVAL {$valid_year} YEAR),'即將逾期'," .
            "IF(date_first_finish IS NOT NULL AND date_first_finish<DATE_ADD(DATE_SUB(NOW(), INTERVAL {$valid_year} YEAR),INTERVAL {$warning_month} MONTH),'逾期',IF(pass=1,'合格','受訓中'))))) AS expire_state";

        $q = DpStudent::selectRaw($sql)->with('author', 'county', 'dpAdvanceSubjects')->where('advance', true);

        /** @var User $user */
        $user = $user == null ? Auth::user() : $user;
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
        if (request()->has('checkbox_willingness')) {
            $q->where('willingness', request()->get('checkbox_willingness'));
        }

        if ($training = request()->get('training')) {
            $q->where('plan', $training);
        }

        if ($gender = request()->get('gender')) {
            $q->where('gender', $gender);
        }

        if ($countyId = request()->get('county_id')) {
            $q->where('county_id', $countyId);
        }
        $pass = request()->get('pass', null);
        if ($pass !== null && $pass == '2') {
            //即將逾期
            $date_first_finish = Carbon::now()->subYears($valid_year)->addMonth($warning_month)->isoFormat('Y-M-D');
            $date_last_finish = Carbon::now()->subYears($valid_year)->isoFormat('Y-M-D');
            $q->whereNotNull('date_first_finish')
                ->where('date_first_finish', '<=', $date_first_finish)
                ->where('date_first_finish', '>=', $date_last_finish);
        } elseif ($pass !== null && $pass == '3') {
            //逾期
            $date_first_finish = Carbon::now()->subYears($valid_year)->isoFormat('Y-M-D');
            $q->whereNotNull('date_first_finish')
                ->where('date_first_finish', '<', $date_first_finish);
        } elseif ($pass !== null) {
            $q->where('pass', $pass);
        }

        return $q->orderBy('certificate', 'DESC')->latest('created_at');
    }

    public function getFilteredData()
    {
        return $this->getFilteredDataQuery()->paginate(20);
    }

    public function getAllFilteredData($user = null)
    {
        return $this->getFilteredDataQuery($user)->get();
    }

    public function getPassCount()
    {
        return $this->getFilteredDataQuery()->select(['pass', \DB::raw('count(*) as count')])
            ->groupBy('pass')->pluck('count', 'pass');
    }

    public function getDashboardData()
    {
        return DpStudent::with('author')->where('active', true)->where('advance', true)->latest('created_at')->simplePaginate(5);
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
        $data['advance'] = true;
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
