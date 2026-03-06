<div class="flex flex-row items-center justify-between w-full mb-1 ml-auto mr-auto flex-nowrap" id="search-form">
    {!! Form::open(['method'=>'get', 'class' => 'flex flex-row items-center justify-between w-full mb-1
    ml-auto mr-auto flex-nowrap space-x-4']) !!}
    <select
        class="flex-1 h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50"
        id="year" name="year">
        <option value="">- 年份 -</option>
        @foreach ($availableYears as $year)
        <option value="{{ $year }}" {{ $year==request('year')?'selected':'' }}>{{ intval($year)-1911 }}</option>
        @endforeach
    </select>
    <select
        class="inline-block w-auto align-middle bg-white border-gray-300 rounded-md shadow-sm placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50"
        name="topic_id" id="topic_id">
        <option value=""> - 工作項目 -</option>
        @foreach($topicOptions as $topicOption)
        <option value="{{ $topicOption->id }}" data-year="{{ $topicOption->year }}" @if($topicOption->id ==
            request('topic_id')) selected @endif>{{ $topicOption->title }}</option>
        @endforeach
    </select>
    {!! Form::select('county_id', $counties, request('county_id'), ['class' => 'h-12 px-4 border-gray-300 rounded-md
    shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 flex-1', 'placeholder' => ' -
    縣市與分區 - ']) !!}
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
            $('#year').change(function () {
                let optionSelector = $('#topic_id option');
                let year = $(this).val();
                if (!year) {
                    optionSelector.show();
                    return;
                }
                optionSelector.hide();
                optionSelector.filter(function () {
                    return $(this).data('year') == year;
                }).show();
            })
        });
</script>
@endsection