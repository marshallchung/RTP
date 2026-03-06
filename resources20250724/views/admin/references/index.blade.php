@extends('admin.layouts.dashboard', [
'heading' => '計劃規範與相關資料',
'header_btn' => ['新增', route('admin.references.create')],
'breadcrumbs' => ['計劃規範與相關資料']
])

@section('title', '計劃規範與相關資料')

@section('inner_content')
<table class="w-full bg-white border shadow-lg text-mainAdminTextGrayDark">
    <thead>
        <tr>
            <th class="p-2 font-normal text-left border-r w-14 last:border-r-0">上線</th>
            <th class="p-2 font-normal text-left border-r last:border-r-0">標題</th>
            <th class="p-2 font-normal text-left border-r last:border-r-0">類別</th>
            <th class="p-2 font-normal text-left border-r last:border-r-0">作者</th>
            <th class="p-2 font-normal text-left border-r last:border-r-0">日期</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($references as $references_item)
        <tr>
            <td class="text-center p-2 border-r last:border-r-0">
                @if ($references_item->active)
                <span class="js-toggle-active-btn label label-success"
                    data-route="{{ route('admin.references.update', $references_item->id) }}">是</span>
                @else
                <span class="js-toggle-active-btn label"
                    data-route="{{ route('admin.references.update', $references_item->id) }}">否</span>
                @endif
            </td>
            <td class="p-2 border-r last:border-r-0">{!! Html::linkroute('admin.references.edit',
                $references_item->title, $references_item->id) !!}</td>
            <td class="p-2 border-r last:border-r-0">{{ $references_item->sort }}</td>
            <td class="p-2 border-r last:border-r-0">{{ $references_item->author->name }}</td>
            <td class="p-2 border-r last:border-r-0">{{ $references_item->created_at }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="pull-right">{!! $references->render() !!}</div>
<div id="js-token" class="hidden">{{ csrf_token() }}</div>
@endsection

@section('scripts')
<script src="{{ asset('scripts/generalIndex.js') }}"></script>
@endsection