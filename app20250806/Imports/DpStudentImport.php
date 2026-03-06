<?php

namespace App\Imports;

use App\DpStudent;
use App\DpSubject;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

class DpStudentImport implements ToCollection, WithStartRow, WithMultipleSheets
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
     * DpStudentImport constructor.
     * @param int $userId
     * @param Collection $dpSubjects
     */
    public function __construct(int $userId, Collection $dpSubjects, $Multiple)
    {
        //令欄位名稱不經過 str_slug 轉換，確保中文欄位名稱不會遺失
        HeadingRowFormatter::default('none');
        $this->userId = $userId;
        $this->dpSubjects = $dpSubjects;
        $this->Multiple = $Multiple;
    }

    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $rowId => $row) {
            try {
                //檢查必填欄位
                foreach ([0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 13, 14, 15, 16, 21, 22, 23] as $idx) {
                    if (empty($row[$idx])) {
                        continue 2;
                    }
                }
                //檢查縣市
                $countyName = $row[15];
                $county = User::whereType('county')->whereName($countyName)->first();
                if (!$county) {
                    continue;
                }
                //完訓日期
                $dateFirstFinishYear = $row[3] < 1000 ? $row[3] + 1911 : $row[3];
                $dateFirstFinish = Carbon::createFromDate($dateFirstFinishYear, $row[4], $row[5]);
                //出生日期
                $birthYear = $row[8] < 1000 ? $row[8] + 1911 : $row[8];
                $birth = Carbon::createFromDate($birthYear, $row[9], $row[10]);

                $data = [
                    'user_id'           => $this->userId,
                    'county_id'         => $county->id,
                    'certificate'       => $row[0],
                    'name'              => $row[1],
                    'plan'              => $row[2],
                    'date_first_finish' => $dateFirstFinish,
                    'Multiple'          => $this->Multiple,
                    'unit_first_course' => $row[6],
                    'TID'               => strtoupper($row[7]),
                    'username'          => $row[7],
                    'password'          => bcrypt($birth->format('Ymd')),   //bcrypt($data['birth'])
                    'birth'             => $birth->format('Ymd'),
                    'gender'            => $row[11],
                    'phone'             => $row[12],
                    'mobile'            => $row[13],
                    'email'             => $row[14],

                    'address'            => $row[16],
                    'education'          => $row[17],
                    'service'            => $row[18],
                    'title'              => $row[19],
                    'willingness'        => ($row[20] == '有' || $row[20] == 1),
                    'score_academic'     => $row[21],
                    'physical_pass'      => ($row[22] == '合格' || $row[22] == 1),
                    'pass'               => ($row[23] == '合格' || $row[23] == 1),
                    'field'              => null,
                    'community'          => null,
                    'unit_second_course' => '',
                    'date_second_finish' => null,
                    'active'             => 1,
                ];
                if ($old_student = DpStudent::where('TID', $data['TID'])->whereActive(true)->first()) {
                    $data['change_default'] = $old_student->change_default;
                    $data['next_change'] = $old_student->next_change;
                    $data['password'] = $old_student->password;
                    $data['username'] = $old_student->username;
                    DpStudent::where('TID', $data['TID'])->whereActive(true)->update(['username' => '', 'password' => '', 'active' => false]);
                }
                /** @var DpSubject $dpStudent */
                /*$dpStudent = DpStudent::updateOrCreate([
                    'TID' => $data['TID'],
                ], $data);*/
                $dpStudent = DpStudent::create($data);
                //參訓科目
                foreach ($this->dpSubjects as $idx => $dpSubject) {
                    $cell = $row[23 + $idx];
                    if ($cell == '●') {
                        $dpStudent->dpStudentSubjects()->create([
                            'dp_subject_id' => $dpSubject->id,
                        ]);
                    }
                }
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
