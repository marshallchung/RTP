@extends('admin.layouts.dashboard', [
'heading' => '推動韌性社區 執行情形管理',
'header_btn' => ['新增', route('admin.dc-schedules.create')],
'breadcrumbs' => ['執行情形管理']
])

@section('title', '執行情形管理')

@section('inner_content')
<table class="w-full bg-white border shadow-lg text-mainAdminTextGrayDark">
    <thead>
        <tr>
            <th class="p-2 font-normal text-left border-r w-14 last:border-r-0">上線</th>
            <th class="p-2 font-normal text-left border-r last:border-r-0">標題</th>
            <th class="p-2 font-normal text-left border-r last:border-r-0">作者</th>
            <th class="p-2 font-normal text-left border-r last:border-r-0">日期</th>
        </tr>
    </thead>
    <tbody class="text-content text-mainAdminTextGrayDark">
        @foreach ($schedules as $schedules_item)
        <tr class="border-b last:border-b-0 odd:bg-white even:bg-mainLight">
            <td class="p-2 text-center border-r last:border-r-0">
                @if ($schedules_item->active)
                <span class="js-toggle-active-btn label label-success"
                    data-route="{{ route('admin.dc-schedules.update', $schedules_item->id) }}">是</span>
                @else
                <span class="js-toggle-active-btn label"
                    data-route="{{ route('admin.dc-schedules.update', $schedules_item->id) }}">否</span>
                @endif
            </td>
            <td class="p-2 border-r last:border-r-0">{!! Html::linkroute('admin.dc-schedules.edit',
                $schedules_item->title, $schedules_item->id) !!}</td>
            <td class="p-2 border-r last:border-r-0">{{ $schedules_item->author->name }}</td>
            <td class="p-2 border-r last:border-r-0">{{ $schedules_item->created_at }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="pull-right">{!! $schedules->render() !!}</div>
<div id="js-token" class="hidden">{{ csrf_token() }}</div>
@endsection

@section('scripts')
<script src="{{ asset('scripts/generalIndex.js') }}"></script>
@endsection