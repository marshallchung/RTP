<?php

namespace App\Nfa\Repositories;

use App\DcCertification;
use Illuminate\Database\Eloquent\Builder;

class DcCertificationReviewRepository implements DcCertificationReviewRepositoryInterface
{
    protected function getDataQuery()
    {
        return DcCertification::with('files')
            ->latest('created_at');
    }

    public function getData()
    {
        return $this->getDataQuery()->paginate(20);
    }

    protected function getFilteredDataQuery()
    {
        $query = DcCertification::with('files');

        //過濾
        if ($searchCountyId = request('county_id')) {
            $query->whereHas('dcUnit', function ($query) use ($searchCountyId) {
                /** @var Builder $query */
                $query->whereHas('county', function ($query) use ($searchCountyId) {
                    /** @var Builder $query */
                    $query->where('id', $searchCountyId);
                });
            });
        }
        if ($searchKeyword = \request('dc_unit')) {
            $query->whereHas('dcUnit', function ($query) use ($searchKeyword) {
                /** @var Builder $query */
                $query->where('name', 'like', "%{$searchKeyword}%");
            });
        }
        if ($searchKeyword = \request('term')) {
            $query->where('term', $searchKeyword);
        }
        if ($searchKeyword = \request('review_result')) {
            if ($searchKeyword == 'none') {
                $query->where('review_result', null);
            } elseif ($searchKeyword == 'pass') {
                $query->where('review_result', true);
            } elseif ($searchKeyword == 'failed') {
                $query->where('review_result', false);
            }
        }

        return $query->latest('created_at');
    }

    public function getFilteredData()
    {
        return $this->getFilteredDataQuery()->paginate(20);
    }
}
