<?php

namespace App\Http\Controllers;

use App\DpAdvanceCourseSubject;
use App\DpCivil;
use App\DpCourse;
use App\DpDownload;
use App\DpExperience;
use App\DpStudent;
use App\DpTrainingInstitution;
use App\DpWaiver;
use App\Exports\DpCountyAdvanceStudentExport;
use App\Exports\DpCountyStudentExport;
use App\FrontIntroduction;
use App\Nfa\Traits\FileUploadTrait;
use App\User;
use Auth;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Excel;

//use Menu;

class DpController extends Controller
{
    use FileUploadTrait;

    public function intro(Request $request)
    {
        return view('dp.intro', [
            'intro' => FrontIntroduction::find(1),
        ]);
    }

    public function download(Request $request)
    {
        $page = (object) [
            'title'    => '防災士培訓',
            'subtitle' => '相關資料下載',
            'search' => '/dp/download/search'
        ];
        $categoryOption = DpDownload::sorted()->where('active', 1)->orderBy('position')->groupBy('category')->pluck('category')->toArray();

        return view('dp.panel', compact('categoryOption', 'page'));
    }

    public function search(Request $request)
    {
        $data = DpDownload::with(['author', 'files'])->where('active', 1);
        if ($category = $request->get('category')) {
            $data->where('category', '=', $category);
        }

        $data = $data->orderBy('category')->orderBy('position')->latest('created_at')->get();
        //$data = $data->items();
        return response()->json($data);
    }

    public function course(Request $request)
    {
        $advance = $request->get('advance');
        $data = DpCourse::with('author', 'county')->where('active', true)
            ->where('date_to', '>=', today()->format('Y-m-d'));
        if ($advance !== null && $advance !== '') {
            $data->where('advance', $advance);
        }
        $data = $data->latest('date_from')->paginate(20);

        return view('dp.course', compact('data'));
    }

    public function courseShow(DpCourse $data)
    {
        if ($data->advance) {
            $course_subjects = DpAdvanceCourseSubject::selectRaw('dp_subjects.name,dp_advance_course_subjects.hour,dp_advance_course_subjects.start_date')
                ->join('dp_subjects', 'dp_subjects.id', '=', 'dp_advance_course_subjects.dp_course_subject_id')
                ->where('dp_advance_course_subjects.dp_course_id', $data->id)
                ->orderBy('dp_subjects.position', 'asc')->get();
            $data['course_subjects'] = $course_subjects;
        }
        return view('dp.courseShow', compact('data'));
    }

    public function civil(Request $request)
    {
        $data = DpCivil::where('active', 1)
            ->latest('created_at')->paginate(20);

        return view('dp.civil', compact('data'));
    }

    public function civilShow(DpCivil $data)
    {
        return view('dp.civilShow', compact('data'));
    }

    public function trainingInstitution()
    {
        $data = DpTrainingInstitution::sorted()->with('county')->where('active', true)->where(function ($query) {
            /** @var Builder|DpTrainingInstitution $query */
            $query->whereNull('expired_date')->orWhere('expired_date', '>=', today()->format('Y-m-d'));
        })->get();

        return view('dp.training-institution', compact('data'));
    }

    static public function dpStudentStatistics()
    {
        // $end_day = date('Y-m-d', strtotime('last day of last month')) . " 23:59:59";
        // $end_day = date('Y-m-d H:i:s');
        $end_day = self::getLastMonday();

        $dpStudentStatistics = [
            'total'      => DpStudent::where('date_first_finish', '<=', $end_day)->where('active', true)->count(),
            'last_month' => DpStudent::where('active', true)->whereYear('date_first_finish', '=', now()->subMonth()->year)
                ->whereMonth('date_first_finish', '=', now()->subMonth()->month)->count(),
            'male_count' => DpStudent::where('date_first_finish', '<=', $end_day)->where('active', true)->where('gender', 'LIKE', '%男%')->count(),
            'female_count' => DpStudent::where('date_first_finish', '<=', $end_day)->where('active', true)->where('gender', 'LIKE', '%女%')->count(),
            'advanced_total'      => DpStudent::where('date_first_finish', '<=', $end_day)->where('active', true)->where('advance', true)->count(),
            'advanced_last_month' => DpStudent::where('active', true)->where('advance', true)->whereYear('date_first_finish', '=', now()->subMonth()->year)
                ->whereMonth('date_first_finish', '=', now()->subMonth()->month)->count(),
            'advanced_male_count' => DpStudent::where('date_first_finish', '<=', $end_day)->where('active', true)->where('advance', true)->where('gender', 'LIKE', '%男%')->count(),
            'advanced_female_count' => DpStudent::where('date_first_finish', '<=', $end_day)->where('active', true)->where('advance', true)->where('gender', 'LIKE', '%女%')->count(),
            'normal_total'      => DpStudent::where('date_first_finish', '<=', $end_day)->where('active', true)->where('advance', false)->count(),
            'normal_last_month' => DpStudent::where('active', true)->where('advance', false)->whereYear('date_first_finish', '=', now()->subMonth()->year)
                ->whereMonth('date_first_finish', '=', now()->subMonth()->month)->count(),
            'normal_male_count' => DpStudent::where('date_first_finish', '<=', $end_day)->where('active', true)->where('advance', false)->where('gender', 'LIKE', '%男%')->count(),
            'normal_female_count' => DpStudent::where('date_first_finish', '<=', $end_day)->where('active', true)->where('advance', false)->where('gender', 'LIKE', '%女%')->count(),
        ];
        $dpStudentStatistics['county_male_percentage'] = $dpStudentStatistics['total'] == 0 ? 0 : round($dpStudentStatistics['male_count'] / $dpStudentStatistics['total'] * 100, 2);
        $dpStudentStatistics['county_female_percentage'] = $dpStudentStatistics['total'] == 0 ? 0 : round($dpStudentStatistics['female_count'] / $dpStudentStatistics['total'] * 100, 2);
        $dpStudentStatistics['normal_county_male_percentage'] = $dpStudentStatistics['normal_total'] == 0 ? 0 : round($dpStudentStatistics['normal_male_count'] / $dpStudentStatistics['normal_total'] * 100, 2);
        $dpStudentStatistics['normal_county_female_percentage'] = $dpStudentStatistics['normal_total'] == 0 ? 0 : round($dpStudentStatistics['normal_female_count'] / $dpStudentStatistics['normal_total'] * 100, 2);
        $dpStudentStatistics['advanced_county_male_percentage'] = $dpStudentStatistics['advanced_total'] == 0 ? 0 : round($dpStudentStatistics['advanced_male_count'] / $dpStudentStatistics['advanced_total'] * 100, 2);
        $dpStudentStatistics['advanced_county_female_percentage'] = $dpStudentStatistics['advanced_total'] == 0 ? 0 : round($dpStudentStatistics['advanced_female_count'] / $dpStudentStatistics['advanced_total'] * 100, 2);
        return $dpStudentStatistics;
    }

    static public function statisticsData($end_day, $advance, $county_area = null)
    {
        $students = DpStudent::select(
            'dp_students.county_id',
            'users.name',
            DB::raw('count(case when TRIM(dp_students.gender) = \'男\' then \'x\' end) male_count'),
            DB::raw('count(case when TRIM(dp_students.gender) = \'女\' then \'x\' end) female_count'),
            DB::raw('count(dp_students.id) county_count')
        )->join('users', function ($join) use ($county_area) {
            $join->on('dp_students.county_id', '=', 'users.id');
            if ($county_area !== null) {
                $join->where('users.area', '=', $county_area);
            }
        })->where('active', true)->where('advance', $advance)
            ->whereDate('date_first_finish', '<=', $end_day)
            ->groupBy('dp_students.county_id', 'name')->orderBy('sort_order', 'asc')->get();
        $students->map(function (DpStudent $dpStudent) {
            $dpStudent->county_male_percentage = $dpStudent->male_count / $dpStudent->county_count * 100;
            $dpStudent->county_female_percentage = $dpStudent->female_count / $dpStudent->county_count * 100;
            $dpStudent->total_percentage = $dpStudent->county_count / DpStudent::count() * 100;
            $dpStudent->county_name = $dpStudent->county_count / DpStudent::count() * 100;

            return $dpStudent;
        });
        $students->total_count = DpStudent::where('date_first_finish', '<=', $end_day)->count();
        $students->male_count = DpStudent::where('date_first_finish', '<=', $end_day)->where('gender', 'LIKE', '%男%')->count();
        $students->female_count = DpStudent::where('date_first_finish', '<=', $end_day)->where('gender', 'LIKE', '%女%')->count();
        $students->county_male_percentage = $students->male_count / $students->total_count * 100;
        $students->county_female_percentage = $students->female_count / $students->total_count * 100;
        return $students;
    }

    public function statistics()
    {
        /** @var Collection|DpStudent[] $students */
        $county_area = request()->get('county_area', '0');
        //$end_day = date('Y-m-d', strtotime('last day of last month')) . " 23:59:59";
	$end_day = self::getLastMonday() . " 23:59:59";
        $students = self::statisticsData($end_day, false, $county_area);
        //$end_day = date('Y-m-d', strtotime('last day of last month')) . " 23:59:59";
        $end_year = intval(substr($end_day, 0, 4)) - 1911;
        $end_month = intval(substr($end_day, 5, 2));
        $dpStudentStatistics = self::dpStudentStatistics();
        $endDate = self::getLastMondayString();

        return view('dp.statistics', compact('students', 'county_area', 'end_year', 'end_month', 'dpStudentStatistics' , 'endDate'));
    }


    public function statisticsExport()
    {
        /** @var Collection|DpStudent[] $students */
        $end_day = date('Y-m-d', strtotime('last day of last month')) . " 23:59:59";
        $students = self::statisticsData($end_day, false);
        $end_day = date('Y-m-d', strtotime('last day of last month')) . " 23:59:59";
        $end_year = intval(substr($end_day, 0, 4)) - 1911;
        $end_month = intval(substr($end_day, 5, 2));
        $dpStudentStatistics = self::dpStudentStatistics();
        return Excel::download(new DpCountyStudentExport($dpStudentStatistics, $students, $end_year, $end_month), '各縣市防災士統計.xlsx');
    }

    public function studentList(Request $request)
    {
        return view('dp.student-list');
    }

    public function advancedStatistics()
    {
        /** @var Collection|DpStudent[] $students */
        $county_area = request()->get('county_area', '0');
        $end_day = date('Y-m-d', strtotime('last day of last month')) . " 23:59:59";
        $students = self::statisticsData($end_day, true, $county_area);
        $end_day = date('Y-m-d', strtotime('last day of last month')) . " 23:59:59";
        $end_year = intval(substr($end_day, 0, 4)) - 1911;
        $end_month = intval(substr($end_day, 5, 2));
        $dpStudentStatistics = self::dpStudentStatistics();

        return view('dp.advanced-statistics', compact('students', 'county_area', 'end_year', 'end_month', 'dpStudentStatistics'));
    }

    public function advancedStatisticsExport()
    {
        /** @var Collection|DpStudent[] $students */
        $end_day = date('Y-m-d', strtotime('last day of last month')) . " 23:59:59";
        $students = self::statisticsData($end_day, true);
        $end_day = date('Y-m-d', strtotime('last day of last month')) . " 23:59:59";
        $end_year = intval(substr($end_day, 0, 4)) - 1911;
        $end_month = intval(substr($end_day, 5, 2));
        $dpStudentStatistics = self::dpStudentStatistics();
        return Excel::download(new DpCountyAdvanceStudentExport($dpStudentStatistics, $students, $end_year, $end_month), '各縣市進階防災士統計.xlsx');
    }

    public function advancedStudentList(Request $request)
    {
        return view('dp.advanced-student-list');
    }

    static function mb_substr_replace($original, $replacement, $position, $length)
    {
        $startString = mb_substr($original, 0, $position, "UTF-8");
        $endString = mb_substr($original, $position + $length, mb_strlen($original), "UTF-8");

        $out = $startString . $replacement . $endString;

        return $out;
    }

    public function studentSearch(Request $request)
    {
        $sql = "id,certificate,name,date_first_finish,unit_first_course,gender";
        $dpStudentQuery = DpStudent::selectRaw($sql)->where('active', true)->where('advance', false)->latest('created_at');

        if ($filteredName = $request->get('search')) {
            //$dpStudentQuery->where('name', 'like', "%{$filteredName}%");
	    $dpStudentQuery->where('name', $filteredName);
        }

        $dpStudents = $dpStudentQuery->paginate(20);
        $pagination = $dpStudents->links()->render();
        $dpStudents = $dpStudents->items();
        foreach ($dpStudents as $key => $value) {
            $dpStudents[$key]['name'] = self::mb_substr_replace($value['name'], '＊', 1, 1);
        }
        $data = compact('dpStudents', 'pagination');
        return response()->json($data);
    }

    public function advancedStudentSearch(Request $request)
    {
        $sql = "id,certificate,name,date_first_finish,unit_first_course,gender";
        $dpStudentQuery = DpStudent::selectRaw($sql)->where('active', true)->where('advance', true)->latest('created_at');

        if ($filteredName = $request->get('search')) {
            $dpStudentQuery->where('name', 'like', "%{$filteredName}%");
        }

        $dpStudents = $dpStudentQuery->paginate(20);
        $pagination = $dpStudents->links()->render();
        $dpStudents = $dpStudents->items();
        foreach ($dpStudents as $key => $value) {
            $dpStudents[$key]['name'] = self::mb_substr_replace($value['name'], '＊', 1, 1);
        }
        $data = compact('dpStudents', 'pagination');
        return response()->json($data);
    }

    public function student(Request $request)
    {
        $data = Auth::guard('dp')->user();
        $counties = $this->getCounties();
        $fieldOption = [
            '一般職業' => '一般職業',
            '農牧業' => '農牧業',
            '漁業' => '漁業',
            '木材森林業' => '木材森林業',
            '礦業採石業' => '礦業採石業',
            '交通運輸業' => '交通運輸業',
            '餐旅業' => '餐旅業',
            '建築工程業' => '建築工程業',
            '製造業' => '製造業',
            '新聞、廣告業' => '新聞、廣告業',
            '娛樂業' => '娛樂業',
            '文教機關' => '文教機關',
            '宗教團體' => '宗教團體',
            '公共事業' => '公共事業',
            '一般商業' => '一般商業',
            '服務業' => '服務業',
            '家庭管理' => '家庭管理',
            '治安人員' => '治安人員',
            '軍人' => '軍人',
            '資訊業' => '資訊業',
            '職業運動人員' => '職業運動人員',
            '其他' => '其他',
        ];

        return view('dp.student', compact('data', 'counties', 'fieldOption'));
    }

    public function studentUpdate(Request $request)
    {
        /** @var DpStudent $user */
        $user = Auth::guard('dp')->user();
        $updateData = json_decode($request->getContent(), true);
        unset($updateData['TID']);
        unset($updateData['name']);
        unset($updateData['birth']);
        if (!empty($updateData['password'])) {
            $updateData['password'] = bcrypt($updateData['password']);
        } else {
            unset($updateData['password']);
        }
        $user->update($updateData);

        return response()->json([
            'ok' => 1,
        ]);
    }

    public function myCourse(Request $request)
    {
        /** @var DpStudent $user */
        $user = Auth::guard('dp')->user();
        $dp_courses = [];
        $dp_scores = [];
        $dpScores = $user->dpScores;
        foreach ($dpScores as $course_index => $score_data) {
            if (!isset($dp_courses[$score_data->dp_course_id])) {
                if ($dpCourse = DpCourse::find($score_data->dp_course_id, ['id', 'name'])) {
                    $dp_scores[$score_data->dp_course_id] = $score_data;
                    $dp_scores[$score_data->dp_course_id]['name'] = $dpCourse['name'];
                    $dp_courses[$score_data->dp_course_id] = $dpCourse;
                } else {
                    unset($dpScores[$course_index]);
                }
            }
        }

        return view('dp.myCourse', compact('user', 'dp_courses', 'dp_scores'));
    }

    public function experience(Request $request)
    {
        $user = Auth::guard('dp')->user();

        return view('dp.experience', compact('user'));
    }

    public function uploadExperienceFiles($id, Request $request)
    {
        $experience = DpExperience::find($id);
        $this->handleFiles($request, $experience, '');

        flash('檔案操作成功。');

        return redirect()->route('dp.experience');
    }

    public function waiver(Request $request)
    {
        $user = Auth::guard('dp')->user();

        return view('dp.waiver', compact('user'));
    }

    public function uploadWaiverFiles($id, Request $request)
    {
        $waiver = DpWaiver::find($id);
        $this->handleFiles($request, $waiver, '');

        flash('檔案操作成功。');

        return redirect()->route('dp.waiver');
    }

    private function getCounties()
    {
        $countyIdNames = User::where('type', 'county')->whereNull('county_id')->pluck('name', 'id')->toArray();

        return [null => '-'] + $countyIdNames;
    }

    protected static function getLastMonday()
    {
        $todayWeekday = date('N'); // 1 (Monday) to 7 (Sunday)
        if ($todayWeekday == 1) {
            // 今天就是星期一
            return date('Y-m-d');
        }

        return date('Y-m-d', strtotime('last monday'));
    }

    protected static function getLastMondayString() 
    {
        // 1. 取得西元日期字串 (例如: '2025-11-17')
        $western_date = self::getLastMonday();

        // 2. 轉換為 DateTime 物件
        $dateTime = new \DateTime($western_date);

        // 3. 擷取西元年份、月、日
        $western_year = (int)$dateTime->format('Y');
        $month = $dateTime->format('m');
        $day = $dateTime->format('d');
        
        // 4. 計算民國年 (例如: 2025 - 1911 = 114)
        $minguo_year = $western_year - 1911;

        // 5. 組合成所需的格式： YYY年 MM月 DD日
        return $minguo_year . '年 ' . $month . '月 ' . $day . '日';
    }
}
