<?php

namespace App\Exports;

use App\DcUnit;
use App\Nfa\Repositories\DcUnitRepository;
use DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DcUnitExport implements FromCollection, WithEvents
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
        //第一列
        //$user = Auth::user();
        //$county_id = $user ? ($user->origin_role > 2 ? $user->id : null) : null;
        //$repo = new DcUnitRepository();
        //$rankCount = $repo->getRankCount($county_id)->toArray();
        //$withinPlanCount = $repo->getWithinPlanCount($county_id);
        //$nativeCount = $repo->getNativeCount($county_id);
        //$dateExtensionCount = $repo->getDateExtensionCount($county_id);
        //$expireCount = $repo->getExpireCount($county_id);
       // $firstRow = [sprintf(
            //'未審查：%d，一星：%d，二星：%d，三星：%d，計畫內：%d，原民地區：%d，展延狀態：%d，逾期狀態：%d',
           // $rankCount['未審查'] ?? 0,
           // $rankCount['一星'] ?? 0,
           // $rankCount['二星'] ?? 0,
           // $rankCount['三星'] ?? 0,
          //  $withinPlanCount,
           // $nativeCount,
           // $dateExtensionCount,
          //  $expireCount
       // )];
       // $rows->add($firstRow);
        //標題列
        $titleRow = [
            '所在縣市',
            '社區名稱',
            '居住人數',
            '社區概略範圍',
            '過去是否曾推動過防災社區',
            '社區環境概述',
            '社區災害潛勢與風險概述',
            '社區類型',
            '負責人姓名',
            /*'聯絡電話',
            '負責人Email',
            '負責人地址',
            '擔任社區職務',
            '防災士姓名',
            '防災士電話',*/
            '星等',
        ];
        $rows->add($titleRow);
        foreach ($this->data as $item) {
            $row = [
                $item->county->name ?? null,
                $item->name,
                $item->population,
                $item->location,
                $item->is_experienced ? '是' : '否',
                $item->environment,
                $item->risk,
                $item->pattern,
                $item->manager,
                /*$item->phone,
                $item->email,
                $item->manager_position,
                $item->manager_address,
                $item->dp_name,
                $item->dp_phone,*/
                $item->rank,
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
        $sheet->freezePane('A3');
    }
}
