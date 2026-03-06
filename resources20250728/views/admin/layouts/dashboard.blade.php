@extends('admin.layouts.pixeladmin', ['bodyClass' => 'main-menu-animated'])

@section('styles')
<script src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>
@yield('styling')
@endsection

@section('content')
<div class="bg-mainTopMenuBG relative w-screen min-h-[46px] right-0 z-[1030] text-white flex flex-row items-stretch justify-start h-[46px]"
    role="navigation">
    <button type="button" id="main-menu-toggle" @click="toggleMMC"
        class="absolute top-0 left-0 z-10 flex items-center justify-center h-full text-mainMenuIcon bg-mainCyanDark brightness-90 w-14"
        :class="{'before:absolute before:top-[19px] before:border-4 before:border-mainCyanDark before:border-r-mainMenuIcon before:w-2 before:h-2 before:content-[\' \'] before:left-2':!openMMC,'after:absolute after:top-[19px] after:border-4 after:border-mainCyanDark after:border-l-mainMenuIcon after:w-2 after:h-2 after:content-[\' \'] after:left-[2.5rem]':openMMC}">
        <i class="w-4 h-4 i-fa6-solid-bars"></i>
        <span class="absolute z-[1035] -mt-8 left-[51px] leading-[2.875rem] opacity-0 transition-none">關閉</span>
    </button>
    <div class="w-[184px] h-full flex justify-center items-center transition-all duration-500 absolute left-14 top-0"
        :class="{'bg-mainCyanDark text-mainMenuIcon':openMMC,' bg-transparent text-mainMenuTitle':!openMMC}">
        <a href="{{ route('admin.dashboard.index') }}"
            class="text-lg -ml-4 tracking-[0.2rem] whitespace-nowrap transition-all duration-500">{{
            trans('app.name') }}</a>
    </div>
    <div x-data="{
        openAdminMenu:false,
    }" @click.away="openAdminMenu=false" class="relative flex flex-row items-center justify-end w-screen">
        <button type="button" @click="openAdminMenu=!openAdminMenu"
            class="h-full px-6 text-black transition-all duration-500 border-b-2 dropdown-toggle whitespace-nowrap border-b-transparent hover:border-b-mainMenuIcon"
            :class="{' bg-mainTopMenuBG border-b-mainMenuIcon':openAdminMenu,'bg-transparent':!openAdminMenu}"
            data-toggle="dropdown">歡迎，{{
            Auth::user()->name }}！</button>
        <ul x-show="openAdminMenu" x-transition
            class="absolute flex-col hidden top-[46px] right-0 w-40 text-sm justify-center space-y-2 items-start bg-mainTopMenuBG text-black p-4"
            :class="{'flex':openAdminMenu,'hidden':!openAdminMenu}">
            @if(Auth::user()->hasPermission('admin-permissions')||Auth::user()->hasPermission('NFA-permissions')||Auth::user()->hasPermission('DEP-permissions'))
            <li><a href="{{ route('admin.users.reset.index') }}">帳號管理</a></li>
            @endif
            @if(Auth::user()->can_change_identity || Auth::user()->hasPermission('DEP-permissions'))
            <li><a href="{{ route('admin.identity.index') }}">身分切換</a>
            </li>
            @endif
            <li><a href="{{ route('admin.users.password.index') }}">改密碼</a></li>
            <li><a href="{{ route('admin.auth.logout') }}">登出</a></li>
        </ul>
    </div>
</div>
<div class="flex flex-row items-stretch justify-start w-full bg-mainMenuBG">
    @include('admin.layouts.sidebar')
    <div class="relative flex-1 bg-mainLight">
        @include('flash::message')

        @include('admin.layouts.partials.errors')

        @include('admin.layouts.partials.header')

        @yield('inner_content')
    </div>
</div>
@endsection