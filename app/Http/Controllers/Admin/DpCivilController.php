<?php

namespace App\Http\Controllers\Admin;

use App\DpCivil;
use App\Http\Requests\StoreDpCivilRequest;
use App\Nfa\Repositories\DpCivilRepositoryInterface;
use Flash;
use Illuminate\Http\Request;

class DpCivilController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param DpCivilRepositoryInterface $repo
     * @return \Illuminate\Http\Response
     */
    public function index(DpCivilRepositoryInterface $repo)
    {
        $data = $repo->getFilteredData();
        return view('admin.dp-civil.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.dp-civil.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreDpCivilRequest $request
     * @param DpCivilRepositoryInterface $repo
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDpCivilRequest $request, DpCivilRepositoryInterface $repo)
    {
        $data = $repo->postData($request->all());

        Flash::success(trans('app.createSuccess', ['type' => '防災士培訓 - 新增社團法人臺灣防災教育訓練學會']));

        return redirect()->route('admin.dp-civil.index');
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
        $data = DpCivil::find($id);
        return view('admin.dp-civil.edit', compact('data'));
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
        $dpCivil = DpCivil::find($id);
        $data = $request->all();

        $dpCivil->update($data);

        Flash::success(trans('app.updateSuccess', ['type' => '社團法人臺灣防災教育訓練學會']));

        return redirect()->route('admin.dp-civil.index');
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
        $data = DpCivil::find($id);
        $data->delete();

        Flash::success(trans('app.deleteSuccess', ['type' => '社團法人臺灣防災教育訓練學會']));

        return redirect()->route('admin.dp-civil.index');
    }
}
