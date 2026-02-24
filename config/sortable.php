<?php

return [
    'entities' => [
        // 'articles' => '\Article' for simple sorting (entityName => entityModel) or
        // 'articles' => ['entity' => '\Article', 'relation' => 'tags'] for many to many or many to many polymorphic relation sorting
        'news'                     => \App\News::class,
        'public-news'              => \App\PublicNews::class,
        'upload'                   => \App\Upload::class,
        'dc_downloads'             => \App\DcDownload::class,
        'dp_downloads'             => \App\DpDownload::class,
        'introduction'             => \App\Introduction::class,
        'address'                  => \App\Address::class,
        'dp-training-institution'  => \App\DpTrainingInstitution::class,
        'home-page-carousel-image' => \App\HomePageCarouselImage::class,
    ],
];
