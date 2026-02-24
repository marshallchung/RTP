@extends('admin.layouts.dashboard', [
'heading' => '計畫規範與相關資料',

'breadcrumbs' => ['計畫規範與相關資料']
])

@section('title', '計畫規範與相關資料')

@section('inner_content')
<div x-data="{
    loading:false,
    getData(page){
        location.href = '{{ route('admin.uploads.index') }}?page=' + encodeURIComponent(page);
    },
    updateActive(e){
        var This=this;
        var activeButton = e.target;
        var url=activeButton.dataset.route;
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        const page = urlParams.get('page');
        if(page){
            url+='?page=' + page;
        }
        var token='{{ csrf_token() }}';
        var active=activeButton.innerText=='是'?'0':'1';
        var data = '_method=PUT&_token=' + token+ '&active=' + active + '&response_json=1';
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
            }
        })
        .then(function (json) {
            This.items=json;
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
    },
    updatePosition(fromId,fromPosition,toPosition){

        var This=this;
        var url=this.updateRoute.replace('99999999',fromId);
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        const page = urlParams.get('page');
        if(page){
            url+='?page=' + page;
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
    items: {{ json_encode($uploads->items()) }},
    usedKeyboard: false,
    dropcheck: 0,
    originalIndexBeingDragged: null,
    indexBeingDragged: null,
    indexBeingDraggedOver: null,
    originalIdBeingDragged: null,
    originalPositionBeingDragged: null,
    positionBeingDraggedOver: null,
    preDragOrder: {{ json_encode($uploads->items()) }},
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
    editRoute:'{{ route('admin.uploads.' . $routeName, 99999999) }}',
    updateRoute:'{{ route('admin.uploads.update', 99999999) }}',
    routeName:'{{ $routeName }}',
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
    <div x-show="loading" class="absolute z-[1100] inset-0 bg-black/30 hidden justify-center items-center"
        :class="{'flex':loading,'hidden':!loading}">
        <div class="relative w-full h-full">
            <img src="/image/loading.svg" class="w-16 h-16 absolute left-1/2 top-[calc(50vh-2rem)]" alt="">
        </div>
    </div>
    <table class="w-full bg-white border text-mainAdminTextGrayDark border-mainGray"
        @keydown.window.tab="usedKeyboard = true" @dragleave="dropcheck--;dropcheck || rePositionPlaceholder()"
        @dragover.stop.prevent @dragend="revertState()" @drop.stop.prevent="resetState()">
        <thead>
            <tr class="border-b bg-mainLight">
                <th class="p-2 font-normal text-left border-r w-14 last:border-r-0">排序</th>
                <th class="p-2 font-normal text-left border-r w-14 last:border-r-0">上線</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">名稱</th>
                <th class="p-2 font-normal text-left border-r w-36 last:border-r-0">類別</th>
                <th class="w-32 p-2 font-normal text-left border-r last:border-r-0">上傳使用者</th>
                <th class="p-2 font-normal text-left border-r w-28 last:border-r-0">日期</th>
            </tr>
        </thead>
        <tbody class="text-content text-mainAdminTextGrayDark sortable" data-entityname="upload">
            <template x-for="(upload, index) in items">
                <tr :x-ref="index" @dragstart="dragstart($event)"
                    @dragend="$event.target.setAttribute('draggable', false)" @dragover="updateListOrder($event)"
                    draggable="false" x-bind:data-itemId="upload.id" x-bind:data-position="upload.position"
                    class="border-b last:border-b-0 odd:bg-white even:bg-mainLight"
                    :class="{'opacity-35 bg-white': indexBeingDragged == index,}">
                    <td class="p-2 text-center border-r sortable-handle last:border-r-0">
                        <button type="button" @mousedown="setParentDraggable(event)" @keyup.stop.prevent
                            @keydown.arrow-down="highlightFirstContextButton($event)" @dragover.stop.prevent
                            class="cursor-move" :class="{'focus:outline-none': !usedKeyboard}">
                            <i class="w-3 h-3 i-fa6-solid-arrows-up-down" aria-hidden="true"></i>
                            <span x-text="upload.position"></span>
                        </button>
                    </td>
                    <td class="p-2 border-r last:border-r-0">
                        <div class="flex items-center justify-center w-full h-full">
                            <template x-if="routeName === 'show'">
                                <span class="flex items-center justify-center w-5 h-5 text-xs text-white rounded"
                                    :class="{'bg-green-500':upload.active,'bg-black/30':!upload.active}"
                                    x-text="upload.active?'是':'否'"></span>
                            </template>
                            <template x-if="routeName !== 'show'">
                                <button @click="updateActive" type="button"
                                    class="flex items-center justify-center w-5 h-5 text-xs text-white rounded"
                                    :class="{'bg-green-500':upload.active,'bg-black/30':!upload.active}"
                                    x-bind:data-route="updateRoute.replace('99999999',upload.id)"
                                    x-text="upload.active?'是':'否'"></button>
                            </template>
                        </div>
                    </td>
                    <td class="p-2 border-r last:border-r-0">
                        <a :href="editRoute.replace('99999999',upload.id)" class="text-mainBlueDark"
                            x-text="upload.name"></a>
                    </td>
                    <td class="p-2 border-r last:border-r-0" x-text="upload.type=='related-files'?'相關計畫與規定':
                    (upload.type=='evaluation'?'評鑑表單':
                    (upload.type=='evaluation-instructions'?'評鑑方式說明':
                    (upload.type=='rules'?'計畫規範':
                    (upload.type=='references'?'相關資料':
                    (upload.type=='others'?'其它':
                    (upload.type=='references_2'?'計畫資料':
                    (upload.type=='rules_2'?'相關規範':
                    (upload.type=='others_2'?'參考資料':''))))))))">
                    </td>
                    <td class="p-2 border-r last:border-r-0" x-text="upload.user.name"></td>
                    <td class="p-2 border-r last:border-r-0"
                        x-text="(new Date(upload.created_at)).toLocaleString('chinese',{hour12:false}).substring(0,10)">
                    </td>
                </tr>
            </template>
        </tbody>
    </table>
    <div class="">{!! $uploads->render() !!}</div>
    <div id="js-token" class="hidden">{{ csrf_token() }}</div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('scripts/generalIndex.js') }}"></script>
@include('admin.layouts.partials.sortableScript')
@endsection