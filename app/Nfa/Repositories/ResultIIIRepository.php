<?php

namespace App\Nfa\Repositories;

use App\CentralReport;
use App\Http\Controllers\ResultIIIController;
use App\Report;
use App\ReportPublicDate;
use App\RootTopic;
use App\Topic;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class ResultIIIRepository implements ResultIIIRepositoryInterface
{
    public function getPublicDateByYear($year)
    {
        if ($year === null) {
            $year = (string) Date('Y');
        }

        $reportPublicDate = ReportPublicDate::where('year', $year)->where('date_type', 'resultIII')->ffirst();

        return $reportPublicDate === null ? null : $reportPublicDate->public_date;
    }

    public function getPublicDates()
    {
        $currentYear = (string) Date('Y');
        $publicDates = ReportPublicDate::orderBy('year', 'DESC')->get();

        // If they don't have one for this year, create a default one for them
        if ($publicDates->where('year', $currentYear)->isEmpty()) {
            $currentYearPublicDate = ReportPublicDate::create([
                'date_type'        => 'resultIII',
                'year'        => $currentYear,
                'public_date' => Carbon::create($currentYear, 12, 31, 9, 0),
                'expire_soon_date' => Carbon::create($currentYear, 12, 31, 9, 0),
                'expire_date' => Carbon::create($currentYear, 12, 31, 9, 0),
            ]);

            $publicDates->prepend($currentYearPublicDate);
        }

        return $publicDates;
    }

    public function updatePublicDate($id, $year, $date, $time)
    {
        /** @var ReportPublicDate $publicDate */
        $publicDate = ReportPublicDate::where('date_type', 'resultIII')->find($id);

        $publicDate->public_date = $date . ' ' . $time . ':00';

        $publicDate->update();

        return $publicDate;
    }

    public function findOrCreateReportTopic($reportId, $topicId)
    {
        /** @var Report $report */
        $report = Report::firstOrNew(['id' => $reportId]);

        if ($topicId) {
            $report->user_id = Auth::user()->id;
            $report->topic_id = $topicId;

            $report->save();
        }

        return $report;
    }

    public function findOrCreateCentralReportTopic($reportId, $topicId)
    {
        /** @var CentralReport $report */
        $report = CentralReport::firstOrNew(['id' => $reportId]);

        if ($topicId) {
            $report->user_id = Auth::user()->id;
            $report->topic_id = $topicId;

            $report->save();
        }

        return $report;
    }

    public function generateUsersCurrentReport()
    {
        $userId = Auth::user()->id;

        $report = $this->generateReport($userId);

        return $report['report'];
    }

    private function generateReport($userId, $topic = null)
    {
        /** @var User $user */
        $user = User::find($userId);
        $level = $user->level;
        $type = $user->type;
        $class = $user->class;

        /** @var Builder $topicQuery */
        $topicQuery = Topic::where('work_type', 'resultiii')->whereHas('rootTopic');
        if ($topic) {
            $topics = $topicQuery->with(['Reports' => function ($query) use ($user) {
                /** @var \Illuminate\Database\Eloquent\Builder $query */
                $query->with('files')->where('user_id', $user->id);
            }])->where('id', $topic)->get();
        } else {
            $topics = $topicQuery->with(['Reports' => function ($query) use ($user) {
                /** @var \Illuminate\Database\Eloquent\Builder $query */
                $query->with('files')->where('user_id', $user->id);
            }])->where('type', 'LIKE', "%{$type}%")
                ->where(function ($query) use ($level, $user) {
                    /** @var \Illuminate\Database\Eloquent\Builder $query */
                    $query->where('levels', '1.2') //通用
                        ->orWhere('user_id', $user->id);
                })
                ->where('class', 'LIKE', "%{$class}%")
                ->where(function ($query) use ($level, $type, $class) {
                    /** @var \Illuminate\Database\Eloquent\Builder $query */
                    $query->where('exclude', 'NOT LIKE', "%{$level}-{$type}-{$class}%")->orWhereNull('exclude');
                })->get();
        }
        //} else {
        //    $topics = $topicQuery->with(['reports' => function ($query) use ($user) {
        //        $query->with('files')->where('user_id', $user->id);
        //    }])->where('type', 'LIKE', "%{$type}%")
        //        ->where(function ($query) use ($level, $user) {
        //            $query->where('levels', '1.2') //通用
        //                ->orWhere('user_id', $user->id);
        //        })
        //        ->where('class', 'LIKE', "%{$class}%")
        //        ->where(function ($query) use ($level, $type, $class) {
        //            $query->where('exclude', 'NOT LIKE', "%{$level}-{$type}-{$class}%")->orWhereNull('exclude');
        //        })->get();
        //}
        $topics->load('reports.files');

        return [
            'user'   => $user,
            'report' => $this->sortTopicsIntoCategories($topics),
        ];
    }

    private function sortTopicsIntoCategories($topics, $filter = true)
    {
        $rootTopics = RootTopic::all();
        $categories = [];
        foreach ($rootTopics as $rootTopic) {
            $categories[$rootTopic->id] = (object) [
                'name'  => $rootTopic->title,
                'items' => [],
            ];
        }

        foreach ($topics as $topic) {
            if (isset($categories[$topic->category])) {
                $categories[$topic->category]->items[] = $topic;
            }
        }

        if ($filter === true) {
            return array_filter($categories, function ($category) {
                return !empty($category->items);
            });
        } else {
            return $categories;
        }
    }

    public function generateCentralReport($year)
    {
        $userId = Auth::user()->id;

        $report = $this->generateReport($userId, $year, null, 'centralReports');

        return $report['report'];
    }

    public function generateReportByUserIdAndYear($id, $year = null)
    {
        return $this->generateReport($id, $year);
    }

    public function generateReportByUserId($id)
    {
        return $this->generateReport($id, null);
    }

    public function generateReportByUserIdAndTopic($id, $topic)
    {
        return $this->generateReport($id, null, $topic);
    }

    public function getReportFilesByUserIdAndYear($id, $year = null)
    {
        $report = $this->generateReport($id, $year);

        $files = [];

        foreach ($report['report'] as $topic) {
            foreach ($topic->items as $item) {
                if (isset($item->reports) && isset($item->reports->files)) {
                    foreach ($item->reports->files as $file) {
                        if (!array_key_exists($topic->name, $files)) {
                            $files[$topic->name] = [];
                        }

                        array_push($files[$topic->name], (object) [
                            'name' => $file->name,
                            'path' => storage_path('app/' . $file->path),
                        ]);
                    }
                }
            }
        }

        return $files;
    }

    public function findUsersCurrentTopicReportByTopicId($id)
    {
        $userId = Auth::user()->id;
        $startOfYear = Carbon::create(null, 1, 1, 0, 0, 0);
        $endOfYear = Carbon::create(null, 12, 31, 23, 59, 59);

        return $this->findTopicReport($userId, $id, $startOfYear, $endOfYear);
    }

    private function findTopicReport($userId, $topicId, $startDate, $endDate)
    {
        return Report::with('files')->where('user_id', $userId)->where('topic_id', $topicId)->whereBetween('created_at', [$startDate, $endDate])->first();
    }
}
