@extends('layouts.app')

@section('title', '會員登入')
@section('subtitle', '忘記密碼')

@section('content')
<div x-data="{
}" class="flex flex-row items-center justify-center w-full">
    <div class="flex flex-col flex-1 w-full max-w-md pb-16 space-y-12">
        <div class="flex flex-col items-center justify-start w-full space-y-12" id="dp" aria-labelledby="aDp">
            <form action="{{ route('user.execResetPassword') }}" method="post"
                class="flex flex-col items-center justify-center w-full space-y-6">
                {{ csrf_field() }}
                <input type="hidden" name="type" value="{{ Request::input('type') }}">
                <h1 class="w-full text-center">忘記密碼</h1>
                <label class="flex flex-col items-start justify-start w-full space-y-2" for="dpUsername">
                    <span>行動電話</span>
                    <input type="text" id="mobile" name="mobile"
                        class="w-full px-4 bg-white border border-gray-300 rounded-md shadow-sm h-11 placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50"
                        placeholder="請輸入申請帳號時使用的行動電話" value="{{ Request::input('mobile') }}" />
                </label>
                <label class="flex flex-col items-start justify-start w-full space-y-2" for="dpUsername">
                    <span>E-mail</span>
                    <input type="text" id="email" name="email"
                        class="w-full px-4 bg-white border border-gray-300 rounded-md shadow-sm h-11 placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50"
                        placeholder="請輸入申請帳號時使用的E-mail" value="{{ Request::input('email') }}" />
                </label>
                @if (request()->hasValidSignature() || (!empty($dcUser) && !empty($signature)))
                <input type="hidden" name="signature" value="{{ $signature }}">
                <input type="hidden" name="dcUser" value="{{ $dcUser }}">
                <label class="flex flex-col items-start justify-start w-full space-y-2" for="dpUsername">
                    <span>新密碼</span>
                    <input type="password" id="password" name="password"
                        class="w-full px-4 bg-white border border-gray-300 rounded-md shadow-sm h-11 placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50"
                        placeholder="請輸入6-12碼英文和數字組合" value="{{ Request::input('password') }}" />
                </label>
                <label class="flex flex-col items-start justify-start w-full space-y-2" for="dpUsername">
                    <span>確認新密碼</span>
                    <input type="password" id="password-confirm" name="password-confirm"
                        class="w-full px-4 bg-white border border-gray-300 rounded-md shadow-sm h-11 placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50"
                        placeholder="請輸入申請帳號時使用的E-mail" value="{{ Request::input('password-confirm') }}" />
                </label>
                <input type="hidden" id="hasValidSignature" name="hasValidSignature" value="hasValidSignature">
                @else
                <h5 class="w-full text-center text-mainAdminTextGrayDark">
                    請輸入申請帳號時填寫的行動電話與E-mail，我們會寄送確認信至您的信箱，請點選信件內的連結來重設密碼</h5>
                @endif
                <div class="text-center">
                    <button type="submit"
                        class="flex items-center justify-center h-12 text-white rounded w-36 bg-rose-600">重設密碼</button>
                </div>
            </form>
            <div class="w-full font-bold text-center text-mainAdminTextGrayDark">
                如仍無法取得預設密碼，請於上班時間聯絡消防署<br>
                電話：02-81966118、02-81966124<br>
                電子郵件：hsuyaya@nfa.gov.tw、fc831139@nfa.gov.tw<br>

            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
@endsection