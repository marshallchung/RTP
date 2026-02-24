<?php

namespace App\Exports;

use App\DpStudent;
use App\DpAdvanceStudentSubject;
use App\DpSubject;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DpCountyAdvanceStudentExport implements FromCollection, WithEvents, ShouldAutoSize, WithCustomStartCell, WithStyles
{
    /**
     * @var DpStudent[]|Collection
     */
    private $dpStudentStatistics;
    private $data;
    private $end_year;
    private $end_month;

    /**
     * DpCountyAdvanceStudentExport constructor.
     * @param Array|[] $dpStudentStatistics
     * @param Collection|DpStudent[] $data
     */
    public function __construct(array $dpStudentStatistics, Collection $data, $end_year, $end_month)
    {
        $this->dpStudentStatistics = $dpStudentStatistics;
        $this->data = $data;
        $this->end_year = $end_year;
        $this->end_month = $end_month;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $rows = collect();
        //標題列
        $titleRow = [
            '縣市名稱',
            '總人數',
            '縣市比例',
            '男性人數',
            "男性比例",
            '女性人數',
            "女性比例",
        ];
        /** @var DpSubject $dpSubject */
        $rows->add($titleRow);

        foreach ($this->data as $item) {
            $row = [
                $item->name,
                $item->male_count + $item->female_count,
                round($item->total_percentage, 2) . '%',
                $item->male_count,
                round($item->county_male_percentage, 2) . '%',
                $item->female_count,
                round($item->county_female_percentage, 2) . '%',
            ];
            $rows->add($row);
        }

        return $rows;
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        $dpStudentStatistics = $this->dpStudentStatistics;
        return [
            AfterSheet::class => function (AfterSheet $event) use ($dpStudentStatistics) {
                /** @var Worksheet $sheet */
                $sheet = $event->sheet;
                //第一列
                $header = "防災士總人數：{$dpStudentStatistics['advanced_total']}，" .
                    "男性：{$dpStudentStatistics['advanced_male_count']}人" .
                    "({$dpStudentStatistics['advanced_county_male_percentage']}%)，" .
                    "女性：{$dpStudentStatistics['advanced_female_count']}人" .
                    "({$dpStudentStatistics['advanced_county_female_percentage']}%)，" .
                    "（截至 {$this->end_year} 年 {$this->end_month} 月)";
                $sheet->setCellValue('A1', $header);
                $sheet->getStyle('A1')->getFont()->setSize(14);
                $sheet->getStyle('A1')->getFont()->setBold(true);
                $sheet->mergeCells('A1:G1');
                //凍結
                $sheet->freezePane('A2');
                $sheet->calculateColumnWidths();
            },
        ];
    }

    /**
     * @return string
     */
    public function startCell(): string
    {
        return 'A2';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            "A2:G24"  => [
                'font' => [
                    'size' => 13,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_RIGHT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => false,
                ]
            ],
        ];
    }
}
