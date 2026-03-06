<div class="flex justify-between w-full ml-auto mr-auto text-center flex-nowrap" style="margin-bottom: 5px"
    id="search-form">
    <input type="checkbox">有意願參加防災工作
    {!! Form::open(['method'=>'get', 'class' => 'flex flex-row items-center justify-between w-full mb-1
    ml-auto mr-auto flex-nowrap space-x-4']) !!}
    {!! Form::text('certificate', request('certificate'), ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
    focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 flex-1', 'placeholder' => '證書編號']) !!}
    {!! Form::text('name', request('name'), ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
    focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 flex-1', 'placeholder' => '姓名']) !!}
    {!! Form::select('gender', $genderOptions, request('gender'), ['class' => 'h-12 px-4 border-gray-300 rounded-md
    shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 flex-1', 'placeholder' =>
    '性別'])
    !!}
    {!! Form::text('unit_first_course', request('unit_first_course'), ['class' => 'h-12 px-4 border-gray-300 rounded-md
    shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 flex-1', 'placeholder' =>
    '培訓單位']) !!}
    {!! Form::select('county_id', $counties, request('county_id'), ['class' => 'h-12 px-4 border-gray-300 rounded-md
    shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 flex-1', 'placeholder' =>
    '所在縣市'])
    !!}
    <div class="input-daterange input-group" id="datepicker-range">
        {!! Form::text('start_at', request('start_at'), ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 flex-1', 'placeholder' => '授證查詢
        起始日期']) !!}
        <span class="input-group-addon">～</span>
        {!! Form::text('end_at', request('end_at'), ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 flex-1', 'placeholder' => '授證查詢
        結束日期']) !!}
    </div>
    {!! Form::select('pass', $passOptions, request('pass'), ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
    focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 flex-1', 'placeholder' => '認證合格']) !!}
    <button type="submit" class="flex items-center justify-center w-20 h-10 text-white bg-mainCyanDark">搜尋</button>
    <a href="{{ request()->url() }}"
        class="flex items-center justify-center w-20 h-10 border border-gray-200 bg-gray-50 hover:bg-gray-100 text-mainAdminTextGrayDark">清空</a>
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
            $('#datepicker-range').datepicker({
                clearBtn: true,
                format: "yyyy-mm-dd"
            });
        });
</script>
@endsection