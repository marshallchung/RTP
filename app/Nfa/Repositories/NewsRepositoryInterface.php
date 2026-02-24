<?php

namespace App\Nfa\Repositories;

interface NewsRepositoryInterface
{
    public function getNews();

    public function getDashboardNews();

    public function postNews($data);

    public function getDashboardNewsLeft();

    public function getDashboardNewsRight();
}
