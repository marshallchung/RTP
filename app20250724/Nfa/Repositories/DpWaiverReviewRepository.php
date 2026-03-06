<?php

namespace App\Nfa\Repositories;

use App\DpWaiver;
use Illuminate\Database\Eloquent\Builder;

class DpWaiverReviewRepository implements DpWaiverReviewRepositoryInterface
{
    protected function getDataQuery()
    {
        return DpWaiver::with('dpScore.dpCourse', 'dpScore.dpStudent', 'dpScore.author', 'files')
            ->latest('created_at');
    }

    public function getData()
    {
        return $this->getDataQuery()->paginate(20);
    }

    protected function getFilteredDataQuery()
    {
        $query = DpWaiver::with('dpScore.dpCourse', 'dpScore.dpStudent', 'dpScore.author', 'files');

        //過濾
        if ($searchKeyword = \request('TID')) {
            $query->whereHas('dpScore.dpStudent', function ($query) use ($searchKeyword) {
                /** @var Builder $query */
                $query->where('TID', 'like', "%{$searchKeyword}%");
            });
        }
        if ($searchKeyword = \request('name')) {
            $query->whereHas('dpScore.dpStudent', function ($query) use ($searchKeyword) {
                /** @var Builder $query */
                $query->where('name', 'like', "%{$searchKeyword}%");
            });
        }
        if ($searchKeyword = \request('course_id')) {
            $query->whereHas('dpScore', function ($query) use ($searchKeyword) {
                /** @var Builder $query */
                $query->where('dp_course_id', $searchKeyword);
            });
        }
        if ($searchKeyword = \request('author_id')) {
            $query->whereHas('dpScore', function ($query) use ($searchKeyword) {
                /** @var Builder $query */
                $query->where('user_id', $searchKeyword);
            });
        }
        if ($searchKeyword = \request('review_result')) {
            $query->whereHas('dpScore', function ($query) use ($searchKeyword) {
                /** @var Builder $query */
                if ($searchKeyword == 'none') {
                    $query->where('review_result', null);
                } elseif ($searchKeyword == 'pass') {
                    $query->where('review_result', true);
                } elseif ($searchKeyword == 'failed') {
                    $query->where('review_result', false);
                }
            });
        }

        return $query->latest('created_at');
    }

    public function getFilteredData()
    {
        return $this->getFilteredDataQuery()->paginate(20);
    }
}
