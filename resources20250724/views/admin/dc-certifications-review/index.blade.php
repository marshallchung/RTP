@extends('admin.layouts.dashboard', [
'heading' => '推動韌性社區 > 標章申請表審查',

'breadcrumbs' => ['標章申請表審查']
])

@section('title', '標章申請表審查')

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
    @include('admin.dc-certifications-review.partials.filter')
    <table class="w-full bg-white border shadow-lg text-mainAdminTextGrayDark">
        <thead>
            <tr class="border-b bg-mainLight">
                <th class="w-20 p-2 font-normal text-left border-r last:border-r-0">縣市</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0bw-29">社區</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">工作項目</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">檔案</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">審查結果</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">審查時間</th>
            </tr>
        </thead>
        <tbody class="text-content text-mainAdminTextGrayDark">
            @foreach ($data as $dcCertification)
            <tr>
                <td class="p-2 border-r last:border-r-0">{{ $dcCertification->dcUnit->county->name ?? null}}</td>
                <td class="p-2 border-r last:border-r-0">{{ $dcCertification->dcUnit->name ?? null}}</td>
                <td class="p-2 border-r last:border-r-0">{{ config('dc.certification.items')[$dcCertification->term] ??
                    $dcCertification->term }}</td>
                <td class="p-2 border-r last:border-r-0">
                    <ul class="flex flex-col space-y-2 list-none">
                        @foreach($dcCertification->files as $file)
                        <li>
                            <a href="{{ url($file->path) }}" target="_blank" class="text-mainBlueDark">{{ $file->name
                                }}</a>
                            （上傳時間：{{ $file->created_at }}）
                        </li>
                        @endforeach
                        {{ Form::open(['route' => ['admin.dc-certifications-review.download-files',
                        $dcCertification->id]])
                        }}
                        <button type="submit"
                            class="flex items-center justify-center h-8 px-4 text-sm bg-gray-100 border border-gray-300 text-mainAdminTextGrayDark hover:bg-gray-50">打包下載</button>
                        {{ Form::close() }}
                    </ul>
                </td>
                <td class="p-2 border-r last:border-r-0">{!! link_to_route('admin.dc-certifications-review.edit',
                    $dcCertification->review_result_text,
                    $dcCertification,['class'=>'flex items-center justify-center h-10 w-20 text-sm text-white
                    bg-mainCyanDark']) !!}</td>
                <td class="p-2 border-r last:border-r-0">{{ $dcCertification->review_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="">{!! $data->appends(request()->input())->render() !!}</div>
    <div id="js-token" class="hidden">{{ csrf_token() }}</div>
</div>
@endsection