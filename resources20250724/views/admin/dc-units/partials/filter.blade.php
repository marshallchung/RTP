<div class="flex flex-row flex-wrap items-center justify-between w-full mb-1 ml-auto mr-auto" id="search-form">
    {!! Form::open(['method'=>'get', 'class' => 'flex flex-row items-center justify-between w-full mb-1
    ml-auto mr-auto flex-wrap space-x-4']) !!}
    <label>{!! Form::checkbox('is_close_to_expired_date_or_expired', True,
        request('is_close_to_expired_date_or_expired', ['class' =>
        'border-gray-300 rounded-sm bg-white text-mainCyanDark'])) !!}
        星等即將到期(前1個月)</label>
    <input type="checkbox">計畫內
    <input type="checkbox">原民地區
    {!! Form::select('county_id', $counties, request('county_id'), ['class' => 'h-12 px-4 border-gray-300 rounded-md
    shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 flex-1', 'placeholder' =>
    '所在縣市'])
    !!}
    {!! Form::text('name', request('name'), ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
    focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 flex-1', 'placeholder' => '社區名稱']) !!}
    {!! Form::select('rank', $ranks, request('rank'), ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
    focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 flex-1', 'placeholder' => '星等']) !!}


    <select name="Year">
        　 <option value="2023">效期</option>
        　 <option value="2022">效期內</option>
        <option value="2021">已過期</option>
    </select>

    <select name="Year1">
        　 <option value="2023">審查狀態</option>
        　 <option value="2022">通過</option>
        <option value="2021">未通過</option>
    </select>
    <button type="submit" class="flex items-center justify-center w-20 h-10 text-white bg-mainCyanDark">搜尋</button>
    <a href="{{ request()->url() }}"
        class="flex items-center justify-center w-20 h-10 bg-gray-100 border border-gray-300 text-mainAdminTextGrayDark cursor-pointer hover:bg-gray-50 ">清空</a>

    {!! Form::close() !!}
</div>

@section('scripts')
@parent
<script>
    $(function () {
            $("#search-form").submit(function () {
                // Disable empty input before submit, prevent dirty url
                $(this).find(":input").filter(function () {
                    return !this.value;
                }).attr("disabled", "disabled");
                return true;
            });
        });
</script>
@endsection