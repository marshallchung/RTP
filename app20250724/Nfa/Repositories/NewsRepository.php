<?php

namespace App\Nfa\Repositories;

use App\News;
use App\NewsType;
use Auth;
use Illuminate\Database\Eloquent\Builder;

class NewsRepository implements NewsRepositoryInterface
{
    public function getNews()
    {
        return News::sorted()->with('author')->latest('created_at')->paginate(20);
    }

    public function getDashboardNews()
    {
        return News::sorted()->with('author', 'files')->where('active', true)->latest('created_at')->simplePaginate(5);
    }

    public function postNews($data)
    {
        $user = Auth::user();

        return $user->news()->create($data);
    }

    public function getDashboardNewsLeft()
    {
        return News::with('author', 'files')->where('active', true)->whereHas('newsType', function ($query) {
            /** @var Builder|NewsType $query */
            $query->where('name', '近期重點工作');
        })->latest('created_at')->simplePaginate(5, ['*'], 'page_l');
    }

    public function getDashboardNewsRight()
    {
        return News::with('author', 'files')->where('active', true)->whereDoesntHave('newsType', function ($query) {
            /** @var Builder|NewsType $query */
            $query->where('name', '近期重點工作');
        })->latest('created_at')->simplePaginate(5, ['*'], 'page_r');
    }
}
