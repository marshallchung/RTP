<?php

namespace App\Nfa\Repositories;

use App\FrontDownload;
use Auth;

class FrontDownloadRepository implements FrontDownloadRepositoryInterface
{
    public function getData()
    {
        return FrontDownload::with('author')->latest('created_at')->paginate(20);
    }

    public function getDashboardData()
    {
        return FrontDownload::with('author')->where('active', true)->latest('created_at')->simplePaginate(5);
    }

    public function postData($data)
    {
        $user = Auth::user();

        return $user->frontDownload()->create($data);
    }
}
