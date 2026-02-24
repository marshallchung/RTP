<?php

namespace App\Http\Middleware;

use App\DpStudent;
use App\Http\Controllers\ReportController;
use App\IntroductionType;
use App\Video;
use Auth;
use Closure;
use Menu;

class LaravelMenu
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //左側
        Menu::make('left', function ($menu) {
            /* @var \Lavary\Menu\Builder $menu */
            //$menu->add('Home', ['route' => 'index']);
            //$menu->add('About', 'javascript:void(0)');
            //$menu->add('Contact', 'javascript:void(0)');
        });
        //右側
        Menu::make('right', function ($menu) {
            /* @var \Lavary\Menu\Builder $menu */

            // 英文頁面
            $menu->add('English', 'javascript:void(0)')->active('introduction/*')->nickname('English');
            $menu->item('English')->add('Intro', ['route' => ['static-page', 'intro_eng']]);
            $menu->item('English')->add('Disaster Relief Volunteer', ['route' => ['static-page', 'bousai_eng']]);
            $menu->item('English')->add('Resilient Community', ['route' => ['static-page', 'community_eng']]);

            // 關於我們
            $menu->add('關於我們', 'javascript:void(0)')->active('aboutus/*')->nickname('1');

            // 關於我們-最新消息
            $menu->item('1')->add('最新消息', ['route' => ['introduction.public-news.index']]);

            // 強韌專區
            $menu->item('1')->add('強韌臺灣計畫簡介', ['route' => ['static-page', 'rtp_intro']]);
            //    $menu->item('1_1')->add('資料下載', ['route' => ['centralReport.index', 150]])->active();

            // 深耕專區
            //$menu->item('1')->add('深耕專區', 'javascript:void(0)')->nickname('1_1');
            $menu->item('1')->add('深耕專區', ['route' => ['static-page', 'pdmcb_intro']]);
            $menu->item('1')->add('直轄市、縣(市)政府歷年成果統計', ['route' => ['report.statistic']]);
            //    $menu->item('1_1')->add('防災避難看板', ['route' => 'sign-location.index']);
            //    $menu->item('1_1')->add('防救災圖資', ['route' => 'image-data.index']);
            //$menu->item('1')->add('深耕專區-資料下載', ['route' => ['centralReport.index', 149]])->active();


            // 防災士培訓
            $dpMenu = $menu->add('防災士培訓', 'javascript:void(0)')->active('dp/*');
            $dpTopics = [
                'intro'                 => '防災士簡介',
                //'student-list'          => '防災士名冊查詢',
                //'advanced-student-list' => '進階防災士名冊查詢',
                'download'              => '相關資料下載',
                'course'                => '培訓課程查詢',
                'training-institution'  => '培訓機構查詢',
                'statistics'            => '防災士統計',
                'advanced-statistics'   => '進階防災士統計',
            ];
            /*if (Auth::guard('dp')->check()) {
                $dpTopics['student'] = '個人資料修改';
                $dpTopics['myCourse'] = '個人學習履歷';
                $dpTopics['waiver'] = '課程抵免';
                $dpTopics['experience'] = '參與工作項目';
            }*/
            foreach ($dpTopics as $id => $name) {
                $dpMenu->add($name, ['route' => ['dp.' . $id]]);
            }

            // 推動韌性社區
            $dcMenu = $menu->add('推動韌性社區', 'javascript:void(0)')->active('dc/*');
            $dcTopics = [
                'intro'    => '韌性社區簡介',
                'download' => '相關資料下載',
            ];
            /*if (Auth::guard('dc')->check()) {
                $dcTopics['unit'] = '社區資料登錄';
                //                $dcTopics['upload'] = '上傳與管理檔案';
                $dcTopics['certification'] = '標章申請作業';
            }*/
            $dcTopics['unit.index'] = '韌性社區名單查詢';

            foreach ($dcTopics as $id => $name) {
                $dcMenu->add($name, ['route' => ['dc.' . $id]]);
            }

            // 相關資源與連結
            $resourcesMenu = $menu->add('相關資源連結', 'javascript:void(0)')->active('resource/*');
            $resourcesMenu->add('全民防災e點通', ['url' => 'https://bear.emic.gov.tw/MY/#/home/index'])->link->attr('target', '_blank');
            $resourcesMenu->add('友善災防連結', ['route' => ['resource.downloadIndex']]);
            $resourcesMenu->add(
                '災害潛勢地圖查詢',
                ['url' => 'https://dmap.ncdr.nat.gov.tw/主選單/地圖查詢/gis查詢/']
            )->link->attr('target', '_blank');
            $resourcesMenu->add('QA專區', ['route' => ['qa.index']])->active('QA/*');

            // QA 專區

            //宣導影片及文宣專區
            $resourcesMenu = $menu->add('影片及文宣', ['route' => ['resource.videoIndex']]);
            foreach (Video::$sorts as $idx => $sort) {
                $resourcesMenu->add($sort['name'], ['route' => ['resource.videoIndex', 'sort' => $idx]]);
            }

            // 相關資源與連結
            $linkMenu = $menu->add('業務人員版', route('admin.dashboard.index'));

            // 會員
            /*if (Auth::guard('dc')->check() || Auth::guard('dp')->check()) {
                $userMenu = $menu->add('韌性社區登出', ['route' => ['user.logout']]);
            } else {
                $userMenu = $menu->add('韌性社區登入', ['route' => ['user.loginIndex']]);
            }*/

            //會員
            //            if (auth()->check()) {
            //                if (!auth()->user()->is_confirmed) {
            //                    $menu->add('尚未完成信箱驗證', ['route' => 'confirm-mail.resend'])
            //                        ->link->attr(['class' => 'text-danger']);
            //                }
            //                //管理員
            //                if (Laratrust::hasPermission('menu.view') and auth()->user()->isConfirmed) {
            //                    /** @var \Lavary\Menu\Builder $adminMenu */
            //                    $adminMenu = $menu->add('管理選單', 'javascript:void(0)');
            //
            //                    if (Laratrust::hasPermission(['user.manage', 'user.view'])) {
            //                        $adminMenu->add('會員清單', ['route' => 'user.index'])
            //                    }
            //
            //                    if (Laratrust::hasPermission('role.manage')) {
            //                        $adminMenu->add('角色管理', ['route' => 'role.index']);
            //                    }
            //
            //                    if (Laratrust::hasPermission('log-viewer.access')) {
            //                        $adminMenu->add(
            //                            '記錄檢視器 <i class="fa fa-external-link" aria-hidden="true"></i>',
            //                            ['route' => 'log-viewer::dashboard']
            //                        )->link->attr('target', '_blank');
            //                    }
            //                }
            //                /** @var \Lavary\Menu\Builder $userMenu */
            //                $userMenu = $menu->add(auth()->user()->name, 'javascript:void(0)');
            //                $userMenu->add('個人資料', ['route' => 'profile'])->active('profile/*');
            //                $userMenu->add('登出', ['route' => 'logout']);
            //            } else {
            //                //遊客
            //                $menu->add('登入', ['route' => 'login']);
            //            }
        });

        return $next($request);
    }
}
