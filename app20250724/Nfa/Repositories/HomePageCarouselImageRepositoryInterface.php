<?php

namespace App\Nfa\Repositories;

interface HomePageCarouselImageRepositoryInterface
{
    public function get();

    public function store($data);

    public function homePageCarouselItems();
}
