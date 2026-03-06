<?php

namespace App\Http\Controllers\Admin\Nfa;

use App\File;
use App\Http\Controllers\Controller;
use App\Report;
use App\RootTopic;
use App\Topic;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class ReportFixController extends Controller
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /** @var User $user */
        $user = auth()->user();
        if ($user->username != 'admin') {
            abort(404);
        }
        $oldTopics = Topic::where('work_type', 'reports')
            ->whereHas('rootTopic', function ($query) {
                /** @var Builder|RootTopic $query */
                $query->where('year', 2018);
            })
            ->pluck('title', 'id');
        $newTopics = Topic::where('work_type', 'reports')
            ->whereHas('rootTopic', function ($query) {
                /** @var Builder|RootTopic $query */
                $query->where('year', 2019);
            })
            ->pluck('title', 'id');

        $oldTopicOptions = [null => null] + $oldTopics->toArray();
        $newTopicOptions = [null => null] + $newTopics->toArray();

        return view('admin.report-fix.index', compact('oldTopicOptions', 'newTopicOptions'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function post(Request $request)
    {
        /** @var User $user */
        $user = auth()->user();
        if ($user->username != 'admin') {
            abort(404);
        }
        $this->validate($request, [
            'oldTopic' => 'required|exists:topics,id',
            'newTopic' => 'required|exists:topics,id',
            'type'     => 'required|in:county,district',
        ]);
        /** @var Topic $fromTopic */
        $fromTopic = Topic::find($request->get('oldTopic'));
        /** @var Topic $toTopic */
        $toTopic = Topic::find($request->get('newTopic'));
        $type = $request->get('type');

        $fromReportIds = $fromTopic->reports()->whereHas('user', function ($query) use ($type) {
            /** @var Builder|User $query */
            $query->where('type', $type);
        })->pluck('id');
        /** @var Collection|File[] $fromReportFiles */
        $fromReportFiles = File::where('post_type', Report::class)
            ->whereIn('post_id', $fromReportIds)
            ->whereYear('created_at', 2019)
            ->get();

        $count = 0;
        foreach ($fromReportFiles as $file) {
            $toReport = Report::firstOrCreate([
                'user_id'  => $file->post->user_id,
                'topic_id' => $toTopic->id,
            ]);
            $file->post()->associate($toReport);
            $file->save();
            $count++;
        }

        \Flash::success('已移動 ' . $count . ' 個檔案');

        return redirect()->route('admin.reports.fix');
    }
}
