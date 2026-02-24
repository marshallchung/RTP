<?php

namespace App\DataTables;

use App\Qa;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class QADataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('created_at', function (Qa $qa) {
                return $qa->created_at->format('Y-m-d');
            })
            ->editColumn('title', function (Qa $qa) {
                return link_to_route('qa.show', $qa->title, $qa);
            })
            ->editColumn('counter_count', function (Qa $qa) {
                return $qa->counter_count;
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Qa $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Qa $model)
    {
        return $model->newQuery()
            ->with('author')
            ->where('publish', true);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('qa-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(2);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('id')->visible(false),
            Column::make('sort')->title('分類'),
            Column::make('created_at')->title('日期'),
            Column::make('author.name')->title('發布單位'),
            Column::make('title')->title('標題'),
            Column::make('counter_count')->title('點擊率')->searchable(false),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'QA_' . date('YmdHis');
    }
}
