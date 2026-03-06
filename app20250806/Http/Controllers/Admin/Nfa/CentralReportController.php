<?php

namespace App\Http\Controllers\Admin\Nfa;

use App\CentralReport;
use App\File;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReportRequest;
use App\Jobs\ZipCentralReportDocuments;
use App\Nfa\Repositories\FileRepositoryInterface;
use App\Nfa\Repositories\ReportRepositoryInterface;
use App\Nfa\Repositories\UserRepositoryInterface;
use App\Nfa\Traits\FileUploadTrait;
use App\Topic;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class CentralReportController extends Controller
{
    use FileUploadTrait;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
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
        if (in_array($user->type, ['district', 'county'])) {
            Flash::error('本區資料上傳功能僅限「平台管理員」或「消防署」權限帳號登入。');

            return redirect()->back();
        }

        $report = $reportRepo->generateCentralReport($year);

        //整理檔案，並依年份分類
        $fileList = [];
        foreach ($report as $category) {
            foreach ($category->items as $topic) {
                /** @var Topic $topic */
                //DB::enableQueryLog();
                $reportCollection = $topic->centralReportCollection()->with('files')->where('user_id', $user->id)->get();
                //dd($reportCollection);
                //dd(DB::getQueryLog());
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
        $reportType = 'centralReports';

        return view('admin.report.ctsubmit', compact('report', 'fileList', 'year', 'reportType'));
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
        $topic = Topic::with('reports.files')->find($id);

        $report = $reportRepo->findUsersCurrentTopicReportByTopicId($id);
        //dd($report);

        $reportType = 'centralReports';

        return view('admin.report.create', compact('topic', 'report', 'reportType'));
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
        Log::debug('report_id: ' . $request->get('report_id') . ',topic_id: ' . $request->get('topic_id'));
        $report = $reportRepo->findOrCreateCentralReportTopic($request->get('report_id'), $request->get('topic_id'));
        Log::debug('reportRepo report id: ' . $report->id);

        $this->handleFiles($request, $report/*, '', 1*/);

        $year = $request->input('year', 2017);
        if ($year < 2017) {
            $year = 2017;
        }

        return redirect()->route('admin.centralReports.submit', [
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

        extract($reportRepo->generateCentralReportByUserIdAndYear($user->id, $year));
        /** @var CentralReport $report */
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
                            $year = $file->created_at->year;
                            if (!isset($fileList[$topic->id][$year])) {
                                $fileList[$topic->id][$year] = [];
                            }
                            $fileList[$topic->id][$year][] = $file;
                        }
                    }
                }
            }
        }
        array_walk($fileList, 'krsort');
        $canToggle = true;

        return view('admin.report.show', compact('user', 'fullName', 'report', 'year', 'hasFiles', 'fileList', 'canToggle'));
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

    public function updatePublicDate(CentralReportRepositoryInterface $reportRepo, Request $request, $id)
    {
        extract($request->only(['year', 'date', 'time']));

        $publicDate = $reportRepo->updatePublicDate($id, $year, $date, $time);

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
}
