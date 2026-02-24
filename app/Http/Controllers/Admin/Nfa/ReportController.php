<?php

namespace App\Http\Controllers\Admin\Nfa;

use App\Exports\CountyReportExport;
use App\Exports\EvaluationCommissionExport;
use App\Exports\ReportExport;
use App\File;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReportRequest;
use App\Jobs\ZipReportDocuments;
use App\Nfa\Repositories\FileRepositoryInterface;
use App\Nfa\Repositories\ReportRepositoryInterface;
use App\Nfa\Repositories\UserRepositoryInterface;
use App\Nfa\Traits\FileUploadTrait;
use App\Report;
use App\Services\ReportsExportService;
use App\Topic;
use App\User;
use Carbon\Carbon;
use Flash;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\StaticPage;

class ReportController extends Controller
{
    use FileUploadTrait;

    /**
     * Display a listing of the resource.
     *
     * @param UserRepositoryInterface $userRepo
     * @param null $year
     * @return Response
     */
    public function index(UserRepositoryInterface $userRepo, $year = null)
    {
        if (!$year) {
            $year = request()->get('year', date('Y'));
        }
        $accounts = $userRepo->getCountyDistrictAccountsByClassThenArea();

        return view('admin.report.index', compact('year', 'accounts'));
    }

    public function sample()
    {
        $counties = User::with('county')->where('type', 'county')->orWhere('type', 'district')->get()
            ->pluck('full_county_name', 'id')->toArray();

        $topicQuery = Topic::with('rootTopic', 'reports.user.county')
            ->with(['reports' => function ($query) {
                $query->with(['files' => function ($query) {
                    $query->where('is_sample', true);
                }]);
            }])
            ->where('work_type', 'reports')
            ->whereHas('rootTopic', function ($query) {
                $query->where('year', '>=', 2018);
            });
        $topicOptions = (clone $topicQuery)
            ->select('id', 'title', 'category')->get()->each(function ($topicOption) {
                $topicOption->year = $topicOption->rootTopic->year ?? null;
            });
        $availableYears = $topicOptions->pluck('year', 'year')->sortKeysDesc();

        // 過濾
        if ($searchYear = request('year')) {
            $topicQuery->whereHas('rootTopic', function ($query) use ($searchYear) {
                $query->where('year', $searchYear);
            });
        }
        if ($searchTopic = Topic::find(request('topic_id'))) {
            $topicQuery->where('id', $searchTopic->id);
        }
        if ($searchCounty = User::find(request('county_id'))) {
            $topicQuery->with(['reports' => function ($query) use ($searchCounty) {
                $query->with(['files' => function ($query) {
                    $query->where('is_sample', true);
                }])->where('user_id', $searchCounty->id);
            }]);
        }

        $topics = $topicQuery->paginate(50);

        return view('admin.report.sample', compact('availableYears', 'counties', 'topicOptions', 'topics'));
    }

    public function sampleReview()
    {
        $counties = User::with('county')->where('type', 'county')->orWhere('type', 'district')->get()
            ->pluck('full_county_name', 'id')->toArray();

        $topicQuery = Topic::with('rootTopic', 'reports.user.county')
            ->with(['reports' => function ($query) {
                $query->with(['files' => function ($query) {
                    $query->where('is_recommend', true);
                }]);
            }])
            ->where('work_type', 'reports')
            ->whereHas('rootTopic', function ($query) {
                $query->where('year', '>=', 2018);
            });
        $topicOptions = (clone $topicQuery)
            ->select('id', 'title', 'category')->get()->each(function ($topicOption) {
                $topicOption->year = $topicOption->rootTopic->year ?? null;
            });
        $availableYears = $topicOptions->pluck('year', 'year')->sortKeysDesc();

        // 過濾
        if ($searchYear = request('year')) {
            $topicQuery->whereHas('rootTopic', function ($query) use ($searchYear) {
                $query->where('year', $searchYear);
            });
        }
        if ($searchTopic = Topic::find(request('topic_id'))) {
            $topicQuery->where('id', $searchTopic->id);
        }
        if ($searchCounty = User::find(request('county_id'))) {
            $topicQuery->with(['reports' => function ($query) use ($searchCounty) {
                $query->with(['files' => function ($query) {
                    $query->where('is_recommend', true);
                }])->where('user_id', $searchCounty->id);
            }]);
        }

        $topics = $topicQuery->paginate(50);

        return view('admin.report.sample-review', compact('availableYears', 'counties', 'topicOptions', 'topics'));
    }

    public function postSampleReviewUpdateIsSample(Request $request, File $file)
    {
        $file->update([
            'is_sample' => $request->get('is_sample'),
        ]);
        Flash::success('優良範本狀態已更新。');

        return back();
    }

    public function postSampleReviewUpdateMemo(Request $request, File $file)
    {
        $file->update([
            'memo' => $request->get('memo') ?? '',
        ]);
        Flash::success('評論已更新。');

        return back();
    }

    /**
     * Show the report with all the topics
     *
     * @param ReportRepositoryInterface $reportRepo
     * @param $year
     * @return Response
     */
    public function submit(ReportRepositoryInterface $reportRepo, $year)
    {

        $user = auth()->user();
        if (intval($year) < 2023) {
            $year = "2023";
        }
        /*if (!in_array($user->type, ['district', 'county'])) {
            Flash::error('使用縣市季進度管制表 - 資料上傳功能請用「縣市公所」或「鄉鎮市區」權限帳號登入。');
            //return redirect()->route('admin.seasonalReports.submit', ['year' => $year]);
            //return '請用縣市權限帳號登入。';
            return redirect()->back();
        }*/

        $report = $reportRepo->generateUsersCurrentReport($year);
        $report = collect($report)->sortBy('name', SORT_NUMERIC);

        //整理檔案，並依年份分類
        $fileList = [];
        foreach ($report as $category) {
            foreach ($category->items as $topic) {
                /** @var Topic $topic */
                $reportCollection = $topic->reportCollection()->with('files')->where('user_id', $user->id)->get();
                foreach ($reportCollection as $reportItem) {
                    foreach ($reportItem->files as $file) {
                        $itemYear = $file->created_at->year;
                        if (!isset($fileList[$topic->id][$itemYear])) {
                            $fileList[$topic->id][$itemYear] = [];
                        }
                        $fileList[$topic->id][$itemYear][] = $file;
                    }
                }
            }
        }
        array_walk($fileList, 'krsort');

        //dd($year);
        return view('admin.report.submit', compact('report', 'fileList', 'year'));
    }

    /**
     * @param File $file
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function deleteFileInSubmitPage(File $file)
    {
        /** @var User $user */
        $user = auth()->user();
        if (!$file->post || $file->post->user_id != $user->id) {
            abort(403);
        }
        $file->delete();
        if ($file->post->files->count() == 0) {
            $file->post->delete();
        }
        if (request()->has('response_json') && request()->get('response_json')) {
            return response()->json('ok');
        } else {
            Flash::success(trans('app.deleteSuccess', ['type' => '檔案']));

            return redirect()->back();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param ReportRepositoryInterface $reportRepo
     * @param $id
     * @return Response
     */
    public function create(ReportRepositoryInterface $reportRepo, $id)
    {
        $year = request()->get('year', date('Y'));
        $topic = Topic::with(['reports.files' => function ($query) use ($year) {
            $query->whereYear('created_at', $year);
        }])->find($id);

        $report = $reportRepo->findUsersCurrentTopicReportByTopicId($id, $year);

        //dd($report);

        return view('admin.report.create', compact('topic', 'report', 'year'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreReportRequest $request
     * @param ReportRepositoryInterface $reportRepo
     * @return Response
     */
    public function store(StoreReportRequest $request, ReportRepositoryInterface $reportRepo)
    {
        $report = $reportRepo->findOrCreateReportTopic($request->get('report_id'), $request->get('topic_id'));

        $year = $request->input('year', 2023);
        if ($year < 2023) {
            $year = 2023;
        }

        $this->handleFiles($request, $report, '', 'files', $year);

        return redirect()->route('admin.reports.submit', [
            'year'  => $year,
            'title' => $request->input('title', null),
        ]);
    }

    public function show(UserRepositoryInterface $userRepo, ReportRepositoryInterface $reportRepo, $id, $year = null)
    {
        if (!isset($year)) {
            $year = date('Y');
        }

        $user = $userRepo->findById($id);

        $county = $userRepo->findParentCountyOrNull($user);

        $fullName = $county === null ? $user->name : $county->name . ' - ' . $user->name;

        extract($reportRepo->generateReportByUserIdAndYear($user->id, $year));
        /** @var Report $report */
        $hasFiles = $this->reportHasFiles($report);

        // 是否為 歷史參考資料區
        $isHistoricalReferenceArea = $year <= 2017;
        $filteredYear = \request('filter_year');
        if ($isHistoricalReferenceArea) {
            // 歷史參考資料區，額外實做依照年份過濾
            // FIXME: 不該在此 function 直接分歧
            $existsYears = File::where('post_type', Report::class)
                ->select(DB::raw('YEAR(created_at) as created_year'))
                ->distinct()->pluck('created_year', 'created_year')
                ->filter(function ($year) {
                    return $year <= 2017;
                })
                ->sortKeysDesc()->toArray();

            $availableYears = [null => '顯示全部'] + $existsYears;
            view()->share(compact('availableYears'));
        }
        //整理檔案，並依年份分類
        $fileList = [];
        foreach ($report as $category) {
            foreach ($category->items as $topic) {
                /** @var Topic $topic */
                $reportCollection = $topic->reportCollection()->with('files')->where('user_id', $user->id)->get();
                if (!empty($reportCollection)) {
                    foreach ($reportCollection as $reportItem) {
                        foreach ($reportItem->files as $file) {
                            if (\request('filter_recommend') && !$file->is_recommend) {
                                //FIXME: 過濾推薦的暫時方案，應直接從 query 過濾
                                continue;
                            }
                            $checkYear = $file->created_at->year;
                            if ($filteredYear && $checkYear != $filteredYear) {
                                //FIXME: 依據年份過濾的暫時方案，應直接從 query 過濾
                                continue;
                            }
                            if (!isset($fileList[$topic->id][$checkYear])) {
                                $fileList[$topic->id][$checkYear] = [];
                            }
                            $fileList[$topic->id][$checkYear][] = $file;
                        }
                    }
                }
            }
        }
        array_walk($fileList, 'krsort');

        return view('admin.report.show', compact('user', 'fullName', 'report', 'year', 'hasFiles', 'fileList', 'isHistoricalReferenceArea'));
    }

    private function reportHasFiles($report)
    {
        foreach ($report as $category) {
            foreach ($category->items as $topic) {
                if (isset($topic->reports) && isset($topic->reports->files)) {
                    return true;
                }
            }
        }

        return false;
    }

    public function download(UserRepositoryInterface $userRepo, ReportRepositoryInterface $reportRepo, $id, $year = null)
    {
        if (!isset($year)) {
            $year = date('Y');
        }
        // $user = $userRepo->findByName($name);

        $report = $reportRepo->getReportFilesByUserIdAndYear($id, $year);

        $path = $this->dispatch(new ZipReportDocuments($report));

        return response()->download($path, "{$id}-執行成果.zip");
    }

    /**
     * @return \Maatwebsite\Excel\BinaryFileResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function downloadXlsx()
    {
        $fileName = '深耕資訊網資料查詢';

        return \Excel::download(new ReportExport($fileName, request()->all()), $fileName . '.xlsx');
    }

    /**
     * @return \Maatwebsite\Excel\BinaryFileResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function downloadXlsxByCounty()
    {
        $fileName = '深耕資訊網資料查詢';

        return \Excel::download(new CountyReportExport($fileName, request()->all()), $fileName . '.xlsx');
    }

    public function getPublicDates(ReportRepositoryInterface $reportRepo)
    {
        $publicDates = $reportRepo->getPublicDates();

        return view('admin.report.dates', compact('publicDates'));
    }

    public function updatePublicDate(ReportRepositoryInterface $reportRepo, Request $request, $id)
    {
        extract($request->only(['year', 'date', 'expire_soon_date', 'expire_date']));

        $publicDate = $reportRepo->updatePublicDate($id, $year, $date, $expire_soon_date, $expire_date);

        Flash::success(trans('app.updateSuccess', ['type' => '公開日期']));

        return redirect()->back();
    }

    /**
     *  2016 New code, 更改對民眾網開放權限。
     * @param FileRepositoryInterface $fileRepo
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggle(FileRepositoryInterface $fileRepo, $id)
    {
        /** @var File $file */
        $file = $fileRepo->find($id);

        if (!$file) {
            abort(404);
        }

        $file->opendata = !($file->opendata);
        $file->save();

        return redirect()->back();
    }

    /**
     * 設定優良範本
     * @param FileRepositoryInterface $fileRepo
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleIsSample(FileRepositoryInterface $fileRepo, $id)
    {
        /** @var File $file */
        $file = $fileRepo->find($id);

        if (!$file) {
            abort(404);
        }

        $file->is_sample = !($file->is_sample);
        $file->save();

        return redirect()->back();
    }

    /**
     *  inquire依分類及縣市，管理員使用。
     * @param UserRepositoryInterface $userRepo
     * @param ReportRepositoryInterface $reportRepo
     * @param int $year
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function inquire(UserRepositoryInterface $userRepo, ReportRepositoryInterface $reportRepo, $year = null)
    {
        if (request()->get('year')) {
            $year = request()->get('year');
        }
        if (!$year) {
            $year = date('Y');
        }
        //if ($year < 2017) $year = 2017;

        /*if ($year < date('Y')) {
            $startYear = $year;
        } else {
            $startYear = date('Y');
        }*/
        $startYear = date('Y') - 5;
        //$year = intval($year) < $startYear ? $startYear : (intval($year) > 2022 ? 2022 : intval($year));
        $availableYears = [];
        for ($i = 2023; $i <= 2026; $i++) {
            $availableYears[$i] = $i;
        }

        //地區清單
        $counties = User::where('type', 'county')->whereNull('county_id')->pluck('name', 'id')->toArray();
        $counties = [null => '-'] + $counties;
        //類別清單
        $startOfYear = Carbon::create($year, 1, 1, 0, 0, 0);
        $endOfYear = Carbon::create($year, 12, 31, 23, 59, 59);
        $categories = Topic::whereBetween('created_at', [$startOfYear, $endOfYear])
            ->pluck('title', 'id')
            ->toArray();
        $categories = [null => '-'] + $categories;
        //過濾
        $userQuery = User::whereIn('type', ['county', 'district']);
        $topicQuery = Topic::whereBetween('created_at', [$startOfYear, $endOfYear]);
        $topicQuery->where('id', '!=', 37)->where('work_type', 'reports');
        /** @var Report|Builder $reportQuery */
        //$reportQuery = Report::query();
        if ($countyId = request()->get('county_id')) {
            $countyUserIds = User::where('id', $countyId)->orWhere('county_id', $countyId)->pluck('id');
            $userQuery->whereIn('id', $countyUserIds);
            //$reportQuery->whereIn('user_id', $countyUserIds);
        }
        if ($categoryId = request()->get('category_id')) {
            $topicQuery->where('id', $categoryId);
            //$reportQuery->where('topic_id', $categoryId);
        }
        //$reportQuery->whereYear('created_at', '=', request()->get('year', $year)); //歷史資料市2016
        //取出
        $users = $userQuery->get();
        $topics = $topicQuery->get();
        //$reports = $reportQuery->get();
        $report_sql = "reports.topic_id,reports.user_id,GROUP_CONCAT(reports.id) AS report_count";
        $reports = User::selectRaw($report_sql)
            ->join('reports', function ($join) use ($year, $categoryId) {
                $join->on('reports.user_id', '=', 'users.id')
                    ->whereRaw("reports.topic_id IN (SELECT topics.id FROM topics WHERE YEAR(topics.created_at) = '{$year}' AND topics.work_type='reports')");
                if ($categoryId) {
                    $join->where("reports.topic_id", $categoryId);
                }
            })->where('users.type', 'county');
        if ($countyId) {
            $reports->where('users.id', $countyId);
        }
        $reports = $reports->groupBy('users.id')->groupBy('reports.topic_id')->get();
        $data = [];
        $county_files = [];
        foreach ($reports as $report) {
            $data[$report->topic_id][$report->user_id] = $report->report_count;
            $county_files[$report->user_id] = $report->report_count;
        }

        return view('admin.report.inquire', compact('counties', 'categories', 'topics', 'users', 'data', 'year', 'availableYears', 'county_files'));
    }

    /**
     *  distribute編輯縣市政府歷年統計民眾版靜態頁面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getDistribute()
    {
        $year = request()->get('year');
        if (empty($year)) {
            $year = intval(date('Y')) - 1911;
        }
        $availableYears = [];
        for ($i = 116; $i >= 112; $i--) {
            $availableYears[$i] = $i;
        }
        $countyOptions = User::where('type', 'county')->get(['id', 'name']);
        $user = auth()->user();
        $data = null;
        $method = '-edit';
        if ($user->type == 'county') {
            $county_id = $user->id;
            $countyOptions = $countyOptions->where('id', '=', $user->id)->pluck('name', 'id')->toArray();
            //取得縣市政府統計表
            $id = "report-{$year}-{$user->id}";
            $title = "{$user->name}政府{$year}年統計";
            if (!$data = StaticPage::where('id', '=', $id)->first()) {
                //無縣市政府統計表，取得統計表樣本
                $method = '-create';
                if ($data = StaticPage::where('id', '=', "report-{$year}")->first(['id', 'title', 'content'])) {
                    $data = $data->toArray();
                    $data['id'] = $id;
                    $data['title'] = $title;
                    $data['no_temp'] = false;
                } else {
                    $data = ['id' => $id, 'title' => '尚未建立樣板表格', 'content' => '', 'no_temp' => true];
                }
            } else {
                $data = $data->toArray();
            }
        } else {
            $oldCountyOptions = $countyOptions->pluck('name', 'id')->toArray();
            //取得統計表樣本
            $countyOptions = [0 => '統計樣板'];
            foreach ($oldCountyOptions as $key => $value) {
                $countyOptions[$key] = $value;
            }
            $county_id = request()->get('county_id', '0');
            if ($county_id != '0') {
                $id = "report-{$year}-{$county_id}";
                $title = $countyOptions[$county_id] . "政府{$year}年統計";
                if (!$data = StaticPage::where('id', '=', $id)->first()) {
                    //無縣市政府統計表，取得統計表樣本
                    $method = '-create';
                    if ($data = StaticPage::where('id', '=', "report-{$year}")->first(['id', 'title', 'content'])) {
                        $data = $data->toArray();
                        $data['id'] = $id;
                        $data['title'] = $title;
                        $data['no_temp'] = false;
                    } else {
                        $data = ['id' => $id, 'title' => '尚未建立樣板表格', 'content' => '', 'no_temp' => true];
                    }
                } else {
                    $data = $data->toArray();
                }
            } else {
                $id = "report-{$year}";
                $title = "縣市政府{$year}年統計樣板";
                if (!$data = StaticPage::where('id', '=', $id)->first()) {
                    $method = '-create';
                    $data = ['id' => $id, 'title' => $title, 'content' => '', 'no_temp' => false];
                }
            }
        }
        return view('admin.report.distribute' . $method, compact('user', 'data', 'year', 'county_id', 'availableYears', 'countyOptions'));
    }

    /**
     *  update儲存縣市政府歷年統計民眾版靜態頁面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function updateDistribute()
    {
        $data = request()->only(['content', 'id', 'title']);
        try {
            if (!StaticPage::where('id', '=', $data['id'])->first()) {
                StaticPage::create($data);
            } else {
                StaticPage::where('id', '=', $data['id'])->update($data);
            }
            Flash::success($data['title'] . "儲存成功");

            return redirect()->back();
        } catch (\Throwable $th) {
            Flash::error("無法儲存" . $data['title'] . ' - ' . $th->getMessage());

            return redirect()->back();
        }
    }

    public function deleteDistribute()
    {
        $data = request()->only(['id', 'title']);
        try {
            if (StaticPage::where('id', '=', $data['id'])->first()) {
                StaticPage::where('id', '=', $data['id'])->delete();
            }
            Flash::success($data['title'] . "刪除成功");

            return redirect()->back();
        } catch (\Throwable $th) {
            Flash::error("無法刪除" . $data['title'] . ' - ' . $th->getMessage());

            return redirect()->back();
        }
    }

    /**
     *  inquire依分類及縣市，縣市及公所使用。
     * @param UserRepositoryInterface $userRepo
     * @param ReportRepositoryInterface $reportRepo
     * @param int $year
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function inquireByCounty(UserRepositoryInterface $userRepo, ReportRepositoryInterface $reportRepo, $year = null)
    {
        if (request()->get('year')) {
            $year = request()->get('year');
        }
        if (!$year) {
            $year = date('Y');
        }
        $availableYears = [];
        for ($i = 2027; $i >= 2023; $i--) {
            $availableYears[$i] = $i;
        }

        //強制地區
        $county = User::where('id', auth()->user()->id)->where('type', 'county')->first();
        if (!$county) {
            $isDistrict = true;
            //return '請用縣市或鄉鎮區公所帳號登入。';
        }
        //類別清單
        $startOfYear = Carbon::create($year, 1, 1, 0, 0, 0);
        $endOfYear = Carbon::create($year, 12, 31, 23, 59, 59);
        //$categories = Topic::whereBetween('created_at', [$startOfYear, $endOfYear])
        //    ->pluck('title', 'id')
        //    ->toArray();
        //$categories = [null => '-'] + $categories;
        //過濾
        $userQuery = User::whereNotNull('type');
        $topicQuery = Topic::whereBetween('created_at', [$startOfYear, $endOfYear]);
        $topicQuery = $topicQuery->where('id', '!=', 37)->where('work_type', 'reports');
        /** @var Report|Builder $reportQuery */
        //$reportQuery = Report::query();

        if (isset($isDistrict)) {
            $countyUserIds = [auth()->user()->id];
        } else {
            $countyUserIds = User::where('id', $county->id)->orWhere('county_id', $county->id)->pluck('id');
        }

        $userQuery->whereIn('id', $countyUserIds);
        //$reportQuery->with('files')->whereIn('user_id', $countyUserIds);
        if ($categoryId = request()->get('category_id')) {
            $topicQuery->where('id', $categoryId);
            //$reportQuery->where('topic_id', $categoryId);
        }

        //取出
        $users = $userQuery->get();

        $topics = $topicQuery->get();
        //$reports = $reportQuery->get();
        $report_sql = "reports.topic_id,reports.user_id,GROUP_CONCAT(reports.id) AS report_count";
        $reports = User::selectRaw($report_sql)
            ->join('reports', function ($join) use ($year, $categoryId) {
                $join->on('reports.user_id', '=', 'users.id')
                    ->whereRaw("reports.topic_id IN (SELECT topics.id FROM topics WHERE YEAR(topics.created_at) = '{$year}' AND topics.work_type='reports')");
                if ($categoryId) {
                    $join->where("reports.topic_id", $categoryId);
                }
            })->where('users.type', 'county');
        if ($countyUserIds) {
            $reports->whereIn('users.id', $countyUserIds);
        }
        $reports = $reports->groupBy('users.id')->groupBy('reports.topic_id')->get();
        $data = [];
        $county_files = [];
        foreach ($reports as $report) {
            $data[$report->topic_id][$report->user_id] = $report->report_count;
            $county_files[$report->user_id] = $report->report_count;
        }
        $categories = ['' => '-'] + $topicQuery->pluck('title', 'id')->toArray();

        /**
         * 2024/05/30 下載檔案SQL
         * SELECT files.* FROM `topics`
         * JOIN reports ON reports.topic_id=topics.id AND reports.user_id=12
         * JOIN files ON files.post_id=reports.id AND files.post_type='App\\Report'
         * WHERE topics.work_type='reports';
         */
        return view('admin.report.inquireByCounty', compact('categories', 'year', 'topics', 'users', 'data', 'county_files', 'availableYears'));
    }

    public function downloadFilesByCounty()
    {

        $year = request()->get('year');
        $user_id = request()->get('user_id');
        $topic = Topic::where('work_type', 'reports')->whereRaw("YEAR(created_at) = '{$year}'")->get();
        $user = User::where('id', $user_id)->first();
        $category_id = request()->get('category_id');
        $reports = User::join('reports', function ($join) use ($year, $category_id) {
            $join->on('reports.user_id', '=', 'users.id')
                ->whereRaw("reports.topic_id IN (SELECT topics.id FROM topics WHERE YEAR(topics.created_at) = '{$year}' AND topics.work_type='reports')");
            if ($category_id) {
                $join->where("reports.topic_id", $category_id);
            }
        })
            ->join('files', function ($join) {
                $join->on('files.post_id', '=', 'reports.id')
                    ->where('files.post_type', '=', 'App\Report');
            })
            ->where('users.type', 'county')
            ->where('users.id', $user_id)
            ->get(['files.id', 'files.post_id', 'files.post_type', 'files.name', 'files.path', 'files.mime_type', 'files.file_size']);
        if ($reports) {
            $reports = $reports->toArray();
        }
        $reportsExportService = new ReportsExportService();
        $zipFile = $reportsExportService->export($year, $user, $reports);
        if (empty($zipFile)) {
            return redirect()->back();
        } else {
            return response()->download($zipFile);
        }
    }

    /**
     * @param UserRepositoryInterface $userRepo
     * @param int $year
     * @return Response
     */
    public function evaluationCommission(UserRepositoryInterface $userRepo, $year = 2023)
    {
        if (request()->get('year')) {
            $year = request()->get('year');
        }
        $availableYears = [];
        for ($i = 2027; $i >= 2023; $i--) {
            $availableYears[$i] = $i;
        }

        $accounts = $userRepo->getCountyDistrictAccountsByClassThenArea();

        return view('admin.report.evaluationCommission.index', compact('year', 'availableYears', 'accounts'));
    }

    /**
     * @param UserRepositoryInterface $userRepo
     * @param $id
     * @param int $year
     * @return Response
     */
    public function evaluationCommissionShow(UserRepositoryInterface $userRepo, $id, $year = 2016)
    {
        if (request()->get('year')) {
            $year = request()->get('year');
        }
        $startYear = min($year, date('Y'));
        $availableYears = [];
        for ($i = 2022; $i >= $startYear; $i--) {
            $availableYears[$i] = $i;
        }

        /** @var User $user */
        $user = $userRepo->findById($id);

        /** @var User $county */
        $county = $userRepo->findParentCountyOrNull($user);

        $fullName = $county === null ? $user->name : $county->name . ' - ' . $user->name;

        $files = File::wherePostType(Report::class)->whereIn('post_id', $user->reports->pluck('id'))
            ->whereYear('created_at', '=', $year)
            ->with('post.user', 'post.topic')->orderBy('created_at')->get();


        return view('admin.report.evaluationCommission.show', compact('user', 'fullName', 'year', 'files'));
    }

    /**
     * @param UserRepositoryInterface $userRepo
     * @param $id
     * @param int $year
     * @return \Maatwebsite\Excel\BinaryFileResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function evaluationCommissionExport(UserRepositoryInterface $userRepo, $id, $year = 2016)
    {
        if (request()->get('year')) {
            $year = request()->get('year');
        }
        $startYear = min($year, date('Y'));
        $availableYears = [];
        for ($i = 2022; $i >= $startYear; $i--) {
            $availableYears[$i] = $i;
        }

        /** @var User $user */
        $user = $userRepo->findById($id);

        /** @var User $county */
        $county = $userRepo->findParentCountyOrNull($user);

        $fullName = $county === null ? $user->name : $county->name . ' - ' . $user->name;

        $files = File::wherePostType(Report::class)->whereIn('post_id', $user->reports->pluck('id'))
            ->whereYear('created_at', '=', $year)
            ->with('post.user', 'post.topic')->orderBy('created_at')->get();

        return \Excel::download(new EvaluationCommissionExport($files), '管考作業 - ' . $fullName . '.xlsx');
    }
}
