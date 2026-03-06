<?php

namespace App\Nfa\Repositories;

use App\PublicNews;
use Auth;

class PublicNewsRepository implements PublicNewsRepositoryInterface
{
    public function getNews()
    {
        return PublicNews::sorted()->with('author')->latest('created_at')->paginate(20);
    }

    public function postNews($data)
    {
        $user = Auth::user();

        return $user->publicNews()->create($data);
    }
}
