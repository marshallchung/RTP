<header id="masthead" class="site-header container" role="banner">
    <div class="flex flex-row flex-wrap">
        <div class="site-branding col-sm-4">
            <h1 class="site-title">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('image/logo.png') }}" alt="{{ config('app.cht_name') }}">
                </a>
            </h1>
        </div>

        <div class="col-sm-8 mainmenu">
            <div class="mobilenavi"></div>
            <div id="submenu" class="topmenu">
                <ul id="topmenu" class="sfmenu sf-js-enabled sf-shadow">
                    <li id="menu-item-116"
                        class="menu-item menu-item-type-post_type menu-item-object-page current-menu-item page_item page-item-108 current_page_item menu-item-116">
                        <a href="http://demo.fabthemes.com/revera/">Home</a>
                    </li>

                    {{-- 右側選單 --}}
                    @include(config('laravel-menu.views.bootstrap-items'), ['items' => Menu::get('right')->roots()])
                </ul>
            </div>
        </div>
    </div>
</header>