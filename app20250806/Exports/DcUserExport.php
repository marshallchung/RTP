<?php

namespace App\Exports;

use App\DcUnit;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DcUserExport implements FromCollection, WithEvents
{
    use RegistersEventListeners;

    /**
     * @var DcUnit[]|Collection
     */
    private $data;

    /**
     * DcUnitExport constructor.
     * @param Collection|DcUnit[] $data
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
            '所在縣市',
            '社區名稱',
            '帳號',
            '建立日期',
        ];
        $rows->add($titleRow);
        foreach ($this->data as $item) {
            $row = [
                $item->county->name ?? null,
                $item->name,
                $item->dcUser->username ?? null,
                $item->dcUser->created_at ?? null,
            ];
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
        $sheet->freezePane('A2');
    }
}
