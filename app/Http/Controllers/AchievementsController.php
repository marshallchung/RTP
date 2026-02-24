<?php

namespace App\Http\Controllers;

use App\DataTables\IntroductionDataTable;
use App\DataTables\Scopes\IntroductionIntroductionTypeScope;
use App\Introduction;
use App\IntroductionType;
use App\PublicNews;
use App\Services\CountService;
use DB;
use Illuminate\Http\Request;

class AchievementsController extends Controller
{
    public function intro()
    {
        $introductions = Introduction::where('introduction_type_id', 0)->where('active', 1)->get();

        return view('introduction.intro', compact('introductions'));
    }

    public function index(Request $request, IntroductionType $introductionType)
    {
        return view('introduction.index', compact('introductionType'));
    }

    public function search(Request $request, IntroductionType $introductionType)
    {
        $introductionQuery = Introduction::sorted()->where('active', true)->where('introduction_type_id', $introductionType->id)->with('author');
        if ($filteredSearch = $request->get('search', '')) {
            $introductionQuery->where('title', 'like', "%{$filteredSearch}%");
        }

        $introductionList = $introductionQuery->paginate(20, ['id', 'title']);
        $pagination = $introductionList->links()->render();
        $introductionList = $introductionList->items();
        $introductionType = $introductionType->id;
        $data = compact('introductionList', 'pagination', 'introductionType');
        return response()->json($data);
    }

    public function show(Introduction $introduction)
    {
        //檢查是否上線
        if (!$introduction->active) {
            abort(404);
        }

        return view('introduction.show', compact('introduction'));
    }

    public function publicNews()
    {
        $sorts = PublicNews::select('sort')->groupBy('sort')
            ->orderBy(DB::raw('CASE WHEN sort = \'活動訊息\' THEN \'10\'
              WHEN sort = \'防災士開班\' THEN \'20\'
              WHEN sort = \'重要成果統計\' THEN \'30\'
              WHEN sort = \'即時新聞\' THEN \'40\'
              WHEN sort = \'法規動態\' THEN \'50\'
              WHEN sort = \'防災士培訓\' THEN \'60\'
              WHEN sort = \'推動韌性社區\' THEN \'70\'
              WHEN sort = \'縣市年度評鑑成績\' THEN \'80\'
              WHEN sort = \'澄清專區\' THEN \'90\'
              WHEN sort = \'其他\' THEN \'99\'
              ELSE sort END '))->pluck('sort');

        $dataQuery = PublicNews::orderBy('position')->with('author', 'files', 'counter')->latest('created_at')
            ->where('active', true);

        if ($filteredSort = request('sort')) {
            $dataQuery->where('sort', $filteredSort);
        }

        $data = $dataQuery->paginate(20);

        foreach ($data as $idx => $item) {
            $data[$idx]['content'] = preg_replace("/(<img[^>]*alt=\".*?\")([^\"']*)(\/>)(*SKIP)(*F)|(<img[^\>]*)(\/>)/", "$4 alt=\"\"$5", $item->content);
            //$item->content = preg_replace("/(<img[^>]*alt=\".*?\")([^\"']*)(\/>)(*SKIP)(*F)|(<img[^\>]*)(\/>)/", "$4 alt=\"\"$5", $item->content);
        }

        return view('public-news.index', compact('sorts', 'data', 'filteredSort'));
    }

    public function publicNewsShow(PublicNews $publicNews, CountService $countService)
    {
        if (!$publicNews->active) {
            abort(404);
        }

        $countService->increase($publicNews);

        return view('public-news.show', compact('publicNews'));
    }
}
