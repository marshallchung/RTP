<?php

namespace App\Http\Controllers;

use App\CentralReport;
use App\DataTables\CentralReportDataTable;
use App\DataTables\Scopes\CentralReportCountyScope;
use App\DataTables\Scopes\CentralReportTopicScope;
use App\Topic;
use App\User;
use Illuminate\Http\Request;

class CentralReportController extends Controller
{
    public static $validTopic = [
        149 => '成果資料下載',
        150 => '第一期資料成果-5年中程',
        151 => '第一期成果書',
        152 => '第二期成果書',
        460 => '第三期成果書',
    ];

    public function index(Request $request, $topicId)
    {
        if (!in_array($topicId, array_keys(static::$validTopic))) {
            abort(404);
        }
        $title = static::$validTopic[$topicId];
        $countyOptions = User::where('type', 'county')->pluck('name', 'id')->toArray();
        $countyOptions = [null => '- 縣市 -'] + $countyOptions;
        $reportType = 'centralReport';
        $county = User::find(request('county'));
        return view('report.index', compact('title', 'topicId', 'county', 'countyOptions', 'reportType'));
    }

    public function search(Request $request, $topicId)
    {
        if (!in_array($topicId, array_keys(static::$validTopic))) {
            return [];
        }
        $search = $request->get('search', null);
        $county = $request->get('county', null);
        $centralReportQuery = CentralReport::getReportFiles($topicId, $search, $county);

        $centralReportList = $centralReportQuery->paginate(10, ['topics.*', 'files.name', 'files.path', "files.created_at AS fileDate"]);
        $pagination = $centralReportList->links()->render();
        $centralReportList = $centralReportList->items();
        $data = compact('centralReportList', 'pagination');
        return response()->json($data);
    }
}
