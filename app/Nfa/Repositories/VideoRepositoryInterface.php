<?php

namespace App\Nfa\Repositories;

use App\Video;

interface VideoRepositoryInterface
{
    public function getVideo();

    public function getDashboardVideo();

    public function processData($data);

    public function create($data);

    public function update(Video $video, $data);

    public function getDashboardVideoLeft();

    public function getDashboardVideoRight();
}
