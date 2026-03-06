<?php

namespace App\Http\Controllers\Admin;

use App\FrontDownload;
use App\Http\Requests\StoreNewsRequest;
use App\Nfa\Repositories\FrontDownloadRepository;
use App\Nfa\Repositories\FrontDownloadRepositoryInterface;
use App\Nfa\Traits\FileUploadTrait;
use Flash;

class FrontDownloadController extends Controller
{
    use FileUploadTrait;

    /**
     * Display a listing of the resource.
     *
     * @param FrontDownloadRepositoryInterface $repo
     * @return \Illuminate\Http\Response
     */
    public function index(FrontDownloadRepositoryInterface $repo)
    {
        $data = $repo->getData();
        return view('admin.frontDownloads.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $editableCategory = FrontDownload::groupBy('category')->pluck('category')->toArray();
        return view('admin.frontDownloads.create', compact('editableCategory'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreNewsRequest $request
     * @param FrontDownloadRepositoryInterface $repo
     * @return \Illuminate\Http\Response
     */
    public function store(StoreNewsRequest $request, FrontDownloadRepositoryInterface $repo)
    {
        $data = $repo->postData($request->all());

        $this->handleFiles($request, $data);

        Flash::success(trans('app.createSuccess', ['type' => '防災士培訓 - 最新消息']));

        return redirect()->route('admin.frontDownload.index');
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
        $editableCategory = FrontDownload::groupBy('category')->pluck('category')->toArray();
        $news = FrontDownload::find($id);
        return view('admin.frontDownloads.edit', compact('news', 'editableCategory'));
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
        $news = FrontDownload::find($id);
        $news->update($request->all());

        $this->handleFiles($request, $news);
        if ($request->has('response_json') && $request->get('response_json')) {
            $guidanceRepo = new FrontDownloadRepository();
            $guidance = $guidanceRepo->getData()->items();
            return response()->json($guidance);
        } else {
            Flash::success(trans('app.updateSuccess', ['type' => '消息']));

            return redirect()->route('admin.frontDownload.index');
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
        $news = FrontDownload::find($id);
        $news->delete();

        Flash::success(trans('app.deleteSuccess', ['type' => '消息']));

        return redirect()->route('admin.frontDownload.index');
    }
}
