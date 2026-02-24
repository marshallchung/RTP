<?php

namespace App\Http\Controllers\Admin\Nfa;

use App\Exports\CountyResultIIIExport;
use App\Exports\ResultIIIExport;
use App\File;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReportRequest;
use App\Nfa\Repositories\ResultIIIRepositoryInterface;
use App\Nfa\Repositories\UserRepositoryInterface;
use App\Nfa\Traits\FileUploadTrait;
use App\Report;
use App\Topic;
use App\User;
use Flash;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Response;

class ResultIIIController extends Controller
{
    use FileUploadTrait;

    /**
     * Display a listing of the resource.
     *
     * @param UserRepositoryInterface $userRepo
     * @param null $year
     * @return Response
     */
    public function index(UserRepositoryInterface $userRepo)
    {
        $accounts = $userRepo->getCountyDistrictAccountsByClassThenArea();

        return view('admin.resultiii.index', compact('accounts'));
    }

    /**
     * Show the report with all the topics
     *
     * @param ResultIIIRepositoryInterface $resultIIIRepo
     * @param $year
     * @return Response
     */
    public function submit(ResultIIIRepositoryInterface $resultIIIRepo)
    {
        $user = auth()->user();
        if (!in_array($user->type, ['district', 'county'])) {
            Flash::error('使用縣市季進度管制表 - 資料上傳功能請用「縣市公所」或「鄉鎮市區」權限帳號登入。');
            //return redirect()->route('admin.seasonalReports.submit', ['year' => $year]);
            //return '請用縣市權限帳號登入。';
            return redirect()->back();
        }

        $report = $resultIIIRepo->generateUsersCurrentReport();
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
        return view('admin.resultiii.submit', compact('report', 'fileList'));
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
        Flash::success(trans('app.deleteSuccess', ['type' => '檔案']));

        return redirect()->back();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param ResultIIIRepositoryInterface $resultIIIRepo
     * @param $id
     * @return Response
     */
    public function create(ResultIIIRepositoryInterface $resultIIIRepo, $id)
    {
        $topic = Topic::with('reports.files')->find($id);

        $report = $resultIIIRepo->findUsersCurrentTopicReportByTopicId($id);

        return view('admin.resultiii.create', compact('topic', 'report'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreReportRequest $request
     * @param ResultIIIRepositoryInterface $resultIIIRepo
     * @return Response
     */
    public function store(StoreReportRequest $request, ResultIIIRepositoryInterface $resultIIIRepo)
    {
        $report = $resultIIIRepo->findOrCreateReportTopic($request->get('report_id'), $request->get('topic_id'));

        $this->handleFiles($request, $report);

        return redirect()->route('admin.resultiii.submit', [
            'title' => $request->input('title'),
        ]);
    }

    public function show(UserRepositoryInterface $userRepo, ResultIIIRepositoryInterface $resultIIIRepo, $id)
    {
        $user = $userRepo->findById($id);

        $county = $userRepo->findParentCountyOrNull($user);

        $fullName = $county === null ? $user->name : $county->name . ' - ' . $user->name;

        extract($resultIIIRepo->generateReportByUserId($user->id));
        /** @var Report $report */
        $hasFiles = $this->reportHasFiles($report);

        //整理檔案，並依年份分類
        $fileList = [];
        foreach ($report as $category) {
            foreach ($category->items as $topic) {
                /** @var Topic $topic */
                $reportCollection = $topic->reportCollection()->with('files')->where('user_id', $user->id)->get();
                if (!empty($reportCollection)) {
                    foreach ($reportCollection as $reportItem) {
                        foreach ($reportItem->files as $file) {
                            $checkYear = $file->created_at->year;
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

        return view('admin.resultiii.show', compact('user', 'fullName', 'report', 'hasFiles', 'fileList'));
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

    /**
     * @return \Maatwebsite\Excel\BinaryFileResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function downloadXlsx()
    {
        $fileName = '深耕資訊網資料查詢';

        return \Excel::download(new ResultIIIExport(request()->all()), $fileName . '.xlsx');
    }

    /**
     * @return \Maatwebsite\Excel\BinaryFileResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function downloadXlsxByCounty()
    {
        $fileName = '深耕資訊網資料查詢';

        return \Excel::download(new CountyResultIIIExport(request()->all()), $fileName . '.xlsx');
    }

    /**
     *  inquire依分類及縣市，管理員使用。
     * @param UserRepositoryInterface $userRepo
     * @param ResultIIIRepositoryInterface $resultIIIRepo
     * @param int $year
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function inquire(UserRepositoryInterface $userRepo, ResultIIIRepositoryInterface $resultIIIRepo)
    {
        //地區清單
        $counties = User::where('type', 'county')->whereNull('county_id')->pluck('name', 'id')->toArray();
        $counties = [null => '-'] + $counties;

        //過濾
        $userQuery = User::whereNotNull('type');
        $topicQuery = Topic::query();
        $topicQuery->where('work_type', 'resultiii');
        /** @var Report|Builder $reportQuery */
        $reportQuery = Report::query();
        if ($countyId = request()->get('county_id')) {
            $countyUserIds = User::where('id', $countyId)->orWhere('county_id', $countyId)->pluck('id');
            $userQuery->whereIn('id', $countyUserIds);
            $reportQuery->whereIn('user_id', $countyUserIds);
        }
        if ($categoryId = request()->get('category_id')) {
            $topicQuery->where('id', $categoryId);
            $reportQuery->where('topic_id', $categoryId);
        }

        //取出
        $users = $userQuery->get();
        $topics = $topicQuery->get();
        $reports = $reportQuery->get();
        $data = [];
        foreach ($reports as $report) {
            $data[$report->topic_id][$report->user_id] = isset($data[$report->topic_id][$report->user_id])
                ? $data[$report->topic_id][$report->user_id]++ : 1;
        }

        $categories = ['' => '-'] + $topicQuery->pluck('title', 'id')->toArray();

        return view('admin.resultiii.inquire', compact('counties', 'categories', 'topics', 'users', 'data'));
    }

    /**
     *  inquire依分類及縣市，縣市及公所使用。
     * @param UserRepositoryInterface $userRepo
     * @param ResultIIIRepositoryInterface $resultIIIRepo
     * @param int $year
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function inquireByCounty(UserRepositoryInterface $userRepo, ResultIIIRepositoryInterface $resultIIIRepo)
    {
        //強制地區
        $county = User::where('id', auth()->user()->id)->where('type', 'county')->first();
        if (!$county) {
            $isDistrict = true;
            //return '請用縣市或鄉鎮區公所帳號登入。';
        }
        //類別清單
        //$categories = Topic::whereBetween('created_at', [$startOfYear, $endOfYear])
        //    ->pluck('title', 'id')
        //    ->toArray();
        //$categories = [null => '-'] + $categories;
        //過濾
        $userQuery = User::whereNotNull('type');
        $topicQuery = Topic::where('work_type', 'resultiii');
        /** @var Report|Builder $reportQuery */
        $reportQuery = Report::query();

        if (isset($isDistrict)) {
            $countyUserIds = [auth()->user()->id];
        } else {
            $countyUserIds = User::where('id', $county->id)->orWhere('county_id', $county->id)->pluck('id');
        }

        $userQuery->whereIn('id', $countyUserIds);
        $reportQuery->whereIn('user_id', $countyUserIds);
        if ($categoryId = request()->get('category_id')) {
            $topicQuery->where('id', $categoryId);
            $reportQuery->where('topic_id', $categoryId);
        }

        //取出
        $users = $userQuery->get();

        $topics = $topicQuery->get();
        $reports = $reportQuery->get();
        $data = [];
        foreach ($reports as $report) {
            $data[$report->topic_id][$report->user_id] = isset($data[$report->topic_id][$report->user_id])
                ? $data[$report->topic_id][$report->user_id]++ : 1;
        }
        $categories = ['' => '-'] + $topicQuery->pluck('title', 'id')->toArray();

        return view('admin.resultiii.inquireByCounty', compact('categories', 'topics', 'users', 'data'));
    }
}
