<?php $__env->startSection('title', '防災士培訓機構'); ?>

<?php $__env->startSection('inner_content'); ?>
<div x-data="{
    loading:false,
    makUrl(url,page){
        var param='';
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        if(page){
            param += param.length>0 ? '&' : '?';
            param += 'page=' + encodeURIComponent(page);
        }else{
            if(urlParams.has('page')){
                const page = urlParams.get('page');
                param += param.length>0 ? '&' : '?';
                param += 'page=' + encodeURIComponent(page);
            }
        }
        if(urlParams.has('filter_name')){
            param += param.length>0 ? '&' : '?';
            param += 'filter_name=' + encodeURIComponent(urlParams.get('filter_name'));
        }
        if(urlParams.has('filter_county_id')){
            param += param.length>0 ? '&' : '?';
            param += 'filter_county_id=' + encodeURIComponent(urlParams.get('filter_county_id'));
        }
        if(urlParams.has('filter_phone')){
            param += param.length>0 ? '&' : '?';
            param += 'filter_phone=' + encodeURIComponent(urlParams.get('filter_phone'));
        }
        if(urlParams.has('filter_address')){
            param += param.length>0 ? '&' : '?';
            param += 'filter_address=' + encodeURIComponent(urlParams.get('filter_address'));
        }
        return url+param;
    },
    getData(page){
        location.href = this.makUrl('<?php echo e(route('admin.dp-training-institution.index')); ?>',page);
    },
    updateActive(e){
        var This=this;
        var activeButton = e.target;
        var url=this.makUrl(activeButton.dataset.route);
        var token='<?php echo e(csrf_token()); ?>';
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
        var url=this.makUrl(this.updateRoute.replace('99999999',fromId));
        var token='<?php echo e(csrf_token()); ?>';
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
            return response.json();
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
            location.reload();
        })
        .finally(() => {
            this.loading=false;
        });
    },
    items: <?php echo e($data->makeHidden('updated_at')->toJson(JSON_PRETTY_PRINT)); ?>,
    usedKeyboard: false,
    dropcheck: 0,
    originalIndexBeingDragged: null,
    indexBeingDragged: null,
    indexBeingDraggedOver: null,
    originalIdBeingDragged: null,
    originalPositionBeingDragged: null,
    positionBeingDraggedOver: null,
    preDragOrder: <?php echo e($data->makeHidden('updated_at')->toJson(JSON_PRETTY_PRINT)); ?>,
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
    editRoute:'<?php echo e(route('admin.dp-training-institution.edit', 99999999)); ?>',
    updateRoute:'<?php echo e(route('admin.dp-training-institution.update', 99999999)); ?>',
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
    <?php echo $__env->make('admin.dp-training-institution.partials.filter', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <table class="w-full bg-white border text-mainAdminTextGrayDark border-mainGray"
        @keydown.window.tab="usedKeyboard = true" @dragleave="dropcheck--;dropcheck || rePositionPlaceholder()"
        @dragover.stop.prevent @dragend="revertState()" @drop.stop.prevent="resetState()">
        <thead>
            <tr class="border-b bg-mainLight">
                <th class="p-2 font-normal text-left border-r w-14 last:border-r-0">排序</th>
                <th class="p-2 font-normal text-left border-r w-14 last:border-r-0">上線</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">名稱</th>
                <th class="w-24 p-2 font-normal text-left border-r last:border-r-0">縣市</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">連絡電話</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">訓練地址</th>
                <th class="w-24 p-2 font-normal text-left border-r last:border-r-0">官方網址</th>
                <th class="p-2 font-normal text-left border-r w-28 last:border-r-0">有效期限</th>
            </tr>
        </thead>
        <tbody class="text-content text-mainAdminTextGrayDark sortable" data-entityname="dp-training-institution">
            <template x-for="(item, index) in items">
                <tr :x-ref="index" @dragstart="dragstart($event)"
                    @dragend="$event.target.setAttribute('draggable', false)" @dragover="updateListOrder($event)"
                    draggable="false" x-bind:data-itemId="item.id" x-bind:data-position="item.position"
                    class="border-b last:border-b-0 odd:bg-white even:bg-mainLight"
                    :class="{'opacity-35 bg-white': indexBeingDragged == index,}">
                    <td class="p-2 text-center border-r cursor-move sortable-handle last:border-r-0">
                        <button type="button" @mousedown="setParentDraggable(event)" @keyup.stop.prevent
                            @keydown.arrow-down="highlightFirstContextButton($event)" @dragover.stop.prevent
                            class="cursor-move" :class="{'focus:outline-none': !usedKeyboard}">
                            <i class="w-3 h-3 i-fa6-solid-arrows-up-down" aria-hidden="true"></i>
                            <span x-text="item.position"></span>
                        </button>
                    </td>
                    <td class="p-2 text-center border-r last:border-r-0">
                        <div class="flex items-center justify-center">
                            <button @click="updateActive" type="button"
                                class="flex items-center justify-center w-5 h-5 text-xs text-white rounded"
                                :class="{'bg-green-500':item.active,'bg-black/30':!item.active}"
                                x-bind:data-route="updateRoute.replace('99999999',item.id)"
                                x-text="item.active?'是':'否'"></button>
                        </div>
                    </td>
                    <td class="p-2 border-r last:border-r-0">
                        <a :href="editRoute.replace('99999999',item.id)" class="text-mainBlueDark"
                            x-text="item.name"></a>
                    </td>
                    <td class="p-2 border-r last:border-r-0" x-text="item.county?item.county.name:''">
                    </td>
                    <td class="p-2 border-r last:border-r-0" x-text="item.phone"></td>
                    <td class="p-2 border-r last:border-r-0" x-text="item.address"></td>
                    <td class="p-2 text-center border-r last:border-r-0">
                        <template x-if="item.url">
                            <a :href="item.url" target="_blank">
                                <i class="w-5 h-5 i-fa6-solid-globe text-mainBlueDark"></i>
                            </a>
                        </template>
                    </td>
                    <td class="p-2 border-r last:border-r-0" x-text="item.expired_date"></td>
                </tr>
            </template>
        </tbody>
    </table>
    <div class=""><?php echo $data->appends(request()->input())->render(); ?></div>
    <div id="js-token" class="hidden"><?php echo e(csrf_token()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="<?php echo e(asset('scripts/generalIndex.js')); ?>"></script>
<?php echo $__env->make('admin.layouts.partials.sortableScript', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.dashboard', [
'heading' => '防災士培訓 > 防災士培訓機構',
'header_btn' => [
// '匯出', route('admin.dp-students.export', request()->input()),
'新增', route('admin.dp-training-institution.create')
],
'breadcrumbs' => ['防災士培訓機構']
], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/Marshall/Desktop/RTP-main/resources/views/admin/dp-training-institution/index.blade.php ENDPATH**/ ?>