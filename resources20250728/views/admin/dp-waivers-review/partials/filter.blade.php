<div class="flex flex-row items-center justify-between w-full mb-1 ml-auto mr-auto flex-nowrap" id="search-form">
    {!! Form::open(['method'=>'get', 'class' => 'flex flex-row items-center justify-between w-full mb-1
    ml-auto mr-auto flex-nowrap space-x-4']) !!}
    {!! Form::text('TID', request('TID'), ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
    focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 flex-1', 'placeholder' => '身份證字號']) !!}
    {!! Form::text('name', request('name'), ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
    focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 flex-1', 'placeholder' => '姓名']) !!}
    {!! Form::select('course_id', $courseOptions, request('course_id'), ['class' => 'h-12 px-4 border-gray-300
    rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 flex-1',
    'placeholder' =>
    '欲抵免防災士課程內容']) !!}
    {!! Form::select('author_id', $authorOptions, request('author_id'), ['class' => 'h-12 px-4 border-gray-300
    rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 flex-1',
    'placeholder' =>
    '辦理單位']) !!}
    {!! Form::select('review_result', $reviewResultOptions, request('review_result'), ['class' => 'h-12 px-4
    border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50
    flex-1',
    'placeholder' => '審查結果']) !!}
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
        });
</script>
@endsection