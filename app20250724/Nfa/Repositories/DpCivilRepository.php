<?php

namespace App\Nfa\Repositories;

use App\DpCivil;

class DpCivilRepository implements DpCivilRepositoryInterface
{
    protected function getDataQuery()
    {
        /** @var DpCivil $q */
        $q = DpCivil::query();

        return $q->latest('created_at');
    }

    public function getData()
    {
        return $this->getDataQuery()->paginate(20);
    }

    public function getAllData()
    {
        return $this->getDataQuery()->get();
    }

    protected function getFilteredDataQuery()
    {
        $q = DpCivil::query();

        $filterableFields = ['name', 'phone', 'address'];
        foreach ($filterableFields as $filterableField) {
            $searchKeyword = request()->get($filterableField);
            if ($searchKeyword) {
                $q->where($filterableField, 'like', "%{$searchKeyword}%");
            }
        }

        if ($countyId = request()->get('county_id')) {
            $q->where('county_id', $countyId);
        }

        return $q->latest('created_at');
    }

    public function getFilteredData()
    {
        return $this->getFilteredDataQuery()->paginate(20);
    }

    public function getAllFilteredData()
    {
        return $this->getFilteredDataQuery()->get();
    }

    public function getDashboardData()
    {
        return DpCivil::with('author')->where('active', true)->latest('created_at')->simplePaginate(5);
    }

    public function postData($data)
    {
        return DpCivil::create($data);
    }
}
