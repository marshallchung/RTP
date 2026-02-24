<?php

namespace App\Exports;

use App\Questionnaire;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Sheet;

class QuestionnaireExport implements FromCollection, WithEvents, WithTitle
{
    use RegistersEventListeners;

    /**
     * @var Questionnaire
     */
    private $questionnaire;
    /**
     * @var array
     */
    private $answers;
    /**
     * @var array
     */
    private $comments;

    /**
     * QuestionnaireExport constructor.
     * @param Questionnaire $questionnaire
     * @param array|null $answers
     * @param array|null $comments
     */
    public function __construct($questionnaire)
    {
        $this->questionnaire = $questionnaire;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $rows = collect();
        $head = [
            '題目', '答案', '分數', '審查意見',
        ];
        $rows->add($head);

        $scoreSum = 0;
        foreach ($this->questionnaire['questions'] as $question) {
            $answer = "";
            if ($question['type'] == 'radio' || $question['type'] == 'checkbox') {
                foreach ($question['options'] as $opt_name => $opt_data) {
                    if ($opt_data['selected']) {
                        if ($answer != '') {
                            $answer .= "，";
                        }
                        $answer .= $opt_name;
                    }
                }
            } elseif ($question['type'] == 'text') {
                $answer .= $question['answer'];
            }
            $rowData = [
                $question['content'],
                $answer,
                $question['basic_score'] + $question['advance_score'],
                $question['comment_content'] ?? '',
            ];
            $rows->add($rowData);
        }

        $rows->add(['總分', '', $this->questionnaire['basic_total_score'] + $this->questionnaire['advance_total_score']]);
        $rows->add(['加權值', '', ($this->questionnaire['basic_total_score'] * $this->questionnaire['basic_weight']) +
            floatval(number_format(($this->questionnaire['advance_total_score'] * $this->questionnaire['advanced_weight']), 2, '.', ''))]);

        return $rows;
    }

    /**
     * @param AfterSheet $event
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public static function afterSheet(AfterSheet $event)
    {
        /** @var Sheet $sheet */
        $sheet = $event->sheet;
        $sheet->getDelegate()->getParent()->getDefaultStyle()->getFont()->setSize(14);
        $sheet->getDelegate()->getStyle('A1:D' . $sheet->getDelegate()->getHighestRow())
            ->applyFromArray([
                'borders' => [
                    'outline' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ]);
    }

    /**
     * @inheritDoc
     */
    public function title(): string
    {
        return date('Ymd') . '產出';
    }


    protected static function countScore($question, $rawAnswer)
    {
        if ($rawAnswer === '') {
            return 0;
        }

        $rawAnswer = self::fixOptStr($rawAnswer);

        switch ($question->type) {
            case 'radio':
                $question->options = self::fixOptStr($question->options);
                $exploded = explode(']', $question->options);
                $options = [];
                foreach ($exploded as $key => $item) {
                    if (str_replace(' ', '', $item) === '') {
                        continue;
                    }

                    $info = explode('[', $item);
                    $key = self::fixOptStr($info[0]);
                    $options[$key] = $info[1];
                }
                $score = 0;

                if (!isset($options[$rawAnswer])) {
                    break;
                }

                $score = $options[$rawAnswer];
                break;

            case 'checkbox':
                $exploded = explode(']', $question->options);
                $options = [];
                foreach ($exploded as $key => $item) {
                    if (str_replace(' ', '', $item) === '') {
                        continue;
                    }

                    $info = explode('[', $item);
                    $idx = self::fixOptStr($info[0]);
                    $options[$idx] = $info[1];
                }
                $score = 0;

                foreach ($rawAnswer as $key => $answer) {
                    $answer = self::fixOptStr($answer);
                    if (!isset($options[$answer])) {
                        continue;
                    }

                    $score += $options[$answer];
                }
                break;

            default:
                $score = 0;
        }

        // 分數上限
        if ($question->score_limit != 0 && $score > $question->score_limit) {
            $score = $question->score_limit;
        }

        // 加成 & 權重
        $score *= $question->gain;
        if ($question->extra_gain > 0) {
            $score *= $question->extra_gain;
        }

        return $score;
    }

    public static function fixOptStr($str)
    {
        return str_replace(["\n", "\r"], '', $str);
    }
}
