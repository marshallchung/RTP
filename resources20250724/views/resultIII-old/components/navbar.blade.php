<nav class="bg-white navbar navbar-expand-lg navbar-light flex-col">
    <div
        class="flex justify-between w-full px-4 mx-auto flex flex-nowrap text-center justify-between w-full pr-4 pl-4 ml-auto mr-auto item-center flex-nowrap">
        <a class="navbar-brand py-[0.3125rem] ml-4 text-xl whitespace-nowrap" href="{{ url('/') }}">
            <img src="{{ asset('image/logo.jpg') }}" alt="{{ config('app.cht_name') }}">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive"
            aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- Item 會由 LaravelMenu 生成 --}}
        <div class="navbar-collapse" id="navbarResponsive">
            {{-- 左側選單 --}}
            <ul class="navbar-nav">
                @include(config('laravel-menu.views.bootstrap-items'), array('items' => Menu::get('left')->roots()))
            </ul>
            {{-- 右側選單 --}}
            <ul class="ml-auto navbar-nav">
                @include(config('laravel-menu.views.bootstrap-items'), array('items' => Menu::get('right')->roots()))
            </ul>
        </div>
    </div>
    {{-- 站內搜尋 --}}
    <div class="flex flex-nowrap text-center justify-between w-full pr-4 pl-4 ml-auto mr-auto flex">
        <div class="hidden ml-auto text-right md:block" style="width:60%">
            <img src="{{ asset('image/DRV_logo.png') }}" class="w-10 h-10 m-2 align-self-end" alt="防災士">
            <span class="text-secondary align-self-end">防災士認證總人數: </span>
            <strong class="ml-1 text-5xl text-mainBlue align-self-end">{{ number_format($dpStudentStatistics['total'])
                }}</strong>
            <span class="ml-1 text-mainBlue align-self-end" 位 </span>
                <span class="text-secondary align-self-end">※統計至 {{ now()->subMonth()->format('Y 年 n 月') }}底</span>
                <span class="ml-1 text-mainBlue align-self-end"><a href="{{ route('dp.statistics') }}"
                        style="text-decoration:underline;">詳細統計</a></span>
        </div>
        <div class="ml-auto">
            <form action="{{ route('search') }}" method="GET">
                <div class="mb-3 input-group">
                    <input type="text"
                        class="inline-block w-auto align-middle bg-white border-gray-300 rounded-md shadow-sm placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50"
                        placeholder="站內搜尋" name="q" value="{{ request('q') }}" aria-label="站內搜尋">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</nav>
@if(Route::currentRouteName() != 'index')
<div class="page-head">
    <div class="container">
        <h3>@yield('title')</h3>
        <p>@yield('subtitle')</p>
    </div>
</div>
@endif