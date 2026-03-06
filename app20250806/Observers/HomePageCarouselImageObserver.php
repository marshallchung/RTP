<?php

namespace App\Observers;

use App\HomePageCarouselImage;

class HomePageCarouselImageObserver
{
    public function creating(HomePageCarouselImage $homePageCarouselImage)
    {
        $homePageCarouselImage->position = 1;
        HomePageCarouselImage::query()->increment('position');
    }

    public function deleting(HomePageCarouselImage $homePageCarouselImage)
    {
        $homePageCarouselImage->next()->decrement('position');
    }

    public function updating(HomePageCarouselImage $homePageCarouselImage)
    {
        if ($homePageCarouselImage->isDirty('position')) {
            $originPosition = $homePageCarouselImage->getOriginal('position');
            $newPosition = $homePageCarouselImage->position;
            if ($newPosition > $originPosition) {
                \DB::table('home_page_carousel_images')
                    ->whereBetween('position', [$originPosition + 1, $newPosition])
                    ->decrement('position');
            } elseif ($newPosition < $originPosition) {
                \DB::table('home_page_carousel_images')
                    ->whereBetween('position', [$newPosition, $originPosition - 1])
                    ->increment('position');
            }
        }
    }
}
