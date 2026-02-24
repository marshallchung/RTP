<?php

namespace App\Http\Controllers\Admin;

use App\DcDownload;
use App\Http\Requests\StoreNewsRequest;
use App\Nfa\Repositories\DcDownloadRepository;
use App\Nfa\Repositories\DcDownloadRepositoryInterface;
use App\Nfa\Traits\FileUploadTrait;
use Flash;
use Illuminate\Support\Facades\DB;

class DcDownloadController extends Controller
{
    use FileUploadTrait;

    /**
     * Display a listing of the resource.
     *
     * @param DcDownloadRepositoryInterface $repo
     * @return \Illuminate\Http\Response
     */
    public function index(DcDownloadRepositoryInterface $repo)
    {
        $data = $repo->getData();
        return view('admin.dc-downloads.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $editableCategory = DcDownload::groupBy('category')->pluck('category')->toArray();
        return view('admin.dc-downloads.create', compact('editableCategory'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreNewsRequest $request
     * @param DcDownloadRepositoryInterface $newsRepo
     * @return \Illuminate\Http\Response
     */
    public function store(StoreNewsRequest $request, DcDownloadRepositoryInterface $newsRepo)
    {
        $news = $newsRepo->postData($request->all());

        $this->handleFiles($request, $news);

        Flash::success(trans('app.createSuccess', ['type' => '防災士培訓 - 最新消息']));

        return redirect()->route('admin.dcDownload.index');
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
        $editableCategory = DcDownload::groupBy('category')->pluck('category')->toArray();
        $news = DcDownload::find($id);
        return view('admin.dc-downloads.edit', compact('news', 'editableCategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
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
                DB::select('call dc_downloads_exange_position(?,?,?)', [$fromId, $fromPosition, $toPosition]);
            }
        } else {
            $dcDownload = DcDownload::find($id);
            $dcDownload->update($request->all());

            $this->handleFiles($request, $dcDownload);
        }
        if ($request->has('response_json') && $request->get('response_json')) {
            $dcDownloadRepo = new DcDownloadRepository();
            $dcDownload = $dcDownloadRepo->getData()->items();
            return response()->json($dcDownload);
        } else {
            Flash::success(trans('app.updateSuccess', ['type' => '消息']));

            return redirect()->route('admin.dcDownload.index');
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
        $news = DcDownload::find($id);
        $news->delete();

        Flash::success(trans('app.deleteSuccess', ['type' => '消息']));

        return redirect()->route('admin.dcDownload.index');
    }
}
