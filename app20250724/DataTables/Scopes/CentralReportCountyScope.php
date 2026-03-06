<?php

namespace App\DataTables\Scopes;

use App\CentralReport;
use App\User;
use Yajra\DataTables\Contracts\DataTableScope;

class CentralReportCountyScope implements DataTableScope
{
    /**
     * @var User
     */
    private $user;

    /**
     * ReportTopicScope constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Apply a query scope.
     *
     * @param \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder|Report $query
     * @return mixed
     */
    public function apply($query)
    {
        $countyIds = User::where('id', $this->user->id)->orWhere('county_id', $this->user->id)->pluck('id');

        return $query->whereIn('user_id', $countyIds);
    }
}
