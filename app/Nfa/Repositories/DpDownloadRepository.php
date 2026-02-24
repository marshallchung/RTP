<?php

namespace App\Nfa\Repositories;

use App\DpDownload;
use Auth;

class DpDownloadRepository implements DpDownloadRepositoryInterface
{
    public function getData()
    {
        return DpDownload::sorted()->with('author')->paginate(20);
    }

    public function getDashboardData()
    {
        return DpDownload::sorted()->with('author')->where('active', true)->simplePaginate(5);
    }

    public function postData($data)
    {
        $user = Auth::user();

        return $user->dpDownload()->create($data);
    }
}
