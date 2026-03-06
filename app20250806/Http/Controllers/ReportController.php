<?php

namespace App\Http\Controllers;

use App\DataTables\ReportDataTable;
use App\DataTables\Scopes\ReportCountyScope;
use App\DataTables\Scopes\ReportTopicScope;
use App\File;
use App\Nfa\Repositories\ReportRepository;
use App\Nfa\Repositories\UserRepository;
use App\Report;
use App\ReportPublicDate;
use App\StaticPage;
use App\Topic;
use App\User;
use Illuminate\Http\Request;
use Dompdf\Dompdf;

class ReportController extends Controller
{
    public static $validTopic = [
        9   => '鄉（鎮、市、區）地區災害防救計畫',
        10  => '各類災害標準作業程序',
        19  => '防救災教育訓練教材',
        11  => '各鄉（鎮、市、區）災害應變中心建置',
        15  => '兵棋推演資料',
        149 => '成果資料下載',
        150 => '第一期資料成果-5年中程',
        151 => '第一期成果書',
        152 => '第二期成果書',
    ];

    public function statistic()
    {
        $countyOptions = User::where('type', 'county')->get(['id', 'name'])->keyBy('id')->toArray();
        $year = request('year', date("Y"));
        if ($report_public_dates = ReportPublicDate::where('date_type', '=', 'reports')->where('year', '=', $year)->first()) {
            $public_date = substr($report_public_dates->public_date, 0, 10);
        } else {
            $public_date = "{$year}-12-31";
        }
        $county = request('county', array_keys($countyOptions)[0]);
        if (date("Y-m-d") >= $public_date) {
            $chinese_year = intval($year) - 1911;
            $data = StaticPage::where('id', '=', "report-{$chinese_year}-{$county}")->first();
        } else {
            $data = null;
        }
        return view('report.statistic', compact('year', 'county', 'countyOptions', 'data'));
    }

    public function export($year, $county)
    {
        if ($report_public_dates = ReportPublicDate::where('date_type', '=', 'reports')->where('year', '=', $year)->first()) {
            $public_date = substr($report_public_dates->public_date, 0, 10);
        } else {
            $public_date = "{$year}-12-31";
        }
        if (date("Y-m-d") >= $public_date) {
            $user = User::where('id', $county)->first();
            $chinese_year = intval($year) - 1911;
            $data = StaticPage::where('id', '=', "report-{$chinese_year}-{$county}")->first();

            $dompdf = new Dompdf();
            $html = view('admin.report.export', compact('data', 'user', 'chinese_year'))->render();
            $dompdf->loadHtml($html);
            $dompdf->render();
            return $dompdf->stream($user->name . $chinese_year . '年成果統計');
        } else {
            return redirect('', 403);
        }
    }

    private function reportHasFiles($report)
    {
        foreach ($report as $category) {
            foreach ($category->items as $topic) {
                if (isset($topic->reports) && isset($topic->reports->files)) {
                    return true;
                }
            }
        }

        return false;
    }

    public function index(ReportDataTable $dataTable, Topic $topic)
    {
        if (!in_array($topic->id, array_keys(static::$validTopic))) {
            abort(404);
        }
        $title = static::$validTopic[$topic->id];
        $countyOptions = User::where('type', 'county')->pluck('name', 'id')->toArray();
        $countyOptions = [null => '- 縣市 -'] + $countyOptions;
        $reportType = 'report';
        $county = User::find(request('county'));
        return view('report.index', compact('title', 'topic', 'county', 'countyOptions', 'reportType'));
    }

    public function search(Request $request, $topicId)
    {
        if (!in_array($topicId, array_keys(static::$validTopic))) {
            return [];
        }
        $search = $request->get('search', null);
        $county = $request->get('county', null);
        $centralReportQuery = Report::getReportFiles($topicId, $search, $county);

        $centralReportList = $centralReportQuery->paginate(10);
        $pagination = $centralReportList->links()->render();
        $centralReportList = $centralReportList->toArray()['data'];
        foreach ($centralReportList as $key => $one_report) {
            $report_list = explode(',', $one_report['report_list']);
            if ($files = File::whereIn('post_id', $report_list)->where('files.opendata', '=', 1)->where('files.post_type', '=', Report::class)->get(['name', 'path', 'created_at'])) {
                $centralReportList[$key]['files'] = $files->toArray();
            }
        }
        $data = compact('centralReportList', 'pagination');
        return response()->json($data);
    }
}
