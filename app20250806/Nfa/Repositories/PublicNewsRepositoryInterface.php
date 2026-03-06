<?php

namespace App\Nfa\Repositories;

interface PublicNewsRepositoryInterface
{
    public function getNews();

    public function postNews($data);
}
