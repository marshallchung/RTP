<?php

namespace App\Exports;

use File;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EvaluationCommissionExport implements FromCollection, WithEvents
{
    /**
     * @var File[]|Collection
     */
    private $data;

    /**
     * EvaluationCommissionExport constructor.
     * @param Collection|File[] $data
     */
    public function __construct(Collection $data)
    {
        $this->data = $data;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $rows = collect();
        //標題列
        $titleRow = [
            '編號',
            '單位名稱',
            '上傳日期',
            '上傳項目',
            '檔案名稱',
        ];
        $rows->add($titleRow);
        foreach ($this->data as $key => $item) {
            $row = [
                $key + 1,
                $item->post->user->name,
                $item->created_at,
                $item->post->topic->title ?? $item->post->topic_id,
                $item->name,
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
        return [
            AfterSheet::class => function (AfterSheet $event) {
                /** @var Worksheet $sheet */
                $sheet = $event->sheet;
                //超連結
                foreach ($this->data as $key => $item) {
                    $cell = 'E' . ($key + 2);
                    $sheet->getCell($cell)
                        ->getHyperlink()
                        ->setUrl(url($item->path))
                        ->setTooltip('點擊下載檔案');
                    $sheet->getStyle($cell)
                        ->applyFromArray(['font' => ['color' => ['rgb' => '0000FF'], 'underline' => 'single']]);
                }
                //凍結
                $sheet->freezePane('A2');
            },
        ];
    }
}
