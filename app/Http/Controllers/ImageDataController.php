<?php

namespace App\Http\Controllers;

use App\DataTables\ImageDataDataTable;
use App\DataTables\Scopes\ImageDataCountyScope;
use App\DataTables\Scopes\ImageDataDistrictScope;
use App\DataTables\Scopes\ImageDataTypeScope;
use App\File;
use App\ImageDatum;
use App\ImageDatumType;
use App\User;
use Illuminate\Http\Request;

class ImageDataController extends Controller
{
    public function index()
    {
        $counties = User::where('type', 'county')->get();

        return view('image-data.index', compact('counties'));
    }

    public function show(Request $request, $county)
    {
        /** @var User $countyUser */
        $countyUser = User::where('type', 'county')->where('name', $county)->first();
        if (!$countyUser) {
            abort(404);
        }
        $districtOptions = User::where('county_id', $countyUser->id)
            ->where('type', 'district')->pluck('name', 'id')->toArray();
        $districtOptions = [null => '- 地區 -'] + $districtOptions;
        $typeOptions = ImageDatumType::pluck('name', 'id')->toArray();
        $typeOptions = [null => '- 類型 -'] + $typeOptions;
        return view('image-data.show', compact('countyUser', 'districtOptions', 'typeOptions'));
    }

    public function search(Request $request, $county)
    {
        $pagination = "";
        $imageDataList = [];
        /** @var User $countyUser */
        $countyUser = User::where('type', 'county')->where('name', $county)->first();
        if (!$countyUser) {
            $data = compact('imageDataList', 'pagination');
            return $request->json($data);
        }
        $dataQuery = ImageDatum::getDataList($countyUser->id, $request->get('district'), $request->get('type'), $request->get('search'));
        $imageDataList = $dataQuery->paginate(10);
        $pagination = $imageDataList->links()->render();
        $imageDataList = $imageDataList->toArray()['data'];
        foreach ($imageDataList as $key => $one_report) {
            $image_list = explode(',', $one_report['image_list']);
            if ($files = File::whereIn('post_id', $image_list)->where('files.post_type', '=', ImageDatum::class)->get(['name', 'path', 'created_at'])) {
                $imageDataList[$key]['files'] = $files->toArray();
            }
        }
        return response()->json(compact('imageDataList', 'pagination'));
    }
}
