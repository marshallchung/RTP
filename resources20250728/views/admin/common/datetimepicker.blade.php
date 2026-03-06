<div x-data="{
    month_names:['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月','十一月', '十二月'],
    days:['週日', '週一', '週二', '週三', '週四', '週五', '週六'],
    showDatepicker: false,
    showMonth:false,
    dateInputElement:'',
    rootContainer:'',
    month: '',
    year: '',
    no_of_days: [],
    getPrivMonth() {
        if (this.month == 0) {
            this.year--;
            this.month = 11;
        } else {
            this.month--;
        }
        this.getNoOfDays();
    },
    getNextMonth() {
        if (this.month == 11) {
            this.year++;
            this.month = 0;
        } else {
            this.month++;
        }
        this.getNoOfDays();
    },
    isToday(date) {
        if (date === undefined) {
            return false;
        }
        const today = new Date();
        const d = new Date(this.year, this.month, date);
        const todayStr = today.formatStr('yyyy-MM-dd');
        const dStr = d.formatStr('yyyy-MM-dd');
        return todayStr === dStr ? true : false;
    },
    doShowDatePicker(dateInputElement){
        this.dateInputElement = dateInputElement;
        let targetInput = dateInputElement;
        let rect = targetInput.getBoundingClientRect();
        let contentRect = dateInputElement.closest('div.relative').getBoundingClientRect();
        let rootModal = document.getElementById('datePickerModal');
        this.showDatepicker=true;
        rootModal.style.left = (rect.left-contentRect.left) + 'px';
        rootModal.style.top = (rect.bottom-contentRect.top+6) + 'px';
        let value = targetInput.value;
        if(value.length>7){
            let year=parseInt(value.substr(0,4));
            let month=parseInt(value.substr(5,2))-1;
            if(this.year!=year || this.month!=month){
                this.year=year;
                this.month=month;
                this.getNoOfDays();
            }
        }
    },
    doHideDatePicker(){
        this.dateInputElement = '';
        this.showDatepicker=false;
    },
    getDateValue(date) {
        let selectedDate = (date === '' ? '' : new Date(this.year, this.month, date));
        if(this.dateInputElement !== ''){
            let targetInput = this.dateInputElement;
            targetInput.value = (selectedDate === '' ? '' : selectedDate.formatStr('yyyy-MM-dd'));
            targetInput.dispatchEvent(new Event('input'));
        }
        this.showDatepicker = false;
    },
    getNoOfDays() {
        this.month = typeof this.month === 'string' ? parseInt(this.month):this.month;
        let daysInMonth = new Date(this.year, this.month + 1, 0).getDate();
        let dayOfWeek = new Date(this.year, this.month).getDay();
        let daysArray=[];
        for (var i = 1; i <= dayOfWeek; i++) {
            daysArray.push('');
        }
        for (var i=1; i <=daysInMonth; i++) {
            daysArray.push(i);
        }
        this.no_of_days=daysArray;
    },
    initDate() {
        let today=new Date();
        this.month=today.getMonth();
        this.year=today.getFullYear();
    }}" x-init="[initDate(), getNoOfDays()]"
    @showdate.window="doShowDatePicker($event.detail.element,$event.detail.container)"
    @hidedate.window="doHideDatePicker()" class="absolute top-0 left-0 z-50 p-4 bg-white rounded-lg shadow w-80"
    x-show="showDatepicker" x-transition.duration.500ms id="datePickerModal"
    @click.away="if(document.activeElement===null || document.activeElement.tagName!=='INPUT' || document.activeElement.dataset.type!=='date'){showDatepicker = false}">

    <div class="flex items-center justify-between mb-2">
        <div class="relative">
            <input x-model="year" @change="getNoOfDays" class="w-12 ml-1 text-lg font-normal text-gray-600 border-none">
            <span x-text="month_names[month]" @click="showMonth=!showMonth"
                class="text-lg font-bold text-gray-800 cursor-pointer"></span>
            <div x-show="showMonth" x-transition.duration.500ms @click.away="showMonth=false;"
                class="absolute z-50 p-2 bg-white border rounded-md shadow-md border-mainGreyLight">
                <select x-model="month" size="12" @change="getNoOfDays();showMonth=false;"
                    class="border-none appearance-none">
                    <option value="0" x-bind:selected="month===0">一月</option>
                    <option value="1" x-bind:selected="month===1">二月</option>
                    <option value="2" x-bind:selected="month===2">三月</option>
                    <option value="3" x-bind:selected="month===3">四月</option>
                    <option value="4" x-bind:selected="month===4">五月</option>
                    <option value="5" x-bind:selected="month===5">六月</option>
                    <option value="6" x-bind:selected="month===6">七月</option>
                    <option value="7" x-bind:selected="month===7">八月</option>
                    <option value="8" x-bind:selected="month===8">九月</option>
                    <option value="9" x-bind:selected="month===9">十月</option>
                    <option value="10" x-bind:selected="month===10">十一月</option>
                    <option value="11" x-bind:selected="month===11">十二月</option>
                </select>
            </div>
        </div>
        <div>
            <button type="button"
                class="inline-flex p-1 transition duration-100 ease-in-out rounded-full cursor-pointer hover:bg-gray-200"
                @click="getPrivMonth()">
                <svg class="inline-flex w-6 h-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <button type="button"
                class="inline-flex p-1 transition duration-100 ease-in-out rounded-full cursor-pointer hover:bg-gray-200"
                @click="getNextMonth()">
                <svg class="inline-flex w-6 h-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    </div>

    <div class="flex flex-wrap mb-3 -mx-1">
        <template x-for="(day, index) in days" :key="index">
            <div class="px-1 w-[14.26%]">
                <div x-text="day" class="text-xs font-medium text-center text-gray-800">
                </div>
            </div>
        </template>
    </div>

    <div class="flex flex-wrap -mx-1">
        <template x-for="date in no_of_days">
            <div class="px-1 mb-1 w-[14.28%]">
                <div @click="date!='' ? getDateValue(date) : null" x-text="date"
                    class="text-sm leading-loose text-center transition duration-100 ease-in-out rounded-full"
                    :class="{'cursor-pointer': date!='', 'bg-blue-500 text-white': isToday(date) == true, 'text-gray-700 hover:bg-blue-200': isToday(date) == false }">
                </div>
            </div>
        </template>
        <div class="px-1 mb-1 w-[28.5%]">
            <div @click="getDateValue('')"
                class="text-sm leading-loose text-center text-red-700 transition duration-100 ease-in-out rounded-full cursor-pointer hover:bg-red-200">
                清除
            </div>
        </div>
    </div>
    <script>
        Date.prototype.formatStr = function (fmt) {
            var o = {
                "M+": this.getMonth() + 1, //月份
                "d+": this.getDate(), //日
                "h+": this.getHours(), //小時
                "m+": this.getMinutes(), //分
                "s+": this.getSeconds(), //秒
                "q+": Math.floor((this.getMonth() + 3) / 3), //季度
                "S": this.getMilliseconds() //毫秒
            };
            if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
            for (var k in o)
                if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" +
                    o[k]).substr(("" + o[k]).length)));
            return fmt;
        }
    </script>
</div>