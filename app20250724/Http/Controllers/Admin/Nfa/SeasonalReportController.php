<?php

namespace App\Http\Controllers\Admin\Nfa;

use App\Exports\CountyReportExport;
use App\Exports\ReportExport;
use App\File;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSeasonalReportRequest;
use App\Jobs\ZipSeasonalReportDocuments;
use App\Nfa\Repositories\FileRepositoryInterface;
use App\Nfa\Repositories\SeasonalReportRepositoryInterface;
use App\Nfa\Repositories\UserRepositoryInterface;
use App\Nfa\Traits\FileUploadTrait;
use App\SeasonalReport;
use App\Services\SeasonalReportExportService;
use App\Topic;
use App\User;
use Carbon\Carbon;
use Flash;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SeasonalReportController extends Controller
{
    use FileUploadTrait;

    /**
     * Display a listing of the resource.
     *
     * @param UserRepositoryInterface $userRepo
     * @param null $year
     * @param null $season
     * @return Response
     */
    public function index(UserRepositoryInterface $userRepo, $year = null, $season = null)
    {
        $availableYears = [];
        for ($i = 2027; $i >= 2023; $i--) {
            $availableYears[] = $i;
        }
        if (request()->has('year')) {
            $year = request()->get('year');
        }
        if (!$year) {
            $year = date('Y');
        }

        $accounts = $userRepo->getCountyDistrictAccountsByClassThenArea();

        //dd($accounts);

        return view('admin.seasonalReport.index', compact('year', 'season', 'availableYears', 'accounts'));
    }

    /**
     * Show the report with all the topics
     *
     * @param SeasonalReportRepositoryInterface $reportRepo
     * @param $year
     * @return Response
     */
    public function submit(SeasonalReportRepositoryInterface $reportRepo, $year)
    {
        $user = auth()->user();
        if (!in_array($user->type, ['county'])) {
            return '請用縣市帳號登入...';
        }

        $report = $reportRepo->generateUsersCurrentSeasonalReport($year);

        //整理檔案，並依年份分類
        $fileList = [];
        foreach ($report as $category) {
            foreach ($category->items as $topic) {
                /** @var Topic $topic */
                $reportCollection = $topic->seasonalReportCollection()->with('files')
                    ->where('user_id', $user->id)->get();

                // if ($topic->title == '調查民間志工團體資源') {dd($reportCollection);}

                foreach ($reportCollection as $reportItem) {
                    foreach ($reportItem->files as $file) {
                        $info = explode('_', $file->memo);
                        if (!$info[0]) {
                            continue;
                        }
                        $year = $info[0];
                        $season = $info[1];
                        if (!isset($fileList[$topic->id][$year][$season])) {
                            $fileList[$topic->id][$year][$season] = [];
                            array_walk($fileList[$topic->id], 'ksort');
                        }
                        $fileList[$topic->id][$year][$season][] = $file;
                    }
                }
            }
        }
        array_walk($fileList, 'krsort');

        return view('admin.seasonalReport.submit', compact('report', 'fileList'));
    }

    public function downloadFilesByCounty()
    {

        $year = request()->get('year');
        $season = '';
        if (in_array(request()->get('season'), ['1', '2', '3'])) {
            $season = request()->get('season');
        }
        $user_id = request()->get('user_id');
        $topic = Topic::where('work_type', 'seasonalReports')->whereRaw("YEAR(created_at) = '{$year}'")->get();
        $user = User::where('id', $user_id)->first();
        $category_id = request()->get('category_id');
        $reports = User::join('seasonal_reports', function ($join) use ($year, $category_id) {
            $join->on('seasonal_reports.user_id', '=', 'users.id')
                ->whereRaw("seasonal_reports.topic_id IN (SELECT topics.id FROM topics WHERE YEAR(topics.created_at) = '{$year}' AND topics.work_type='seasonalReports')");
            if ($category_id) {
                $join->where("seasonal_reports.topic_id", $category_id);
            }
        })
            ->join('files', function ($join) use ($year, $season) {
                $join->on('files.post_id', '=', 'seasonal_reports.id')
                    ->where('files.post_type', '=', 'App\SeasonalReport');
                if (!empty($season)) {
                    $join->where('files.memo', '=', "{$year}_{$season}");
                }
            })
            ->where('users.type', 'county')
            ->where('users.id', $user_id)
            ->get(['files.id', 'files.post_id', 'files.post_type', 'files.name', 'files.path', 'files.mime_type', 'files.file_size']);
        if ($reports) {
            $reports = $reports->toArray();
        }
        $reportsExportService = new SeasonalReportExportService();
        $zipFile = $reportsExportService->export($year, $season, $user, $reports);
        if (empty($zipFile)) {
            return redirect()->back();
        } else {
            return response()->download($zipFile);
        }
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
     * @param SeasonalReportRepositoryInterface $reportRepo
     * @param $id
     * @return Response
     */
    public function create(SeasonalReportRepositoryInterface $reportRepo, $id)
    {
        $topic = Topic::with('seasonalReports.files')->find($id);

        $report = $reportRepo->findUsersCurrentTopicSeasonalReportByTopicId($id);

        $years = [];
        //for ($i = (int)date('Y'); $i >= 2018; $i--) {
        for ($i = 2027; $i >= 2023; $i--) {
            $years[$i] = $i;
        }
        $year = date('Y');

        return view('admin.seasonalReport.create', compact('topic', 'report', 'years', 'year'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreSeasonalReportRequest $request
     * @param SeasonalReportRepositoryInterface $reportRepo
     * @return Response
     */
    public function store(StoreSeasonalReportRequest $request, SeasonalReportRepositoryInterface $reportRepo)
    {
        $report = $reportRepo->findOrCreateSeasonalReportTopic(
            $request->get('report_id'),
            $request->get('topic_id')
        );

        $file_memo = $request->get('year') . '_' . $request->get('season');
        $this->handleFiles($request, $report, $file_memo);

        return redirect()->route('admin.seasonalReports.submit', ['year' => $request->get('year')]);
    }

    public function show(UserRepositoryInterface $userRepo, SeasonalReportRepositoryInterface $reportRepo, $id, $year = null, $season = null)
    {
        if (!$year) {
            $year = date('Y');
        }

        $user = $userRepo->findById($id);

        $county = $userRepo->findParentCountyOrNull($user);

        $fullName = $county === null ? $user->name : $county->name . ' - ' . $user->name;

        extract($reportRepo->generateSeasonalReportByUserIdAndYear($user->id, $year));
        /** @var SeasonalReport $report */
        $hasFiles = $this->reportHasFiles($report);

        //整理檔案，並依年份分類
        $targetSeason = $season;
        $fileList = [];
        foreach ($report as $category) {
            foreach ($category->items as $topic) {
                /** @var Topic $topic */
                $reportCollection = $topic->seasonalReportCollection()->with('files')
                    ->where('user_id', $user->id)->get();
                foreach ($reportCollection as $reportItem) {
                    foreach ($reportItem->files as $file) {
                        $info = explode('_', $file->memo);
                        if (!$info[0]) {
                            continue;
                        }
                        $year = $info[0];
                        $season = $info[1];
                        if ($targetSeason && $targetSeason != $season) {
                            continue;
                        }

                        if (!isset($fileList[$topic->id][$year][$season])) {
                            $fileList[$topic->id][$year][$season] = [];
                            array_walk($fileList[$topic->id], 'ksort');
                        }
                        $fileList[$topic->id][$year][$season][] = $file;
                    }
                }
            }
        }
        array_walk($fileList, 'krsort');

        return view(
            'admin.seasonalReport.show',
            compact('user', 'fullName', 'report', 'year', 'hasFiles', 'fileList', 'season')
        );
    }

    private function reportHasFiles($report)
    {
        foreach ($report as $category) {
            foreach ($category->items as $topic) {
                if (isset($topic->seasonalReports) && isset($topic->seasonalReports->files)) {
                    return true;
                }
            }
        }

        return false;
    }

    public function download(UserRepositoryInterface $userRepo, SeasonalReportRepositoryInterface $reportRepo, $id, $year = null)
    {
        if (!isset($year)) {
            $year = date('Y');
        }
        // $user = $userRepo->findByName($name);

        $report = $reportRepo->getSeasonalReportFilesByUserIdAndYear($id, $year);

        $path = $this->dispatch(new ZipSeasonalReportDocuments($report));

        return response()->download($path, "{$id}-執行成果.zip");
    }

    /**
     * @return \Maatwebsite\Excel\BinaryFileResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function downloadXlsx()
    {
        $fileName = '執行進度管制表';

        return \Excel::download(new ReportExport($fileName, request()->all()), $fileName . '.xlsx');
    }

    /**
     * @return \Maatwebsite\Excel\BinaryFileResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function downloadXlsxByCounty()
    {
        $fileName = '執行進度管制表';

        return \Excel::download(new CountyReportExport($fileName, request()->all()), $fileName . '.xlsx');
    }

    public function getPublicDates(SeasonalReportRepositoryInterface $reportRepo)
    {
        $publicDates = $reportRepo->getPublicDates();

        return view('admin.seasonalReport.dates', compact('publicDates'));
    }

    public function updatePublicDate(SeasonalReportRepositoryInterface $reportRepo, Request $request, $id)
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
     *  2016 New code, inquire依分類及縣市。
     * @param UserRepositoryInterface $userRepo
     * @param SeasonalReportRepositoryInterface $reportRepo
     * @param int $year
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function inquire(UserRepositoryInterface $userRepo, SeasonalReportRepositoryInterface $reportRepo, $year = 2023)
    {
        if (request()->get('year')) {
            $year = request()->get('year');
        }
        if (!$year) {
            $year = date('Y');
        }
        $startYear = date('Y') - 5;

        $availableYears = [];
        for ($i = 2027; $i >= 2023; $i--) {
            $availableYears[] = $i;
        }

        $season = request()->get('season');

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
        $userQuery = User::whereNotNull('type')->where('type', 'county');
        $topicQuery = Topic::where('work_type', 'seasonalReports'); //whereBetween('created_at', [$startOfYear, $endOfYear]);
        $topicQuery->with('SeasonalReports')
            ->whereBetween('created_at', [$startOfYear, $endOfYear]);

        /** @var SeasonalReport|Builder $reportQuery */
        $reportQuery = SeasonalReport::with('files');
        if ($countyId = request()->get('county_id')) {
            $countyUserIds = User::where('id', $countyId)->orWhere('county_id', $countyId)->pluck('id');
            $userQuery->whereIn('id', $countyUserIds);
            $reportQuery->whereIn('user_id', $countyUserIds);
        }
        if ($categoryId = request()->get('category_id')) {
            $topicQuery->where('id', $categoryId);
            $reportQuery->where('topic_id', $categoryId);
        }
        $reportQuery->whereYear('created_at', '=', request()->get('year', $year));
        $users = $userQuery->get();
        $topics = $topicQuery->get();
        $seasonalReports = $reportQuery->get();
        $data = [];
        $county_files = [];

        foreach ($seasonalReports as $report) {
            foreach ($report->files as $file) {
                $file_info = explode('_', $file->memo);
                $file_year = $file_info[0];
                $file_season = $file_info[1];

                if ($year == $file_year && ($season == '' || $season == $file_season)) {
                    $data[$report->topic_id][$report->user_id] = isset($data[$report->topic_id][$report->user_id])
                        ? $data[$report->topic_id][$report->user_id]++ : 1;
                    if (!array_key_exists($report->user_id, $county_files)) {
                        $county_files[$report->user_id] = 0;
                    }
                    $county_files[$report->user_id]++;
                }
            }
        }

        return view('admin.seasonalReport.inquire', compact('county_files', 'counties', 'categories', 'topics', 'users', 'data', 'year', 'availableYears'));
    }

    /**
     *  2016 New code, inquire依分類及縣市。
     * @param UserRepositoryInterface $userRepo
     * @param SeasonalReportRepositoryInterface $reportRepo
     * @param int $year
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function inquireByCounty(UserRepositoryInterface $userRepo, SeasonalReportRepositoryInterface $reportRepo, $year = null)
    {
        $availableYears = [];
        for ($i = 2027; $i >= 2023; $i--) {
            $availableYears[$i] = $i;
        }
        if (request()->has('year')) {
            $year = request()->get('year');
        }
        if (!$year) {
            $year = date('Y');
        }

        $season = request()->get('season');

        //強制地區
        $county = User::where('id', auth()->user()->id)->where('type', 'county')->first();
        if (!$county) {
            return redirect()->back();
        }
        //類別清單
        $startOfYear = Carbon::create($year, 1, 1, 0, 0, 0);
        $endOfYear = Carbon::create($year, 12, 31, 23, 59, 59);
        $categories = Topic::where('work_type', 'seasonalReports')->whereBetween('created_at', [$startOfYear, $endOfYear])
            ->pluck('title', 'id')
            ->toArray();
        $categories = [null => '-'] + $categories;
        //過濾
        $userQuery = User::whereNotNull('type');

        $user = auth()->user();

        $topicQuery = Topic::where('work_type', 'seasonalReports'); //whereBetween('created_at', [$startOfYear, $endOfYear]);
        $topicQuery->with('SeasonalReports')
            //->where('type', 'county')
            ->whereBetween('created_at', [$startOfYear, $endOfYear]);
        $topicQuery->where(function ($query) use ($user) {
            /** @var \Illuminate\Database\Eloquent\Builder $query */
            $query->where('levels', '1.2') //通用
                ->orWhere('user_id', $user->id);
        });

        /** @var SeasonalReport|Builder $reportQuery */
        $reportQuery = SeasonalReport::with('files');

        $countyUserIds = User::where('id', $county->id)->orWhere('county_id', $county->id)->pluck('id');
        $userQuery->whereIn('id', $countyUserIds);
        $userQuery->where('type', 'county');
        $reportQuery->whereIn('user_id', $countyUserIds);
        if ($categoryId = request()->get('category_id')) {
            $topicQuery->where('id', $categoryId);
            $reportQuery->where('topic_id', $categoryId);
        }
        $reportQuery->whereYear('created_at', '=', request()->get('year', $year));
        //取出
        $users = $userQuery->get();
        $topics = $topicQuery->get();
        //dd($topics);
        $topics = $reportRepo->sortTopicsIntoCategories($topics, $year, false);

        $seasonalReports = $reportQuery->get();
        $data = [];
        $county_files = [];
        foreach ($seasonalReports as $report) {
            foreach ($report->files as $file) {
                $file_info = explode('_', $file->memo);
                $file_year = $file_info[0];
                $file_season = $file_info[1];

                if ($year == $file_year && ($season == '' || $season == $file_season)) {
                    $data[$report->topic_id][$report->user_id] = isset($data[$report->topic_id][$report->user_id])
                        ? $data[$report->topic_id][$report->user_id]++ : 1;
                    if (!array_key_exists($report->user_id, $county_files)) {
                        $county_files[$report->user_id] = 0;
                    }
                    $county_files[$report->user_id]++;
                }
            }
        }

        return view('admin.seasonalReport.inquireByCounty', compact('county_files', 'categories', 'topics', 'users', 'year', 'data', 'availableYears'));
    }
}
