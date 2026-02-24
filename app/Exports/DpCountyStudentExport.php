<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DpCountyStudentExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize, WithStyles
{
    use Exportable;

    private $end_year;
    private $end_month;

    public function __construct($end_year, $end_month)
    {
        $this->end_year = $end_year;
        $this->end_month = $end_month;
    }

    public function query()
    {
        return DpStudent::query(); 
    }

    public function headings(): array
    {
        return [
            '縣市名稱',
            '總人數',
            '縣市比例',
            '男性人數',
            "男性比例",
            '女性人數',
            "女性比例",
        ];
    }

    public function map($item): array
    {
        return [
            $item->name,
            $item->male_count + $item->female_count,
            round($item->total_percentage, 2) . '%',
            $item->male_count,
            round($item->county_male_percentage, 2) . '%',
            $item->female_count,
            round($item->county_female_percentage, 2) . '%',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            "A1:G1"  => [
                'font' => [
                    'bold' => true,
                    'size' => 14,
                ],
            ],
            "A2:G100"  => [
                'font' => [
                    'size' => 12,
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'wrapText' => false,
                ]
            ],
        ];
    }
}
