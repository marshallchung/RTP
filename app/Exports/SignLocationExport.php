<?php

namespace App\Exports;

use App\SignLocation;
use App\User;
use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SignLocationExport implements FromQuery, WithMapping, WithHeadings, WithEvents, ShouldAutoSize
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
        $countyUser = [];
        if (!empty($this->countyId)) $countyUser = User::find($this->countyId);

        $signLocationQuery = SignLocation::with('user');

        if (!empty($countyUser)) {
            $countyUserIds = User::where('id', $countyUser->id)->orWhere('county_id', $countyUser->id)->pluck('id');
            $signLocationQuery->whereIn('user_id', $countyUserIds);
        }

        return $signLocationQuery;
    }

    /**
     * @param Address $address
     *
     * @return array
     */
    public function map($signLocation): array
    {
        return [
            // $signLocation->id,
            $signLocation->user->getCountyNameAttribute(),
            $signLocation->user->name,
            trim($signLocation->description),
            $signLocation->latitude,
            $signLocation->longitude,
        ];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            // '資料ID',
            '縣市',
            '單位',
            '簡介',
            '緯度',
            '經度',
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
