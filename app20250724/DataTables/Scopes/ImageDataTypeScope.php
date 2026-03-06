<?php

namespace App\DataTables\Scopes;

use App\ImageDatum;
use App\ImageDatumType;
use Yajra\DataTables\Contracts\DataTableScope;

class ImageDataTypeScope implements DataTableScope
{
    /**
     * @var ImageDatumType
     */
    private $imageDatumType;

    /**
     * ImageDataCountyScope constructor.
     * @param ImageDatumType $imageDatumType
     */
    public function __construct(ImageDatumType $imageDatumType)
    {
        $this->imageDatumType = $imageDatumType;
    }

    /**
     * Apply a query scope.
     *
     * @param \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder|ImageDatum $query
     * @return mixed
     */
    public function apply($query)
    {
        return $query->where('image_datum_type_id', $this->imageDatumType->id);
    }
}
