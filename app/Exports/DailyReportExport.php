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

class DailyReportExport implements FromCollection, WithEvents, ShouldAutoSize, WithCustomStartCell, WithStyles
{
    /**
     * @var DpStudent[]|Collection
     */
    private $data;

    /**
     * DpStudentExport constructor.
     * @param Collection|DpStudent[] $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $user = auth()->user();
        extract($this->data);
        $rows = collect();
        //標題列
        $titleRow = [
            '計畫管考項目狀態通知',
            $user->name,
            (intval(date("Y")) - 1911) . date("年n月d日"),
        ];
        /** @var DpSubject $dpSubject */
        $rows->add($titleRow);
        $rows->add(['']);
        $rows->add(['管考項目', '截止日期', '狀態']);
        $c_expire_date = (array_key_exists('reports', $report_public_dates)) ? $report_public_dates['reports']['c_expire_date'] : '';
        $status = "";
        if ($user->type == 'county' && count($report_data) > 0) {
            if ($report_data[0]['topic_count'] == $report_data[0]['report_count']) {
                $status = "已繳交";
            } elseif (
                date("Y-m-d") >= $report_public_dates['reports']['expire_soon_date'] &&
                date("Y-m-d") <= $report_public_dates['reports']['expire_date'] &&
                $report_data[0]['report_count'] <= $report_data[0]['topic_count']
            ) {
                $status = "即將逾期";
            } elseif (
                $report_public_dates['reports']['expire_date'] <= date("Y-m-d") &&
                $report_data[0]['report_count'] <= $report_data[0]['topic_count']
            ) {
                $status = "逾期";
            } elseif ($report_data[0]['report_count'] == 0) {
                $status = "尚未繳交";
            }
        } elseif ($user->type != 'county' && count($report_data) > 0) {
            foreach ($report_data as $one_data) {
                if (
                    date("Y-m-d") >= $report_public_dates['reports']['expire_soon_date'] &&
                    date("Y-m-d") <= $report_public_dates['reports']['expire_date'] &&
                    $one_data['report_count'] <= $one_data['topic_count']
                ) {
                    $status .= !empty($status) ? "\n" : '';
                    $status .= "即將逾期：" . $one_data['name'];
                } elseif (
                    $report_public_dates['reports']['expire_date'] <= date("Y-m-d") &&
                    $one_data['report_count'] <= $one_data['topic_count']
                ) {
                    $status .= !empty($status) ? "\n" : '';
                    $status .= "逾期：" . $one_data['name'];
                }
            }
        }
        $rows->add(['計畫執行成果', $c_expire_date, $status]);

        $c_expire_date = (array_key_exists('plan', $report_public_dates)) ? $report_public_dates['plan']['c_expire_date'] : '';
        $status = "";
        if ($user->type == 'county' && count($plan_data) > 0) {
            if ($plan_data[0]['plan_count'] > 0) {
                $status = "已繳交";
            } elseif (
                date("Y-m-d") >= $report_public_dates['plan']['expire_soon_date'] &&
                date("Y-m-d") <= $report_public_dates['plan']['expire_date'] && $plan_data[0]['plan_count'] == 0
            ) {
                $status = "即將逾期";
            } elseif (
                $report_public_dates['plan']['expire_date'] <= date("Y-m-d") &&
                $plan_data[0]['plan_count'] == 0
            ) {
                $status = "逾期";
            } elseif ($plan_data[0]['plan_count'] == 0) {
                $status = "尚未繳交";
            }
        } elseif ($user->type != 'county' && count($plan_data) > 0) {
            foreach ($plan_data as $one_data) {
                if (
                    date("Y-m-d") >= $report_public_dates['plan']['expire_soon_date'] &&
                    date("Y-m-d") <= $report_public_dates['plan']['expire_date'] &&
                    $one_data['plan_count'] == 0
                ) {
                    $status .= !empty($status) ? "\n" : '';
                    $status .= "即將逾期：" . $one_data['name'];
                } elseif (
                    $report_public_dates['plan']['expire_date'] <= date("Y-m-d") &&
                    $one_data['plan_count'] == 0
                ) {
                    $status .= !empty($status) ? "\n" : '';
                    $status .= "逾期：" . $one_data['name'];
                }
            }
        }
        $rows->add(['縣市執行計畫', $c_expire_date, $status]);

        $c_expire_date = (array_key_exists('presentation', $report_public_dates)) ? $report_public_dates['presentation']['c_expire_date'] : '';
        $status = "";
        if ($user->type == 'county' && count($presentation_data) > 0) {
            if ($presentation_data[0]['presentation_count'] > 0) {
                $status = "已繳交";
            } elseif (
                date("Y-m-d") >= $report_public_dates['presentation']['expire_soon_date'] &&
                date("Y-m-d") <= $report_public_dates['presentation']['expire_date'] &&
                $presentation_data[0]['presentation_count'] == 0
            ) {
                $status = "即將逾期";
            } elseif (
                $report_public_dates['presentation']['expire_date'] <= date("Y-m-d") &&
                $presentation_data[0]['presentation_count'] == 0
            ) {
                $status = "逾期";
            } elseif ($presentation_data[0]['presentation_count'] == 0) {
                $status = "尚未繳交";
            }
        } elseif ($user->type != 'county' && count($presentation_data) > 0) {
            foreach ($presentation_data as $one_data) {
                if (
                    date("Y-m-d") >= $report_public_dates['presentation']['expire_soon_date'] &&
                    date("Y-m-d") <= $report_public_dates['presentation']['expire_date'] &&
                    $one_data['presentation_count'] == 0
                ) {
                    $status .= !empty($status) ? "\n" : '';
                    $status .= "即將逾期：" . $one_data['name'];
                } elseif (
                    $report_public_dates['presentation']['expire_date'] <= date("Y-m-d") &&
                    $one_data['presentation_count'] == 0
                ) {
                    $status .= !empty($status) ? "\n" : '';
                    $status .= "逾期：" . $one_data['name'];
                }
            }
        }
        $rows->add(['期末簡報上傳', $c_expire_date, $status]);

        $c_expire_date = (array_key_exists('sample', $report_public_dates)) ? $report_public_dates['sample']['c_expire_date'] : '';
        $status = "";
        if ($user->type == 'county' && count($sample_report_data) > 0) {
            if ($sample_report_data[0]['topic_count'] == $sample_report_data[0]['report_count']) {
                $status = "已繳交";
            } elseif (
                date("Y-m-d") >= $report_public_dates['sample']['expire_soon_date'] &&
                date("Y-m-d") <= $report_public_dates['sample']['expire_date'] &&
                $sample_report_data[0]['report_count'] <= $sample_report_data[0]['topic_count']
            ) {
                $status = "即將逾期";
            } elseif (
                $report_public_dates['sample']['expire_date'] <= date("Y-m-d") &&
                $sample_report_data[0]['report_count'] <= $sample_report_data[0]['topic_count']
            ) {
                $status = "逾期";
            } elseif ($sample_report_data[0]['report_count'] == 0) {
                $status = "尚未繳交";
            }
        } elseif ($user->type != 'county' && count($sample_report_data) > 0) {
            foreach ($sample_report_data as $one_data) {
                if (
                    date("Y-m-d") >= $report_public_dates['sample']['expire_soon_date'] &&
                    date("Y-m-d") <= $report_public_dates['sample']['expire_date'] &&
                    $one_data['report_count'] <= $one_data['topic_count']
                ) {
                    $status .= !empty($status) ? "\n" : '';
                    $status .= "即將逾期：" . $one_data['name'];
                } elseif (
                    $report_public_dates['sample']['expire_date'] <= date("Y-m-d") &&
                    $one_data['report_count'] <= $one_data['topic_count']
                ) {
                    $status .= !empty($status) ? "\n" : '';
                    $status .= "逾期：" . $one_data['name'];
                }
            }
        }
        $rows->add(['優良範本資料', $c_expire_date, $status]);

        $c_expire_date = (array_key_exists('seasonal1', $report_public_dates)) ? $report_public_dates['seasonal1']['c_expire_date'] : '';
        $status = "";
        if ($user->type == 'county' && count($seasonal_report_2_data) > 0) {
            if ($seasonal_report_2_data[0]['topic_count'] == $seasonal_report_2_data[0]['report_count']) {
                $status = "已繳交";
            } elseif (
                date("Y-m-d") >= $report_public_dates['seasonal1']['expire_soon_date'] &&
                date("Y-m-d") <= $report_public_dates['seasonal1']['expire_date'] &&
                $seasonal_report_2_data[0]['report_count'] <= $seasonal_report_2_data[0]['topic_count']
            ) {
                $status = "即將逾期";
            } elseif (
                $report_public_dates['seasonal1']['expire_date'] <= date("Y-m-d") &&
                $seasonal_report_2_data[0]['report_count'] <= $seasonal_report_2_data[0]['topic_count']
            ) {
                $status = "逾期";
            } elseif ($seasonal_report_2_data[0]['report_count'] == 0) {
                $status = "尚未繳交";
            }
        } elseif ($user->type != 'county' && count($seasonal_report_2_data) > 0) {
            foreach ($seasonal_report_2_data as $one_data) {
                if (
                    date("Y-m-d") >= $report_public_dates['seasonal1']['expire_soon_date'] &&
                    date("Y-m-d") <= $report_public_dates['seasonal1']['expire_date'] &&
                    $one_data['report_count'] <= $one_data['topic_count']
                ) {
                    $status .= !empty($status) ? "\n" : '';
                    $status .= "即將逾期：" . $one_data['name'];
                } elseif (
                    $report_public_dates['seasonal1']['expire_date'] <= date("Y-m-d") &&
                    $one_data['report_count'] <= $one_data['topic_count']
                ) {
                    $status .= !empty($status) ? "\n" : '';
                    $status .= "逾期：" . $one_data['name'];
                }
            }
        }
        $rows->add(['縣市季進度管制表-期中', $c_expire_date, $status]);

        $c_expire_date = (array_key_exists('seasonal2', $report_public_dates)) ? $report_public_dates['seasonal2']['c_expire_date'] : '';
        $status = "";
        if ($user->type == 'county' && count($seasonal_report_3_data) > 0) {
            if ($seasonal_report_3_data[0]['topic_count'] == $seasonal_report_3_data[0]['report_count']) {
                $status = "已繳交";
            } elseif (
                date("Y-m-d") >= $report_public_dates['seasonal2']['expire_soon_date'] &&
                date("Y-m-d") <= $report_public_dates['seasonal2']['expire_date'] &&
                $seasonal_report_3_data[0]['report_count'] <= $seasonal_report_3_data[0]['topic_count']
            ) {
                $status = "即將逾期";
            } elseif (
                $report_public_dates['seasonal2']['expire_date'] <= date("Y-m-d") &&
                $seasonal_report_3_data[0]['report_count'] <= $seasonal_report_3_data[0]['topic_count']
            ) {
                $status = "逾期";
            } elseif ($seasonal_report_3_data[0]['report_count'] == 0) {
                $status = "尚未繳交";
            }
        } elseif ($user->type != 'county' && count($seasonal_report_3_data) > 0) {
            foreach ($seasonal_report_3_data as $one_data) {
                if (
                    date("Y-m-d") >= $report_public_dates['seasonal2']['expire_soon_date'] &&
                    date("Y-m-d") <= $report_public_dates['seasonal2']['expire_date'] &&
                    $one_data['report_count'] <= $one_data['topic_count']
                ) {
                    $status .= !empty($status) ? "\n" : '';
                    $status .= "即將逾期：" . $one_data['name'];
                } elseif (
                    $report_public_dates['seasonal2']['expire_date'] <= date("Y-m-d") &&
                    $one_data['report_count'] <= $one_data['topic_count']
                ) {
                    $status .= !empty($status) ? "\n" : '';
                    $status .= "逾期：" . $one_data['name'];
                }
            }
        }
        $rows->add(['縣市季進度管制表-期末', $c_expire_date, $status]);


        $rows->add(['績效評估自評表', '填報日期', '狀態']);
        foreach ($questionnaire_data as $one_questionnaire) {
            $c_expire_date = $one_questionnaire['c_date_from'] . "～" . $one_questionnaire['c_date_to'];
            $status = "";
            if (date("Y-m-d") < $one_questionnaire['date_from']) {
                $status = "尚未開始";
            } elseif (date("Y-m-d") > $one_questionnaire['date_to']) {
                $status = "已結束";
            } else {
                $status = "進行中";
            }
            $rows->add([$one_questionnaire['title'], $c_expire_date, $status]);
        }
        $rows->add(['']);


        $rows->add(['進階防災士逾期狀況', '', '']);
        $rows->add(['姓名', '進階防災士受訓中狀態到期日', '狀態']);
        foreach ($dp_advance_soon_expire_data as $one_student) {
            $c_expire_date = date("Y-m-d", strtotime($one_student['date_first_finish'] . " +{$DP_student_valid_year} year"));
            $rows->add([$one_student['name'], $c_expire_date, $one_student['expire_state']]);
        }
        foreach ($dp_advance_expire_data as $one_student) {
            $c_expire_date = date("Y-m-d", strtotime($one_student['date_first_finish'] . " +{$DP_student_valid_year} year"));
            $rows->add([$one_student['name'], $c_expire_date, $one_student['expire_state']]);
        }
        $rows->add(['']);


        $rows->add(['韌性社區逾期狀況', '', '']);
        $rows->add(['社區名稱', '星等', '狀態']);
        foreach ($dc_unit_soon_expire_data as $one_unit) {
            $c_expire_date = $one_unit['rank'] . "\n" . "(有效期限： {$one_unit['rank_expired_date']})";
            $rows->add([$one_unit['county']['name'] . $one_unit['name'], $c_expire_date, '即將逾期']);
        }
        foreach ($dc_unit_expire_data as $one_unit) {
            $c_expire_date = $one_unit['rank'] . "\n" . "(有效期限： {$one_unit['rank_expired_date']})";
            $rows->add([$one_unit['county']['name'] . $one_unit['name'], $c_expire_date, '逾期']);
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
                $event->sheet->calculateColumnWidths();
            },
        ];
    }

    /**
     * @return string
     */
    public function startCell(): string
    {
        return 'A1';
    }

    public function styles(Worksheet $sheet)
    {
        $shift_row1 = 16 + count($this->data['dp_advance_soon_expire_data']) + count($this->data['dp_advance_expire_data']);
        $shift_row2 = $shift_row1 + 1;
        return [
            'A'  => ['alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ]],
            'B:C'  => ['alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ]],
            'A1:C1'  => ['font' => [
                'size' => 16,
                'bold' => true,
            ]],
            'A3:C3'  => ['font' => [
                'size' => 15,
                'bold' => true,
            ]],
            'A13:C13'  => ['font' => [
                'size' => 15,
                'bold' => true,
            ]],
            'A14:C14'  => ['font' => [
                'size' => 15,
                'bold' => true,
            ]],
            "A{$shift_row1}:C{$shift_row1}"  => ['font' => [
                'size' => 15,
                'bold' => true,
            ]],
            "A{$shift_row2}:C{$shift_row2}"  => ['font' => [
                'size' => 15,
                'bold' => true,
            ]],
        ];
    }
}
