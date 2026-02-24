<?php

namespace App\Http\Controllers\Admin;

use App\DpResource;
use App\Nfa\Repositories\DpResourceRepositoryInterface;
use App\Nfa\Traits\FileUploadTrait;
use Auth;
use Flash;
use Illuminate\Http\Request;

class DpResourceController extends Controller
{
    use FileUploadTrait;

    /**
     * Display a listing of the resource.
     *
     * @param DpResourceRepositoryInterface $repo
     * @return \Illuminate\Http\Response
     */
    public function index(DpResourceRepositoryInterface $repo)
    {
        $data = $repo->getData();
        $routeName = (Auth::user()->hasPermission('DP-resources-manage')) ? 'edit' : 'show';
        return view('admin.dp-resources.index', compact('data', 'routeName'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //地區清單
        return view('admin.dp-resources.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param DpResourceRepositoryInterface $repo
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, DpResourceRepositoryInterface $repo)
    {
        $data = $repo->postData($request->all());

        $this->handleFiles($request, $data);

        Flash::success(trans('app.createSuccess', ['type' => '防災士培訓 - 新增受訓者與防災士']));

        return redirect()->route('admin.dp-resources.index');
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
        $data = DpResource::with('files')->where('id', $id)->first();
        return view('admin.dp-resources.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $model = DpResource::find($id);
        $data = $request->all();

        if (isset($data['units'])) {
            $data['units'] = implode(',', $data['units']);
        }

        $model->update($data);

        $this->handleFiles($request, $model);

        Flash::success(trans('app.updateSuccess', ['type' => '培訓資料']));

        return redirect()->route('admin.dp-resources.index');
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
        //dd($id);
        $data = DpResource::find($id);
        $data->delete();

        Flash::success(trans('app.deleteSuccess', ['type' => '培訓資料']));

        return redirect()->route('admin.dp-resources.index');
    }

    public function view(DpResourceRepositoryInterface $repo)
    {
        $data = $repo->getData();
        //dd($uploads);
        return view('admin.dp-resources.view', compact('data'));
    }
}
