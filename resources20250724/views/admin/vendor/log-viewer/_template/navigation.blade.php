<nav class="overflow-visible bg-gray-800 border-gray-600 mb-5 navbar-fixed-top">
    <div
        class="flex flex-nowrap text-center justify-between w-full pr-4 pl-4 ml-auto mr-auto flex item-center justify-between flex-nowrap w-full px-4 mx-auto">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a href="{{ route('log-viewer::dashboard') }}"
                class="navbar-brand py-[0.3125rem] ml-4 text-xl whitespace-nowrap">
                <i class="fa fa-fw fa-book"></i> LogViewer
            </a>
        </div>
        <div class="navbar-collapse" id="navbar">
            <ul class="nav navbar-nav">
                <li class="{{ Route::is('log-viewer::dashboard') ? 'active' : '' }}">
                    <a href="{{ route('log-viewer::dashboard') }}">
                        <i class="fa fa-dashboard"></i> Dashboard
                    </a>
                </li>
                <li class="{{ Route::is('log-viewer::logs.list') ? 'active' : '' }}">
                    <a href="{{ route('log-viewer::logs.list') }}">
                        <i class="fa fa-archive"></i> Logs
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>