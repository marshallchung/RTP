@extends('admin.layouts.dashboard', [
'heading' => 'QA專區 列表',
'breadcrumbs' => ['列表']
])

@section('title', '問題列表')

@section('inner_content')

<div x-data="{
    getData(page){
        var url = '{{ request()->url() }}?page=' + encodeURIComponent(page);
        location.href = url;
    },}" class="flex flex-row flex-wrap items-start justify-end w-full p-4">
    <div class="flex flex-row items-center justify-end w-full pb-6">
        {!! Form::open(['method' => 'GET', 'route' => 'admin.qas.index', 'class' => 'flex flex-row items-center
        space-x-2
        justify-end w-full']) !!}
        {!! Form::label('sort', '問題分類') !!}
        {!! Form::select('sort', $sorts, request('sort'), ['class' => 'h-12 px-4 border-gray-300 rounded-md
        shadow-sm
        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-44']) !!}
        {!! Form::label('keyWord', '關鍵字') !!}
        {!! Form::text('keyWord', request('keyWord'), ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-56']) !!}
        {!! Form::submit('搜尋', ['class' => 'w-28 cursor-pointer hover:bg-teal-400 text-white flex justify-center
        items-center
        h-12 bg-mainCyanDark rounded']) !!}
        {!! Form::close() !!}
    </div>
    <div class="relative w-full mb-6 bg-white border border-gray-200 rounded-sm">
        <div
            class="relative flex flex-row items-center justify-between px-4 py-2.5 border-b-2 border-gray-200 bg-mainLight1 text-mainAdminTextGrayDark">
            <span class="my-0 text-sm text-mainAdminGrayDark">_</span>
            @include('admin.pagination.simple', ['paginator' => $qas])
        </div>
        <ul class="text-sm text-mainAdminGrayDark">
            @forelse ($qas as $item)
            <li class="first:border-t-0 border-x-0 rounded-none relative block py-3 px-5 -mb-[1px] bg-white border">
                <h4><strong>[@if ($item->sort){{ $item->sort }}@else 未指定@endif]
                        {{ $item->title }}</strong></h4>
                <p class="text-xs text-mainAdminTextGray">
                    發表於{{ $item->author->name }} {{$item->created_at->format('Y-m-d') }}
                </p><br>
                {!! $item->content !!}
                <div>
                    @foreach($item->files as $file)
                    <i class="i-fa6-solid-file"></i>
                    <a href="{{ asset($file->path) }}" target="_blank">{{ $file->name }}</a>
                    @endforeach
                </div>

                @if (Auth::user()->hasPermission('create-QAs'))
                <div class="text-center" style="margin:12px 6px 6px 6px">
                    {!! Form::close() !!}
                    {!! Form::model($item, [
                    'class' => 'flex flex-row flex-wrap text-center',
                    'method' => 'DELETE',
                    'route' => ['admin.qas.destroy', $item->id]]) !!}
                    <a href="{{ route('admin.qas.edit', $item->id) }}"
                        class="flex items-center justify-center w-20 text-sm text-white bg-orange-500 h-9 hover:bg-orange-400">編輯</a>
                    <button type="submit"
                        class="flex items-center justify-center w-20 text-sm text-white h-9 bg-rose-600 hover:bg-rose-500">刪除</button>
                    {!! Form::close() !!}
                </div>
                @endif
            </li>
            @empty
            <li class="first:border-t-0 border-x-0 rounded-none relative block py-3 px-5 -mb-[1px] bg-white border">
                無消息</li>
            @endforelse
        </ul>
    </div>
    <div class="">{!! $qas->render() !!}</div>
    <div id="js-token" class="hidden">{{ csrf_token() }}</div>
</div>

@endsection

@section('scripts')
<script src="{{ asset('scripts/generalIndex.js') }}"></script>
@endsection