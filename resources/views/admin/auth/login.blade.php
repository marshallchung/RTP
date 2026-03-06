@extends('admin.layouts.pixeladmin', ['bodyClass' => 'page-signin'])

@section('title', '登入')

@section('content')

@if (session('success'))
    <script>
        alert('{{ session('success') }}');
    </script>
@endif
<div class="flex items-center justify-center w-screen h-screen bg-gradient-to-t from-gray-200 to-white">
    <div class="flex flex-col items-center justify-start w-full max-w-[34rem] border rounded-3xl bg-white">
        <div class="flex flex-col w-full p-8 space-y-4 text-center text-gray-500">
            <span class="text-4xl">內政部消防署</span>
            <div class="">請輸入使用者帳號及密碼，以登入消防署強韌臺灣計畫資訊網</div>
            <a href="/雙因子驗證登入方法.pdf" target="_blank" style="background: #000; color: #fff; padding: 9px; border-radius: 10px;">雙因子驗證操作方法</a>
        </div>
        <div class="relative flex items-start justify-start w-full p-8 text-mainAdminTextGrayDark">
            <form x-data="{
                iPassword:'',
                iUsername:'',
                showPassword:false,
            }" method="POST" action="{{ route('admin.auth.auth') }}" accept-charset="UTF-8"
                class="flex flex-col items-center justify-start w-full">
                {{ csrf_field() }}
                <label class="relative flex flex-col items-start justify-start w-full space-y-2">
                    <span class="text-sm text-gray-500">帳號</span>
                    <div class="relative flex flex-row w-full">
                        <div class="absolute top-0 left-0 flex items-center justify-center w-10 h-12">
                            <i class="w-5 h-5 text-gray-400 i-lucide-user"></i>
                        </div>
                        <input x-ref="username" x-model="iUsername"
                            class="w-full h-12 pl-10 pr-4 placeholder-gray-400 {{ $errors->has('username')?'border-rose-300 focus:border-rose-300 focus:ring-rose-200':'border-gray-300 focus:border-sky-300 focus:ring-sky-200' }} rounded-md shadow-sm focus:ring focus:ring-opacity-50"
                            placeholder="USERNAME" name="username" type="text">
                    </div>
                </label>
                @if ($errors->has('username'))
                <div
                    class="border help-block bg-rose-50 border-rose-100 w-full text-rose-400 py-1.5 rounded-sm relative mt-0.5 px-3 after:border-b-rose-50 after:border-b-[6px] after:absolute after:left-[17px] after:-top-[6px] after:border-l-[6px] after:border-r-[6px] after:border-l-transparent after:border-r-transparent before:border-b-rose-100 before:border-b-[7px] before:absolute before:left-4 before:-top-[7px] before:border-l-[6px] before:border-r-[6px] before:border-l-transparent before:border-r-transparent">
                    {{ $errors->first('username') }}</div>
                @endif
                <label class="relative flex flex-col items-start justify-start w-full mt-8 space-y-2">
                    <span class="text-sm text-gray-500">密碼</span>
                    <div class="relative flex flex-row w-full">
                        <div class="absolute top-0 left-0 flex items-center justify-center w-10 h-12">
                            <i class="w-5 h-5 text-gray-400 i-lucide-lock"></i>
                        </div>
                        <input x-ref="password" x-model="iPassword" :type="showPassword?'text':'password'"
                            class="w-full h-12 px-10 placeholder-gray-400 {{ $errors->has('password')?'border-rose-300 focus:border-rose-300 focus:ring-rose-200':'border-gray-300 focus:border-sky-300 focus:ring-sky-200' }} rounded-md shadow-sm focus:ring focus:ring-opacity-50"
                            placeholder="Password" name="password" type="password" value="">
                        <button type="button"
                            class="absolute top-0 right-0 items-center justify-center hidden w-10 h-12 "
                            @click="showPassword=!showPassword" :class="{'flex':iPassword,'hidden':!iPassword}">
                            <i class="w-5 h-5 text-gray-400"
                                :class="{'i-lucide-eye':showPassword,'i-lucide-eye-off':!showPassword}"></i>
                        </button>
                    </div>
                </label>
                @if ($errors->has('password'))
                <div
                    class="border help-block bg-rose-50 border-rose-100 w-full text-rose-400 py-1.5 rounded-sm relative mt-0.5 px-3 after:border-b-rose-50 after:border-b-[6px] after:absolute after:left-[17px] after:-top-[6px] after:border-l-[6px] after:border-r-[6px] after:border-l-transparent after:border-r-transparent before:border-b-rose-100 before:border-b-[7px] before:absolute before:left-4 before:-top-[7px] before:border-l-[6px] before:border-r-[6px] before:border-l-transparent before:border-r-transparent">
                    {{ $errors->first('password') }}</div>
                @endif
                @csrf
                <div class="relative flex flex-row items-start justify-center w-full mt-8">
                    <div class="g-recaptcha" data-sitekey="{{ config('google-recaptcha.site-key') }}"></div>
                </div>
                @if ($errors->has('g-recaptcha-response'))
                <div
                    class="border help-block bg-rose-50 border-rose-100 w-full text-rose-400 py-1.5 rounded-sm relative mt-0.5 px-3 after:border-b-rose-50 after:border-b-[6px] after:absolute after:left-[17px] after:-top-[6px] after:border-l-[6px] after:border-r-[6px] after:border-l-transparent after:border-r-transparent before:border-b-rose-100 before:border-b-[7px] before:absolute before:left-4 before:-top-[7px] before:border-l-[6px] before:border-r-[6px] before:border-l-transparent before:border-r-transparent">
                    {{ $errors->first('g-recaptcha-response') }}</div>
                @endif 
                <button type="submit"
                    class="p-2.5 px-10 rounded-md text-center transition-all duration-300 h-12 w-full bg-blue-600 hover:bg-blue-500 text-white shadow-lg shadow-blue-500/70 mt-8">登入</button>
            </form>
        </div>
    </div>
</div>
@endsection