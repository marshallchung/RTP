<?php

namespace App\Nfa\Repositories;

use App\RootTopic;
use App\SeasonalReport;
use App\ReportPublicDate;
use App\Topic;
use App\User;
use Auth;
use Carbon\Carbon;

class SeasonalReportRepository implements SeasonalReportRepositoryInterface
{
    public function getPublicDateByYear($year)
    {
        if ($year === null) $year = (string) Date('Y');

        $reportPublicDate = ReportPublicDate::where('year', $year)->where(function ($query) {
            $query->where('date_type', 'seasonal1')
                ->orWhere('date_type', 'seasonal2')
                ->orWhere('date_type', 'seasonal0');
        })->first();

        return $reportPublicDate === null ? null : $reportPublicDate->public_date;
    }

    public function getPublicDates()
    {
        $currentYear = (string) Date('Y');
        $publicDates = ReportPublicDate::where(function ($query) {
            $query->where('date_type', 'seasonal1')
                ->orWhere('date_type', 'seasonal2')
                ->orWhere('date_type', 'seasonal0');
        })->orderBy('year', 'DESC')->orderBy('date_type', 'ASC')->get();

        // If they don't have one for this year, create a default one for them
        if ($publicDates->where('year', $currentYear)->isEmpty()) {
            $currentYearPublicDate = ReportPublicDate::create([
                'date_type'        => 'seasonal1',
                'year'        => $currentYear,
                'public_date' => Carbon::create($currentYear, 12, 31, 9, 0),
                'expire_soon_date' => Carbon::create($currentYear, 12, 31, 9, 0),
                'expire_date' => Carbon::create($currentYear, 12, 31, 9, 0),
            ]);

            $publicDates->prepend($currentYearPublicDate);
            $currentYearPublicDate = ReportPublicDate::create([
                'date_type'        => 'seasonal2',
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
        $publicDate = ReportPublicDate::find($id);

        $publicDate->public_date = $date . ' 09:00:00';
        $publicDate->expire_soon_date = $expire_soon_date . ' 09:00:00';
        $publicDate->expire_date = $expire_date . ' 09:00:00';

        $publicDate->update();

        return $publicDate;
    }

    public function findOrCreateSeasonalReportTopic($reportId, $topicId)
    {
        /** @var SeasonalReport $report */
        /*$report = SeasonalReport::where(function ($query) {
            $query->where('date_type', 'seasonal1')
            ->orWhere('date_type', 'seasonal2');
        })->firstOrNew(['id' => $reportId]);*/
        $report = SeasonalReport::firstOrNew(['id' => $reportId]);
        if ($topicId) {
            $report->user_id = Auth::user()->id;
            $report->topic_id = $topicId;

            $report->save();
        }

        return $report;
    }

    public function generateUsersCurrentSeasonalReport($year)
    {
        $userId = Auth::user()->id;

        $report = $this->generateSeasonalReport($userId, $year);

        return $report['report'];
    }

    private function generateSeasonalReport($userId, $year = null, $topic = null)
    {
        /** @var User $user */
        $user = User::find($userId);
        $level = $user->level;
        $type = $user->type;
        $class = $user->class;

        $topicQuery = Topic::where('work_type', 'seasonalReports');
        if (isset($topic)) {
            $topicQuery->where('id', $topic);
        }

        if ($year) {
            $startOfYear = Carbon::create($year, 1, 1, 0, 0, 0);
            $endOfYear = Carbon::create($year, 12, 31, 23, 59, 59);
            $topicQuery->with(['SeasonalReports' => function ($query) use ($user, $startOfYear, $endOfYear) {
                /** @var \Illuminate\Database\Eloquent\Builder $query */
                $query->with('files')->where('user_id', $user->id);
            }])->where('type', 'LIKE', "%{$type}%")
                ->where('class', 'LIKE', "%{$class}%")
                ->whereBetween('created_at', [$startOfYear, $endOfYear])
                ->where(function ($query) use ($level, $type, $class) {
                    /** @var \Illuminate\Database\Eloquent\Builder $query */
                    $query->where('exclude', 'NOT LIKE', "%{$level}-{$type}-{$class}%")->orWhereNull('exclude');
                });

            if ($user->origin_role > 2) {
                $topicQuery->where(function ($query) use ($level, $user) {
                    /** @var \Illuminate\Database\Eloquent\Builder $query */
                    $query->where('levels', '1.2') //通用
                        ->orWhere('user_id', $user->id);
                });
            }
            $topics = $topicQuery->get();
        } else {
            $topicQuery->with(['SeasonalReports' => function ($query) use ($user) {
                /** @var \Illuminate\Database\Eloquent\Builder $query */
                $query->with('files')->where('user_id', $user->id);
            }])->where('type', 'LIKE', "%{$type}%")
                ->where('class', 'LIKE', "%{$class}%")
                ->where(function ($query) use ($level, $type, $class) {
                    /** @var \Illuminate\Database\Eloquent\Builder $query */
                    $query->where('exclude', 'NOT LIKE', "%{$level}-{$type}-{$class}%")->orWhereNull('exclude');
                });

            if ($user->origin_role > 2) {
                $topicQuery->where(function ($query) use ($level, $user) {
                    /** @var \Illuminate\Database\Eloquent\Builder $query */
                    $query->where('levels', '1.2') //通用
                        ->orWhere('user_id', $user->id);
                });
            }
            $topics = $topicQuery->get();
        }

        return [
            'user'   => $user,
            'report' => $this->sortTopicsIntoCategories($topics, $year),
        ];
    }

    public function sortTopicsIntoCategories($topics, $year, $filter = true)
    {
        $rootTopics = RootTopic::where([
            'year'      => $year,
            'work_type' => 'seasonalReports',
        ])->get();
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
        //dd($categories);

        if ($filter === true) {
            return array_filter($categories, function ($category) {
                return !empty($category->items);
            });
        } else {
            return $categories;
        }
    }

    public function generateSeasonalReportByUserIdAndYear($id, $year = null)
    {
        return $this->generateSeasonalReport($id, $year);
    }

    public function generateSeasonalReportByUserId($id)
    {
        return $this->generateSeasonalReport($id, null);
    }

    public function generateSeasonalReportByUserIdAndTopic($id, $topic)
    {
        return $this->generateSeasonalReport($id, null, $topic);
    }

    public function getSeasonalReportFilesByUserIdAndYear($id, $year = null)
    {
        $report = $this->generateSeasonalReport($id, $year);

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

    public function findUsersCurrentTopicSeasonalReportByTopicId($id)
    {
        $userId = Auth::user()->id;
        $startOfYear = Carbon::create(null, 1, 1, 0, 0, 0);
        $endOfYear = Carbon::create(null, 12, 31, 23, 59, 59);

        return $this->findTopicSeasonalReport($userId, $id, $startOfYear, $endOfYear);
    }

    private function findTopicSeasonalReport($userId, $topicId, $startDate, $endDate)
    {
        return SeasonalReport::with('files')->where('user_id', $userId)->where('topic_id', $topicId)->whereBetween('created_at', [$startDate, $endDate])->first();
    }
}
