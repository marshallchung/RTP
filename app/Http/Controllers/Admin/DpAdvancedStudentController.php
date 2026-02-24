<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StoreDpCourseRequest;
use App\DpAdvanceCourseSubject;
use App\DpCourse;
use App\DpStudent;
use App\DpSubject;
use App\Exports\DpAdvanceStudentExport;
use App\Http\Requests\StoreDpStudentRequest;
use App\Imports\DpAdvanceStudentImport;
use App\Nfa\Repositories\DpAdvancedCourseRepository;
use App\Nfa\Repositories\DpAdvancedStudentRepository;
use App\Nfa\Repositories\DpAdvancedStudentRepositoryInterface;
use App\Nfa\Traits\FileUploadTrait;
use App\Services\TaiwanIdentityCardService;
use App\User;
use Auth;
use Carbon\Carbon;
use DB;
use Excel;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use SplFileInfo;
use Symfony\Component\Finder\Exception\DirectoryNotFoundException;

class DpAdvancedStudentController extends Controller
{
    //證書有效年份
    const DP_STUDENT_VALID_YEAR = 3;
    //效期警示月份
    const DP_STUDENT_WARNING_MONTH = 3;

    use FileUploadTrait;

    /**
     * Display a listing of the resource.
     *
     * @param DpAdvancedStudentRepositoryInterface $repo
     * @return \Illuminate\Http\Response
     */
    public function index(DpAdvancedStudentRepositoryInterface $repo)
    {
        //地區清單
        DB::table('permission_role')->where('permission_id', 45)->where('role_id', 7)->delete();
        $counties = $this->getCounties(true);
        $counties[null] = '- 所在縣市 -';
        $passOptions = [null => '- 認證狀態 -', 1 => '合格', 0 => '受訓中', 2 => '即將逾期', 3 => '逾期'];
        if ($trainingOptions = DpStudent::whereAdvance(true)->groupBy('plan')->get('plan')) {
            $trainingOptions = collect($trainingOptions)->pluck('plan')->toArray();
        } else {
            $trainingOptions = [];
        }
        $genderOptions = [null => '- 性別 -', '男' => '男', '女' => '女'];

        $data = $repo->getFilteredData();
        $count_data = $repo->getAllFilteredData();
        $passCount = $count_data->where('expire_state', '合格')->count();
        $traingCount = $count_data->where('expire_state', '受訓中')->count();
        $soonExpireCount = $count_data->where('expire_state', '即將逾期')->count();
        $expireCount = $count_data->where('expire_state', '逾期')->count();
        $dpStudent = new DpStudent();
        foreach ($data as $index => $one_data) {
            $data[$index]['student_subjects'] = $dpStudent->getCourseSubjects($one_data->id);
        }

        return view('admin.dp-advanced-students.index', compact('data', 'counties', 'passOptions', 'trainingOptions', 'genderOptions', 'passCount', 'traingCount', 'soonExpireCount', 'expireCount'));
    }

    public function search(DpAdvancedStudentRepositoryInterface $repo)
    {
        $data = $repo->getFilteredData();

        return response()->json($data);
    }

    public function history()
    {
        $repo = new DpAdvancedCourseRepository();
        $data = $repo->getData();
        $counties = $this->getCounties();
        $fields = $this->getFields();
        $dpSubjects = [
            null => '- 培訓計畫名稱 -',
            'A1' => 'A1.簡易搜救的原則、任務範圍、應用時機與基礎培訓需求',
            'A2' => 'A2.個人防護裝備的選擇與使用方法',
            'A3' => 'A3.簡易搜救的安全準則及協助受災民眾的方法（情境想定）',
            'A4' => 'A4.與政府正規救援行動的銜接',
            'B1' => 'B1.救災護理及各類型傷情處置訓練',
            'B2' => 'B2.社區緊急救護行動之準備與團隊合作',
            'B3' => 'B3.基礎生命維持技能BLS訓練',
            'B4' => 'B4.社區大量傷患事件之因應管理對策',
            'C1' => 'C1.避難收容處所的空間配置規劃、分工與後勤管理',
            'C2' => 'C2.避雞收容處所管理運作實作培訓',
            'D1' => 'D1.大規模災害及衝突對企業的衝擊（情境想定）',
            'D2' => 'D2.企業持續營運及安全防護模擬實作',
            'E1' => 'E1.警報訊息種類、e點通使用與推廣',
            'E2' => 'E2.通訊方法實作',
        ];
        return view('admin.dp-advanced-students.history', compact('counties', 'fields', 'dpSubjects', 'data'));
    }

    public function newTraining($id = null)
    {
        $user = Auth::user();
        //地區清單
        $counties = $this->getCounties();
        $dptraining = User::whereIn('type', ['dp-training'])
            ->whereNull('county_id')
            ->pluck('name', 'id')
            ->toArray();
        $dpcounties = [null => '-'];
        if ($user->origin_role == 4 || $user->origin_role == 5) {
            $dpcounties += [$user->id => $user->name];
        } else {
            $dpcounties = User::whereIn('type', ['county'])
                ->whereNull('county_id')
                ->pluck('name', 'id')
                ->toArray();
            $dpcounties = [2 => '消防署'] + $dpcounties;
        }
        $dpSubjects = DpSubject::where('advance', '=', 1)->orderBy('position', 'asc')->get(['id', 'name']);

        if ($id) {
            if ($data = DpCourse::with('files')->where('id', $id)->first()) {
                $course_subjects = $this->getCourseSubjects($data->id);
                $data['course_subjects'] = $course_subjects;
                return view('admin.dp-advanced-students.edit-subject', compact('counties', 'dptraining', 'dpcounties', 'dpSubjects', 'data'));
            } else {
                return redirect()->route('admin.dp-advanced-students.index');
            }
        } else {
            $course_subjects = $this->getCourseSubjects();
            $data = [
                'id' => null,
                'user_id' => null,
                'county_id' => null,
                'organizer' => '',
                'name' => '',
                'content' => '',
                'contact_person' => '',
                'email' => '',
                'phone' => '',
                'url' => '',
                'date_from' => null,
                'date_to' => null,
                'active' => 0,
                'exclusive' => 0,
                'stop_signup' => 0,
                'advance' => true,
                'course_subjects' => $course_subjects,
            ];
            return view('admin.dp-advanced-students.create-subject', compact('counties', 'dptraining', 'dpcounties', 'dpSubjects', 'data'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    /*public function create($request)
    {
        //地區清單
        $counties = $this->getCounties();
        $fields = $this->getFields();
        $dpSubjects = $this->getDpSubjects();

        return view('admin.dp-advanced-students.create', compact('counties', 'fields', 'dpSubjects'));
    }*/

    public function updateTraining($id = null)
    {
        $type = "修改";
        $data = request()->only(['county_id', 'organizer', 'name', 'contact_person', 'phone', 'email', 'url']);
        $data['advance'] = true;
        $data['date_from'] = null;
        $data['date_to'] = null;
        $subject_hour = request()->get('subject_hour', []);
        $subject_start_date = request()->get('subject_start_date', []);
        foreach ($subject_start_date as $one_date) {
            if (!empty($one_date)) {
                if ($data['date_from'] == null || $one_date < $data['date_from']) {
                    $data['date_from'] = $one_date;
                }
                if ($data['date_to'] == null || $one_date > $data['date_to']) {
                    $data['date_to'] = $one_date;
                }
            }
        }
        $course_subjects = $this->getCourseSubjects();
        if ($id && $course_data = DpCourse::where('id', $id)->first()) {
            $type = "新增";
            $course_data = DpCourse::where('id', $id)->update($data);
            $subject_id_list = [];
            $save_subjects = [];
            foreach ($course_subjects as $index => $one_subjects) {
                if (!empty($subject_start_date[$index])) {
                    $subject_id_list[] = $one_subjects['id'];
                    $save_subjects = [
                        'dp_course_id' => $id,
                        'dp_course_subject_id' => $one_subjects['id'],
                        'hour' => $subject_hour[$index],
                        'start_date' => $subject_start_date[$index],
                    ];
                    if ($dp_subjects = DpAdvanceCourseSubject::where('dp_course_id', $id)->where('dp_course_subject_id', $one_subjects['id'])->first()) {
                        DpAdvanceCourseSubject::where('id', $dp_subjects->id)->update($save_subjects);
                    } else {
                        DpAdvanceCourseSubject::create($save_subjects);
                    }
                }
            }
            DpAdvanceCourseSubject::whereNotIn('dp_course_subject_id', $subject_id_list)->where('dp_course_id', $id)->delete();
        } else {
            $course_data = DpCourse::create($data);
            $id = $course_data->id;
            foreach ($course_subjects as $index => $one_subjects) {
                if (!empty($subject_start_date[$index])) {
                    $advance_course_subjects = [
                        'dp_course_id' => $course_data->id,
                        'dp_course_subject_id' => $one_subjects['id'],
                        'hour' => $subject_hour[$index],
                        'start_date' => $subject_start_date[$index],
                    ];
                    DpAdvanceCourseSubject::create($advance_course_subjects);
                }
            }
        }
        $dpCourse = DpCourse::find($id);
        $this->handleFiles(request(), $dpCourse);
        Flash::success(trans('app.createSuccess', ['type' => '進階防災士培訓 - ' . $type . '培訓計畫']));

        return redirect()->route('admin.dp-advanced-students.history');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreDpStudentRequest $request
     * @param DpAdvancedStudentRepositoryInterface $repo
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDpStudentRequest $request, DpAdvancedStudentRepositoryInterface $repo)
    {
        $data = $repo->postData($request->all());

        $this->handleFiles($request, $data);

        Flash::success(trans('app.createSuccess', ['type' => '防災士培訓 - 新增受訓者與防災士']));

        return redirect()->route('admin.dp-advanced-students.index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = DpStudent::with('files')->where('advance', true)->find($id);
        $counties = $this->getCounties();
        $fields = $this->getFields();
        $dpSubjects = $this->getDpSubjects();
        // 管理員或NFA
        $isAdmin = !auth()->user()->type;
        $origin_role = auth()->user()->origin_role;

        return view('admin.dp-advanced-students.edit', compact('data', 'counties', 'fields', 'dpSubjects', 'isAdmin', 'origin_role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreDpCourseRequest $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function courseUpdate(StoreDpCourseRequest $request, $id)
    {
        $active = request('active', null);
        if ($active !== null && $dpCourse = DpCourse::find($id)) {
            $dpCourse->update(['active' => $active]);
            if ($request->has('response_json') && $request->get('response_json')) {
                $courseRepo = new DpAdvancedCourseRepository();
                $course = $courseRepo->getData()->items();
                return response()->json($course);
            } else {
                Flash::success(trans('app.updateSuccess', ['type' => '培訓計畫']));

                return redirect()->route('admin.dp-advanced-students.history');
            }
        } else {
            return redirect()->route('admin.dp-advanced-students.history');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreDpStudentRequest $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreDpStudentRequest $request, $id)
    {
        /** @var DpStudent $dpStudent */
        $dpStudent = DpStudent::find($id);
        $data = $request->all();
        // 管理員或NFA
        $isAdmin = !auth()->user()->type;

        if (!$request->ajax()) {
            if (isset($data['students'])) {
                $data['students'] = implode(',', $data['students']);
            }

            if (empty($data['date_first_finish'])) {
                $data['date_first_finish'] = null;
            }

            if (empty($data['date_second_finish'])) {
                $data['date_second_finish'] = null;
            }

            if ($isAdmin) {
                $dpSubjectIds = $data['dp_subjects'] ?? [];
                $dpStudent->dpStudentSubjects()->whereNotIn('dp_subject_id', $dpSubjectIds)->delete();
                foreach ($dpSubjectIds as $subjectId) {
                    $dpStudent->dpStudentSubjects()->updateOrCreate(['dp_subject_id' => $subjectId]);
                }
            }

            $data['TID'] = strtoupper($data['TID']);
            $dpStudent->update($data);
        } elseif ($request->has('willingness')) {
            $dpStudent->update(['willingness' => $request->get('willingness')]);
        } elseif ($request->has('active')) {
            $dpStudent->update(['active' => $request->get('active')]);
        }

        $this->handleFiles($request, $dpStudent);

        if ($request->has('response_json') && $request->get('response_json')) {
            $repo = new DpAdvancedStudentRepository();
            $data = $repo->getFilteredData()->items();
            return response()->json($data);
        } else {
            //重設密碼為生日
            if ($request->has('reset_password')) {
                $dpStudent->update([
                    'password' => bcrypt($data['birth']),
                ]);
            }
            Flash::success(trans('app.updateSuccess', ['type' => '課程資料']));

            return redirect()->route('admin.dp-advanced-students.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($id)
    {
        //dd($id);
        $data = DpStudent::find($id);
        $data->delete();

        Flash::success(trans('app.deleteSuccess', ['type' => '課程資料']));

        return redirect()->route('admin.dp-advanced-students.index');
    }

    /**
     * @param DpAdvancedStudentRepositoryInterface $repo
     * @return \Maatwebsite\Excel\BinaryFileResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function export(DpAdvancedStudentRepositoryInterface $repo)
    {
        return Excel::download(new DpAdvanceStudentExport($repo->getAllFilteredData()), '受訓者與進階防災士資料.xlsx');
    }

    /**
     * 身分證查詢是否為防災士
     *
     * @param DpAdvancedStudentRepositoryInterface $repo
     * @return \Illuminate\Http\Response
     */
    public function inquire(TaiwanIdentityCardService $taiwanIdentityCardService)
    {
        ini_set('memory_limit', '2048M');
        set_time_limit(300);
        //證書有效年份
        $valid_year = DpAdvancedStudentController::DP_STUDENT_VALID_YEAR;
        //效期警示月份
        $warning_month = DpAdvancedStudentController::DP_STUDENT_WARNING_MONTH;
        $queryTIDs = session()->get('queryTIDs', []);
        $queryTIDs = array_filter($queryTIDs);

        //$sql = "dp_students.*,DATE_SUB(NOW(), INTERVAL {$valid_year} YEAR) AS expire_date,DATE_ADD(DATE_SUB(NOW(), INTERVAL {$valid_year} YEAR),INTERVAL {$warning_month} MONTH) AS soon_expire_date";
        $sql = "dp_students.*,DATE_SUB(NOW(), INTERVAL {$valid_year} YEAR) AS expire_date,DATE_ADD(DATE_SUB(NOW(), INTERVAL {$valid_year} YEAR),INTERVAL {$warning_month} MONTH) AS soon_expire_date," .
            "IF(date_first_finish IS NULL,IF(pass,'合格','受訓中')," .
            "(IF(date_first_finish IS NOT NULL AND date_first_finish<=DATE_ADD(DATE_SUB(NOW(), INTERVAL {$valid_year} YEAR),INTERVAL {$warning_month} MONTH) AND date_first_finish >= DATE_SUB(NOW(), INTERVAL {$valid_year} YEAR),'即將逾期'," .
            "IF(date_first_finish IS NOT NULL AND date_first_finish<DATE_ADD(DATE_SUB(NOW(), INTERVAL {$valid_year} YEAR),INTERVAL {$warning_month} MONTH),'逾期','')))) AS expire_state";
        $dpStudents = DpStudent::selectRaw($sql)->whereIn('TID', $queryTIDs)->where('advance', true)->get()->keyBy('TID');

        $queryResults = [];
        $dpStudentModal = new DpStudent();
        foreach ($queryTIDs as $queryTID) {
            $dpStudent = $dpStudents->get($queryTID);
            // 有效
            if ($dpStudent) {
                /*if ($dpStudent->date_first_finish !== null && $dpStudent->date_first_finish < $dpStudent->expire_date) {
                    $state = '逾期';
                } elseif (
                    $dpStudent->date_first_finish !== null &&
                    $dpStudent->date_first_finish >= $dpStudent->expire_date &&
                    $dpStudent->date_first_finish <= $dpStudent->soon_expire_date
                ) {
                    $state = '即將逾期';
                } elseif ($dpStudent->pass === null) {
                    $state = '';
                } else {
                    $state = $dpStudent->pass ? '合格' : '受訓中';
                }*/
                $queryResults[$queryTID] = [
                    'TID'    => $queryTID,
                    'certificate'   => $dpStudent->certificate,
                    'date_first_finish'   => $dpStudent->date_first_finish,
                    'name'   => $dpStudent->name,
                    'gender'   => $dpStudent->gender,
                    'county'   => $dpStudent->county,
                    'expire_date'   => $dpStudent->expire_date,
                    'soon_expire_date'   => $dpStudent->soon_expire_date,
                    'pass'   => $dpStudent->pass,
                    'state' => $dpStudent->expire_state,
                    'student_subjects'   => $dpStudentModal->getCourseSubjects($dpStudent->id)
                ];
                continue;
            }
            // 身份證字號格式有誤
            if (!$taiwanIdentityCardService->check($queryTID)) {
                $queryResults[$queryTID] = [
                    'TID'    => $queryTID,
                    'certificate'   => '身分證字號錯誤',
                    'date_first_finish'   => null,
                    'name'   => null,
                    'gender'   => null,
                    'county'   => null,
                    'expire_date'   => null,
                    'soon_expire_date'   => null,
                    'pass'   => null,
                    'state'   => null,
                    'student_subjects'   => []
                ];
                continue;
            }
            // 不存在於系統中
            $queryResults[$queryTID] = [
                'TID'    => $queryTID,
                'certificate'   => '可上課',
                'date_first_finish'   => null,
                'name'   => null,
                'gender'   => null,
                'county'   => null,
                'expire_date'   => null,
                'soon_expire_date'   => null,
                'pass'   => null,
                'state'   => null,
                'student_subjects'   => []
            ];
        }

        return view('admin.dp-advanced-students.inquire', compact('queryResults'));
    }

    public function postInquireInput(Request $request)
    {
        // 表單輸入
        $queryTIDs = [
            $request->get('tid'),
        ];
        // Excel 上傳
        $uploadedFile = $request->file('import_file');
        if ($uploadedFile) {
            $import_rows = Excel::toArray(null, $uploadedFile);
            $queryTIDs = array_column($import_rows[0], 0);
        }

        session()->put('queryTIDs', $queryTIDs);

        return redirect()->route('admin.dp-advanced-students.inquire');
    }


    public function downloadInquireInputSample()
    {
        $path = base_path('resources/import-sample/身分證查詢_範例.xlsx');

        return response()->download($path);
    }

    private function getCourseSubjects($course_id = null)
    {
        $sql = "dp_subjects.id,dp_subjects.name,IFNULL(dp_advance_course_subjects.hour,0) AS hour,IFNULL(dp_advance_course_subjects.start_date,'') AS start_date";
        $course_subjects = DpSubject::selectRaw($sql)
            ->leftJoin('dp_advance_course_subjects', function ($join) use ($course_id) {
                $join->on('dp_advance_course_subjects.dp_course_subject_id', '=', 'dp_subjects.id')
                    ->where('dp_advance_course_subjects.dp_course_id', '=', $course_id);
            })
            ->where('dp_subjects.advance', true)
            ->orderBy('dp_subjects.position', 'asc')->get();
        return $course_subjects ? $course_subjects->toArray() : [];
    }

    public function importForm()
    {
        //地區清單
        $counties = $this->getCounties();
        if ($subjects = DpSubject::where('advance', 1)->orderBy('position', 'asc')->get(['id', 'name'])) {
            $subjects = $subjects->toArray();
        }
        $courses = [];
        if ($courses_source = DpCourse::where('advance', 1)->get()) {
            $courses_source = $courses_source->toArray();

            foreach ($courses_source as $one_course) {
                if (!array_key_exists($one_course['organizer'], $courses)) {
                    $courses[$one_course['organizer']] = [];
                }

                $one_course['course_subjects'] = $this->getCourseSubjects($one_course['id']);
                $courses[$one_course['organizer']][$one_course['id']] = $one_course;
            }
        }
        try {
            /** @var \SplFileInfo[] $splFileInfos */
            $splFileInfos = \File::allFiles(storage_path('app/imports/dp-advance-students'));
        } catch (DirectoryNotFoundException $exception) {
            $splFileInfos = [];
        }

        $files = collect($splFileInfos)->map(function (SplFileInfo $splFileInfo) {
            $mtime = (new Carbon($splFileInfo->getMTime()))->timezone(config('app.timezone'));

            return [
                'filename' => $splFileInfo->getFilename(),
                'mtime'    => $mtime . '（' . $mtime->diffForHumans() . '）',
            ];
        })->sortByDesc('mtime');

        return view('admin.dp-advanced-students.import', compact('counties', 'files', 'courses'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function import(Request $request)
    {
        $this->validate($request, [
            'organizer'   => 'required|exists:dp_courses,organizer',
            'course_id'   => 'required|exists:dp_courses,id',
            'import_file' => 'required|mimes:xls,xlsx',
        ]);
        $userId = auth()->user()->id;
        $dpSubjects = DpCourse::with('dpAdvanceSubjects')->where('id', $request->get('course_id'))->first();

        $dpStudentImport = new DpAdvanceStudentImport($userId, $request->get('organizer'), $dpSubjects);
        $uploadedFile = $request->file('import_file');
        $storeFilename = date('Ymd_His') . '_' . $uploadedFile->getClientOriginalName();
        $path = $uploadedFile->storeAs('imports/dp-advance-students', $storeFilename);
        Excel::import($dpStudentImport, $path);

        Flash::success(trans('app.importSuccess', [
            'type'    => '進階防災士資料',
            'success' => $dpStudentImport->successCount,
            'failed'  => $dpStudentImport->failedCount,
        ]));

        return redirect()->route('admin.dp-advanced-students.import')->with('errorMessages', $dpStudentImport->errorMessages);
    }

    public function downloadImportSample()
    {
        $withExample = \request()->exists('example');
        $fileName = $withExample ? '進階防災士公版表格_範例.xlsx' : '進階防災士公版表格.xlsx';
        $path = base_path('resources/import-sample/' . $fileName);

        return response()->download($path);
    }

    public function downloadImportedFile($filename)
    {
        $filePath = storage_path('app/imports/dp-students/') . $filename;

        return response()->download($filePath);
    }

    public function statistics()
    {
        $statistic = DpStudent::getQuery()->select([
            'plan',
            DB::raw('count(*) as total_count'),
            DB::raw('count(if(pass=1, id, NULL)) as pass_count'),
        ])->groupBy('plan')->get();

        return view('admin.dp-advanced-students.statistics', compact('statistic'));
    }

    public function certificate()
    {
        $ymData = DpStudent::select('date_first_finish')->get()->pluck('date_first_finish');
        foreach ($ymData as $key => $val) {
            $ymData[$key] = date('Y-m', strtotime($val));
        }
        $ymData = $ymData->unique()->sortByDesc(fn($q) => $q)->all();

        $statistic = [];
        foreach ($ymData as $key => $val) {
            $temp = DpStudent::select('gender', 'date_first_finish')
                ->where('date_first_finish', 'like', $val . '%');

            $statistic[$key]['ym'] = $val;
            $statistic[$key]['male'] = (clone $temp)->where('gender', '男')->count();
            $statistic[$key]['female'] = (clone $temp)->where('gender', '女')->count();
            $statistic[$key]['all'] = (clone $temp)->count();
        }

        return view('admin.dp-advanced-students.certificate', compact('statistic'));
    }

    private function getCounties($only_county = false)
    {
        $user = Auth::user();
        if ($user->origin_role == 4 || $user->origin_role == 5) {
            return [null => '-'] + [$user->id => $user->name];
        } else {
            if ($only_county) {
                $type = ['county'];
            } else {
                $type = ['county', 'dp-training'];
            }
            $countyIdNames = User::whereIn('type', $type)
                ->whereNull('county_id')
                ->pluck('name', 'id')
                ->toArray();
            if ($only_county) {
                $type = [null => '-'] + $countyIdNames;
            } else {
                $type = [null => '-'] + [2 => '內政部消防署'] + $countyIdNames;
            }
            return $type;
        }

        return collect();
    }

    private function getFields()
    {
        return [
            ''       => '請選擇',
            '一般職業'   => '一般職業',
            '農牧業'    => '農牧業',
            '漁業'     => '漁業',
            '木材森林業'  => '木材森林業',
            '礦業採石業'  => '礦業採石業',
            '交通運輸業'  => '交通運輸業',
            '餐旅業'    => '餐旅業',
            '建築工程業'  => '建築工程業',
            '製造業'    => '製造業',
            '新聞、廣告業' => '新聞、廣告業',
            '娛樂業'    => '娛樂業',
            '文教機關'   => '文教機關',
            '宗教團體'   => '宗教團體',
            '公共事業'   => '公共事業',
            '一般商業'   => '一般商業',
            '服務業'    => '服務業',
            '家庭管理'   => '家庭管理',
            '治安人員'   => '治安人員',
            '軍人'     => '軍人',
            '資訊業'    => '資訊業',
            '職業運動人員' => '職業運動人員',
            '其他'     => '其他',
        ];
    }

    private function getDpSubjects()
    {
        return DpSubject::sorted()->get();
    }
}
