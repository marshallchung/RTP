<?php

namespace App\Nfa\Repositories;

use App\DpTrainingInstitution;

class DpTrainingInstitutionRepository implements DpTrainingInstitutionRepositoryInterface
{
    protected function getDataQuery()
    {
        $q = DpTrainingInstitution::with('county');

        return $q->sorted();
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
        $q = DpTrainingInstitution::with('county');

        $filterableFields = ['filter_name', 'filter_phone', 'filter_address', 'charging_standard'];
        foreach ($filterableFields as $filterableField) {
            $searchKeyword = request()->get($filterableField);
            $filterableField = str_replace('filter_', '', $filterableField);
            if ($searchKeyword) {
                $q->where($filterableField, 'like', "%{$searchKeyword}%");
            }
        }

        if ($countyId = request()->get('filter_county_id')) {
            $q->where('county_id', $countyId);
        }

        return $q->sorted();
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
        return DpTrainingInstitution::with('author')->where('active', true)->latest('created_at')->simplePaginate(5);
    }

    public function postData($data)
    {
        return DpTrainingInstitution::create($data);
    }
}
