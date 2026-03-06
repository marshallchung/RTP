<?php

namespace App\Http\Controllers\Admin\Nfa;

use App\Nfa\Repositories\SampleReportRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Nfa\Traits\FileUploadTrait;
use App\RootTopic;
use App\SampleReport;
use App\Topic;
use App\User;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SampleReportController extends Controller
{
    use FileUploadTrait;

    /**
     * 列表顯示優選範本。
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', 1800);
        /* 2024-02-26
        $counties = User::with('county')->where('type', 'county')->orWhere('type', 'district')->get()
            ->pluck('full_county_name', 'id')->toArray();
        */
        $counties = User::with('county')->where('type', 'county')->get()
            ->pluck('full_county_name', 'id')->toArray();

        $rootTopicQuery = RootTopic::with('topics.sampleReports.user.county', 'topics.sampleReports.files')
            ->where('work_type', 'reports')->orderByDesc('year')->orderBy('id');
        $rootTopicOptions = (clone $rootTopicQuery)->select('id', 'title', 'year')->get()
            ->groupBy('year')->sortByDesc(function ($object, $key) {
                return $key;
            });
        $availableYears = (clone $rootTopicQuery)->pluck('year', 'year')->sortKeysDesc();

        // 過濾
        if ($searchYear = request('year')) {
            $rootTopicQuery->where('year', $searchYear);
        }
        if ($searchTopic = topic::find(request('topic_id'))) {
            $rootTopicQuery->with(['topics' => function ($query) use ($searchTopic) {
                $query->where('id', $searchTopic->id);
            }]);
        }
        if ($searchCounty = User::find(request('county_id'))) {
            $rootTopicQuery->with(['topics.sampleReports' => function ($query) use ($searchCounty) {
                $query->where('user_id', $searchCounty->id);
            }]);
        }

        $rootTopics = $rootTopicQuery->paginate(50);
        foreach ($rootTopics as $key => $rootTopic) {
            foreach ($rootTopic->topics as $topic_key => $topic) {
                foreach ($topic->sampleReports as $sample_key => $sampleReport) {
                    $rootTopics[$key]->topics[$topic_key]->sampleReports[$sample_key]->user->full_county_name = $sampleReport->user->full_county_name;
                }
            }
        }
        $pagination = $rootTopics->render();
        $rootTopics = $rootTopics->toArray();
        $authUser = Auth::user();
        $isAbleToReviewReportSample = $authUser->isAbleTo('review-report-sample');
        $isAbleToCreateReports = $authUser->isAbleTo('create-reports');

        return view('admin.sample-report.index', compact('availableYears', 'counties', 'rootTopicOptions', 'rootTopics', 'authUser', 'pagination', 'isAbleToReviewReportSample', 'isAbleToCreateReports'));
    }

    /**
     * 新增優選範本。
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Topic $topic)
    {
        return view('admin.sample-report.create', compact('topic'));
    }

    /**
     * 儲存優選範本。
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, Topic $topic)
    {
        $this->validate($request, [
            'files' => 'required',
        ]);

        /** @var User $user */
        $user = auth()->user();
        /** @var SampleReport $sampleReport */
        $sampleReport = SampleReport::create([
            'user_id'       => $user->id,
            'topic_id' => $topic->id,
        ]);

        $this->handleFiles($request, $sampleReport);

        Flash::success('檔案已上傳');

        return redirect()->route('admin.sample-report.index');
    }

    /**
     * 刪除優選範本。
     *
     * @param \App\SampleReport $sampleReport
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(SampleReport $sampleReport)
    {
        if (!Auth::user()->owns($sampleReport)) {
            abort(403);
        }
        foreach ($sampleReport->files as $file) {
            $file->delete();
        }
        $sampleReport->delete();

        Flash::success('檔案已刪除');

        return redirect()->route('admin.sample-report.index');
    }

    public function getPublicDates(SampleReportRepositoryInterface $reportRepo)
    {
        $publicDates = $reportRepo->getPublicDates();

        return view('admin.sample-report.dates', compact('publicDates'));
    }

    public function updatePublicDate(SampleReportRepositoryInterface $reportRepo, Request $request, $id)
    {
        extract($request->only(['year', 'date', 'expire_soon_date', 'expire_date']));

        $publicDate = $reportRepo->updatePublicDate($id, $year, $date, $expire_soon_date, $expire_date);

        Flash::success(trans('app.updateSuccess', ['type' => '公開日期']));

        return redirect()->back();
    }

    public function postSampleReviewUpdateIsSample(Request $request, SampleReport $sampleReport)
    {
        $sampleReport->update([
            'is_sample' => $request->get('is_sample'),
        ]);
        Flash::success('優選範本狀態已更新');

        return back();
    }

    public function postSampleReviewUpdateMemo(Request $request, SampleReport $sampleReport)
    {
        $sampleReport->update([
            'memo' => $request->get('memo') ?? '',
        ]);
        Flash::success('評論已更新。');

        return back();
    }
}
