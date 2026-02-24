@if ($errors->any())
<div x-data="{
    closeAlert(){
        const elements = document.getElementsByClassName('pa-page-alerts-box');
        while(elements.length > 0){
            elements[0].parentNode.removeChild(elements[0]);
        }
    }
}" class="pa-page-alerts-box">
    <div
        class="border-mainDangerBorder bg-mainDangerBG bg-mainAlertBgImage bg-[length:20px_20px] px-[18px] py-[15px] text-white">
        <button type="button" @click="closeAlert"
            class="top-0 float-right p-0 text-xl font-bold cursor-pointer close text-black/20 drop-shadow-sm shadow-white"
            data-dismiss="alert" aria-hidden="true">&times;</button>
        <p class="mb-1">{{ trans('validation.hasErrors') }}</p>
        <ul class="pl-10 list-disc">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif