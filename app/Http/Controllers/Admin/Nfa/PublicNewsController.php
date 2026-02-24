<?php

namespace App\Http\Controllers\Admin\Nfa;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePublicNewsRequest;
use App\Nfa\Repositories\PublicNewsRepository;
use App\Nfa\Repositories\PublicNewsRepositoryInterface;
use App\Nfa\Traits\FileUploadTrait;
use App\PublicNews;
use Auth;
use Flash;
use Illuminate\Support\Facades\DB;

class PublicNewsController extends Controller
{
    use FileUploadTrait;

    public function index(PublicNewsRepositoryInterface $newsRepo)
    {
        ini_set('memory_limit', '512M');
        $publicNews = $newsRepo->getNews();
        $routeName = (Auth::user()->hasPermission('create-public-news')) ? 'edit' : 'show';

        return view('admin.public-news.index', compact('publicNews', 'routeName'));
    }

    public function create()
    {
        $sorts = self::sorts();

        return view('admin.public-news.create', compact('sorts'));
    }

    private static function sorts()
    {
        return [
            ''         => '請選擇',
            '活動訊息'     => '活動訊息',
            '防災士開班'     => '防災士開班',
            '重要成果統計' => '重要成果統計',
            '即時新聞'    => '即時新聞',
            '法規動態'   => '法規動態',
            '防災士培訓'    => '防災士培訓',
            '推動韌性社區'    => '推動韌性社區',
            '縣市年度評鑑成績'   => '縣市年度評鑑成績',
            '澄清專區'   => '澄清專區',
            '其他'   => '其他',
        ];
    }

    public function store(StorePublicNewsRequest $request, PublicNewsRepositoryInterface $newsRepo)
    {
        $news = $newsRepo->postNews($request->all());

        $this->handleFiles($request, $news);

        Flash::success(trans('app.createSuccess', ['type' => '消息']));

        return redirect()->route('admin.public-news.index');
    }

    public function edit(PublicNews $publicNews)
    {
        $sorts = self::sorts();

        return view('admin.public-news.edit', compact('publicNews', 'sorts'));
    }

    public function show(PublicNews $publicNews)
    {
        return view('admin.public-news.show', compact('publicNews'));
    }

    public function update(StorePublicNewsRequest $request, PublicNews $publicNews)
    {
        if ($request->has('fromPosition') && $request->has('toPosition')) {
            $fromId = $request->get('fromId');
            $fromPosition = $request->get('fromPosition');
            $toPosition = $request->get('toPosition');
            if ($fromPosition && $toPosition && $fromPosition !== $toPosition) {
                DB::select('call public_news_exange_position(?,?,?)', [$fromId, $fromPosition, $toPosition]);
            }
        } else {
            $publicNews->update($request->all());

            $this->handleFiles($request, $publicNews);
        }
        if ($request->has('response_json') && $request->get('response_json')) {
            $uploadsRepo = new PublicNewsRepository();
            $uploads = $uploadsRepo->getNews()->items();
            return response()->json($uploads);
        } else {
            Flash::success(trans('app.updateSuccess', ['type' => '消息']));

            return redirect()->route('admin.public-news.index');
        }
    }

    /**
     * @param PublicNews $publicNews
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(PublicNews $publicNews)
    {
        $publicNews->delete();

        Flash::success(trans('app.deleteSuccess', ['type' => '消息']));

        return redirect()->route('admin.public-news.index');
    }
}
