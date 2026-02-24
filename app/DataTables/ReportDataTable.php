<?php

namespace App\DataTables;

use App\File;
use App\Report;
use App\User;
use Illuminate\Database\Query\Builder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;

class ReportDataTable extends DataTable
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
            ->editColumn('title', 'report.datatables.title')
            ->editColumn('user_id', function ($report) {
                return view('report.datatables.user', compact('report'));
            })
            ->filterColumn('user_id', function ($query, $keyword) {
                /* @var Builder|Report $query */
                $query->whereHas('user', function ($query) use ($keyword) {
                    /* @var Builder|User $query */
                    $query->where('name', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('user.county_name', function ($query, $keyword) {
                /* @var Builder|Report $query */
                $query->whereHas('user', function ($query) use ($keyword) {
                    /* @var Builder|User $query */
                    $query->whereHas('county', function ($query) use ($keyword) {
                        /* @var Builder|User $query */
                        $query->where('name', 'like', "%{$keyword}%");
                    });
                });
            })
            ->addColumn('files', function ($report) {
                return view('report.datatables.files', compact('report'));
            })
            ->filterColumn('files', function ($query, $keyword) {
                /* @var Builder|Report $query */
                $query->whereHas('files', function ($query) use ($keyword) {
                    /* @var Builder|File $query */
                    $query->where('opendata', 1)->where('name', 'like', "%{$keyword}%");
                });
            })
            ->escapeColumns([]);
    }

    /**
     * Get query source of dataTable.
     *
     * @param Report $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Report $model)
    {
        return $model->newQuery()
            ->with('user.county', 'files')
            ->whereHas('files', function ($query) {
                /* @var Builder|File $query */
                //僅限有公開檔案的
                $query->where('opendata', 1);
            })
            ->select();
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
            ->parameters($this->getBuilderParameters());
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'id'               => ['visible' => false],
            'user.county_name' => [
                'title'     => '縣市',
                'orderable' => false,
            ],
            'user_id'          => [
                'title'     => '地區',
                'orderable' => false,
            ],
            // 'created_at'       => [
            //     'title'     => '日期',
            //     'orderable' => false,
            // ],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'report_' . time();
    }
}
