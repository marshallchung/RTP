@extends('admin.layouts.dashboard', [
'heading' => '培訓資源',
'breadcrumbs' => [
'檢視'
]
])

@section('title', '培訓資源')

@section('inner_content')
<div class="flex flex-row flex-wrap w-full p-4">
    <div class="relative w-full mb-6 bg-white border border-gray-200 rounded-sm">
        <div
            class="relative flex flex-row items-center justify-between px-4 py-2.5 border-b-2 border-gray-200 bg-mainLight1 text-mainAdminTextGrayDark">
            <span class="my-0 text-sm text-mainAdminGrayDark">培訓資源</span>
            @include('admin.pagination.simple', ['paginator' => $data])
        </div>
        <ul class="text-sm text-mainAdminGrayDark">
            @forelse ($data as $item)
            <li class="first:border-t-0 border-x-0 rounded-none relative block py-3 px-5 -mb-[1px] bg-white border">
                <h4><strong>{{ $item->name }}</strong></h4>
                <p class="text-xs text-mainAdminTextGray">
                    發表於{{ $item->author->name }} {{$item->created_at->format('Y-m-d') }}</p>

                @if (count($item->files))
                <h6 class="mt-3 text-sm"><strong>附件檔</strong></h6>
                @foreach ($item->files as $file)
                <div class="w-full p-3 mt-2 text-xs rounded bg-mainLight text-mainBlueDark hover:text-cyan-700">
                    <a href="{{ $file->path }}">{{ $file->name }}</a>
                </div>
                @endforeach
                @endif
            </li>
            @empty
            <li class="first:border-t-0 border-x-0 rounded-none relative block py-3 px-5 -mb-[1px] bg-white border">
                無消息</li>
            @endforelse
        </ul>
    </div>
</div>
@stop