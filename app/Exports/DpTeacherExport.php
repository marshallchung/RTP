<?php

namespace App\Exports;

use App\DpSubject;
use App\DpTeacher;
use App\DpTeacherSubject;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DpTeacherExport implements FromCollection, WithEvents, ShouldAutoSize, WithStrictNullComparison
{

    use RegistersEventListeners;

    /**
     * @var DpTeacher[]|Collection
     */
    private $data;

    /**
     * DpTeacherExport constructor.
     * @param Collection|DpTeacher[] $data
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
        $dpTeacherCount = DpTeacher::with('author', 'dpTeacherSubjects.dpSubject');
        $filterableFields = ['name', 'location'];
        foreach ($filterableFields as $filterableField) {
            $searchKeyword = request()->get($filterableField);
            if ($searchKeyword) {
                $dpTeacherCount->where($filterableField, 'like', "%{$searchKeyword}%");
            }
        }
        /*if (request()->has('expired') && request('expired') === '1') {
            $dpTeacherCount->whereHas('dpTeacherSubjects.DpSubject', function ($q) {
                $time = strtotime("-3 year", time());
                $date = date("Y-m-d", $time);
                $q->where('type', '=', '種子師資')
                    ->where('pass_date', '<', $date);
            });
        } elseif (request()->has('expired') && request('expired') === '0') {
            $dpTeacherCount->whereHas('dpTeacherSubjects.DpSubject', function ($q) {
                $time = strtotime("-3 year", time());
                $date = date("Y-m-d", $time);
                $q->where('type', '=', '種子師資')
                    ->where('pass_date', '>=', $date);
            });
        } else {
            $dpTeacherCount->whereHas('dpTeacherSubjects.DpSubject', function ($q) {
                $q->where('type', '=', '種子師資');
            });
        }*/

        if ($filteredDpSubject = DpSubject::find(request('dp_subject'))) {
            $dpTeacherCount->whereHas('dpTeacherSubjects.DpSubject', function ($q) use ($filteredDpSubject) {
                /** @var Builder|DpSubject $q */
                $q->where('id', $filteredDpSubject->id);
            });
        }
        $dpTeacherCount = $dpTeacherCount->get()->count();


        $dpTeacherExpiredCount = DpTeacher::with('dpTeacherSubjects.dpSubject')
            ->whereHas('dpTeacherSubjects.DpSubject', function ($q) {
                $time = strtotime("-3 year", time());
                $date = date("Y-m-d", $time);
                $q->where('type', '=', '種子師資')
                    ->where('pass_date', '<', $date);
            });
        $filterableFields = ['name', 'location'];
        foreach ($filterableFields as $filterableField) {
            $searchKeyword = request()->get($filterableField);
            if ($searchKeyword) {
                $dpTeacherExpiredCount->where($filterableField, 'like', "%{$searchKeyword}%");
            }
        }

        if ($filteredDpSubject = DpSubject::find(request('dp_subject'))) {
            $dpTeacherExpiredCount->whereHas('dpTeacherSubjects.DpSubject', function ($q) use ($filteredDpSubject) {
                $q->where('id', $filteredDpSubject->id);
            });
        }
        $dpTeacherExpiredCount = $dpTeacherExpiredCount->get()->count();


        $dpTeacherNotExpiredCount = DpTeacher::with('dpTeacherSubjects.dpSubject')
            ->whereHas('dpTeacherSubjects.DpSubject', function ($q) {
                $time = strtotime("-3 year", time());
                $date = date("Y-m-d", $time);
                $q->where('type', '=', '種子師資')
                    ->where('pass_date', '>=', $date);
            });
        $filterableFields = ['name', 'location'];
        foreach ($filterableFields as $filterableField) {
            $searchKeyword = request()->get($filterableField);
            if ($searchKeyword) {
                $dpTeacherNotExpiredCount->where($filterableField, 'like', "%{$searchKeyword}%");
            }
        }

        if ($filteredDpSubject = DpSubject::find(request('dp_subject'))) {
            $dpTeacherNotExpiredCount->whereHas('dpTeacherSubjects.DpSubject', function ($q) use ($filteredDpSubject) {
                $q->where('id', $filteredDpSubject->id);
            });
        }
        $dpTeacherNotExpiredCount = $dpTeacherNotExpiredCount->get()->count();
        $dpSeedTeacherCount = $dpTeacherExpiredCount + $dpTeacherNotExpiredCount;
        $dpBaseTeacherCount = $dpTeacherCount - $dpSeedTeacherCount;

        $rows = collect();
        //第一列
        /*$row1 = ['', '', '', '', '', '', '', '', '', '基本師資：'];
        $row2 = ['', '', '', '', '', '', '', '', '', '種子師資：'];
        $row3 = ['', '', '', '', '', '', '', '', '', '基本及種子師資：'];*/
        $row1 = ["防災士師資總人數{$dpTeacherCount}人", '', '', '', '', '', '', '', '基本師資：'];
        $row2 = ["基本師資總人數{$dpBaseTeacherCount}人，種子師資總人數{$dpSeedTeacherCount}人", '', '', '', '', '', '', '', '種子師資：'];
        $row3 = ["無逾期師資人數{$dpTeacherNotExpiredCount}人，逾期師資人數{$dpTeacherExpiredCount}人", '', '', '', '', '', '', '', '基本及種子師資：'];
        //標題列
        $titleRow = [
            '姓名',
            '身分證',
            '服務單位',
            '職稱',
            '市內電話',
            '行動電話',
            '電子郵件',
            '居住縣市',
            '現居地址',
            '學經歷專長',
        ];
        /** @var DpSubject $dpSubject */
        $dpSubjects = DpSubject::sorted()->with('dpTeacherSubjects')->where('advance', '=', 0)->get();
        $filteredTeacherIds = $this->data->pluck('id')->toArray();
        foreach ($dpSubjects as $idx => $dpSubject) {
            $countBasicTeacher = $dpSubject->dpTeacherSubjects()->whereIn('dp_teacher_id', $filteredTeacherIds)
                ->where('type', '基本師資')->count();
            $countSeedTeacher = $dpSubject->dpTeacherSubjects()->whereIn('dp_teacher_id', $filteredTeacherIds)
                ->where('type', '種子師資')->count();
            $countBasicAndSeedTeacher = $dpSubject->dpTeacherSubjects()
                ->whereIn('dp_teacher_id', $filteredTeacherIds)
                ->where('type', '基本及種子師資')->count();
            $row1[] = $countBasicTeacher;
            $row2[] = $countSeedTeacher;
            $row3[] = $countBasicAndSeedTeacher;
            $titleRow[] = $dpSubject->name;
        }
        $titleRow[] = '地址識別碼';
        $rows->add($row1);
        $rows->add($row2);
        $rows->add($row3);
        $rows->add($titleRow);
        $teacherTypes = [
            '基本師資' => 'A',
            '種子師資' => 'B',
            '基本及種子師資' => 'C',
        ];
        foreach ($this->data as $item) {
            $row = [
                $item->name,
                $item->tid,
                $item->belongsTo,
                $item->title,
                $item->phone,
                $item->mobile,
                $item->email,
                $item->location,
                $item->address,
                $item->content,
            ];
            foreach ($dpSubjects as $dpSubject) {
                /** @var DpTeacherSubject $dpTeacherSubject */
                $dpTeacherSubject = $item->dpTeacherSubjects->where('dp_subject_id', $dpSubject->id)->first();
                $teacherType = $dpTeacherSubject ? Arr::get($teacherTypes, $dpTeacherSubject->type) : null;
                // 處理種子師資的通過日期
                if ($dpTeacherSubject && $dpTeacherSubject->type == '種子師資' && $dpTeacherSubject->pass_date) {
                    $passDateCarbon = new Carbon($dpTeacherSubject->pass_date);
                    $teacherType .= '-' . $passDateCarbon->format('Ymd');
                }
                $row[] = $teacherType;
            }
            $row[] = $item->addressId;
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
        $sheet->freezePane('A5');
    }
}
