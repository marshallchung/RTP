<?php

namespace App\Nfa\Repositories;

use App\HomePageCarouselImage;

class HomePageCarouselImageRepository implements HomePageCarouselImageRepositoryInterface
{
    public function get()
    {
        return HomePageCarouselImage::sorted()->latest('created_at')->withCount('files')->paginate(20);
    }

    public function store($data)
    {
        return HomePageCarouselImage::create($data);
    }

    public function homePageCarouselItems()
    {
        $homePageCarouselImages = HomePageCarouselImage::sorted()->latest('created_at')->where('active', 1)->get();
        $items = [];
        foreach ($homePageCarouselImages as $homePageCarouselImage) {
            foreach ($homePageCarouselImage->files as $file) {
                $items[] = [
                    'title'     => $homePageCarouselImage->title,
                    'url'       => $homePageCarouselImage->url,
                    'image_url' => url($file->file_path),
                ];
            }
        }

        return $items;
    }
}
