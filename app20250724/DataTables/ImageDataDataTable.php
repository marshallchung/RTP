<?php

namespace App\DataTables;

use App\File;
use App\ImageDatum;
use App\Report;
use App\User;
use Illuminate\Database\Query\Builder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;

class ImageDataDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);

        return $dataTable
            ->editColumn('title', 'image-data.datatables.title')
            ->editColumn('user_id', function ($imageDatum) {
                return view('image-data.datatables.user', compact('imageDatum'));
            })
            ->filterColumn('user_id', function ($query, $keyword) {
                /* @var Builder|Report $query */
                $query->whereHas('user', function ($query) use ($keyword) {
                    /* @var Builder|User $query */
                    $query->where('name', 'like', "%{$keyword}%");
                });
            })
            ->addColumn('files', function ($imageDatum) {
                return view('image-data.datatables.files', compact('imageDatum'));
            })
            ->filterColumn('files', function ($query, $keyword) {
                /* @var Builder|Report $query */
                $query->whereHas('files', function ($query) use ($keyword) {
                    /* @var Builder|File $query */
                    $query->where('name', 'like', "%{$keyword}%");
                });
            })
            ->editColumn('image_datum_type_id', function ($imageDatum) {
                return view('image-data.datatables.image_datum_type', compact('imageDatum'));
            })
            ->filterColumn('image_datum_type_id', function ($query, $keyword) {
                /* @var Builder|Report $query */
                $query->whereHas('imageDatumType', function ($query) use ($keyword) {
                    /* @var Builder|User $query */
                    $query->where('name', 'like', "%{$keyword}%");
                });
            })
            ->escapeColumns([]);
    }

    /**
     * Get query source of dataTable.
     *
     * @param ImageDatum $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ImageDatum $model)
    {
        return $model->newQuery()
            ->with('user', 'files', 'imageDatumType')
            ->select(array_keys($this->getColumns()));
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->addColumn([
                'data'      => 'files',
                'name'      => 'files',
                'title'     => '檔案',
                'orderable' => false,
            ])
            ->minifiedAjax()
            ->parameters($this->getBuilderParameters())
            ->parameters([
                'order' => [[1, 'desc']],
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'id'                  => ['visible' => false],
            'user_id'             => ['title' => '地區'],
            'image_datum_type_id' => ['title' => '類型'],
            'created_at'          => ['title' => '日期'],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'image_data_' . time();
    }
}
