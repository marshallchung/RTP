@extends('layouts.app')

@section('title', '會員登入')
@section('subtitle', '會員登入')

@section('content')
<div x-data="{
    loading:false,
    showResetPassword:false,
    canCancleResetPassword:false,
    user_id:'',
    type:'dc',
    attempting_page:'',
    error:'',
    closeModal(){
        this.showResetPassword=false;
        if (this.attempting_page) {
            window.location = this.attempting_page;
        } else {
            window.location = '{{ url('/') }}';
        }
    },
    onChange(e){
        var This=this;
        const formData = new FormData(e.target);
        this.loading=true;
        fetch(e.target.action,{
            method:'POST',
            body:formData,
            headers: {
                'Accept': 'application/json',
            },
        })
        .then((res) => {
            return res.json();
        })
        .then((data) => {
            if (data.error) {
                alert(data.error);
            }else{
                alert(data.ok);
                if (This.attempting_page) {
                    window.location = This.attempting_page;
                } else {
                    window.location = '{{ url('/') }}';
                }
            }
        }).catch(function(error) {
            if (error.status == 429) {
                alert('嘗試登入次數過多，請稍後再試。');
            }
            if (error.status == 419) {
                alert('頁面逾期，請重新輸入');
                location.reload();
            }
        })
        .finally(() => {
            this.loading=false;
        });
    },
    onSubmit(e){
        var This=this;
        const formData = new FormData(e.target);
        this.loading=true;
        fetch(e.target.action,{
            method:'POST',
            body:formData,
            headers: {
                'Accept': 'application/json',
            },
        })
        .then((res) => {
            return res.json()
        })
        .then((data) => {
            if (data.error) {
                if(data.reset_password){
                    This.showResetPassword=true;
                    This.canCancleResetPassword=data.reset_password=='next_change'?true:false;
                    This.attempting_page=data.attempting_page;
                    This.user_id=data.user_id;
                    This.type=data.type;
                    This.error=data.error;
                }else{
                    alert(data.error);
                }
            }else{
                if (data.attempting_page) {
                    window.location = data.attempting_page;
                } else {
                    window.location = '{{ url('/') }}';
                }
            }
        }).catch(function(error) {
            if (error.status == 429) {
                alert('嘗試登入次數過多，請稍後再試。');
            }
            if (error.status == 419) {
                alert('頁面逾期，請重新輸入');
                location.reload();
            }
        })
        .finally(() => {
            this.loading=false;
        });
    },
    signin_dp(){
        alert('防災士帳號為身份證字號，' + '\n' +
                    '預設密碼為出生年月日8碼' + '\n' +
                    '（如西元1974年6月7日出生者為19740607）' + '\n' +
                    '如帳號或密碼有問題，請於上班時間聯絡消防署防災士客服：' + '\n' +
                    '電話：02-81966142；' + '\n' +
                    '電子郵件：tdrvtiedp@gmail.com' + '\n');
    },
    signin_dc(){
        alert('請於上班時間聯絡消防署' + '\n' +
                    '電話：02-81966126、02-81966122' + '\n' +
                    '電子郵件：hsuyaya@nfa.gov.tw、eric@nfa.gov.tw');
    }
}" class="flex flex-row items-center justify-center w-full">
    <div class="flex flex-col flex-1 w-full max-w-5xl pb-16 space-y-12">
        <div class="flex flex-col items-center justify-center w-full space-x-0 space-y-8 sm:flex-row sm:space-y-0 sm:space-x-20"
            id="divDp">
            @if(false)
            <div class="flex flex-row items-start justify-center w-full" id="dp" aria-labelledby="aDp">
                <form action="{{ route('user.login') }}" method="post" @submit.prevent="onSubmit"
                    class="flex flex-col items-center justify-center w-full space-y-6">
                    {{ csrf_field() }}
                    <input type="hidden" name="type" value="dp">
                    <h1 class="w-full text-left">防災士</h1>
                    <label class="flex flex-col items-start justify-start w-full space-y-2" for="dpUsername">
                        <span>帳號</span>
                        <input id="dpUsername" type="text" name="username"
                            class="w-full px-4 bg-white border border-gray-300 rounded-md shadow-sm h-11 placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50" />
                    </label>
                    <label class="flex flex-col items-start justify-start w-full space-y-2" for="dpPassword">
                        <span>密碼</span>
                        <input id="dpPassword" type="password" name="password"
                            class="w-full px-4 bg-white border border-gray-300 rounded-md shadow-sm h-11 placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50" />
                    </label>
                    <div class="relative flex flex-row items-start justify-center w-full mt-8">
                        <div class="g-recaptcha" data-sitekey="{{ config('google-recaptcha.site-key') }}"></div>
                    </div>
                    <div class="flex flex-row items-center justify-between w-full">
                        <button type="submit"
                            class="flex items-center justify-center h-12 text-sm text-white rounded w-28 sm:text-base sm:w-36 bg-rose-600">登入防災士</button>
                        <button @click="signin_dp" type="button"
                            class="flex items-center justify-center h-12 text-sm text-white rounded w-28 sm:text-base sm:w-36 bg-amber-400">帳號申請</button>
                        <button type="button"
                            class="flex items-center justify-center h-12 text-sm w-28 sm:text-base sm:w-36"
                            onclick="location.href='{{ route('user.resetPassword') }}?type=dp';">忘記密碼
                        </button>
                    </div>
                </form>
            </div>
            @endif
            <div class="flex flex-row items-start justify-center w-full max-w-xl" id="dc" aria-labelledby="aDc">
                <form class="flex flex-col items-center justify-center w-full space-y-6" @submit.prevent="onSubmit"
                    action="{{ route('user.login') }}" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="type" value="dc">
                    <h1 class="w-full text-left">韌性社區</h1>
                    <label class="flex flex-col items-start justify-start w-full space-y-2" for="dcUsername">
                        <span>帳號</span>
                        <input id="dcUsername" name="username"
                            class="w-full px-4 bg-white border border-gray-300 rounded-md shadow-sm h-11 placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50" />
                    </label>
                    <label class="flex flex-col items-start justify-start w-full space-y-2" for="dcPassword">
                        <span>密碼</span>
                        <input id="dcPassword" type="password" name="password"
                            class="w-full px-4 bg-white border border-gray-300 rounded-md shadow-sm h-11 placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50" />
                    </label>
                    <div class="relative flex flex-row items-start justify-center w-full mt-8">
                        <div class="g-recaptcha" data-sitekey="{{ config('google-recaptcha.site-key') }}"></div>
                    </div>
                    <div class="flex flex-row items-center justify-between w-full">
                        <button type="submit"
                            class="flex items-center justify-center h-12 text-sm text-white rounded w-28 sm:text-base sm:w-36 bg-rose-600 disabled:bg-mainGray disabled:hover:bg-mainGray"
                            x-bind:disabled="loading">
                            <template x-if="!loading">
                                <span>登入韌性社區</span>
                            </template>
                            <template x-if="loading">
                                <div class="flex flex-row items-center space-x-1">
                                    <img src="/image/loading.svg" class="w-6 h-6" alt="">
                                    <span>登入中..</span>
                                </div>
                            </template>
                        </button>
                        <button @click="signin_dc" type="button"
                            class="flex items-center justify-center h-12 text-sm text-white rounded w-28 sm:text-base sm:w-36 bg-amber-400">帳號申請</button>
                        <button type="button"
                            class="flex items-center justify-center h-12 text-sm w-28 sm:text-base sm:w-36"
                            onclick="location.href='{{ route('user.resetPassword') }}?type=dc';">忘記密碼
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="flex flex-col items-center justify-center w-full space-y-2">
            <div class="w-full font-bold text-center text-rose-600">
                上班時間請聯繫消防署專線<br />
                02-81966118、02-81966122
            </div>
            <div class="w-full max-w-xl text-center">
                <a href="https://www.facebook.com/groups/bousaiTW" target="_blank"><img src="image/fanpage.png" alt=""
                        style="width:60%;"></a>
            </div>
        </div>
    </div>
    <div x-show.transition="showResetPassword"
        class="fixed inset-0 flex-col justify-start items-center pt-[30vh] bg-black/50 z-[10050] hidden"
        :class="{'hidden':!showResetPassword,'flex':showResetPassword}">
        <div
            class="z-10 flex flex-col items-center justify-center w-full max-w-xl bg-white rounded-lg shadow-lg text-mainGrayDark">
            <div class="flex flex-row items-center justify-center w-full px-6 py-4 rounded-t-lg bg-mainLight">
                <h4 class="modal-title"><span>重設密碼</span>
                </h4>
            </div>
            <div class="flex flex-row items-center justify-center w-full px-6 py-4 rounded-t-lg bg-mainLight">
                <h6 class="modal-title" x-text="error"></h6>
            </div>
            <form class="flex flex-col items-center justify-center w-full p-5 space-y-4" method="post"
                action="/changePassword" @submit.prevent="onChange">
                <input type="hidden" name="type" x-model="type">
                <input type="hidden" name="user_id" x-model="user_id">
                <div class="modal-body flex flex-col justify-center items-center min-h-[6rem] w-full space-y-6">
                    {{ csrf_field() }}
                    <label class="flex flex-col items-start justify-start w-full space-y-2" for="dpUsername">
                        <span>新密碼</span>
                        <input type="password" id="password" name="password"
                            class="w-full px-4 bg-white border border-gray-300 rounded-md shadow-sm h-11 placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50"
                            placeholder="請輸入6-12碼英文和數字組合" />
                    </label>
                    <label class="flex flex-col items-start justify-start w-full space-y-2" for="dpUsername">
                        <span>確認新密碼</span>
                        <input type="password" id="password-confirm" name="password-confirm"
                            class="w-full px-4 bg-white border border-gray-300 rounded-md shadow-sm h-11 placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50"
                            placeholder="請輸入申請帳號時使用的E-mail" />
                    </label>
                </div>
                <div class="flex flex-row items-center w-full pb-4 justify-evenly">
                    <button type="submit"
                        class="px-4 text-sm text-white rounded cursor-pointer py-1.5 bg-mainCyanDark hover:bg-teal-400 disabled:bg-mainGray disabled:hover:bg-mainGray"
                        x-bind:disabled="loading">
                        <template x-if="!loading">
                            <span>變更</span>
                        </template>
                        <template x-if="loading">
                            <div class="flex flex-row items-center space-x-1">
                                <img src="/image/loading.svg" class="w-6 h-6" alt="">
                                <span>變更</span>
                            </div>
                        </template>
                    </button>
                    <template x-if="canCancleResetPassword">
                        <button type="button" @click="closeModal"
                            class="px-4 text-sm rounded cursor-pointer py-1.5 bg-gray-100 hover:bg-gray-50 border border-gray-300 btnCloseModal">關閉</button>
                    </template>
                </div>
            </form>
        </div>
    </div>
    <div x-show="loading" class="absolute z-[1100] inset-0 bg-black/30 hidden justify-center items-center"
        :class="{'flex':loading,'hidden':!loading}">
        <div class="relative w-full h-full">
            <img src="/image/loading.svg" class="w-16 h-16 absolute left-1/2 top-[calc(50vh-2rem)]" alt="">
        </div>
    </div>
</div>
@endsection

@section('js')
@endsection