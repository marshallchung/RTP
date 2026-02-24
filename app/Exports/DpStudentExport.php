<?php

namespace App\Exports;

use App\DpStudent;
use App\DpStudentSubject;
use App\DpSubject;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DpStudentExport implements FromCollection, WithEvents, ShouldAutoSize, WithCustomStartCell
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
            '上線',
            '證書編號',
            '姓名',
            '培訓計畫名稱',
            "年\n(西元)",
            '月',
            '日',
            '培訓單位',
            '身分證字號',
            "年\n(西元)",
            '月',
            '日',
            '性別',
            '市內電話',
            '行動電話',
            'E-mail',
            '所屬縣市',
            '居住地址',
            '最高學歷',
            '服務單位',
            '職稱',
            '學科測驗成績',
            '術科測驗成績是否合格',
            '認證結果是否合格',
        ];
        /** @var DpSubject $dpSubject */
        $dpSubjects = DpSubject::sorted()->with('dpTeacherSubjects')->get();
        foreach ($dpSubjects as $idx => $dpSubject) {
            $titleRow[] = $dpSubject->name;
        }
        $titleRow[] = '地址識別碼';
        $rows->add($titleRow);

        $this->data->load('dpStudentSubjects');
        foreach ($this->data as $item) {
            $dateFirstFinish = $item->date_first_finish ? Carbon::parse($item->date_first_finish) : null;
            try {
                $birth = Carbon::parse($item->birth);
            } catch (\Throwable $th) {
                $empty_date = ['year' => '', 'month' => '', 'day' => ''];
                $birth = (object) $empty_date;
            }
            $row = [
                $item->active,
                $item->certificate,
                $item->name,
                $item->plan,
                $dateFirstFinish ? $dateFirstFinish->year : '',
                $dateFirstFinish ? $dateFirstFinish->month : '',
                $dateFirstFinish ? $dateFirstFinish->day : '',
                $item->unit_first_course,
                $item->TID,
                $birth ? $birth->year : '',
                $birth ? $birth->month : '',
                $birth ? $birth->day : '',
                $item->gender,
                $item->phone,
                $item->mobile,
                $item->email,
                $item->county ? $item->county->name : '',
                $item->address,
                $item->education,
                $item->service,
                $item->title,
                $item->score_academic,
                $item->physical_pass ? 1 : null,
                $item->pass ? 1 : null,
            ];
            foreach ($dpSubjects as $dpSubject) {
                /** @var DpStudentSubject $dpStudentSubject */
                $dpStudentSubject = $item->dpStudentSubjects
                    ->where('dp_subject_id', $dpSubject->id)->first();
                $row[] = $dpStudentSubject ? '●' : null;
            }
            $row[] = $item->addressId;
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
                $totalCount = $data->count();
                $maleCount = $data->where('gender', '男')->count();
                $femaleCount = $totalCount - $maleCount;
                $sheet->setCellValue('A1', "男性：{$maleCount}人，女性：{$femaleCount}人，防災士共{$totalCount}人");
                $sheet->getStyle('A1')->getFont()->getColor()->setRGB('FF0000');
                $sheet->getStyle('A1')->getFont()->setBold(true);
                $sheet->setCellValue('R1', '合格打1');
                $sheet->setCellValue('S1', '參訓情形(已完成課程或抵免者打「●」)');
                $sheet->setCellValue('D1', "完訓日期\n(發證日期)");
                $sheet->mergeCells('D1:F1');
                $sheet->setCellValue('I1', '出生日期');
                $sheet->mergeCells('I1:K1');
                //凍結
                $sheet->freezePane('A3');
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
}
