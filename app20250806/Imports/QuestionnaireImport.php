<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class QuestionnaireImport implements ToCollection, WithMultipleSheets
{
    private $range;
    public $data;

    /**
     * QuestionnaireImport constructor.
     * @param $range
     */
    public function __construct(array $range = [])
    {
        $this->range = $range;
    }

    /**
     * @param Collection $rows
     * @throws \Exception
     */
    public function collection(Collection $rows)
    {
        $this->data = $rows;
        if (isset($this->range['column_from'])) {
            $columnFrom = Coordinate::columnIndexFromString($this->range['column_from']);
        } else {
            $columnFrom = 1;
        }
        if (isset($this->range['column_to'])) {
            $columnTo = Coordinate::columnIndexFromString($this->range['column_to']);
        } else {
            $columnTo = 1000;
        }
        $rows = $rows->map(function ($row) use ($columnTo, $columnFrom) {
            $rowArray = $row->toArray();
            $rowArray = array_slice($rowArray, $columnFrom - 1, $columnTo - $columnFrom + 1);

            return $rowArray;
        });
        cache()->put('import_data', $rows);
    }

    public function sheets(): array
    {
        return [
            0 => new static($this->range),
        ];
    }
}
