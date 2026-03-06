<?php

namespace App\Http\Controllers;

use App\Report;
use App\StaticPage;
use App\Topic;
use Illuminate\Support\Collection;

class ResultIIIController
{
    public function __construct()
    {
        $menu = [
            'overview'    => [
                ['name' => '細述深耕', 'url' => route('resultIII.detail'), 'icon' => asset('canvas/demos/hosting/images/svg/web.svg')],
                ['name' => '暢敘深耕', 'url' => route('resultIII.overview', '暢敘深耕'), 'icon' => asset('canvas/demos/hosting/images/svg/cloud.svg')],
                ['name' => '卓越深耕', 'url' => route('resultIII.overview', '卓越深耕'), 'icon' => asset('canvas/demos/hosting/images/svg/dedicated.svg')],
                ['name' => '深耕美談', 'url' => route('resultIII.overview', '深耕美談'), 'icon' => asset('canvas/demos/hosting/images/svg/shared.svg')],
                ['name' => '深耕集錦', 'url' => route('resultIII.highlights'), 'icon' => asset('canvas/demos/hosting/images/svg/web.svg')],
                ['name' => '碩果深耕', 'url' => route('resultIII.overview', '碩果深耕'), 'icon' => asset('canvas/demos/hosting/images/svg/cloud.svg')],
            ],
            'achievement' => [
                [
                    'name' => '1.災害潛勢及防災地圖', 'url' => route('resultIII.achievement', '1.災害潛勢及防災地圖'),
                    'icon' => asset('canvas/demos/hosting/images/svg/web.svg')
                ],
                [
                    'name' => '2.防災教育訓練及講習', 'url' => route('resultIII.achievement', '2.防災教育訓練及講習'),
                    'icon' => asset('canvas/demos/hosting/images/svg/cloud.svg')
                ],
                [
                    'name' => '3.防災觀摩及表揚活動', 'url' => route('resultIII.achievement', '3.防災觀摩及表揚活動'),
                    'icon' => asset('canvas/demos/hosting/images/svg/dedicated.svg')
                ],
                [
                    'name' => '4.災害防救區域治理', 'url' => route('resultIII.achievement', '4.災害防救區域治理'),
                    'icon' => asset('canvas/demos/hosting/images/svg/web.svg')
                ],
                [
                    'name' => '5.兵棋推演及演練', 'url' => route('resultIII.achievement', '5.兵棋推演及演練'),
                    'icon' => asset('canvas/demos/hosting/images/svg/cloud.svg')
                ],
                [
                    'name' => '6.韌性社區及防災士', 'url' => route('resultIII.achievement', '6.韌性社區及防災士'),
                    'icon' => asset('canvas/demos/hosting/images/svg/dedicated.svg')
                ],
                [
                    'name' => '7.推廣普及防災知識', 'url' => route('resultIII.achievement', '7.推廣普及防災知識'),
                    'icon' => asset('canvas/demos/hosting/images/svg/web.svg')
                ],
                [
                    'name' => '8.防災合作夥伴', 'url' => route('resultIII.achievement', '8.防災合作夥伴'),
                    'icon' => asset('canvas/demos/hosting/images/svg/cloud.svg')
                ],
            ],
        ];

        view()->share('menu', $menu);
    }

    public function index()
    {
        return view('resultIII.index');
    }


    /**
     * 細述深耕
     * @return \Illuminate\Contracts\View\View
     */
    public function detail()
    {
        $showMenu = 'overview';
        $pageTitle = '細述深耕';
        $staticPage = StaticPage::findOrFail('resultiii-0-1-1');

        return view('resultIII.static-page', compact('showMenu', 'pageTitle', 'staticPage'));
    }

    /**
     * 深耕概要
     * @return \Illuminate\Contracts\View\View
     */
    public function overview($overviewType)
    {
        $showMenu = 'overview';
        $pageTitle = $overviewType;
        $overviewTypeIds = [
            '暢敘深耕' => 1,
            '卓越深耕' => 2,
            '深耕美談' => 3,
            '碩果深耕' => 4,
        ];
        if (!array_key_exists($overviewType, $overviewTypeIds)) {
            abort(404);
        }
        $overviewTypeId = $overviewTypeIds[$overviewType];

        $staticPages = StaticPage::with('user')->where('id', 'like', "resultiii-{$overviewTypeId}-%")->orderBy('created_at', 'asc')->get();

        $countiesStaticPages = [];
        foreach ($staticPages as $staticPage) {
            $countiesStaticPages[] = [
                'county'       => $staticPage->user->full_county_name,
                'staticPageId' => $staticPage->id,
            ];
        }

        return view('resultIII.overview.index', compact('showMenu', 'pageTitle', 'overviewType', 'countiesStaticPages'));
    }

    /**
     * 深耕概要
     * @return \Illuminate\Contracts\View\View
     */
    public function overviewShow($overviewType, $staticPageId)
    {
        $showMenu = 'overview';
        $pageTitle = $overviewType;
        $overviewTypeIds = [
            '暢敘深耕' => 1,
            '卓越深耕' => 2,
            '深耕美談' => 3,
            '碩果深耕' => 4,
        ];
        if (!array_key_exists($overviewType, $overviewTypeIds)) {
            abort(404);
        }
        $overviewTypeId = $overviewTypeIds[$overviewType];

        $staticPage = StaticPage::with('user')->where('id', 'like', "resultiii-{$overviewTypeId}-%")->findOrFail($staticPageId);

        return view('resultIII.overview.show', compact('showMenu', 'pageTitle', 'overviewType', 'staticPage'));
    }

    /**
     * 成果展示
     * @return \Illuminate\Contracts\View\View
     */
    public function achievement(string $topicName)
    {
        $showMenu = 'achievement';
        $pageTitle = '成果展示';
        $topic = Topic::whereWorkType('resultiii')->whereTitle($topicName)->firstOrFail();
        /** @var Collection|Report[] $reportCollection */
        $reportCollection = $topic->reportCollection()->with('files', 'user.county')->whereHas('files')->get();
        $files = [];
        foreach ($reportCollection as $report) {
            foreach ($report->files as $file) {
                $files[] = [
                    'county_name' => $report->user->full_county_name,
                    'file'        => $file,
                ];
            }
        }

        return view('resultIII.achievement', compact('showMenu', 'pageTitle', 'topic', 'files'));
    }

    /**
     * 深耕集錦
     * @return \Illuminate\Contracts\View\View
     */
    public function highlights()
    {
        $showMenu = 'overview';
        $pageTitle = '深耕集錦';
        $staticPage = StaticPage::findOrFail('resultiii-0-1-2');

        return view('resultIII.static-page', compact('showMenu', 'pageTitle', 'staticPage'));
    }

    /**
     * 遠望深耕
     * @return \Illuminate\Contracts\View\View
     */
    public function lookahead()
    {
        $pageTitle = '遠望深耕';
        $staticPage = StaticPage::findOrFail('resultiii-0-1-3');

        return view('resultIII.static-page', compact('pageTitle', 'staticPage'));
    }
}
