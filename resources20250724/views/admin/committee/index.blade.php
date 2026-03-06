@extends('admin.layouts.dashboard', ['heading' => '深耕計畫評鑑專區',
'breadcrumbs' => [
'深耕計畫評鑑專區'
]
])

@section('title', '深耕計畫評鑑專區')

@section('inner_content')
<div class="flex flex-row flex-wrap">
    <div class="col-lg-8">
        <div class="border-gray-200 mb-6 relative bg-white border rounded-sm">
            <div class="bg-white m-0 p-5">
                <div class="note note-info">
                    <p>{{ trans('app.committee.instructions') }}</p>
                    @if (isset($files['evaluation']))
                    <a href="{{ $files['evaluation']->path }}" class="btn btn-labeled btn-danger"><span
                            class="btn-label icon fa fa-download"></span>評鑑表單</a>
                    @endif
                    &nbsp;&nbsp;
                    @if (isset($files['instructions']))
                    <a href="{{ $files['instructions']->path }}" class="btn btn-labeled btn-info"><span
                            class="btn-label icon fa fa-download"></span>評鑑方式說明</a>
                    @endif
                </div>
                <ul class="nav nav-tabs">
                    @foreach ($accounts as $index => $level)
                    <li class="{{ $index !== 0 ?: 'active' }}">
                        <a href="#area-tab-{{ $index }}" data-toggle="tab">{{ $level['name'] }}</a>
                    </li>
                    @endforeach
                </ul>
                <div class="tab-content tab-content-bordered">
                    @foreach ($accounts as $index => $level)
                    <div id="area-tab-{{ $index }}" class="tab-pane {{ $index !== 0 ?: 'active' }}">
                        <div class="panel-group">
                            @foreach ($level['accounts'] as $account)
                            <div class="border-gray-200 mb-6 relative bg-white border rounded-sm">
                                <div
                                    class="text-mainAdminTextGrayDark bg-gray-100 border-gray-200 border-b-2 pb-2 px-5 pt-3 relative">
                                    <a href="#accordion-{{ $account->id }}" class="accordion-toggle collapsed"
                                        data-toggle="collapse">{{ $account->name }}</a>
                                </div>
                                <div id="accordion-{{ $account->id }}" class="panel-collapse collapse list-group">
                                    <a href="{{ route('admin.reports.show', $account->id) }}"
                                        class="first:border-t-0 border-x-0 rounded-none relative block py-3 px-5 -mb-[1px] bg-white border">{{
                                        $account->name }}政府</a>
                                    @foreach ($account->districts as $class => $districts)
                                    @if (count($districts))
                                    <div
                                        class="text-mainAdminTextGrayDark bg-gray-100 border-gray-200 border-b-2 pb-2 px-5 pt-3 relative">
                                        <a href="#accordion-{{ $account->id }}-{{ $class }}"
                                            class="accordion-toggle collapsed" data-toggle="collapse">第{{ $class === 1 ?
                                            '一' : '二' }}
                                            類鄉鎮市區公所</a>
                                    </div>
                                    <div id="accordion-{{ $account-> id }}-{{ $class }}"
                                        class="panel-collapse collapse">
                                        @foreach ($districts as $district)
                                        <a href="{{ route('admin.reports.show', $district->id) }}"
                                            class="first:border-t-0 border-x-0 rounded-none relative block py-3 px-5 -mb-[1px] bg-white border">{{
                                            $district->name }}</a>
                                        @endforeach
                                    </div>
                                    @endif
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection