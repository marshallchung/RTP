<?php

namespace App\Exports;

use App\Report;
use App\SeasonalReport;
use App\Topic;
use App\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportExport implements FromCollection, WithEvents, ShouldAutoSize
{
    use RegistersEventListeners;
    /**
     * @var string
     */
    private $filename;
    /**
     * @var array
     */
    private $input;

    /**
     * ReportExport constructor.
     * @param string $filename
     * @param array $input
     */
    public function __construct(string $filename, array $input = [])
    {
        $this->filename = $filename;
        $this->input = $input;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $year = !empty($this->input['year']) ? $this->input['year'] : 2018;
        $startOfYear = Carbon::create($year, 1, 1, 0, 0, 0);
        $endOfYear = Carbon::create($year, 12, 31, 23, 59, 59);
        $topicQuery = Topic::whereBetween('created_at', [$startOfYear, $endOfYear]);

        if ($this->filename == '深耕資訊網資料查詢') {
            $topicQuery->where('id', '!=', 37)->where('work_type', 'reports');
            $userQuery = User::whereNotNull('type');
            $reportQuery = Report::query();
        } elseif ($this->filename == '執行進度管制表') {
            $topicQuery->where('work_type', 'seasonalReports');
            $userQuery = User::where('type', 'county');
            $reportQuery = SeasonalReport::query();
        }

        if (!empty($input['county_id'])) {
            $countyId = $input['county_id'];
            $countyUserIds = User::where('id', $countyId)->orWhere('county_id', $countyId)->pluck('id');
            $userQuery->whereIn('id', $countyUserIds);
            $reportQuery->whereIn('user_id', $countyUserIds);
        }
        if (!empty($input['category_id'])) {
            $categoryId = $input['category_id'];
            $topicQuery->where('id', $categoryId);
            $reportQuery->where('topic_id', $categoryId);
        }
        $reportQuery->whereYear('created_at', '=', $year);
        //取出
        $users = $userQuery->get();
        $topics = $topicQuery->get();
        $reports = $reportQuery->get();
        $data = [];
        foreach ($reports as $report) {
            $data[$report->topic_id][$report->user_id] = isset($data[$report->topic_id][$report->user_id])
                ? $data[$report->topic_id][$report->user_id]++ : 1;
        }

        $rows = collect();
        $titleRow = array_merge([''], $users->pluck('name')->toArray());
        $rows->add($titleRow);
        foreach ($topics as $topic) {
            $row = [$topic->title];
            foreach ($users as $user) {
                $row[] = isset($data[$topic->id][$user->id]) ? '✓' : '';
            }
            $rows->add($row);
        }

        return $rows;
    }

    /**
     * @param AfterSheet $event
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public static function afterSheet(AfterSheet $event)
    {
        /** @var Worksheet $sheet */
        $sheet = $event->sheet;
        $sheet->freezePane('B2');
    }
}
