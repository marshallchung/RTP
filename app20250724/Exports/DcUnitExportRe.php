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
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use App\User;
use Carbon\Carbon;

class DcUnitExportRe implements FromCollection, WithEvents
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

    private function getCounties($county_id = null)
    {
        if ($county_id) {
            $countyIdNames = User::where('id', $county_id)->where('type', 'county')->whereNull('county_id')->pluck('name', 'id')->toArray();
        } else {
            $countyIdNames = User::where('type', 'county')->whereNull('county_id')->pluck('name', 'id')->toArray();
        }

        return [null => '縣市'] + $countyIdNames;
    }

    public function collection()
    {
        $rows = collect();

        $user = Auth::user();
        $county_id = $user ? ($user->origin_role > 2 ? $user->id : null) : null;
        $repo = new DcUnitRepository();
        $rankCount = $repo->getRankCount($county_id)->toArray();
        $withinPlanCount = $repo->getWithinPlanCount($county_id);
        $nativeCount = $repo->getNativeCount($county_id);
        $dateExtensionCount = $repo->getDateExtensionCount($county_id);
        $expireCount = $repo->getExpireCount($county_id);

        $total = ($rankCount['一星'] ?? 0) + ($rankCount['二星'] ?? 0) + ($rankCount['三星'] ?? 0);

        $this->summaryText =
        '一星：' . ($rankCount['一星'] ?? 0) .
        '，二星：' . ($rankCount['二星'] ?? 0) .
        '，三星：' . ($rankCount['三星'] ?? 0) .
        '，總計：' . $total;

        $countyList = [
            '基隆市', '臺北市', '新北市', '桃園市', '新竹縣', '新竹市',
            '苗栗縣', '臺中市', '南投縣', '彰化縣', '雲林縣', '嘉義市', '嘉義縣',
            '臺南市', '高雄市', '屏東縣', '宜蘭縣', '花蓮縣', '臺東縣',
            '澎湖縣', '金門縣', '連江縣',
        ];
        $years = ['109', '110', '111', '112', '113'];
        $ranks = ['一星', '二星'];
        $dataMap = [];
        foreach ($countyList as $county) {
            foreach ($years as $year) {
                foreach ($ranks as $rank) {
                    $dataMap[$county][$year][$rank] = 0; 
                }
            }

            $dataMap[$county]['total']['一星'] = 0;
            $dataMap[$county]['total']['二星'] = 0;
        }
        $countyIdNameMap = User::where('type', 'county')
        ->whereNull('county_id')
        ->pluck('name', 'id')
        ->toArray();
        foreach ($this->data as $unit) {
            $rank = $unit->rank;
            $date = $unit->rank_started_date ? Carbon::parse($unit->rank_started_date) : null;
            if (!$date || !in_array($rank, $ranks)) {
                continue;
            }

            $year = (string)($date->year - 1911); // 民國年
            $county = $countyIdNameMap[$unit->county_id] ?? null;

            if (!$county || !in_array($county, $countyList) || !in_array($year, $years)) {
                continue;
            }

            $dataMap[$county][$year][$rank]++;
            $dataMap[$county]['total'][$rank]++;
        }
        foreach ($countyList as $county) {
            $row = [$county];
            foreach ($years as $year) {
                foreach ($ranks as $rank) {
                    $row[] = $dataMap[$county][$year][$rank];
                }
            }
            $row[] = $dataMap[$county]['total']['一星'];
            $row[] = $dataMap[$county]['total']['二星'];
            $rows->push($row);
        }
        $this->bodyRows = $rows;
        return collect();
    }

    /**
     * @param AfterSheet $event
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public static function afterSheet(AfterSheet $event)
    {
        /** @var Worksheet $sheet */
        $sheet = $event->sheet;
        $sheet->mergeCells('A1:J1')          
              ->setCellValue('A1', '109‑113年韌性社區標章通過統計表');
        $sheet->mergeCells('A2:J2');
        $exportObj = $event->getConcernable();
        $sheet->setCellValue('A2', $exportObj->summaryText ?? '');
        $sheet->freezePane('B5');
        $sheet->mergeCells('A3:A4')
              ->setCellValue('A3','縣市別');
        $years  = ['109','110','111','112','113'];
        $ranks  = ['一星','二星'];
        $colIndex = 2; 
        foreach ($years as $year) {
            $startColLetter = Coordinate::stringFromColumnIndex($colIndex);
            $endColLetter   = Coordinate::stringFromColumnIndex($colIndex+1);

            $sheet->mergeCells("{$startColLetter}3:{$endColLetter}3")
                ->setCellValue("{$startColLetter}3", "{$year}年");

            $sheet->setCellValue("{$startColLetter}4", '一星');
            $sheet->setCellValue("{$endColLetter}4",   '二星');

            $colIndex += 2;
        }

        // 累積數量欄
        $accStartCol = Coordinate::stringFromColumnIndex($colIndex);
        $accEndCol   = Coordinate::stringFromColumnIndex($colIndex+1);

        $sheet->mergeCells("{$accStartCol}3:{$accEndCol}3")
            ->setCellValue("{$accStartCol}3",'累積數量');

        $sheet->setCellValue("{$accStartCol}4",'一星');
        $sheet->setCellValue("{$accEndCol}4",  '二星');

        $exportObj = $event->getConcernable();
        $bodyRows  = $exportObj->bodyRows ?? collect();

        // 把資料列貼到 A5
        $sheet->getDelegate()->fromArray($bodyRows->toArray(), null, 'A5');



        // A1 樣式設定
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        // A2 樣式設定
        $sheet->getStyle('A2')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);
        // 設定樣式
        $lastRow    = $sheet->getHighestRow();   // 自動抓到最後列
        $lastCol    = $sheet->getHighestColumn();// 最後欄字母
        $sheet->getStyle("A3:{$lastCol}{$lastRow}")->applyFromArray([
            'font'=>['bold'=>true],
            'alignment'=>[
                'horizontal'=>\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical'=>\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders'=>[
                'allBorders'=>['borderStyle'=>\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
            ],
        ]);
        // 設定列高
        $sheet->getRowDimension(1)->setRowHeight(30);
        $sheet->getRowDimension(2)->setRowHeight(30);
        // 設定列高
        $sheet->getRowDimension(3)->setRowHeight(25);
        $sheet->getRowDimension(4)->setRowHeight(20);

    // 也可設定背景色（選擇性）
    $sheet->getStyle('A2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
    $sheet->getStyle('A2')->getFill()->getStartColor()->setRGB('FCE4D6');
    $sheet->getStyle("{$accStartCol}3:{$accEndCol}{$lastRow}")
          ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                      ->getStartColor()->setRGB('FCE4D6');

    // 3.4 行高
    $sheet->getRowDimension(1)->setRowHeight(28);
    $sheet->getRowDimension(2)->setRowHeight(24);
    $sheet->getRowDimension(3)->setRowHeight(20);
    $sheet->getRowDimension(4)->setRowHeight(18);


    }
}
