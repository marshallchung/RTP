<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class QuestionnaireStatisticExport implements FromCollection, WithEvents, ShouldAutoSize
{
    use RegistersEventListeners;

    /**
     * @var array
     */
    private $data;

    /**
     * QuestionnaireStatisticExport constructor.
     * @param string $filename
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $rows = collect();
        $titleRow = [
            '',
            '績效評估',
            '基本指標',
            '進階指標',
            '總分',
            '加權總分',
        ];
        $rows->add($titleRow);
        foreach ($this->data as $key => $item) {
            foreach ($item['questionnaires'] as $questionnaire) {
                $row = [
                    $item['account'],
                    $questionnaire['title'],
                    $questionnaire['basic_score'],
                    $questionnaire['advanced_score'],
                    $questionnaire['score'],
                    $questionnaire['weighted_score'],
                ];
                $rows->add($row);
            }
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
        $sheet->freezePane('A2');
    }
}
