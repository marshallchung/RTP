<?php

namespace App\DataTables\Scopes;

use App\Introduction;
use Yajra\DataTables\Contracts\DataTableScope;

class QASortScope implements DataTableScope
{
    /**
     * @var string
     */
    private $sort;

    /**
     * IntroductionIntroductionType constructor.
     * @param string $sort
     */
    public function __construct(string $sort)
    {
        $this->sort = $sort;
    }

    /**
     * Apply a query scope.
     *
     * @param \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder|Introduction $query
     * @return mixed
     */
    public function apply($query)
    {
        return $query->where('sort', $this->sort);
    }
}
