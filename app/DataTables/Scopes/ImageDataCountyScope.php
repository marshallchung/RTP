<?php

namespace App\DataTables\Scopes;

use App\ImageDatum;
use App\User;
use Yajra\DataTables\Contracts\DataTableScope;

class ImageDataCountyScope implements DataTableScope
{
    /**
     * @var User
     */
    private $countyUser;

    /**
     * ImageDataCountyScope constructor.
     * @param User $countyUser
     */
    public function __construct(User $countyUser)
    {
        $this->countyUser = $countyUser;
    }

    /**
     * Apply a query scope.
     *
     * @param \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder|ImageDatum $query
     * @return mixed
     */
    public function apply($query)
    {
        $countyUserIds = User::where(function ($query) {
            /* @var \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder|User $query */
            $query->where('type', 'county')->where('id', $this->countyUser->id);
        })->orWhere(function ($query) {
            /* @var \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder|User $query */
            $query->where('type', 'district')->where('county_id', $this->countyUser->id);
        })
            ->pluck('id')
            ->toArray();

        return $query->whereIn('user_id', $countyUserIds);
    }
}
