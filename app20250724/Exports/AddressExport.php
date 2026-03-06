<?php

namespace App\Exports;

use App\Address;
use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AddressExport implements FromQuery, WithMapping, WithHeadings, WithEvents, ShouldAutoSize
{
    use RegistersEventListeners;
    /**
     * @var int|null
     */
    private $countyId;

    /**
     * AddressExport constructor.
     * @param int|null $countyId
     */
    public function __construct(?int $countyId)
    {
        $this->countyId = $countyId;
    }

    /**
     * @return Builder
     */
    public function query()
    {
        $addressQuery = Address::with('county')->orderBy('county_id')->orderBy('position');
        if ($this->countyId) {
            $addressQuery->where('county_id', $this->countyId);
        }

        return $addressQuery;
    }

    /**
     * @param Address $address
     *
     * @return array
     */
    public function map($address): array
    {
        return [
            $address->county ? $address->county->name : '',
            $address->unit,
            $address->title,
            $address->name,
            $address->phone,
            $address->email,
            $address->updated_at,
        ];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            '縣市',
            '單位',
            '職稱',
            '姓名',
            '公務電話',
            '電子郵件',
            '更新時間',
        ];
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
