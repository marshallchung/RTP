<?php

namespace App\Http\Controllers\Admin\Nfa;

use App\Exports\DailyReportExport;
use App\Http\Controllers\Admin\DpAdvancedStudentController;
use App\Http\Controllers\Controller;
use App\Nfa\Repositories\DcUnitRepository;
use App\Nfa\Repositories\DpAdvancedStudentRepository;
use App\Nfa\Repositories\NewsRepositoryInterface;
use App\Nfa\Repositories\UploadRepositoryInterface;
use App\Plan;
use App\Questionnaire;
use App\ReportPublicDate;
use App\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Excel;


class DashboardController extends Controller
{
    /**
     * Show the dashboard
     *
     * @param NewsRepositoryInterface $newsRepo
     * @param UploadRepositoryInterface $uploadRepo
     * @return Response
     */
    public function index(NewsRepositoryInterface $newsRepo, UploadRepositoryInterface $uploadRepo)
    {
        $user = auth()->user();
        if ($user->hasPermission('admin-permissions') || $user->hasPermission('NFA-permissions') || $user->hasPermission('County-permissions')) {
            extract(self::reportData($user));
            return view('admin.dashboard.report', compact('report_public_dates', 'report_data', 'plan_data', 'presentation_data', 'sample_report_data', 'seasonal_report_2_data', 'seasonal_report_3_data', 'questionnaire_data', 'dc_unit_expire_data', 'dc_unit_soon_expire_data', 'dp_advance_expire_data', 'dp_advance_soon_expire_data', 'user', 'DP_student_valid_year'));
        } else {
            $news = $newsRepo->getDashboardNews();
            $uploads = $uploadRepo->getDashboardUploads();
            //dd($uploads);
            return view('admin.dashboard.index', compact('news', 'uploads'));
        }
    }

    static function reportData($user)
    {
        $year = date("Y");
        $public_dates_sql = "CONCAT(YEAR(public_date)-1911,'/',MONTH(public_date),'/',DAY(public_date)) AS c_public_date,DATE(public_date) AS public_date,
            CONCAT(YEAR(expire_soon_date)-1911,'/',MONTH(expire_soon_date),'/',DAY(expire_soon_date)) AS c_expire_soon_date,DATE(expire_soon_date) AS expire_soon_date,
            CONCAT(YEAR(expire_date)-1911,'/',MONTH(expire_date),'/',DAY(expire_date)) AS c_expire_date,DATE(expire_date) AS expire_date,date_type";
        $report_public_dates = ReportPublicDate::selectRaw($public_dates_sql)->where('year', $year)->get();
        if ($report_public_dates) {
            $report_public_dates = collect($report_public_dates)->keyBy('date_type')->toArray();
        }
        $report_sql = "IFNULL((SELECT COUNT(DISTINCT a.id) FROM topics AS a
            JOIN root_topics AS b ON b.id=a.category AND b.work_type='reports' AND b.year='{$year}'
            GROUP BY a.work_type),0) AS topic_count,
            IFNULL((SELECT COUNT(rp.topic_id) FROM reports AS rp
            JOIN topics AS tp ON tp.id=rp.topic_id AND tp.work_type='reports'
            JOIN root_topics AS rt ON rt.id=tp.category AND rt.work_type='reports' AND rt.year='{$year}'
            WHERE rp.user_id=users.id GROUP BY rp.user_id),0) AS report_count,users.name";
        $report_data = User::selectRaw($report_sql)->where('users.type', 'county');
        if ($user->type == 'county') {
            $report_data->where('users.id', $user->id);
        }
        if ($report_data = $report_data->orderBy('users.sort_order', 'ASC')->get()) {
            $report_data = $report_data->toArray();
        }

        $plan_sql = "users.name,COUNT(DISTINCT plans.id) AS plan_count";
        $plan_data = User::selectRaw($plan_sql)
            ->leftJoin('plans', function ($join) use ($year) {
                $join->on('plans.user_id', '=', 'users.id')
                    ->where('plans.year', '=', $year);
            })
            ->where('users.type', '=', 'county');
        if ($user->type == 'county') {
            $plan_data->where('users.id', $user->id);
        }
        if ($plan_data = $plan_data->groupBy('users.id')->orderBy('users.sort_order', 'ASC')->get()) {
            $plan_data = $plan_data->toArray();
        }

        $presentation_sql = "users.name,COUNT(DISTINCT files.id) AS presentation_count";
        $presentation_data = User::selectRaw($presentation_sql)
            ->leftJoin('presentations', function ($join) use ($year) {
                $join->on('presentations.user_id', '=', 'users.id')
                    ->where('presentations.year', '=', $year);
            })
            ->leftJoin('files', function ($join) use ($year) {
                $join->on('files.post_id', '=', 'presentations.id')
                    ->where('files.post_type', '=', "App\\Presentation");
            })
            ->where('users.type', '=', 'county');
        if ($user->type == 'county') {
            $presentation_data->where('users.id', $user->id);
        }
        if ($presentation_data = $presentation_data->groupBy('users.id')->orderBy('users.sort_order', 'ASC')->get()) {
            $presentation_data = $presentation_data->toArray();
        }

        $sample_report_sql = "IFNULL((SELECT COUNT(DISTINCT a.id) FROM topics AS a
            JOIN root_topics AS b ON b.id=a.category AND b.work_type='reports' AND b.year='{$year}'
            GROUP BY a.work_type),0) AS topic_count,
            IFNULL((SELECT COUNT(rp.topic_id) FROM sample_reports AS rp
            JOIN topics AS tp ON tp.id=rp.topic_id AND tp.work_type='reports'
            JOIN root_topics AS rt ON rt.id=tp.category AND rt.work_type='reports' AND rt.year='{$year}'
            WHERE rp.user_id=users.id GROUP BY rp.user_id),0) AS report_count,users.name";
        $sample_report_data = User::selectRaw($sample_report_sql)->where('users.type', 'county');
        if ($user->type == 'county') {
            $sample_report_data->where('users.id', $user->id);
        }
        if ($sample_report_data = $sample_report_data->orderBy('users.sort_order', 'ASC')->get()) {
            $sample_report_data = $sample_report_data->toArray();
        }

        $seasonal_report_2_sql = "IFNULL((SELECT COUNT(DISTINCT a.id) FROM topics AS a
            JOIN root_topics AS b ON b.id=a.category AND b.work_type='seasonalReports' AND b.year='{$year}'
            GROUP BY a.work_type),0) AS topic_count,
            IFNULL((SELECT COUNT(rp.topic_id) FROM seasonal_reports AS rp
            JOIN files ON files.post_id=rp.id AND files.post_type LIKE \"%SeasonalReport\" AND files.memo='{$year}_2'
            WHERE rp.user_id=users.id GROUP BY rp.user_id),0) AS report_count,users.name";
        $seasonal_report_2_data = User::selectRaw($seasonal_report_2_sql)->where('users.type', '=', 'county');
        if ($user->type == 'county') {
            $seasonal_report_2_data->where('users.id', $user->id);
        }
        if ($seasonal_report_2_data = $seasonal_report_2_data->get()) {
            $seasonal_report_2_data = $seasonal_report_2_data->toArray();
        }

        $seasonal_report_3_sql = "IFNULL((SELECT COUNT(DISTINCT a.id) FROM topics AS a
            JOIN root_topics AS b ON b.id=a.category AND b.work_type='seasonalReports' AND b.year='{$year}'
            GROUP BY a.work_type),0) AS topic_count,
            IFNULL((SELECT COUNT(rp.topic_id) FROM seasonal_reports AS rp
            JOIN files ON files.post_id=rp.id AND files.post_type LIKE \"%SeasonalReport\" AND files.memo='{$year}_3'
            WHERE rp.user_id=users.id GROUP BY rp.user_id),0) AS report_count,users.name";
        $seasonal_report_3_data = User::selectRaw($seasonal_report_3_sql)->where('users.type', '=', 'county');
        if ($user->type == 'county') {
            $seasonal_report_3_data->where('users.id', $user->id);
        }
        if ($seasonal_report_3_data = $seasonal_report_3_data->get()) {
            $seasonal_report_3_data = $seasonal_report_3_data->toArray();
        }

        $questionnaire_sql = "questionnaires.title,
            CONCAT(YEAR(questionnaires.date_from)-1911,'/',MONTH(questionnaires.date_from),'/',DAY(questionnaires.date_from)) AS c_date_from,
            DATE(questionnaires.date_from) AS date_from,
            CONCAT(YEAR(questionnaires.expire_soon_date)-1911,'/',MONTH(questionnaires.expire_soon_date),'/',DAY(questionnaires.expire_soon_date)) AS c_expire_soon_date,
            DATE(questionnaires.expire_soon_date) AS expire_soon_date,
            CONCAT(YEAR(questionnaires.date_to)-1911,'/',MONTH(questionnaires.date_to),'/',DAY(questionnaires.date_to)) AS c_date_to,
            DATE(questionnaires.date_to) AS date_to";
        if ($questionnaire_data = Questionnaire::selectRaw($questionnaire_sql)->whereRaw('YEAR(questionnaires.date_from)=?', [$year])->get()) {
            $questionnaire_data = $questionnaire_data->toArray();
        }

        $dc_unit = new DcUnitRepository();
        request()->merge(['pass' => '1']);
        request()->merge(['Year' => '0']);
        if ($dc_unit_expire_data = $dc_unit->getAllFilteredData($user->type == 'county' ? $user->id : null)) {
            foreach ($dc_unit_expire_data as $index => $value) {
                $dc_unit_expire_data[$index]['rank_expired_date'] = $value->rank_expired_date;
            }
            $dc_unit_expire_data = $dc_unit_expire_data->toArray();
        }
        request()->merge(['Year' => '1']);
        request()->merge(['is_close_to_expired_date_or_expired' => '1']);
        if ($dc_unit_soon_expire_data = $dc_unit->getAllFilteredData($user->type == 'county' ? $user->id : null)) {
            foreach ($dc_unit_soon_expire_data as $index => $value) {
                $dc_unit_soon_expire_data[$index]['rank_expired_date'] = $value->rank_expired_date;
            }
            $dc_unit_soon_expire_data = $dc_unit_soon_expire_data->toArray();
        }

        request()->request->remove('Year');
        request()->request->remove('is_close_to_expired_date_or_expired');
        $dp_advanced_student = new DpAdvancedStudentRepository();
        request()->merge(['pass' => '3']);
        if ($dp_advance_expire_data = $dp_advanced_student->getAllFilteredData($user)) {
            $dp_advance_expire_data = $dp_advance_expire_data->toArray();
        }
        request()->merge(['pass' => '2']);
        if ($dp_advance_soon_expire_data = $dp_advanced_student->getAllFilteredData($user)) {
            $dp_advance_soon_expire_data = $dp_advance_soon_expire_data->toArray();
        }
        $DP_student_valid_year = DpAdvancedStudentController::DP_STUDENT_VALID_YEAR;
        return compact('report_public_dates', 'report_data', 'plan_data', 'presentation_data', 'sample_report_data', 'seasonal_report_2_data', 'seasonal_report_3_data', 'questionnaire_data', 'dc_unit_expire_data', 'dc_unit_soon_expire_data', 'dp_advance_expire_data', 'dp_advance_soon_expire_data', 'DP_student_valid_year');
    }

    /**
     * Export daily report
     *
     * @return Response
     */
    public function export()
    {
        $user = auth()->user();
        if ($user->hasPermission('admin-permissions') || $user->hasPermission('NFA-permissions') || $user->hasPermission('County-permissions')) {
            return Excel::download(new DailyReportExport(self::reportData($user)), '計畫管考項目狀態通知.xlsx');
        } else {
            abort(404);
        }
    }
}
