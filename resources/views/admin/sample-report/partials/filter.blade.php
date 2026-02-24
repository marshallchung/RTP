<div x-data="{
    year:'{{ request('year') }}',
    yearChange(e){
        this.year=e.target.value;
    }
}" class="flex flex-row items-center justify-between w-full mb-1 ml-auto mr-auto flex-nowrap" id="search-form">
    {!! Form::open(['method'=>'get', 'class' => 'flex flex-row items-center justify-between w-full mb-1
    ml-auto mr-auto flex-nowrap space-x-4']) !!}
    <select @change="yearChange"
        class="flex-1 h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50"
        id="year" name="year">
        <option value="">- 年份 -</option>
        @foreach ($availableYears as $one_year)
        <option value="{{ $one_year }}" {{ $one_year==request('year')?'selected':'' }}>{{ intval($one_year)-1911 }}
        </option>
        @endforeach
    </select>
    <select
        class="inline-block w-auto max-w-lg align-middle bg-white border-gray-300 rounded-md shadow-sm placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50"
        name="topic_id" id="topic_id">
        <option value=""> - 工作項目 -</option>
        @foreach($rootTopicOptions as $year => $options)
        <optgroup label="{{ $year-1911 }}年" data-year="{{ $year }}"
            :class="{'hidden':year.toString()!='' && year.toString()!='{{ $year }}','inline':year.toString()=='' || year.toString()=='{{ $year }}'}">
            @foreach($options as $option_title)
        <optgroup label="{{ $option_title->title }}" data-title="{{ $option_title->title }}" :class="{'hidden':year.toString()!='' && year.toString()!='{{ $year }}','inline':year.toString()=='' ||
        year.toString()=='{{ $year }}'}">
            @foreach($option_title->topics as $option)
            <option value="{{ $option->id }}" :class="{'hidden':year.toString()!='' && year.toString()!='{{ $year }}','inline':year.toString()=='' ||
            year.toString()=='{{ $year }}'}" @if($option->id == request('topic_id')) selected @endif>{{
                $option->title }}</option>
            @endforeach
        </optgroup>
        @endforeach
        </optgroup>
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
@endsection