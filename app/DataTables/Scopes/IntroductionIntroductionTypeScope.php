<?php

namespace App\DataTables\Scopes;

use App\Introduction;
use App\IntroductionType;
use Yajra\DataTables\Contracts\DataTableScope;

class IntroductionIntroductionTypeScope implements DataTableScope
{
    /**
     * @var IntroductionType
     */
    private $introductionType;

    /**
     * IntroductionIntroductionType constructor.
     * @param IntroductionType $introductionType
     */
    public function __construct(IntroductionType $introductionType)
    {
        $this->introductionType = $introductionType;
    }

    /**
     * Apply a query scope.
     *
     * @param \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder|Introduction $query
     * @return mixed
     */
    public function apply($query)
    {
        return $query->where('introduction_type_id', $this->introductionType->id);
    }
}
