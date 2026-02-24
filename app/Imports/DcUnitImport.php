<?php

namespace App\Imports;

use App\DcUnit;
use App\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStartRow;

class DcUnitImport implements ToCollection, WithStartRow, WithMultipleSheets
{
    public $successCount = 0;
    public $failedCount = 0;
    public $errorMessages = [];
    /**
     * @var int
     */
    private $userId;

    /**
     * DcUnitImport constructor.
     * @param int $userId
     */
    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }


    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $rowId => $row) {
            try {
                //檢查必填欄位
                foreach ([0, 1, 2, 3, 4, 5, 6, 7, 9, 10, 12, 13, 14] as $idx) {
                    if (empty($row[$idx])) {
                        continue 2;
                    }
                }
                $countyId = User::whereName($row[10])->first()->id ?? null;
                $isExperienced = empty($row[12]) ? '' : ($row[12] == '是' ? 1 : 0);

                //欄位對應
                $data = [
                    'user_id'          => $this->userId,
                    'name'             => $row[0],
                    'manager'          => $row[1],
                    'manager_position' => $row[2],
                    'phone'            => $row[3],
                    'email'            => $row[4],
                    'manager_address'  => $row[5],
                    'dp_name'          => $row[6],
                    'dp_phone'         => $row[7],
                    'population'       => $row[8] ?? 0,
                    'pattern'          => $row[9] ?? null,
                    'county_id'        => $countyId ?? '',
                    'location'         => $row[11] ?? '',
                    'is_experienced'   => $isExperienced,
                    'within_plan'      => ($row[13] == '是' || $row[13] == 1),
                    'native'           => ($row[14] == '是' || $row[14] == 1),
                    'environment'      => $row[15] ?? '',
                    'risk'             => $row[16] ?? '',
                    'active'           => 0,
                ];
                //建立資料
                DcUnit::create($data);
                $this->successCount++;
            } catch (\Exception $exception) {
                $this->errorMessages[] = 'Row ' . ($rowId + $this->startRow()) . ': ' . $exception->getMessage();
                $this->failedCount++;
            }
        }
    }

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        return [
            0 => $this,
        ];
    }
}
