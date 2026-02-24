<?php

namespace App\Nfa\Traits;

use App\Imports\QuestionnaireImport;
use Excel;

trait ExcelTrait
{
    /*
     * 讀入excel資料
     */
    public static function excelToArray($file_uri, $range)
    {
        ini_set('memory_limit', '2448M');

        $import = new QuestionnaireImport($range);
        Excel::import($import, $file_uri);
        $data = cache()->get('import_data')->toArray();

        return $data;
    }
}
