@extends('layouts.app')
@section('title', $page->title)
@section('subtitle', $page->subtitle)
@section('content')
<div x-data="{
        category:'',
        dpDownloads:[],
        getData(category){
            this.category=category;
            var url = '{{ $page->search }}?category=' + encodeURIComponent(this.category);
            fetch(url)
            .then((response)=>{
                return response.json();
            }).then((jsonData) => {
                this.dpDownloads=jsonData;
            }).catch(function(error) {
                console.log(error);
            });
        },
    }" class="flex flex-row items-start justify-center sm:px-20" x-init="$nextTick(() => {
    getData(category);
    })">
    <div class="flex flex-col items-center justify-start w-full max-w-6xl pb-12">
        <div class="flex flex-row flex-wrap items-center justify-center w-full pb-8">
            <button type="button" @click="getData('')" \
                class="flex-1 h-11 max-w-[8rem] first:rounded-l-md last:rounded-r-md border flex justify-center items-center"
                :class="{' bg-lime-600 text-white':category==='','bg-white text-mainAdminTextGrayDark':category!==''}">全部</button>
            @foreach($categoryOption as $item)
            @if (!empty($item))
            <button type="button" @click="getData('{{ $item }}')" \
                class="flex-1 h-11 max-w-[8rem] first:rounded-l-md last:rounded-r-md border flex justify-center items-center"
                :class="{' bg-lime-600 text-white':category==='{{ $item }}','bg-white text-mainAdminTextGrayDark':category!=='{{ $item }}'}">{{
                $item
                }}</button>
            @endif
            @endforeach
        </div>
        <div id="primary"
            class="flex flex-col items-start justify-start flex-1 w-full space-y-8 sm:flex-row sm:space-y-0">
            <div class="flex flex-col items-start justify-start w-full sm:flex-1">
                <template x-if="dpDownloads.length>0">
                    <template x-for="dpDownload in dpDownloads" :key="'dpDownload' + dpDownload.id">
                        <article class="flex flex-col items-start justify-start pb-16 space-y-4">
                            <header class="flex flex-col items-start justify-start space-y-2">
                                <div class="flex flex-row items-center justify-start space-x-1">
                                    <span x-text="dpDownload.author.name"></span>
                                    <span class="posted-on">發表於</span>
                                    <span
                                        x-text="(new Date(dpDownload.created_at)).toLocaleString('chinese',{hour12:false}).substring(0,9)"></span>
                                </div>
                                <h2 class="entry-title" :id="dpDownload.title" x-text="dpDownload.title"></h2>
                            </header>
                            <div x-html="dpDownload.content"></div>
                            <template x-if="dpDownload.files.length>0">
                                <footer class="flex flex-col w-full space-y-2">
                                    <strong>附件檔</strong>
                                    <div
                                        class="flex flex-col w-full p-4 text-xs text-gray-400 bg-gray-100 card-footer text-mainBlueDark">
                                        <template x-for="downloadFile in dpDownload.files"
                                            :key="'downloadFile' + downloadFile.name">
                                            <a :href="'/' + downloadFile.path" class="my-2"
                                                x-text="downloadFile.name"></a>
                                        </template>
                                    </div>
                                </footer>
                            </template>
                        </article>
                    </template>
                </template>
                <template x-if="dpDownloads.length==0">
                    <article class="post">無消息</article>
                </template>
            </div>
            <div
                class="w-full px-0 sm:max-w-[22rem] sm:ml-4 flex items-center justify-center flex-col sm:justify-start space-y-8 sm:items-start sm:pt-20 pt-0">
                @foreach ([
                asset('image/live_photo/photo1.webp'),
                asset('image/live_photo/photo2.webp'),
                asset('image/live_photo/photo3.webp'),
                asset('image/live_photo/photo4.webp'),
                asset('image/live_photo/photo5.webp'),
                asset('image/live_photo/photo6.webp'),
                asset('image/live_photo/photo7.webp'),
                asset('image/live_photo/photo8.webp'),
                ] as $url)
                <div class="w-full h-auto m-4">
                    <img src="{{ $url }}" class="w-full h-auto" />
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
@endsection