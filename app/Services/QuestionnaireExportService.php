<?php

namespace App\Services;

use App\Exports\QuestionnaireExport;
use App\Questionnaire;
use App\User;
use Maatwebsite\Excel\Facades\Excel;
use Dompdf\Dompdf;

class QuestionnaireExportService
{
    /**
     * @param User $account
     * @param Questionnaire $questionnaire
     * @param bool $forceRegenerate
     * @return string
     */
    public function export(User $account, $questionnaire, bool $forceRegenerate = false)
    {
        $fullName = sprintf('%s_%s', $questionnaire['title'], $account->name);
        if ($account->county) {
            $fullName = sprintf('%s_%s - %s', $questionnaire['title'], $account->county->name, $account->name);
        }
        $zipFilePath = storage_path('app/exports' . DIRECTORY_SEPARATOR . $fullName . '.zip');
        if (\File::exists($zipFilePath) && !$forceRegenerate) {
            // 檔案已存在，且不要求重新生成，就直接回傳檔案路徑
            return $zipFilePath;
        }
        $disableAll = true;

        //TODO: 應該在 tmp 處理
        if (!file_exists(storage_path('app/exports'))) {
            mkdir(storage_path('app/exports'));
        }

        $tmpDir = storage_path('app/exports' . DIRECTORY_SEPARATOR . $fullName);
        if (is_dir($tmpDir)) {
            $objects = scandir($tmpDir);
            foreach ($objects as $object) {
                if ($object != '.' && $object != '..') {
                    //if (filetype($tmpDir . "/" . $object) == "dir") {
                    //    rrmdir($tmpDir . "/" . $object);
                    //} else {
                    unlink($tmpDir . '/' . $object);
                    //}
                }
            }
            reset($objects);
            rmdir($tmpDir);
        }
        mkdir($tmpDir);

        if (file_exists($tmpDir . '.zip')) {
            unlink($tmpDir . '.zip');
        }

        //$answers = json_decode($questionnaire->pivot->answers, true);
        //$comments = json_decode($questionnaire->pivot->comments, true);

        // PDF
        //FIXME: 暫時防止在 PHP 7.4 出現 Invalid characters passed for attempted conversion, these have been ignored
        error_reporting(E_ALL ^ E_DEPRECATED);
        /** @var \Barryvdh\DomPDF\PDF $pdf */
        $dompdf = new Dompdf();
        $html = view('admin.questionnaires.export', compact('questionnaire', 'disableAll', 'account'))->render();
        $dompdf->loadHtml($html);
        //$dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        file_put_contents($tmpDir . DIRECTORY_SEPARATOR . 'pdf.pdf', $dompdf->output());
        //$pdf = Pdf::loadview('admin.questionnaires.export', compact('questionnaire', 'disableAll', 'account', 'answers', 'comments'));
        //$pdf->save($tmpDir . DIRECTORY_SEPARATOR . 'pdf.pdf');

        // Excel
        Excel::store(
            new QuestionnaireExport($questionnaire),
            ('exports' . DIRECTORY_SEPARATOR . $fullName) . DIRECTORY_SEPARATOR . 'xlsx.xlsx'
        );

        // 打包
        foreach ($questionnaire['questions'] as $question) {
            foreach ($question['files'] as $file) {
                $filePath = storage_path('app' . DIRECTORY_SEPARATOR . $file['path']);
                if (file_exists($filePath)) {
                    copy($filePath, $tmpDir . DIRECTORY_SEPARATOR . $file['name']);
                }
            }
        }
        $files = glob($tmpDir . DIRECTORY_SEPARATOR . '*');
        $zip = \Zip::create($zipFilePath);
        $zip->add($files);
        $zip->close();

        return $zipFilePath;
    }

    public function batchExport(User $account, Questionnaire $questionnaire, bool $forceRegenerate = false)
    {
        $fullName = sprintf('批次匯出_%s_%s', $questionnaire->title, $account->name);
        $countyAndDistricts = User::where('id', $account->id)->orWhere('county_id', $account->id)->get();

        $zipFilePath = storage_path('app/batch-exports' . DIRECTORY_SEPARATOR . $fullName . '.zip');
        if (\File::exists($zipFilePath) && !$forceRegenerate) {
            // 檔案已存在，且不要求重新生成，就直接回傳檔案路徑
            return $zipFilePath;
        }

        if (!file_exists(storage_path('app/batch-exports'))) {
            mkdir(storage_path('app/batch-exports'));
        }

        $filePaths = [];
        foreach ($countyAndDistricts as $countyAndDistrict) {
            // 重新載入 Questionnaire，確保有 pivot
            $questionnaireOfCountyAndDistrict = $countyAndDistrict->questionnaires()->where('questionnaires.id', $questionnaire->id)->first();
            if (!$questionnaireOfCountyAndDistrict) {
                continue;
            }
            $filePaths[] = $this->export($countyAndDistrict, $questionnaireOfCountyAndDistrict);
        }
        //FIXME: 暫時防止在 PHP 7.4 出現 Unparenthesized `a ? b : c ? d : e` is deprecated. Use either `(a ? b : c) ? d : e` or `a ? b : (c ? d : e)`
        error_reporting(E_ALL ^ E_DEPRECATED);
        $zip = \Zip::create($zipFilePath);
        $zip->add($filePaths);
        $zip->close();

        return $zipFilePath;
    }
}
