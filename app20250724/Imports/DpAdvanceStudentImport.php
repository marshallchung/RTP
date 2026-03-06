<?php

namespace App\Imports;

use App\DpAdvanceCourseSubject;
use App\DpAdvanceStudentSubject;
use App\DpCourse;
use App\DpStudent;
use App\DpSubject;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

class DpAdvanceStudentImport implements ToCollection, WithStartRow, WithMultipleSheets
{
    public $successCount = 0;
    public $failedCount = 0;
    public $errorMessages = [];
    /**
     * @var int
     */
    private $userId;
    private $organizer;
    /**
     * @var Collection
     */
    private $dpSubjects;

    /**
     * DpAdvanceStudentImport constructor.
     * @param int $userId
     * @param Collection $dpSubjects
     */
    public function __construct(int $userId, string $organizer, DpCourse $dpSubjects)
    {
        //令欄位名稱不經過 str_slug 轉換，確保中文欄位名稱不會遺失
        HeadingRowFormatter::default('none');
        $this->userId = $userId;
        $this->organizer = $organizer;
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
                foreach ([4, 5, 6, 7, 8, 9, 11, 12, 13, 14, 15, 16, 17, 18, 19] as $idx) {
                    if (empty($row[$idx])) {
                        continue 2;
                    }
                }
                //檢查縣市
                $countyName = $row[13];
                $county = User::whereType('county')->whereName($countyName)->first();
                if (!$county) {
                    continue;
                }
                $countyName = $row[14];
                $county = User::whereType('county')->whereName($countyName)->first();
                if (!$county) {
                    continue;
                }
                $countyName = $row[17];
                $county = User::whereType('county')->whereName($countyName)->first();
                if (!$county) {
                    continue;
                }
                //完訓日期
                if (empty($row[1])) {
                    $dateFirstFinish = null;
                } else {
                    $dateFirstFinishYear = $row[1] < 1000 ? $row[1] + 1911 : $row[1];
                    $dateFirstFinish = Carbon::createFromDate($dateFirstFinishYear, $row[2], $row[3]);
                }
                //出生日期
                $birthYear = $row[6] < 1000 ? $row[6] + 1911 : $row[6];
                $birth = Carbon::createFromDate($birthYear, $row[7], $row[8]);

                $data = [
                    'user_id'           => $this->userId,
                    'county_id'         => $county->id,
                    'certificate'       => $row[0],
                    'name'              => $row[4],
                    'plan'              => $this->dpSubjects->name,
                    'date_first_finish' => $dateFirstFinish,
                    'Multiple'          => '',
                    'unit_first_course' => $this->organizer,
                    'TID'               => strtoupper($row[5]),
                    'username'          => $row[5],
                    'password'          => bcrypt($birth->format('Ymd')),   //bcrypt($data['birth'])
                    'birth'             => $birth->format('Ymd'),
                    'gender'            => $row[9],
                    'phone'             => $row[10],
                    'mobile'            => $row[11],
                    'email'             => $row[12],
                    'residence_county'  => $row[14],
                    'township'          => $row[15],
                    'address'           => $row[16],
                    'household_county'  => $row[17],
                    'household_township' => $row[18],
                    'household_address' => $row[19],
                    'education'          => $row[20],
                    'service'            => $row[21],
                    'title'              => $row[22],
                    'willingness'        => ($row[23] == '有' || $row[24] == 1),
                    'score_academic'     => 0,
                    'physical_pass'      => 0,
                    'pass'               => ($row[24] == '合格' || $row[24] == 1),
                    'field'              => null,
                    'community'          => null,
                    'unit_second_course' => '',
                    'date_second_finish' => null,
                    'active'             => 1,
                    'advance'             => 1,
                ];
                if ($old_student = DpStudent::where('TID', $data['TID'])->where('advance', 1)->whereActive(true)->first()) {
                    $data['change_default'] = $old_student->change_default;
                    $data['next_change'] = $old_student->next_change;
                    $data['password'] = $old_student->password;
                    $data['username'] = $old_student->username;
                    DpStudent::where('TID', $data['TID'])->where('advance', 1)->whereActive(true)->update(['username' => '', 'password' => '', 'active' => false]);
                }
                /** @var DpSubject $dpStudent */
                /*$dpStudent = DpStudent::updateOrCreate([
                    'TID' => $data['TID'],
                ], $data);*/
                $dpStudent = DpStudent::create($data);
                //參訓科目
                $trainingOptions = [
                    'A1.簡易搜救的原則、任務範圍、應用時機與基礎培訓需求',
                    'A2.個人防護裝備的選擇與使用方法',
                    'A3.簡易搜救的安全準則及協助受災民眾的方法（情境想定）',
                    'A4.與政府正規救援行動的銜接',
                    'B1.救災護理及各類型傷情處置訓練',
                    'B2.社區緊急救護行動之準備與團隊合作',
                    'B3.基礎生命維持技能BLS訓練',
                    'B4.社區大量傷患事件之因應管理對策',
                    'C1.避難收容處所的空間配置規劃、分工與後勤管理',
                    'C2.避雞收容處所管理運作實作培訓',
                    'D1.大規模災害及衝突對企業的衝擊（情境想定）',
                    'D2.企業持續營運及安全防護模擬實作',
                    'E1.警報訊息種類、e點通使用與推廣',
                    'E2.通訊方法實作',
                ];
                for ($row_idx = 0; $row_idx < 14; $row_idx++) {
                    $cell = $row[25 + $row_idx];
                    if ($cell == '●') {
                        foreach ($this->dpSubjects['dpAdvanceSubjects'] as $one_subject) {
                            if ($one_subject['name'] == $trainingOptions[$row_idx]) {
                                DpAdvanceStudentSubject::create(['dp_student_id' => $dpStudent->id, 'dp_course_subject_id' => $one_subject->id, 'dp_advance_course_subjects' => $one_subject->pivot->id]);
                            }
                        }
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
