<?php

namespace App\Http\Controllers\Admin\Nfa;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNewsRequest;
use App\News;
use App\Nfa\Repositories\NewsRepository;
use App\Nfa\Repositories\NewsRepositoryInterface;
use App\Nfa\Repositories\UploadRepository;
use App\Nfa\Traits\FileUploadTrait;
use Auth;
use Flash;
use Illuminate\Support\Facades\DB;

class NewsController extends Controller
{
    use FileUploadTrait;

    public function index(NewsRepositoryInterface $newsRepo)
    {
        $news = $newsRepo->getNews();
        $routeName = (Auth::user()->hasPermission('create-news')) ? 'edit' : 'show';
        if (
            Auth::user()->hasPermission('admin-permissions') ||
            Auth::user()->hasPermission('NFA-permissions')
        ) {
            return view('admin.news.index', compact('news', 'routeName'));
        } else {
            $uploadRepo = new UploadRepository();
            $uploads = $uploadRepo->getDashboardUploads();
            return view('admin.dashboard.index', compact('news', 'uploads'));
        }
    }

    public function create()
    {
        $sorts = self::sorts();
        return view('admin.news.create', compact('sorts'));
    }

    private static function sorts()
    {
        return [
            ''     => '請選擇',
            '一般消息' => '一般消息',
            '其他消息' => '其他消息',
        ];
    }

    public function store(StoreNewsRequest $request, NewsRepositoryInterface $newsRepo)
    {
        $news = $newsRepo->postNews($request->all());

        $this->handleFiles($request, $news);

        Flash::success(trans('app.createSuccess', ['type' => '消息']));

        return redirect()->route('admin.news.index');
    }

    public function edit(News $news)
    {
        $sorts = self::sorts();
        return view('admin.news.edit', compact('news', 'sorts'));
    }

    public function show(News $news)
    {
        return view('admin.news.show', compact('news'));
    }

    public function update(StoreNewsRequest $request, News $news)
    {
        if ($request->has('fromPosition') && $request->has('toPosition')) {
            $fromId = $request->get('fromId');
            $fromPosition = $request->get('fromPosition');
            $toPosition = $request->get('toPosition');
            if ($fromPosition && $toPosition && $fromPosition !== $toPosition) {
                DB::select('call news_exange_position(?,?,?)', [$fromId, $fromPosition, $toPosition]);
            }
        } else {
            $news->update($request->all());

            $this->handleFiles($request, $news);
        }
        if ($request->has('response_json') && $request->get('response_json')) {
            $newsRepo = new NewsRepository();
            $news = $newsRepo->getNews()->items();
            return response()->json($news);
        } else {
            Flash::success(trans('app.updateSuccess', ['type' => '消息']));
            return redirect()->route('admin.news.index');
        }
    }

    /**
     * @param News $news
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(News $news)
    {
        $news->delete();

        Flash::success(trans('app.deleteSuccess', ['type' => '消息']));

        return redirect()->route('admin.news.index');
    }
}
