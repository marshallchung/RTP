<?php

namespace App\Http\Controllers\Admin;

use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $activityLogs = Activity::query()->latest()->paginate(100);

        return view('admin.activity-log.index', compact('activityLogs'));
    }

    public function show(Activity $activity)
    {
        return view('admin.activity-log.show', compact('activity'));
    }
}
