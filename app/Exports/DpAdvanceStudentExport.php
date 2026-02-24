<?php

namespace App\Exports;

use App\DpStudent;
use App\DpAdvanceStudentSubject;
use App\DpSubject;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DpAdvanceStudentExport implements FromCollection, WithEvents, ShouldAutoSize, WithCustomStartCell, WithStyles
{
    /**
     * @var DpStudent[]|Collection
     */
    private $data;

    /**
     * DpStudentExport constructor.
     * @param Collection|DpStudent[] $data
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
            '證書編號',
            '授證日期',
            '姓名',
            '身分證字號',
            "年\n(西元)",
            '月',
            '日',
            '性別',
            '市內電話',
            '行動電話',
            'E-mail',
            '所屬縣市',
            '現居地址-縣市',
            '現居地址-鄉鎮市區',
            '居住地址',
            '戶籍地址-縣市',
            '戶籍地址-鄉鎮市區',
            '戶籍地址',
            '最高學歷',
            '服務單位',
            '職稱',
            '參與防災工作意願',
            '狀態',
            'A1',
            'A2',
            'A3',
            'A4',
            'B1',
            'B2',
            'B3',
            'B4',
            'C1',
            'C2',
            'D1',
            'D2',
            'E1',
            'E2',
        ];
        /** @var DpSubject $dpSubject */
        $rows->add($titleRow);

        $sql = "dp_subjects.name,dp_advance_course_subjects.hour,dp_advance_course_subjects.start_date,dp_courses.name AS course_name";
        foreach ($this->data as $item) {
            $student_id = $item->id;
            $dp_advance_student_subjects = DpSubject::selectRaw($sql)->leftJoin('dp_advance_student_subjects', function ($join) use ($student_id) {
                $join->on('dp_advance_student_subjects.dp_course_subject_id', '=', 'dp_subjects.id')
                    ->where('dp_advance_student_subjects.dp_student_id', '=', $student_id);
            })
                ->leftJoin('dp_advance_course_subjects', 'dp_advance_course_subjects.id', '=', 'dp_advance_student_subjects.dp_advance_course_subjects')
                ->leftJoin('dp_courses', 'dp_courses.id', '=', 'dp_advance_course_subjects.dp_course_id')
                ->where('dp_subjects.advance', true)
                ->orderBy('dp_subjects.position', 'asc')->get();
            try {
                $birth = Carbon::parse($item->birth);
            } catch (\Throwable $th) {
                $empty_date = ['year' => '', 'month' => '', 'day' => ''];
                $birth = (object) $empty_date;
            }
            $row = [
                $item->certificate,
                $item->date_first_finish,
                $item->name,
                $item->TID,
                $birth ? $birth->year : '',
                $birth ? $birth->month : '',
                $birth ? $birth->day : '',
                $item->gender,
                $item->phone,
                $item->mobile,
                $item->email,
                $item->county ? $item->county->name : '',
                $item->residence_county,
                $item->township,
                $item->address,
                $item->household_county,
                $item->household_township,
                $item->household_address,
                $item->education,
                $item->service,
                $item->title,
                $item->willingness ? '有' : '無',
                $item->expire_state,
            ];
            foreach ($dp_advance_student_subjects as $one_subjects) {
                $row[] = $one_subjects->start_date ? ($one_subjects->course_name . "\n" . $one_subjects->start_date) : '';
            }
            $rows->add($row);
        }

        return $rows;
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        $data = $this->data;

        return [
            AfterSheet::class => function (AfterSheet $event) use ($data) {
                /** @var Worksheet $sheet */
                $sheet = $event->sheet;
                //第一列
                $passCount = $data->where('expire_state', '合格')->count();
                $traingCount = $data->where('expire_state', '受訓中')->count();
                $soonExpireCount = $data->where('expire_state', '即將逾期')->count();
                $expireCount = $data->where('expire_state', '逾期')->count();
                $sheet->mergeCells('A1:I1');
                $sheet->setCellValue('A1', "合格人數：{$passCount}人、受訓中人數：{$traingCount}人、即將逾期人數：{$soonExpireCount}人、逾期人數：{$expireCount}人");
                //凍結
                $sheet->freezePane('A3');
                $sheet->calculateColumnWidths();
            },
        ];
    }

    /**
     * @return string
     */
    public function startCell(): string
    {
        return 'A2';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            'A:AE'  => ['alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ]],
        ];
    }
}
