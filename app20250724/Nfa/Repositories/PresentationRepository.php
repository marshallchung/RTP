<?php

namespace App\Nfa\Repositories;

use App\CentralReport;
use App\Report;
use App\ReportPublicDate;
use App\RootTopic;
use App\Topic;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

class PresentationRepository implements PresentationRepositoryInterface
{
    public function getPublicDateByYear($year)
    {
        if ($year === null) {
            $year = (string) Date('Y');
        }

        $reportPublicDate = ReportPublicDate::where('year', $year)->where('date_type', 'presentation')->first();

        return $reportPublicDate === null ? null : $reportPublicDate->public_date;
    }

    public function getPublicDates()
    {
        $currentYear = (string) Date('Y');
        $publicDates = ReportPublicDate::where('date_type', 'presentation')->orderBy('year', 'DESC')->get();

        // If they don't have one for this year, create a default one for them
        if ($publicDates->where('year', $currentYear)->isEmpty()) {
            $currentYearPublicDate = ReportPublicDate::create([
                'date_type'        => 'presentation',
                'year'        => $currentYear,
                'public_date' => Carbon::create($currentYear, 12, 31, 9, 0),
                'expire_soon_date' => Carbon::create($currentYear, 12, 31, 9, 0),
                'expire_date' => Carbon::create($currentYear, 12, 31, 9, 0),
            ]);

            $publicDates->prepend($currentYearPublicDate);
        }

        return $publicDates;
    }

    public function updatePublicDate($id, $year, $date, $expire_soon_date, $expire_date)
    {
        /** @var ReportPublicDate $publicDate */
        $publicDate = ReportPublicDate::where('date_type', 'presentation')->find($id);

        $publicDate->public_date = $date . ' 09:00:00';
        $publicDate->expire_soon_date = $expire_soon_date . ' 09:00:00';
        $publicDate->expire_date = $expire_date . ' 09:00:00';

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
        Log::debug('findOrCreateCentralReportTopic reportId: ' . $reportId . ',topicId: ' . $topicId);
        $report = CentralReport::firstOrNew(['id' => $reportId]);
        Log::debug('findOrCreateCentralReportTopic report id: ' . $report->id);

        if ($topicId) {
            $report->user_id = Auth::user()->id;
            $report->topic_id = $topicId;

            $report->save();
        }

        return $report;
    }

    public function generateUsersCurrentReport($year)
    {
        $userId = Auth::user()->id;

        $report = $this->generateReport($userId, $year);

        return $report['report'];
    }

    public function generateReport($userId, $year = null, $topic = null, $reportType = 'Reports')
    {
        /** @var User $user */
        $user = User::find($userId);
        $level = $user->level;
        $type = $user->type;
        $class = $user->class;

        if (!$year) {
            $year = 2017;
        }
        /** @var Builder $topicQuery */
        $topicQuery = Topic::where('work_type', $reportType)->whereHas('rootTopic', function ($query) use ($year) {
            /** @var Builder|RootTopic $query */
            $query->where('year', $year);
        });
        if ($topic) {
            $topics = $topicQuery->with([$reportType => function ($query) use ($user) {
                /** @var \Illuminate\Database\Eloquent\Builder $query */
                $query->with('files')->where('user_id', $user->id);
            }])->where('id', $topic)->get();
        } else {
            if ($year == 2017) {
                if ($reportType == 'centralReports') {
                    $topics = $topicQuery->with([$reportType => function ($query) use ($user) {
                        /** @var \Illuminate\Database\Eloquent\Builder $query */
                        $query->with('files')->where('user_id', $user->id);
                    }])->get();
                } else {
                    $topics = $topicQuery->with([$reportType => function ($query) use ($user) {
                        /** @var \Illuminate\Database\Eloquent\Builder $query */
                        $query->with('files')->where('user_id', $user->id);
                    }])->where('type', 'LIKE', "%{$type}%")
                        ->where('levels', 'LIKE', "%{$level}%")
                        ->where('class', 'LIKE', "%{$class}%")
                        ->where(function ($query) use ($level, $type, $class) {
                            /** @var \Illuminate\Database\Eloquent\Builder $query */
                            $query->where('exclude', 'NOT LIKE', "%{$level}-{$type}-{$class}%")->orWhereNull('exclude');
                        })->get();
                }
            } else {
                $topics = $topicQuery->with([$reportType => function ($query) use ($user) {
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
            'report' => $this->sortTopicsIntoCategories($topics, $year),
        ];
    }

    private function sortTopicsIntoCategories($topics, $year, $filter = true)
    {
        $rootTopics = RootTopic::where('year', $year)->get();
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

    public function findUsersCurrentTopicReportByTopicId($id, $year = null)
    {
        $userId = Auth::user()->id;
        $startOfYear = Carbon::create($year, 1, 1, 0, 0, 0);
        $endOfYear = Carbon::create($year, 12, 31, 23, 59, 59);

        return $this->findTopicReport($userId, $id, $startOfYear, $endOfYear);
    }

    private function findTopicReport($userId, $topicId, $startDate, $endDate)
    {
        return Report::with('files')->where('user_id', $userId)->where('topic_id', $topicId)->whereBetween('created_at', [$startDate, $endDate])->first();
    }
}
