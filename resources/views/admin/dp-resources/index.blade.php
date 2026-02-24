@extends('admin.layouts.dashboard', [
'heading' => '培訓資源',
'header_btn' => ['新增', route('admin.dp-resources.create')],
'breadcrumbs' => ['計畫規範與相關資料']
])

@section('title', '培訓資源')

@section('inner_content')
<table class="w-full bg-white border shadow-lg text-mainAdminTextGrayDark">
    <thead>
        <tr>
            <th class="p-2 font-normal text-left border-r w-14 last:border-r-0">上線</th>
            <th class="p-2 font-normal text-left border-r last:border-r-0">名稱</th>
            <th class="p-2 font-normal text-left border-r last:border-r-0">上傳使用者</th>
            <th class="p-2 font-normal text-left border-r last:border-r-0">日期</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
        <tr>
            <td class="text-center p-2 border-r last:border-r-0">
                <?php
                        $text = '否';
                        $colorClass = 'label-default';
                        if ($item->active) {
                            $text = '是';
                            $colorClass = 'label-success';
                        }
                    ?>
                @if ($routeName == 'show')
                <span class="label {{ $colorClass }}">{{ $text }}</span>
                @else
                <span class="js-toggle-active-btn label {{ $colorClass }}"
                    data-route="{{ route('admin.dp-resources.update', $item->id) }}">{{ $text }}</span>
                @endif
            </td>
            <td class="p-2 border-r last:border-r-0">{!! Html::linkroute('admin.dp-resources.' . $routeName,
                $item->name, $item->id) !!}</td>
            <td class="p-2 border-r last:border-r-0">{{ $item->author->name }}</td>
            <td class="p-2 border-r last:border-r-0">{{ $item->created_at->format('Y-m-d') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div id="js-token" class="hidden">{{ csrf_token() }}</div>
@endsection

@section('scripts')
<script src="{{ asset('scripts/generalIndex.js') }}"></script>
@endsection