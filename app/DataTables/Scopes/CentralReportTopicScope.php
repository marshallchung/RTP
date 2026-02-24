<?php

namespace App\DataTables\Scopes;

use App\CentralReport;
use App\Topic;
use Yajra\DataTables\Contracts\DataTableScope;

class CentralReportTopicScope implements DataTableScope
{
    /**
     * @var Topic
     */
    private $topic;

    /**
     * ReportTopicScope constructor.
     * @param Topic $topic
     */
    public function __construct(Topic $topic)
    {
        $this->topic = $topic;
    }

    /**
     * Apply a query scope.
     *
     * @param \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder|Report $query
     * @return mixed
     */
    public function apply($query)
    {
        return $query->where('topic_id', $this->topic->id);
    }
}
