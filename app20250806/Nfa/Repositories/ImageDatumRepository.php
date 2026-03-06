<?php

namespace App\Nfa\Repositories;

use App\ImageDatum;

class ImageDatumRepository implements ImageDatumRepositoryInterface
{
    public function getImageDatum($data)
    {
        $imageDatum = ImageDatum::updateOrCreate([
            'user_id'             => $data['user_id'],
            'image_datum_type_id' => $data['image_datum_type_id'],
        ], $data);

        return $imageDatum;
    }
}
