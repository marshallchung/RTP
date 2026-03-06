<?php

namespace App\Nfa\Repositories;

use App\DcDownload;
use Auth;

class DcDownloadRepository implements DcDownloadRepositoryInterface
{
    public function getData()
    {
        return DcDownload::sorted()->with('author')->paginate(20);
    }

    public function getDashboardData()
    {
        return DcDownload::sorted()->with('author')->where('active', true)->simplePaginate(5);
    }

    public function postData($data)
    {
        $user = Auth::user();

        return $user->dcDownload()->create($data);
    }
}
