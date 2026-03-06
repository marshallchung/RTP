<div class="flex flex-row flex-wrap justify-center w-full mb-2">
    <div class="relative flex flex-col w-full max-w-4xl p-5 space-y-4 bg-white">
        <div class="flex flex-col sm:flex-row item-start justify-star">
            <label class="w-20 pr-2 text-base text-left sm:text-right text-mainGrayDark">名 &nbsp;&nbsp;&nbsp;&nbsp;
                稱</label>
            <h5 class="">{{ $data->name }}</h5>
        </div>

        <div class="flex flex-col sm:flex-row item-start justify-star">
            <label class="w-20 pr-2 text-base text-left sm:text-right text-mainGrayDark">連絡電話</label>
            <h5 class="">{{ $data->phone }}</h5>
        </div>

        <div class="flex flex-col sm:flex-row item-start justify-star">
            <label class="w-20 pr-2 text-base text-left sm:text-right text-mainGrayDark">機構地址</label>
            <h5 class="">{{ $data->address }}</h5>
        </div>

        <div class="flex flex-col sm:flex-row item-start justify-star">
            <label
                class="w-20 pr-2 text-base tracking-wider text-left sm:text-right text-mainGrayDark">代&nbsp;表&nbsp;人</label>
            <h5 class="">{{ $data->front_man }}</h5>
        </div>

        <div class="flex flex-col sm:flex-row item-start justify-star">
            <label class="w-20 pr-2 text-base text-left sm:text-right text-mainGrayDark">官方網址</label>
            <h5 class=""><a href="{{ $data->url }}" target="_blank">{{ $data->url }}</a></h5>
        </div>

        <div class="flex flex-col space-y-4 item-start justify-star">
            <label class="w-20 pr-2 text-base text-left sm:text-right text-mainGrayDark">辦理業務</label>
            <div>
                {!! $data->business !!}
            </div>
        </div>
    </div>
</div>