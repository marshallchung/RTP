@extends('admin.layouts.dashboard', [
'heading' => '通訊錄',
'breadcrumbs' => [
'通訊錄',
'資料更新'
]
])

@section('title', '資料更新')

@section('inner_content')
<div x-data="{
    loading:false,
    county_id:'{{ request()->input('county_id',null) }}',
    request_county_id:{{ request()->input('county_id','null') }},
    getData(page){
        var url = '{{ route('admin.address.manage') }}?page=' + encodeURIComponent(page);
        if(this.county_id!==''){
            url += '&county_id=' + encodeURIComponent(this.county_id);
        }
        location.href = url;
    },
    searchData(e){
        var url = '{{ route('admin.address.manage') }}?page=1';
        if(this.county_id!==''){
            url += '&county_id=' + encodeURIComponent(this.county_id);
        }
        location.href = url;
    },
    deleteItem(url){
        if(confirm('確定要刪除嗎？')){
            const queryString = window.location.search;
            const urlParams = new URLSearchParams(queryString);
            const page = urlParams.get('page');
            var This=this;
            if(page){
                url+='?page=' + encodeURIComponent(page);
            }
            var token='{{ csrf_token() }}';
            var data = '_method=DELETE&_token=' + token + '&response_json=1' + '&county_id=' + encodeURIComponent(this.county_id);
            this.loading=true;
            fetch(url,{
                method:'POST',
                body:data,
                headers: {
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With':'XMLHttpRequest',
                    'Accept': '*/*',
                    'Content-Type': 'application/x-www-form-urlencoded; chartset=UTF-8',
                },
            })
            .then((response) => {
                if(response.status===200){
                    return response.json();
                }else{
                    alert('伺服器錯誤: ' + response.status);
                    return false;
                }
            })
            .then(function (json) {
                if(json!==false){
                    This.items=json;
                    This.preDragOrder=json;
                }
            })
            .catch(function(error) {
                if (error.status == 429) {
                    alert('嘗試登入次數過多，請稍後再試。');
                }else if (error.status == 419) {
                    alert('頁面逾期，請重新輸入');
                    location.reload();
                }else{
                    alert('伺服器錯誤: ' + error.message);
                }
            })
            .finally(() => {
                this.loading=false;
            });
        }
    },
    updatePosition(fromId,fromPosition,toPosition){

        var This=this;
        var url=this.updateRoute.replace('99999999',fromId);
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        const page = urlParams.get('page');
        if(page){
            url+='?page=' + page;
            if(this.county_id){
                url+='&county_id=' + encodeURIComponent(this.county_id)
            }
        }else if(this.county_id){
            url+='?county_id=' + encodeURIComponent(this.county_id)
        }
        var token='{{ csrf_token() }}';
        var data = '_method=PUT&_token=' + token+ '&fromId=' + fromId+ '&fromPosition=' + fromPosition + '&toPosition=' + toPosition + '&response_json=1';
        this.loading=true;
        fetch(url,{
            method:'POST',
            body:data,
            headers: {
                'X-CSRF-TOKEN': token,
                'X-Requested-With':'XMLHttpRequest',
                'Accept': '*/*',
                'Content-Type': 'application/x-www-form-urlencoded; chartset=UTF-8',
            },
        })
        .then((response) => {
            if(response.status==200){
                return response.json();
            }else{
                alert('伺服器錯誤: ' + response.status);
                return false;
            }
        })
        .then(function (json) {
            if(json!==false){
                This.items=json;
            }
        })
        .catch(function(error) {
            if (error.status == 429) {
                alert('嘗試登入次數過多，請稍後再試。');
            }else if (error.status == 419) {
                alert('頁面逾期，請重新輸入');
                location.reload();
            }else{
                alert('伺服器錯誤: ' + error.message);
            }
            location.reload();
        })
        .finally(() => {
            this.loading=false;
        });
    },
    user: {{ json_encode($user) }},
    items: {{ json_encode($addresses->items()) }},
    usedKeyboard: false,
    dropcheck: 0,
    originalIndexBeingDragged: null,
    indexBeingDragged: null,
    indexBeingDraggedOver: null,
    originalIdBeingDragged: null,
    originalPositionBeingDragged: null,
    positionBeingDraggedOver: null,
    preDragOrder: {{ json_encode($addresses->items()) }},
    dragstart(event) {
        this.preDragOrder = [...this.items];
        this.indexBeingDragged = event.target.getAttribute('x-ref');
        this.originalIndexBeingDragged = event.target.getAttribute('x-ref');
        this.originalIdBeingDragged = event.target.getAttribute('data-itemId');
        this.originalPositionBeingDragged = event.target.getAttribute('data-position');

        event.dataTransfer.dropEffect = 'copy';
    },
    updateListOrder(event) {
        if (this.indexBeingDragged) {
            if(event.target.tagName.toUpperCase()==='TR'){
                this.indexBeingDraggedOver = event.target.getAttribute('x-ref');
            }else{
                this.indexBeingDraggedOver = event.target.closest('tr').getAttribute('x-ref');
            }
            let from = this.indexBeingDragged;
            let to = this.indexBeingDraggedOver;

            if (this.indexBeingDragged == to || from == to) return;

            this.positionBeingDraggedOver = this.items[parseInt(to)].position;

            this.move(from, to);
            this.indexBeingDragged = to;
        }
    },
    setParentDraggable(event) {
        event.target.closest('tr').setAttribute('draggable', true);
    },
    setParentNotDraggable(event) {
        event.target.closest('tr').setAttribute('draggable', false);
    },
    resetState() {
        if(this.originalIdBeingDragged !==null && this.originalPositionBeingDragged !==null && this.positionBeingDraggedOver !==null){
            this.updatePosition(this.originalIdBeingDragged,this.originalPositionBeingDragged,this.positionBeingDraggedOver);
        }
        this.dropcheck = 0;
        this.indexBeingDragged = null;
        this.preDragOrder = [...this.items];
        this.indexBeingDraggedOver = null;
        this.positionBeingDraggedOver = null;
        this.originalIndexBeingDragged = null;
        this.originalPositionBeingDragged = null;
        this.originalIdBeingDragged = null;
    },
    revertState() {
        this.items = this.preDragOrder.length ? this.preDragOrder : this.items;
        this.resetState();
    },
    rePositionPlaceholder() {
        this.items = [...this.preDragOrder];
        this.indexBeingDragged = this.originalIndexBeingDragged;
    },
    move(from, to) {
        let items = this.items;
        if (to >= items.length) {
            let k = to - items.length + 1;
            while (k--) {
                items.push(undefined);
            }
        }
        items.splice(to, 0, items.splice(from, 1)[0]);
        this.items = items;
    },
    editRoute:'{{ route('admin.address.edit',99999999) }}',
    destroyRoute:'{{ route('admin.address.destroy',99999999) }}',
    updateRoute:'{{ route('admin.address.update', 99999999) }}',
    highlightFirstContextButton($event) {
        event.target.nextElementSibling.querySelector('button').focus();
    },
    highlightNextContextMenuItem(event) {
        event.target.closest('tr').nextElementSibling.querySelector('button').focus();
    },
    highlightPreviousContextMenuItem(event) {
        event.target.closest('tr').previousElementSibling.querySelector('button').focus();
    },
 }" class="flex flex-col items-end justify-start w-full p-4 space-y-4">
    <div class="flex flex-row items-center justify-end mb-4">
        <div class="w-full alert alert-info">
            內政部消防署非常重視您的隱私權，為維護您個人資料之安全性，謹遵循「個人資料保護法」規範。<br>
            保護您個人資料的隱私是本署的責任，有關於您的個人資料與隱私權，本署將依個人資料保護法及相關法令規定，並在保護個人隱私的原則下有限度的運用。<br>
            本通訊錄個人資料，係供業務聯繫使用，僅提供具備進入「災害防救深耕計畫資訊網業務人員版」權限之人員下載查詢，非經當事人同意，絕不轉做其他用途，亦不會揭露任何資訊，並遵循本署個人資料安全控管相關規定辦理。
        </div>
    </div>
    <div class="flex flex-row flex-wrap items-center justify-start w-full space-x-2">
        @if(!$user->type)
        {!! Form::select('county_id', $counties, request('county_id'), ['x-model'=>'county_id','class' => 'h-10 px-4
        w-36
        border-gray-300 rounded-md
        shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50']) !!}
        <button type="button" @click="searchData"
            class="px-4 py-2 text-sm text-white rounded cursor-pointer bg-mainCyanDark hover:bg-teal-400">查詢</button>
        @endif
        <a href="{{ route('admin.address.create', ['county_id' => request('county_id')]) }}"
            class="px-4 py-2 text-sm text-white rounded cursor-pointer bg-mainCyanDark hover:bg-teal-400">新增</a>
    </div>
    <table class="w-full bg-white border shadow-lg text-mainAdminTextGrayDark" @keydown.window.tab="usedKeyboard = true"
        @dragleave="dropcheck--;dropcheck || rePositionPlaceholder()" @dragover.stop.prevent @dragend="revertState()"
        @drop.stop.prevent="resetState()">
        <thead>
            <tr>
                <th class="p-2 font-normal text-left border-r w-14 last:border-r-0">排序</th>
                <th class="p-2 font-normal text-left border-r w-28 last:border-r-0">修改/刪除</th>
                <th class="p-2 font-normal text-left border-r w-28 last:border-r-0">縣市&分區</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">單位</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">職稱</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">姓名</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">公務電話</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">行動電話</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">電子郵件</th>
            </tr>
        </thead>
        <tbody class="text-content text-mainAdminTextGrayDark sortable" data-entityname="address">
            <template x-for="(address, index) in items">
                <tr x-bind:data-itemId="address.id" :x-ref="index" @dragstart="dragstart($event)"
                    @dragend="$event.target.setAttribute('draggable', false)" @dragover="updateListOrder($event)"
                    draggable="false" x-bind:data-itemId="address.id" x-bind:data-position="address.position"
                    class="border-b last:border-b-0 odd:bg-white even:bg-mainLight"
                    :class="{'opacity-35 bg-white': indexBeingDragged == index,}">
                    <td class="p-2 border-r last:border-r-0">
                        <template x-if="(request_county_id || user.origin_role>=4)">
                            <button type="button" @mousedown="setParentDraggable(event)" @keyup.stop.prevent
                                @keydown.arrow-down="highlightFirstContextButton($event)" @dragover.stop.prevent
                                class="cursor-move" :class="{'focus:outline-none': !usedKeyboard}">
                                <i class="w-3 h-3 i-fa6-solid-arrows-up-down" aria-hidden="true"></i>
                                <span x-text="address.position"></span>
                            </button>
                        </template>
                        <template x-if="!(request_county_id || user.origin_role>=4)">
                            <div title="若需重新排序，請先依縣市過濾清單"
                                class="flex items-center justify-center w-6 h-6 cursor-not-allowed text-mainGray">
                                <i class="w-3 h-3 i-fa6-solid-arrows-up-down" aria-hidden="true"></i>
                            </div>
                        </template>
                    </td>
                    <td class="p-2 border-r last:border-r-0">
                        <div class="flex flex-row items-center justify-start space-x-4">
                            <a :href="editRoute.replace('99999999',address.id)"
                                class="flex items-center justify-center w-10 h-8 text-sm text-white rounded cursor-pointer bg-mainCyanDark hover:bg-teal-400">
                                <i class="w-3 h-3 i-fa6-solid-pen-to-square" aria-hidden="true"></i>
                            </a>
                            <button type="button" @click="deleteItem(destroyRoute.replace('99999999',address.id))"
                                class="flex items-center justify-center w-10 h-8 text-sm text-white bg-orange-600 rounded cursor-pointer hover:bg-orange-500">
                                <i class="w-2.5 h-2.5 i-fa6-solid-trash" aria-hidden="true"></i>
                            </button>
                        </div>
                    </td>
                    <td class="p-2 border-r last:border-r-0">
                        <template x-if="address.county">
                            <span x-text="address.county.name"></span>
                        </template>
                    </td>
                    <td class="p-2 border-r last:border-r-0" x-text="address.unit"></td>
                    <td class="p-2 border-r last:border-r-0" x-text="address.title"></td>
                    <td class="p-2 border-r last:border-r-0" x-text="address.name"></td>
                    <td class="p-2 border-r last:border-r-0" x-text="address.phone"></td>
                    <td class="p-2 border-r last:border-r-0" x-text="address.mobile"></td>
                    <td class="p-2 border-r last:border-r-0" x-text="address.email"></td>
                </tr>
            </template>
        </tbody>
    </table>
    <div>
        {!! $addresses->render() !!}
    </div>
    <div x-show="loading" class="absolute z-[1100] inset-0 bg-black/30 hidden justify-center items-center"
        :class="{'flex':loading,'hidden':!loading}">
        <div class="relative w-full h-full">
            <img src="/image/loading.svg" class="w-16 h-16 absolute left-1/2 top-[calc(50vh-2rem)]" alt="">
        </div>
    </div>
</div>
@endsection
@section('scripts')
@endsection