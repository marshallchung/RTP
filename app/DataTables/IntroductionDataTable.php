<?php

namespace App\DataTables;

use App\Introduction;
use App\User;
use Illuminate\Database\Query\Builder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;

class IntroductionDataTable extends DataTable
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
            ->editColumn('title', 'introduction.datatables.title')
            ->editColumn('user_id', function ($introduction) {
                return view('introduction.datatables.author', compact('introduction'));
            })
            ->filterColumn('user_id', function ($query, $keyword) {
                /* @var Builder|Introduction $query */
                $query->whereHas('author', function ($query) use ($keyword) {
                    /* @var Builder|User $query */
                    $query->where('name', 'like', "%{$keyword}%");
                });
            })
            ->escapeColumns([]);
    }

    /**
     * Get query source of dataTable.
     *
     * @param Introduction $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Introduction $model)
    {
        return $model->newQuery()
            ->where('active', true)
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
            ->minifiedAjax()
            ->parameters($this->getBuilderParameters())
            ->parameters([
                'order' => [[4, 'asc']],
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
            'id'         => ['visible' => false],
            'title'      => ['title' => '標題'],
            'user_id'    => ['visible' => false],
            'created_at' => ['visible' => false],
            'position'   => ['visible' => false],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'introduction_' . time();
    }
}
