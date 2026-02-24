<?php

namespace App\Http\Controllers;

use App\DpStudent;
use App\FrontDownload;
use App\Services\CountService;
use App\User;
use App\Video;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResourceController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function linkIndex()
    {
        $counties = User::where('type', 'county')->orderBy('sort_order', 'asc')->get();

        return view('resource.link', compact('counties'));
    }

    public function downloadIndex()
    {
        $page = (object) [
            'title'    => '相關資源與連結',
            'subtitle' => '友善災防連結',
            'search' => '/resource/search'
        ];
        $categoryOption = FrontDownload::with('author')->where('active', 1)->groupBy('category')->pluck('category')->toArray();

        return view('dp.panel', compact('page', 'categoryOption'));
    }

    public function downloadSearch(Request $request)
    {
        $data = FrontDownload::with(['author', 'files'])->where('active', 1);
        if ($category = $request->get('category')) {
            $data->where('category', '=', $category);
        }
        $data = $data->latest('created_at')->paginate(20);
        $data = $data->items();
        return response()->json($data);
    }

    public function videoIndex()
    {
        $page = (object) [
            'title'    => '影片及文宣',
            'subtitle' => '宣導影片及文宣專區',
        ];
        //類別列表
        $selectedSortId = request('sort', '');
        $sorts = Video::$sorts;
        $subSortOptions = [];

        return view('video.index', compact('sorts', 'selectedSortId', 'page', 'subSortOptions'));
    }

    public function videoSearch()
    {
        $data = Video::with('author', 'files', 'counter')->where('active', 1);
        $selectedSortId = request('sort');
        $selectedSubSort = request('sub_sort');
        if ($selectedSortId) {
            $data->where('sort', $selectedSortId);
            $selectedSort = Arr::get(Video::$sorts, $selectedSortId);
            $subSorts = Arr::get($selectedSort, 'sub_sorts', []);
            $subSortOptions = array_combine($subSorts, $subSorts) + ['other' => '其他'];
        } else {
            $subSortOptions = [];
        }
        if ($selectedSubSort) {
            if ($selectedSubSort != 'other') {
                $data->where('sub_sort', $selectedSubSort);
            } else {
                $data->whereNull('sub_sort');
            }
        }
        $data = $data->orderBy('position')
            ->get();
        if ($data) {
            foreach ($data as $key => $value) {
                $data[$key]['thumbnail_url'] = $value->thumbnail_url;
            }
        }
        //類別列表
        $data = compact('data', 'subSortOptions');
        return response()->json($data);
    }

    public function videoShow(Video $video, CountService $countService)
    {
        $countService->increase($video);

        return redirect($video->link_url);
    }

    public function mapIndex()
    {
        $page = (object) [
            'title'    => '相關資源與連結',
            'subtitle' => '全台防災士分布圖',
        ];
        /** @var Collection|DpStudent[] $allDpStudentData */
        $cityNames = User::selectRaw("users.username AS cityName,IFNULL(COUNT(DISTINCT dp_students.id),0) AS total,IFNULL(SUM(IF(dp_students.gender='男',1,0)),0) as male,IFNULL(SUM(IF(dp_students.gender='女',1,0)),0) as female")
            ->leftJoin('dp_students', function ($join) {
                $join->on('dp_students.county_id', '=', 'users.id')
                    ->where('dp_students.active', '=', true)
                    ->where('dp_students.advance', '=', false);
            })->where('users.type', 'county')
            ->groupBy('users.id')->get();

        $counts = collect($cityNames)->keyBy('cityName')->toArray();
         $allDpStudentData = DpStudent::selectRaw("IFNULL(COUNT(DISTINCT dp_students.id),0) AS total,IFNULL(SUM(IF(dp_students.gender='男',1,0)),0) as male,IFNULL(SUM(IF(dp_students.gender='女',1,0)),0) as female")
            ->where('dp_students.active', '=', true)
            ->where('dp_students.advance', '=', false)
            ->groupBy('dp_students.active')->first();
        $totalCount = [
            'total'  => $allDpStudentData->total,
	    //'total'  => 69437,
            'male'   => $allDpStudentData->male,
	    //'male'   => 40273,
            'female' => $allDpStudentData->female,
	    //'female' => 29164,
        ];
        $dpStudentStatistics = DpController::dpStudentStatistics();

        return view('map.index', compact('page', 'counts', 'totalCount', 'dpStudentStatistics'));
    }

    public function advanceMapIndex()
    {
        $page = (object) [
            'title'    => '相關資源與連結',
            'subtitle' => '全台進階防災士分布圖',
        ];
        /** @var Collection|DpStudent[] $allDpStudentData */
        $cityNames = User::selectRaw("users.username AS cityName,IFNULL(COUNT(DISTINCT dp_students.id),0) AS total,IFNULL(SUM(IF(dp_students.gender='男',1,0)),0) as male,IFNULL(SUM(IF(dp_students.gender='女',1,0)),0) as female")
            ->leftJoin('dp_students', function ($join) {
                $join->on('dp_students.county_id', '=', 'users.id')
                    ->where('dp_students.active', '=', true)
                    ->where('dp_students.advance', '=', true);
            })->where('users.type', 'county')
            ->groupBy('users.id')->get();

        $counts = collect($cityNames)->keyBy('cityName')->toArray();
        $allDpStudentData = DpStudent::selectRaw("IFNULL(COUNT(DISTINCT dp_students.id),0) AS total,IFNULL(SUM(IF(dp_students.gender='男',1,0)),0) as male,IFNULL(SUM(IF(dp_students.gender='女',1,0)),0) as female")
            ->where('dp_students.active', '=', true)
            ->where('dp_students.advance', '=', true)
            ->groupBy('dp_students.active')->first();
        $totalCount = [
            'total'  => $allDpStudentData->total,
            'male'   => $allDpStudentData->male,
            'female' => $allDpStudentData->female,
        ];
        $dpStudentStatistics = DpController::dpStudentStatistics();

        return view('map.advance', compact('page', 'counts', 'totalCount', 'dpStudentStatistics'));
    }
}
