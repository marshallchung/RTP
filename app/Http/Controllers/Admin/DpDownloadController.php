<?php

namespace App\Http\Controllers\Admin;

use App\DpDownload;
use App\Http\Requests\StoreNewsRequest;
use App\Nfa\Repositories\DpDownloadRepository;
use App\Nfa\Repositories\DpDownloadRepositoryInterface;
use App\Nfa\Traits\FileUploadTrait;
use Flash;
use Illuminate\Support\Facades\DB;

class DpDownloadController extends Controller
{
    use FileUploadTrait;

    /**
     * Display a listing of the resource.
     *
     * @param DpDownloadRepositoryInterface $repo
     * @return \Illuminate\Http\Response
     */
    public function index(DpDownloadRepositoryInterface $repo)
    {
        $data = $repo->getData();
        return view('admin.dp-downloads.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $editableCategory = DpDownload::groupBy('category')->pluck('category')->toArray();
        return view('admin.dp-downloads.create', compact('editableCategory'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreNewsRequest $request
     * @param DpDownloadRepositoryInterface $newsRepo
     * @return \Illuminate\Http\Response
     */
    public function store(StoreNewsRequest $request, DpDownloadRepositoryInterface $newsRepo)
    {
        $news = $newsRepo->postData($request->all());

        $this->handleFiles($request, $news);

        Flash::success(trans('app.createSuccess', ['type' => '防災士培訓 - 相關資料']));

        return redirect()->route('admin.dpDownload.index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $editableCategory = DpDownload::groupBy('category')->pluck('category')->toArray();
        $news = DpDownload::find($id);
        return view('admin.dp-downloads.edit', compact('news', 'editableCategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreNewsRequest $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreNewsRequest $request, $id)
    {
        if ($request->has('fromPosition') && $request->has('toPosition')) {
            $fromId = $request->get('fromId');
            $fromPosition = $request->get('fromPosition');
            $toPosition = $request->get('toPosition');
            if ($fromPosition && $toPosition && $fromPosition !== $toPosition) {
                DB::select('call dp_downloads_exange_position(?,?,?)', [$fromId, $fromPosition, $toPosition]);
            }
        } else {
            $news = DpDownload::find($id);
            $news->update($request->all());

            $this->handleFiles($request, $news);
        }
        if ($request->has('response_json') && $request->get('response_json')) {
            $dpDownloadsRepo = new DpDownloadRepository();
            $dpDownloads = $dpDownloadsRepo->getData()->items();
            return response()->json($dpDownloads);
        } else {
            Flash::success(trans('app.updateSuccess', ['type' => '消息']));

            return redirect()->route('admin.dpDownload.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($id)
    {
        $news = DpDownload::find($id);
        $news->delete();

        Flash::success(trans('app.deleteSuccess', ['type' => '消息']));

        return redirect()->route('admin.dpDownload.index');
    }
}
