<?php

namespace App\Http\Controllers\Admin\Nfa;

use App\File;
use App\Http\Controllers\Controller;
use App\Nfa\Repositories\FileRepositoryInterface;
use App\Nfa\Repositories\ReportRepositoryInterface;
use App\Nfa\Repositories\UserRepositoryInterface;
use App\Report;
use App\Topic;
use App\User;

class ShowingController extends Controller
{
    private $validTopics = [
        9   => '鄉（鎮、市、區）地區災害防救計畫',
        10  => '各類災害標準作業程序',
        19  => '防救災教育訓練教材',
        11  => '各鄉（鎮、市、區）災害應變中心建置',
        15  => '兵棋推演資料',
        149 => '成果資料下載',
        150 => '第一期資料成果-5年中程',
        151 => '第一期成果書',
        152 => '第二期成果書',
    ];

    /**
     * Display a listing of the resource.
     *
     * @param UserRepositoryInterface $userRepo
     * @param int $topic
     * @return \Illuminate\Http\Response
     */
    public function index(UserRepositoryInterface $userRepo, $topic)
    {
        if (!in_array($topic, array_keys($this->validTopics))) {
            abort(404);
        }

        $title = $this->validTopics[$topic];
        $accounts = $userRepo->getCountyDistrictAccountsByClassThenArea();
        //dd($accounts);
        return view('admin.showing.index', compact('topic', 'title', 'accounts'));
    }

    /**
     * Display the specified resource.
     *
     * @param UserRepositoryInterface $userRepo
     * @param ReportRepositoryInterface $reportRepo
     * @param $topic
     * @param int $id
     * @return \Illuminate\Http\Response
     * @internal param int $type
     */
    public function show(UserRepositoryInterface $userRepo, ReportRepositoryInterface $reportRepo, $topic, $id)
    {
        if (!in_array($topic, array_keys($this->validTopics))) {
            abort(404);
        }
        $title = $this->validTopics[$topic];

        /** @var User $user */
        $user = $userRepo->findById($id);

        $county = $userRepo->findParentCountyOrNull($user);

        $fullName = $county === null ? $user->name : $county->name . ' - ' . $user->name;

        extract($reportRepo->generateReportByUserIdAndTopic($user->id, $topic));
        /** @var Report $report */
        $hasFiles = $this->reportHasFiles($report);

        //整理檔案，並依年份分類
        $fileList = [];
        foreach ($report as $category) {
            foreach ($category->items as $topic) {
                /** @var Topic $topic */
                $reportCollection = $topic->reportCollection()->with('files')->where('user_id', $user->id)->get();
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
        array_walk($fileList, 'krsort');

        /** @var User $authUser */
        $authUser = auth()->user();
        $canToggle = $authUser->hasPermOfUser($user);

        return view('admin.showing.show', compact('topic', 'title', 'user', 'fullName', 'report', 'hasFiles', 'canToggle', 'fileList'));
    }

    private function reportHasFiles($report)
    {
        foreach ($report as $topic) {
            foreach ($topic->items as $topicItem) {
                if (isset($topicItem->reports) && isset($topicItem->reports->files)) {
                    return true;
                }
            }
        }

        return false;
    }

    public function toggle(FileRepositoryInterface $fileRepo, $id)
    {
        /** @var File $file */
        $file = $fileRepo->find($id);

        if (!$file) {
            abort(404);
        }
        /** @var Report $report */
        $report = $file->post;
        $reportUser = $report->user;
        /** @var User $authUser */
        $authUser = auth()->user();
        if (!$authUser->hasPermOfUser($reportUser)) {
            abort(403);
        }

        $file->opendata = !($file->opendata);
        $file->save();

        return redirect()->back();
    }

    public function toggleIsRecommend(FileRepositoryInterface $fileRepo, $id)
    {
        /** @var File $file */
        $file = $fileRepo->find($id);

        if (!$file) {
            abort(404);
        }
        /** @var Report $report */
        $report = $file->post;
        $reportUser = $report->user;
        /** @var User $authUser */
        $authUser = auth()->user();
        if (!$authUser->hasPermOfUser($reportUser)) {
            abort(403);
        }

        $file->is_recommend = !($file->is_recommend);
        $file->save();

        return redirect()->back();
    }
}
