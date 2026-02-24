<?php

namespace App\Http\Controllers\Admin;

use App\DcSchedule;
use App\Http\Requests\StoreNewsRequest;
use App\Nfa\Repositories\DcScheduleRepositoryInterface;
use App\Nfa\Traits\FileUploadTrait;
use Flash;

class DcScheduleController extends Controller
{
    use FileUploadTrait;

    /**
     * Display a listing of the resource.
     *
     * @param DcScheduleRepositoryInterface $schedulesRepo
     * @return \Illuminate\Http\Response
     */
    public function index(DcScheduleRepositoryInterface $schedulesRepo)
    {
        $schedules = $schedulesRepo->getSchedules();
        return view('admin.dc-schedules.index', compact('schedules'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.dc-schedules.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreNewsRequest $request
     * @param DcScheduleRepositoryInterface $schedulesRepo
     * @return \Illuminate\Http\Response
     */
    public function store(StoreNewsRequest $request, DcScheduleRepositoryInterface $schedulesRepo)
    {
        $schedules = $schedulesRepo->postSchedule($request->all());

        $this->handleFiles($request, $schedules);

        Flash::success(trans('app.createSuccess', ['type' => '推動韌性社區 - 執行情行管理']));

        return redirect()->route('admin.dc-schedules.index');
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
        $schedule = DcSchedule::with('files')->where('id', $id)->first();
        return view('admin.dc-schedules.edit', compact('schedule'));
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
        $schedule = DcSchedule::find($id);
        $schedule->update($request->all());

        $this->handleFiles($request, $schedule);

        Flash::success(trans('app.updateSuccess', ['type' => '消息']));

        return redirect()->route('admin.dc-schedules.index');
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
        $schedule = DcSchedule::find($id);
        $schedule->delete();

        Flash::success(trans('app.deleteSuccess', ['type' => '消息']));

        return redirect()->route('admin.dc-schedules.index');
    }
}
