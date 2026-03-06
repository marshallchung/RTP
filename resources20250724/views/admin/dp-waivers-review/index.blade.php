@extends('admin.layouts.dashboard', [
'heading' => '防災士培訓 > 課程抵免審查',

'breadcrumbs' => ['課程抵免審查']
])

@section('title', '課程抵免審查')

@section('inner_content')
<div x-data="{
    loading:false,
    getData(page){
        location.href = '{{ route('admin.news.index') }}?page=' + encodeURIComponent(page);
    },
 }" class="flex flex-col items-end justify-start w-full p-4 space-y-4">
    <div x-show="loading" class="absolute z-[1100] inset-0 bg-black/30 hidden justify-center items-center"
        :class="{'flex':loading,'hidden':!loading}">
        <div class="relative w-full h-full">
            <img src="/image/loading.svg" class="w-16 h-16 absolute left-1/2 top-[calc(50vh-2rem)]" alt="">
        </div>
    </div>
    @include('admin.dp-waivers-review.partials.filter')
    <table class="w-full bg-white border shadow-lg text-mainAdminTextGrayDark">
        <thead>
            <tr class="border-b bg-mainLight">
                <th class="p-2 font-normal text-left border-r last:border-r-0">身分證字號</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">姓名</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">欲抵免防災士課程內容</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">參與其他防災課程名稱</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">檔案</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">辦理單位</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">辦理時間</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">審查結果</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">審查時間</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $dpWaiver)
            <tr>
                <td class="p-2 border-r last:border-r-0">{{ $dpWaiver->dpScore->dpStudent->TID ?? null}}</td>
                <td class="p-2 border-r last:border-r-0">{{ $dpWaiver->dpScore->dpStudent->name ?? null}}</td>
                <td class="p-2 border-r last:border-r-0">{{ $dpWaiver->dpScore->dpCourse->name ?? null }}</td>
                <td class="p-2 border-r last:border-r-0">{{ $dpWaiver->name }}</td>
                <td class="p-2 border-r last:border-r-0">
                    <ul class="list-none">
                        @foreach($dpWaiver->files as $file)
                        <li><a href="{{ url($file->path) }}" target="_blank">{{ $file->name }}</a></li>
                        @endforeach
                    </ul>
                </td>
                <td class="p-2 border-r last:border-r-0">{{ $dpWaiver->dpScore->author->name ?? null }}</td>
                <td class="p-2 border-r last:border-r-0">{{ $dpWaiver->created_at }}</td>
                <td class="p-2 border-r last:border-r-0">{!! link_to_route('admin.dp-waivers-review.edit',
                    $dpWaiver->review_result_text, $dpWaiver) !!}</td>
                <td class="p-2 border-r last:border-r-0">{{ $dpWaiver->review_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="">{!! $data->appends(request()->input())->render() !!}</div>
    <div id="js-token" class="hidden">{{ csrf_token() }}</div>
</div>
@endsection