<?php

namespace App\Http\Controllers;

use App\DcUnit;
use App\DpStudent;
use App\Nfa\Repositories\HomePageCarouselImageRepositoryInterface;
use App\StaticPage;
use Menu;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\PublicNews;
use Illuminate\Support\Facades\DB;
use App\DpCourse;

class HomeController extends Controller
{
    /**
     * 前台顯示防災士人數統計數據。
     * Show the application dashboard.
     *
     * @param HomePageCarouselImageRepositoryInterface $homePageCarouselImageRepository
     * @return \Illuminate\Http\Response
     */
    public function index(HomePageCarouselImageRepositoryInterface $homePageCarouselImageRepository)
    {
        $homePageCarouselItems = $homePageCarouselImageRepository->homePageCarouselItems();
        $end_day = date('Y-m-d', strtotime('last day of last month')) . " 23:59:59";
        $end_year = intval(substr($end_day, 0, 4)) - 1911;
        $end_month = intval(substr($end_day, 5, 2));

        $endDate = self::getLastMondayString();

        /*$dpStudentStatistics = [
            'total'      => DpStudent::where('active', true)->join('users', 'dp_students.county_id', '=', 'users.id')->whereDate('date_first_finish', '<=', $end_day)->count(),
        ];*/
        $rankCount = DcUnit::getQuery()->select('rank', DB::raw('count(*) as count'))
            ->groupBy('rank')->pluck('count', 'rank');
        $rankCount = $rankCount ? $rankCount->toArray() : [];
        $menu = Menu::get('right')->roots();
        $dataQuery = PublicNews::orderBy('position')->with('author', 'files', 'counter')->latest('created_at')
            ->where('active', true);

        $news_data = $dataQuery->limit(3)->get();

        foreach ($news_data as $idx => $item) {
            $news_data[$idx]['content'] = preg_replace("/(<img[^>]*alt=\".*?\")([^\"']*)(\/>)(*SKIP)(*F)|(<img[^\>]*)(\/>)/", "$4 alt=\"\"$5", $item->content);
        }

        $course_data = DpCourse::with('author', 'county')->where('active', true)->where(function ($query) {
            $query->where('date_from', '>=', date("Y-m-d"))
                ->orWhere('date_to', '>=', date("Y-m-d"));
        })
            ->latest('date_from')->limit(4)->get();
        $dpStudentStatistics = DpController::dpStudentStatistics();

        return view('index', compact('homePageCarouselItems', 'rankCount', 'end_year', 'end_month', 'news_data', 'course_data', 'dpStudentStatistics' , 'endDate'));
    }

    /**
     * 前台固定頁面功能。
     *
     */
    public function showStaticPage(StaticPage $staticPage)
    {
        return view('static-page', compact('staticPage'));
    }

    /**
     * 前台搜尋功能。
     *
     */
    public function search()
    {
        return view('search');
    }

    /**
     * 產生sitemap。
     * @see https://github.com/spatie/laravel-sitemap
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sitemap()
    {
        //TODO
        $response = Sitemap::create()
            ->add(Url::create(route('index'))->setPriority(1))
            ->add(Url::create("https://pdmcb.nfa.gov.tw/page/intro_eng")->setPriority(1))
            ->add(Url::create("https://pdmcb.nfa.gov.tw/page/bousai_eng")->setPriority(1))
            ->add(Url::create("https://pdmcb.nfa.gov.tw/page/community_eng")->setPriority(1))
            ->add(Url::create("https://pdmcb.nfa.gov.tw/introduction/news")->setPriority(1))
            ->add(Url::create("https://pdmcb.nfa.gov.tw/page/pdmcb_intro")->setPriority(1))
            ->add(Url::create("https://pdmcb.nfa.gov.tw/dp/intro")->setPriority(1))
            ->add(Url::create("https://pdmcb.nfa.gov.tw/dp/download")->setPriority(1))
            ->add(Url::create("https://pdmcb.nfa.gov.tw/dp/course")->setPriority(1))
            ->add(Url::create("https://pdmcb.nfa.gov.tw/dc/intro")->setPriority(1))
            ->add(Url::create("https://pdmcb.nfa.gov.tw/dc/download")->setPriority(1))
            ->add(Url::create("https://pdmcb.nfa.gov.tw/video")->setPriority(1))
            ->toResponse(request());

        return $response;
    }

    protected function getLastMonday()
    {
        $todayWeekday = date('N'); // 1 (Monday) to 7 (Sunday)
        if ($todayWeekday == 1) {
            // 今天就是星期一
            return date('Y-m-d');
        }

        return date('Y-m-d', strtotime('last monday'));
    }

    protected function getLastMondayString() {

        // 1. 取得西元日期字串 (例如: '2025-11-17')
        $western_date = self::getLastMonday();

        // 2. 轉換為 DateTime 物件
        $dateTime = new \DateTime($western_date);

        // 3. 擷取西元年份、月、日
        $western_year = (int)$dateTime->format('Y');
        $month = $dateTime->format('m');
        $day = $dateTime->format('d');
        
        // 4. 計算民國年 (例如: 2025 - 1911 = 114)
        $minguo_year = $western_year - 1911;

        // 5. 組合成所需的格式： YYY年 MM月 DD日
        return $minguo_year . '年 ' . $month . '月 ' . $day . '日';
    }
}
