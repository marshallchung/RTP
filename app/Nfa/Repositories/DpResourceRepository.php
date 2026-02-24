<?php

namespace App\Nfa\Repositories;

use App\DpResource;
use Auth;

class DpResourceRepository implements DpResourceRepositoryInterface
{
    public function getData()
    {
        return DpResource::with('author', 'files')->latest('created_at')->paginate(20);
    }

    public function postData($data)
    {
        $user = Auth::user();

        return $user->DpResource()->create($data);
    }
}
