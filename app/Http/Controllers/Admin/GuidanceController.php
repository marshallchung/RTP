<?php

namespace App\Http\Controllers\Admin;

use App\Guidance;
use App\Http\Requests\StoreNewsRequest;
use App\Nfa\Repositories\GuidanceRepository;
use App\Nfa\Repositories\GuidanceRepositoryInterface;
use App\Nfa\Traits\FileUploadTrait;
use Auth;
use Flash;

class GuidanceController extends Controller
{
    use FileUploadTrait;

    /**
     * Display a listing of the resource.
     *
     * @param GuidanceRepositoryInterface $repo
     * @return \Illuminate\Http\Response
     */
    public function index(GuidanceRepositoryInterface $repo)
    {
        $data = $repo->getData();
        $routeName = (Auth::user()->hasPermission('create-guidance')) ? 'edit' : 'show';
        return view('admin.guidance.index', compact('data', 'routeName'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.guidance.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreNewsRequest $request
     * @param GuidanceRepositoryInterface $newsRepo
     * @return \Illuminate\Http\Response
     */
    public function store(StoreNewsRequest $request, GuidanceRepositoryInterface $newsRepo)
    {
        $news = $newsRepo->postData($request->all());

        $this->handleFiles($request, $news);

        Flash::success(trans('app.createSuccess', ['type' => '操作教學說明文件 - 最新消息']));

        return redirect()->route('admin.guidance.index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Guidance::find($id);
        return view('admin.guidance.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Guidance::find($id);
        return view('admin.guidance.edit', compact('data'));
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
        $news = Guidance::find($id);
        $news->update($request->all());

        $this->handleFiles($request, $news);
        if ($request->has('response_json') && $request->get('response_json')) {
            $guidanceRepo = new GuidanceRepository();
            $guidance = $guidanceRepo->getData()->items();
            return response()->json($guidance);
        } else {
            Flash::success(trans('app.updateSuccess', ['type' => '消息']));

            return redirect()->route('admin.guidance.index');
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
        $news = Guidance::find($id);
        $news->delete();

        Flash::success(trans('app.deleteSuccess', ['type' => '消息']));

        return redirect()->route('admin.guidance.index');
    }
}
