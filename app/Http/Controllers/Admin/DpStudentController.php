<?php

namespace App\Http\Controllers\Admin;

use App\DpStudent;
use App\DpSubject;
use App\Exports\DpStudentExport;
use App\Http\Requests\StoreDpStudentRequest;
use App\Imports\DpStudentImport;
use App\Imports\DpStudentInquireByTIDImport;
use App\Nfa\Repositories\DpScoreRepository;
use App\Nfa\Repositories\DpStudentRepository;
use App\Nfa\Repositories\DpStudentRepositoryInterface;
use App\Nfa\Traits\FileUploadTrait;
use App\Services\TaiwanIdentityCardService;
use App\User;
use Auth;
use Carbon\Carbon;
use DB;
use Excel;
use Flash;
use Illuminate\Http\Request;
use SplFileInfo;
use Symfony\Component\Finder\Exception\DirectoryNotFoundException;

class DpStudentController extends Controller
{
    use FileUploadTrait;

    /**
     * Display a listing of the resource.
     *
     * @param DpStudentRepositoryInterface $repo
     * @return \Illuminate\Http\Response
     */
    public function index(DpStudentRepositoryInterface $repo)
    {
        //地區清單
        DB::table('permission_role')->where('permission_id', 45)->where('role_id', 7)->delete();
        $counties = $this->getCounties();
        $counties[null] = '- 所在縣市 -';
        $passOptions = [null => '- 認證合格 -', 1 => '合格', 0 => '不合格'];
        $genderOptions = [null => '- 性別 -', '男' => '男', '女' => '女'];

        $passCount = $repo->getPassCount();

        $data = $repo->getFilteredData();

        return view('admin.dp-students.index', compact('data', 'counties', 'passOptions', 'genderOptions', 'passCount'));
    }

    public function search(DpStudentRepositoryInterface $repo)
    {
        $data = $repo->getFilteredData();

        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //地區清單
        $counties = $this->getCounties();
        $fields = $this->getFields();
        $dpSubjects = $this->getDpSubjects();

        return view('admin.dp-students.create', compact('counties', 'fields', 'dpSubjects'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreDpStudentRequest $request
     * @param DpStudentRepositoryInterface $repo
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDpStudentRequest $request, DpStudentRepositoryInterface $repo)
    {
        $data = $repo->postData($request->all());

        $this->handleFiles($request, $data);

        Flash::success(trans('app.createSuccess', ['type' => '防災士培訓 - 新增受訓者與防災士']));

        return redirect()->route('admin.dp-students.index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = DpStudent::find($id);
        $counties = $this->getCounties();
        $dpSubjects = $this->getDpSubjects();

        return view('admin.dp-students.show', compact('data', 'counties', 'dpSubjects'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = DpStudent::with('files')->where('advance', false)->find($id);
        $counties = $this->getCounties();
        $fields = $this->getFields();
        $dpSubjects = $this->getDpSubjects();
        // 管理員或NFA
        $isAdmin = !auth()->user()->type;
        $origin_role = auth()->user()->origin_role;

        return view('admin.dp-students.edit', compact('data', 'counties', 'fields', 'dpSubjects', 'isAdmin', 'origin_role'));
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
            $repo = new DpStudentRepository();
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

            return redirect()->route('admin.dp-students.index');
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

        return redirect()->route('admin.dp-students.index');
    }

    /**
     * @param DpStudentRepositoryInterface $repo
     * @return \Maatwebsite\Excel\BinaryFileResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function export(DpStudentRepositoryInterface $repo)
    {
        return Excel::download(new DpStudentExport($repo->getAllFilteredData()), '受訓者與防災士資料.xlsx');
    }

    /**
     * 身分證查詢是否為防災士
     *
     * @param DpStudentRepositoryInterface $repo
     * @return \Illuminate\Http\Response
     */
    public function inquire(TaiwanIdentityCardService $taiwanIdentityCardService)
    {
        $queryTIDs = session()->get('queryTIDs', []);
        $queryTIDs = array_filter($queryTIDs);

        $dpStudents = DpStudent::whereIn('TID', $queryTIDs)->where('advance', false)->get()->keyBy('TID');

        $queryResults = [];
        foreach ($queryTIDs as $queryTID) {
            $dpStudent = $dpStudents->get($queryTID);
            // 有效
            if ($dpStudent) {
                $queryResults[$queryTID] = [
                    'TID'    => $queryTID,
                    'name'   => $dpStudent->name,
                    'status' => '防災士',
                ];
                continue;
            }
            // 身份證字號格式有誤
            if (!$taiwanIdentityCardService->check($queryTID)) {
                $queryResults[$queryTID] = [
                    'TID'    => $queryTID,
                    'name'   => '',
                    'status' => '身分證字號錯誤',
                ];
                continue;
            }
            // 不存在於系統中
            $queryResults[$queryTID] = [
                'TID'    => $queryTID,
                'name'   => '',
                'status' => '可上課',
            ];
        }

        return view('admin.dp-students.inquire', compact('queryResults'));
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

        return redirect()->route('admin.dp-students.inquire');
    }


    public function downloadInquireInputSample()
    {
        $path = base_path('resources/import-sample/身分證查詢_範例.xlsx');

        return response()->download($path);
    }

    public function importForm()
    {
        //地區清單
        $counties = $this->getCounties();

        try {
            /** @var \SplFileInfo[] $splFileInfos */
            $splFileInfos = \File::allFiles(storage_path('app/imports/dp-students'));
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

        return view('admin.dp-students.import', compact('counties', 'files'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function import(Request $request)
    {
        $this->validate($request, [
            //            'county_id'   => 'required|numeric',
            'import_file' => 'required|mimes:xls,xlsx',
        ]);
        $userId = auth()->user()->id;
        $dpSubjects = $this->getDpSubjects();
        $dpSubjects = $dpSubjects->keyBy('position');

        $dpStudentImport = new DpStudentImport($userId, $dpSubjects, $request['Multiple']);
        $uploadedFile = $request->file('import_file');
        $storeFilename = date('Ymd_His') . '_' . $uploadedFile->getClientOriginalName();
        $path = $uploadedFile->storeAs('imports/dp-students', $storeFilename);
        Excel::import($dpStudentImport, $path);

        Flash::success(trans('app.importSuccess', [
            'type'    => '防災士資料',
            'success' => $dpStudentImport->successCount,
            'failed'  => $dpStudentImport->failedCount,
        ]));

        return redirect()->route('admin.dp-students.import')->with('errorMessages', $dpStudentImport->errorMessages);
    }

    public function downloadImportSample()
    {
        $withExample = \request()->exists('example');
        $fileName = $withExample ? '防災士公版表格_範例.xlsx' : '防災士公版表格.xlsx';
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

        return view('admin.dp-students.statistics', compact('statistic'));
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

        return view('admin.dp-students.certificate', compact('statistic'));
    }

    private function getCounties()
    {
        $user = Auth::user();
        switch ($user->origin_role) {
            case 1:
            case 2:
            case 6:
            case 7:
                $countyIdNames = User::where('type', 'county')->whereNull('county_id')->pluck('name', 'id')->toArray();
                break;

            case 4:
                $countyIdNames = User::where('id', $user->id)->pluck('name', 'id')->toArray();
                break;
            case 5:
                $countyIdNames = User::where('id', $user->county_id)->pluck('name', 'id')->toArray();
                break;
            default:
                $countyIdNames = [];
        }

        return [null => '-'] + $countyIdNames;
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
        return DpSubject::where('advance', false)->sorted()->get();
    }
}
