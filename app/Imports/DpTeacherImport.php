<?php

namespace App\Imports;

use App\DpTeacher;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

class DpTeacherImport implements ToCollection, WithStartRow, WithMultipleSheets
{
    public $successCount = 0;
    public $failedCount = 0;
    public $errorMessages = [];
    /**
     * @var int
     */
    private $userId;
    /**
     * @var Collection
     */
    private $dpSubjects;

    /**
     * DpTeacherImport constructor.
     * @param int $userId
     * @param Collection $dpSubjects
     */
    public function __construct(int $userId, Collection $dpSubjects)
    {
        //令欄位名稱不經過 str_slug 轉換，確保中文欄位名稱不會遺失
        HeadingRowFormatter::default('none');
        $this->userId = $userId;
        $this->dpSubjects = $dpSubjects;
    }

    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $rowId => $row) {
            try {
                //檢查必填欄位
                foreach ([0] as $idx) {
                    if (empty($row[$idx])) {
                        continue 2;
                    }
                }

                //欄位對應
                $data = [
                    'user_id'   => $this->userId,
                    'name'      => $row[0],
                    'tid'       => $row[1],
                    'belongsTo' => $row[2],
                    'title'     => $row[3],
                    'phone'     => $row[4],
                    'mobile'    => $row[5],
                    'email'     => $row[6],
                    'location'  => $row[7],
                    'address'   => $row[8],
                    'content'   => $row[9],
                    'active'    => 0,
                ];

                //建立或更新資料
                $dpTeacher = DpTeacher::updateOrCreate([
                    'tid' => $data['tid'],
                ], $data);
                $dpTeacher->dpTeacherSubjects()->delete(
                    []
                );

                //教授科目
                foreach ($this->dpSubjects as $idx => $dpSubject) {
                    $cell = $row[9 + 1 + $idx];
                    // 處理種子師資的通過日期
                    if (preg_match('#^B-(?P<passDate>\d{8})$#', $cell, $matches)) {
                        $passDateCarbon = new Carbon($matches['passDate']);
                        $passDate = $passDateCarbon->format('Y-m-d');
                        $cell = 'B';
                    } else {
                        $passDate = null;
                    }

                    $types = [
                        'A' => '基本師資',
                        'B' => '種子師資',
                        'C' => '基本及種子師資',
                    ];
                    $type = Arr::get($types, $cell);
                    if (empty($cell) || !$type) {
                        continue;
                    }


                    $dpTeacher->dpTeacherSubjects()->create([
                        'dp_subject_id' => $dpSubject->id,
                        'type'          => $type,
                        'pass_date'     => $passDate,
                    ]);
                }
                $this->successCount++;
            } catch (\Exception $exception) {
                $this->errorMessages[] = 'Row ' . ($rowId + $this->startRow()) . ': ' . $exception->getMessage();
                $this->failedCount++;
            }
        }
    }

    /**
     * 開始列數，跳過上方標題列
     * @return int
     */
    public function startRow(): int
    {
        return 3;
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
