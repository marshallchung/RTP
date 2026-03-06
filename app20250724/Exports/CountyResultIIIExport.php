<?php

namespace App\Exports;

use App\Report;
use App\Topic;
use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CountyResultIIIExport implements FromCollection, WithEvents, ShouldAutoSize
{
    use RegistersEventListeners;

    /**
     * @var array
     */
    private $input;

    /**
     * CountyReportExport constructor.
     * @param string $filename
     * @param array $input
     */
    public function __construct(array $input = [])
    {
        $this->input = $input;
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

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $topicQuery = Topic::query();

        $topicQuery->where('work_type', 'resultiii');
        $userQuery = User::whereNotNull('type');
        $reportQuery = Report::query();


        //強制地區
        $county = User::where('id', auth()->user()->id)->where('type', 'county')->first();
        if (!$county) {
            return null;
        }
        $countyUserIds = User::where('id', $county->id)->orWhere('county_id', $county->id)->pluck('id');
        $userQuery->whereIn('id', $countyUserIds);
        $reportQuery->whereIn('user_id', $countyUserIds);

        if (!empty($input['category_id'])) {
            $categoryId = $input['category_id'];
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
}
