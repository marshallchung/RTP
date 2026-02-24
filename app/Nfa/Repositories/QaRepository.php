<?php

namespace App\Nfa\Repositories;

use App\Qa;
use Auth;

class QaRepository implements QaRepositoryInterface
{
    public function getQas($keyWord, $sort)
    {
        $q = Qa::with('author')
            ->where(function ($query) use ($keyWord) {
                /** @var \Illuminate\Database\Eloquent\Builder $query */
                $query->where('title', 'like', '%' . $keyWord . '%');
                $query->orWhere('content', 'like', '%' . $keyWord . '%');
            });
        if ($sort) {
            $q->where('sort', $sort);
        }
        return $q->latest('created_at')->paginate(10);
    }

    public function getDashboardQa()
    {
        return Qa::with('author')->where('active', true)->latest('created_at')->simplePaginate(5);
    }

    public function postQa($data)
    {
        $user = Auth::user();

        return $user->qas()->create($data);
    }
}
