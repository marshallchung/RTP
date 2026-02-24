@extends('admin.layouts.dashboard', [
'heading' => '靜態頁面',
'breadcrumbs' => ['靜態頁面']
])

@section('title', '靜態頁面')

@section('inner_content')
<div x-data="{
    loading:false,
    getData(page){
        location.href = '/admin/static-page?page=' + encodeURIComponent(page);
    },
 }" class="flex flex-col items-end justify-start w-full p-4 space-y-4">
    <table class="w-full bg-white border shadow-lg text-mainAdminTextGrayDark">
        <thead>
            <tr class="border-b bg-mainLight">
                <th class="p-2 font-normal text-left border-r last:border-r-0">ID</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">管理者</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">標題</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">日期</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">瀏覽</th>
            </tr>
        </thead>
        <tbody class="text-content text-mainAdminTextGrayDark">
            @foreach ($staticPages as $staticPage)
            <tr class="border-b last:border-b-0 odd:bg-white even:bg-mainLight">
                <td class="p-2 border-r last:border-r-0 text-mainBlueDark">{!! Html::linkroute('admin.static-page.edit',
                    $staticPage->id,
                    $staticPage->id) !!}</td>
                <td class="p-2 border-r last:border-r-0">
                    @if($staticPage->user)
                    {{ $staticPage->user->name }}
                    @endif
                </td>
                <td class="p-2 border-r last:border-r-0">{{ $staticPage->title }}</td>
                <td class="p-2 border-r last:border-r-0">{{ $staticPage->created_at }}</td>
                <td class="p-2 border-r last:border-r-0"><a href="{{ route('static-page', $staticPage) }}"
                        class='flex items-center justify-center w-24 h-8 text-sm text-white bg-mainCyanDark hover:bg-teal-400'
                        target="_blank">開啟</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="pull-right">{!! $staticPages->render() !!}</div>
</div>
@endsection