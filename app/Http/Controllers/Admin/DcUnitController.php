<?php

namespace App\Http\Controllers\Admin;

use App\DcUnit;
use App\DcUser;
use App\Exports\DcUnitExport;
use App\Exports\DcUnitExportRe;
use App\Exports\DcUserExport;
use App\Http\Requests\StoreDcUnitRankRequest;
use App\Http\Requests\StoreDcUnitRequest;
use App\Imports\DcUnitImport;
use App\Nfa\Repositories\DcUnitRepository;
use App\Nfa\Repositories\DcUnitRepositoryInterface;
use App\Nfa\Traits\FileUploadTrait;
use App\User;
use Excel;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Laratrust\Contracts\LaratrustUser;
use Carbon\Carbon;

class DcUnitController extends Controller
{
    use FileUploadTrait;
    public $countyList = [
        "臺北市" => "A",
        "臺中市" => "B",
        "基隆市" => "C",
        "臺南市" => "D",
        "高雄市" => "E",
        "新北市" => "F",
        "宜蘭縣" => "G",
        "桃園市" => "H",
        "嘉義市" => "I",
        "新竹縣" => "J",
        "苗栗縣" => "K",
        "南投縣" => "M",
        "彰化縣" => "N",
        "新竹市" => "O",
        "雲林縣" => "P",
        "嘉義縣" => "Q",
        "屏東縣" => "T",
        "花蓮縣" => "U",
        "臺東縣" => "V",
        "金門縣" => "W",
        "澎湖縣" => "X",
        "連江縣" => "Z",
    ];

    /**
     * Display a listing of the resource.
     *
     * @param DcUnitRepositoryInterface $repo
     * @return \Illuminate\Http\Response
     */
    public function index(DcUnitRepositoryInterface $repo)
    {
        //地區清單
        $user = Auth::user();
        $county_id = ($user->origin_role > 2 && $user->origin_role != 6) ? $user->id : null;
        $counties = $this->getCounties($county_id);
        $ranks = [null => '星等'] + $this->getRanks();

        $rankCount = $repo->getRankCount($county_id);
        $withinPlanCount = $repo->getWithinPlanCount($county_id);
        $nativeCount = $repo->getNativeCount($county_id);
        $dateExtensionCount = $repo->getDateExtensionCount($county_id);
        $expireCount = $repo->getExpireCount($county_id);

        $data = $repo->getFilteredData($county_id);
        $pagination = $data->links()->render();
        $data = $data->items();

        foreach ($data as $key => $value) {
            $data[$key]['hasPermOfUser'] = auth()->user()->hasPermOfUser($value->county);
            $data[$key]['dcUser'] = $value->dcUser()->first() ? $value->dcUser()->first()->toArray() : null;
            $data[$key]['rank_expired_date'] = $value->rank_expired_date;
            $data[$key]['is_expire'] = $value->is_expire;
        }
        //auth()->user()->hasPermOfUser($data_item->dcUser->dcUnit->county)

        return view('admin.dc-units.index', compact('data', 'counties', 'ranks', 'rankCount', 'pagination', 'withinPlanCount', 'nativeCount', 'dateExtensionCount', 'expireCount'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //地區清單
        $countyList = $this->countyList;
        $counties = $this->getCounties();
        $ranks = $this->getRanks();

        return view('admin.dc-units.create', compact('counties', 'ranks', 'countyList'));
    }

    private function getCounties($county_id = null)
    {
        if ($county_id) {
            $countyIdNames = User::where('id', $county_id)->where('type', 'county')->whereNull('county_id')->pluck('name', 'id')->toArray();
        } else {
            $countyIdNames = User::where('type', 'county')->whereNull('county_id')->pluck('name', 'id')->toArray();
        }

        return [null => '縣市'] + $countyIdNames;
    }





    /**
     * Store a newly created resource in storage.
     *
     * @param StoreDcUnitRequest $request
     * @param DcUnitRepositoryInterface $repo
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDcUnitRequest $request, DcUnitRepositoryInterface $repo)
    {
        $data = $repo->postData($request->all());

        $this->handleFiles($request, $data, 'dc-location');

        Flash::success(trans('app.createSuccess', ['type' => '防災士培訓 - 新增受訓者與防災士']));

        return redirect()->route('admin.dc-units.index');
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
        $data = DcUnit::find($id);
        $rank_end_date = "";
        $rank_exten_date = "";
        $countyList = $this->countyList;
        if (($data->rank == '一星' || $data->rank == '二星') && $data->rank_started_date) {
            if ($data->date_extension) {
            }
        } elseif ($data->rank == '三星' && $data->rank_started_date) {
            if ($data->date_extension) {
            }
        }
        $counties = $this->getCounties();
        $ranks = $this->getRanks();

        // 檢查權限
        /** @var User $user */
        $user = auth()->user();
        if (!$user->hasPermOfUser($data->county)) {
            return view('admin.dc-units.edit-readonly', compact('data', 'counties', 'ranks', 'countyList'));
        }

        return view('admin.dc-units.edit', compact('data', 'counties', 'ranks', 'countyList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreDcUnitRequest $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreDcUnitRequest $request, $id)
    {
        $dcUnit = DcUnit::find($id);

        // 檢查權限
        /** @var User $user */
        $user = auth()->user();
        if (!$user->hasPermOfUser($dcUnit->county)) {
            Flash::error('無該資料編輯權限');
            if ($request->has('response_json') && $request->get('response_json')) {
                $repo = new DcUnitRepository();
                $withinPlanCount = $repo->getWithinPlanCount();
                $user = Auth::user();
                $county_id = ($user->origin_role > 2 && $user->origin_role != 6) ? $user->id : null;
                $data = $repo->getFilteredData($county_id);
                $pagination = $data->links()->render();
                $data = $data->items();
                foreach ($data as $key => $value) {
                    $data[$key]['hasPermOfUser'] = auth()->user()->hasPermOfUser($value->county);
                    $data[$key]['dcUser'] = $value->dcUser();
                }
                return response()->json(['data' => $data, 'pagination' => $pagination, 'withinPlanCount' => $withinPlanCount]);
            } else {
                return redirect()->back();
            }
        }
        if (!$request->ajax()) {
            $data = $request->all();

            $dcUnit->update($data);
        } elseif ($request->has('native')) {
            $dcUnit->update(['native' => $request->get('native')]);
        } elseif ($request->has('within_plan')) {
            $dcUnit->update(['within_plan' => $request->get('within_plan')]);
        } elseif ($request->has('active')) {
            $dcUnit->update(['active' => $request->get('active')]);
        }
        if ($request->has('response_json') && $request->get('response_json')) {
            $repo = new DcUnitRepository();
            $withinPlanCount = $repo->getWithinPlanCount();
            $user = Auth::user();
            $county_id = ($user->origin_role > 2 && $user->origin_role != 6) ? $user->id : null;
            $data = $repo->getFilteredData($county_id);
            $pagination = $data->links()->render();
            $data = $data->items();
            foreach ($data as $key => $value) {
                $data[$key]['hasPermOfUser'] = auth()->user()->hasPermOfUser($value->county);
            }
            return response()->json(['data' => $data, 'pagination' => $pagination, 'withinPlanCount' => $withinPlanCount]);
        } else {
            $this->handleFiles($request, $dcUnit, 'dc-location');

            Flash::success(trans('app.updateSuccess', ['type' => '課程資料']));

            return redirect()->route('admin.dc-units.index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function editRank($id)
    {
        if (!\Laratrust::hasPermission('DC-rank-manage')) {
            abort(403);
        }
        $data = DcUnit::find($id);
        $ranks = $this->getRanks();

        return view('admin.dc-units.edit-rank', compact('data', 'ranks'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreDcUnitRankRequest $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function updateRank(StoreDcUnitRankRequest $request, $id)
    {
        if (!\Laratrust::hasPermission('DC-rank-manage')) {
            abort(403);
        }
        $dcUnit = DcUnit::find($id);
        $data = $request->only(['rank', 'rank_started_date', 'rank_year', 'extension_date', 'date_extension']);

        $dcUnit->update($data);

        Flash::success(trans('app.updateSuccess', ['type' => '課程資料']));

        return redirect()->route('admin.dc-units.index');
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
        $data = DcUnit::find($id);

        /** @var User $user */
        $user = auth()->user();
        if (!$user->hasPermOfUser($data->county)) {
            if (request()->has('response_json') && request()->get('response_json')) {
                $repo = new DcUnitRepository();
                $withinPlanCount = $repo->getWithinPlanCount();
                $user = Auth::user();
                $county_id = ($user->origin_role > 2 && $user->origin_role != 6) ? $user->id : null;
                $data = $repo->getFilteredData($county_id);
                $pagination = $data->links()->render();
                $data = $data->items();
                foreach ($data as $key => $value) {
                    $data[$key]['hasPermOfUser'] = auth()->user()->hasPermOfUser($value->county);
                }
                return response()->json(['data' => $data, 'pagination' => $pagination, 'withinPlanCount' => $withinPlanCount]);
            } else {
                Flash::error('無該資料編輯權限');

                return redirect()->back();
            }
        }
        $data->delete();
        if (request()->has('response_json') && request()->get('response_json')) {
            $repo = new DcUnitRepository();
            $withinPlanCount = $repo->getWithinPlanCount();
            $user = Auth::user();
            $county_id = ($user->origin_role > 2 && $user->origin_role != 6) ? $user->id : null;
            $data = $repo->getFilteredData($county_id);
            $pagination = $data->links()->render();
            $data = $data->items();
            foreach ($data as $key => $value) {
                $data[$key]['hasPermOfUser'] = auth()->user()->hasPermOfUser($value->county);
            }
            return response()->json(['data' => $data, 'pagination' => $pagination, 'withinPlanCount' => $withinPlanCount]);
        } else {
            Flash::success(trans('app.deleteSuccess', ['type' => '課程資料']));

            return redirect()->route('admin.dc-units.index');
        }
    }

    /**
     * @param DcUnitRepositoryInterface $repo
     * @return \Maatwebsite\Excel\BinaryFileResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function export(DcUnitRepositoryInterface $repo)
    {
        $user = Auth::user();
        $county_id = ($user->origin_role > 2 && $user->origin_role != 6) ? $user->id : null;
        return Excel::download(new DcUnitExport($repo->getAllFilteredData($county_id)), '韌性社區資料.xlsx');
    }
    public function exportRe(DcUnitRepositoryInterface $repo)
    {
        $user = Auth::user();
        $county_id = ($user->origin_role > 2 && $user->origin_role != 6) ? $user->id : null;
        return Excel::download(new DcUnitExportRe($repo->getAllFilteredData($county_id)), '韌性社區報表.xlsx');
        // return Excel::download(new DcUnitExportRe3Star($repo->getAllFilteredData($county_id)), '韌性社區報表.xlsx');
    }

    /**
     * @param DcUnitRepositoryInterface $repo
     * @return \Maatwebsite\Excel\BinaryFileResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function exportDcUser(DcUnitRepositoryInterface $repo)
    {
        $user = Auth::user();
        $county_id = ($user->origin_role > 2 && $user->origin_role != 6) ? $user->id : null;
        return Excel::download(new DcUserExport($repo->getAllFilteredData($county_id)), '韌性社區帳號.xlsx');
    }

    public function importForm()
    {
        return view('admin.dc-units.import');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function import(Request $request)
    {
        $this->validate($request, [
            'import_file' => 'required|mimes:xls,xlsx',
        ]);
        $userId = auth()->user()->id;

        $dcUnitImport = new DcUnitImport($userId);
        Excel::import($dcUnitImport, $request->file('import_file'));

        Flash::success(trans('app.importSuccess', [
            'type'    => '韌性社區資料',
            'success' => $dcUnitImport->successCount,
            'failed'  => $dcUnitImport->failedCount,
        ]));

        return redirect()->route('admin.dc-units.import')->with('errorMessages', $dcUnitImport->errorMessages);
    }

    public function downloadImportSample()
    {
        $withExample = \request()->exists('example');
        $fileName = $withExample ? '韌性社區公版格式_範例.xlsx' : '韌性社區公版格式.xlsx';
        $path = base_path('resources/import-sample/' . $fileName);

        return response()->download($path);
    }

    public function createDcUser(DcUnit $dcUnit)
    {
        return view('admin.dc-units.create-dc-user', compact('dcUnit'));
    }

    /**
     * @param Request $request
     * @param DcUnit $dcUnit
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storeDcUser(Request $request, DcUnit $dcUnit)
    {
        $this->validate($request, [
            'username' => ['required', Rule::unique('dc_users', 'username')->ignore(optional($dcUnit->dcUser)->id)],
            'password' => ['required', Password::min(12)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()] //|between:6,12',
        ], [
            'username.unique'   => '帳號重複！請輸入別的帳號。',
            'username.required' => '請輸入帳號',
            'password.between'  => '密碼長度請介於6~12之間!',
            'password.min' => '密碼長度必須12字元以上',
        ]);
        $dcUser = $dcUnit->dcUser()->updateOrCreate([], [
            'username' => $request->input('username'),
            'password' => bcrypt($request->input('password')),
            'active'   => 1,
        ]);
        Flash::success('帳號建立成功');

        return redirect()->route('admin.dc-units.index');
    }

    private function getRanks()
    {
        $ranks = ['未審查', '一星', '二星', '三星'];

        return array_combine($ranks, $ranks);
    }


    public function report(DcUnitRepositoryInterface $repo) {


        $user = Auth::user();
        $county_id = ($user->origin_role > 2 && $user->origin_role != 6) ? $user->id : null;
        $counties = $this->getCounties($county_id);
        $payload['rankCount'] = $repo->getRankCount($county_id);
        $payload['totalRank'] = ($payload['rankCount']['一星'] ?? 0) + ($payload['rankCount']['二星'] ?? 0) + ($payload['rankCount']['三星'] ?? 0);
        $payload['years'] = ['109', '110', '111', '112', '113'];
        $ranks = ['一星', '二星', '三星'];

        $payload['countyList'] = [
            '基隆市', '臺北市', '新北市', '桃園市', '新竹縣', '新竹市',
            '苗栗縣', '臺中市', '南投縣', '彰化縣', '雲林縣', '嘉義市', '嘉義縣',
            '臺南市', '高雄市', '屏東縣', '宜蘭縣', '花蓮縣', '臺東縣',
            '澎湖縣', '金門縣', '連江縣',
        ];

        // 先處理array 組成
        foreach ($payload['countyList'] as $county) {
            foreach ($payload['years'] as $year) {
                foreach ($ranks as $rank) {
                    $dataMap[$county][$year][$rank] = 0;
                }
            }
            foreach ($ranks as $rank) {
                $dataMap[$county]['total'][$rank] = 0;
            }
        }

        $countyIdNameMap = User::where('type', 'county')
            ->whereNull('county_id')
            ->pluck('name', 'id')
            ->toArray();

        // 加總動作
        $allData = $repo->getAllFilteredData($county_id);

        foreach($allData as $unit) {
            $rank = $unit->rank;
            $date = $unit->rank_started_date ? Carbon::parse($unit->rank_started_date) : null;

            if (!$date || !in_array($rank, $ranks)) {
                continue;
            }

            $year = (string)($date->year - 1911);
            $county = $countyIdNameMap[$unit->county_id] ?? null;

            if (!$county || !in_array($county, $payload['countyList']) || !in_array($year, $payload['years'])) {
                continue;
            }

            $dataMap[$county][$year][$rank]++;
            $dataMap[$county]['total'][$rank]++;
        }
        $payload['dataMaps'] = $dataMap;
        return view('admin.dc-units.report' , $payload);
    }

    public function getReport(Request $request)
    {
        // 1. 驗證輸入
        $validated = $request->validate([
            'county' => 'required|string|max:20',
            'year'   => 'required|integer',     // 民國年，如 '111'
            'rank'   => 'required|in:一星,二星,三星'
        ]);
        //撈取縣市ID
        $county = User::where('name',$request->get('county'))->first();
        $year = $request->get('year');
        $gregorianYear = (int)$validated['year'] + 1911;
        $units= DcUnit::with('author', 'county', 'dcUser')
                ->where('county_id', $county->id)  // 正確使用 ID
                ->where('rank' , $request->get('rank'))
                ->whereYear('rank_started_date', $gregorianYear)  // 假設用開始日期判年
                ->get();

        // 4. 回傳 JSON
        return response()->json([
            'success' => true,
            'data'    => $units,
            'count'   => $units->count()
        ]);

    }
}
