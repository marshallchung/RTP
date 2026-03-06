<footer class="footer bg-dark">
    <div class="container" style="padding: 15px 10px">
        <ul class="list-none text-gray-400 flex-fill my-0">
            <li>
                <a href="{{ route('static-page', 'privacy') }}" class="mx-1">隱私權政策</a>
                <a href="{{ route('static-page', 'security') }}" class="mx-1">網站安全政策</a>
                <a href="{{ route('static-page', 'opendata') }}" class="mx-1">政府網站資料開放宣告</a>
                <a href="{{ route('static-page', 'navigation') }}" class="mx-1">網站導覽</a>
                <a href="https://www.webcheck.nat.gov.tw/dashboard.aspx" class="mx-1">政府網站流量儀表板</a>
            </li>
        </ul>
        <div class="text-gray-400 text-center">
            地址：23143 新北市新店區北新路3段200號8樓 &nbsp;|&nbsp;
            客服專線：02-81966123、02-81966122 &nbsp;|&nbsp;
            客服信箱：hsuyaya@nfa.gov.tw、jimmychiu@nfa.gov.tw
        </div>
        @php
        $totalVisitorCounter = App\Counter::firstOrCreate(['name' => 'total_visitor'], ['count' => 37600]);
        $totalVisitorCounter->count += 1;
        $totalVisitorCounter->save();
        @endphp
        <p class="text-gray-400 text-right">更新日期：2021-09-10&nbsp;|&nbsp;瀏覽人次：{{ $totalVisitorCounter->count }}</p>
        <p class="text-gray-400 text-center" style="margin-bottom: 8px">©2020 All rights reserved.</p>
    </div>
</footer>