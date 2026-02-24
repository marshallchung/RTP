@if (Session::has('flash_notification.message'))
@if (Session::has('flash_notification.overlay'))
@include('flash::modal', ['modalClass' => 'flash-modal', 'title' => Session::get('flash_notification.title'), 'body' =>
Session::get('flash_notification.message')])
@else
<div x-data="{
    closeAlert(){
        const elements = document.getElementsByClassName('pa-page-alerts-box');
        while(elements.length > 0){
            elements[0].parentNode.removeChild(elements[0]);
        }
    }
}" class="pa-page-alerts-box">
    <div class="border-mainCyanDark bg-mainBlueDark bg-mainAlertBgImage bg-[length:20px_20px] px-[18px] py-[15px] text-white"
        data-animate="true">
        <button type="button" @click="closeAlert" class="top-0 float-right p-0 text-xl font-bold cursor-pointer close"
            data-dismiss="alert" aria-hidden="true">&times;</button>
        {!! Session::get('flash_notification.message') !!}
    </div>
</div>
@endif
@endif