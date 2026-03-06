<?php

namespace App\DataTables\Scopes;

use App\ImageDatum;
use App\User;
use Yajra\DataTables\Contracts\DataTableScope;

class ImageDataDistrictScope implements DataTableScope
{
    /**
     * @var User
     */
    private $districtUser;

    /**
     * ImageDataCountyScope constructor.
     * @param User $districtUser
     */
    public function __construct(User $districtUser)
    {
        $this->districtUser = $districtUser;
    }

    /**
     * Apply a query scope.
     *
     * @param \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder|ImageDatum $query
     * @return mixed
     */
    public function apply($query)
    {
        return $query->where('user_id', $this->districtUser->id);
    }
}
