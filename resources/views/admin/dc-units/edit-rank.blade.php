@extends('admin.layouts.dashboard', [
'heading' => '編輯韌性社區',
'breadcrumbs' => [
['推動韌性社區', route('admin.dc-units.index')],
'編輯'
]
])

@section('title', $data->title)

@section('inner_content')
<div x-data="{
    rank:'{{ $data->rank }}',
    rank_started_date:'{{ $data->rank_started_date }}',
    rank_year:'{{ $data->rank_year }}',
    date_extension:{{ $data->date_extension == '1'?'true':'false' }},
    extension_date:'{{ $data->extension_date===null?'':$data->extension_date }}',
    showExpiredDate:false,
    expired_date:'',
    showExtendDate:false,
    extend_date:'',
    showDate(){
        if(!isNaN(Date.parse(this.rank_started_date))){
            this.showExpiredDate=true;
            var rank_year = parseInt(this.rank_year);
            var date_array=this.rank_started_date.split('-');
            this.expired_date = (parseInt(date_array[0])+rank_year) + '/' + date_array[1] + '/' + date_array[2];
            if(this.date_extension){
                this.showExtendDate=true;
                if(this.extension_date.length>=10){
                    date_array=this.extension_date.split('-');
                    this.extend_date = (parseInt(date_array[0])+3) + '/' + date_array[1] + '/' + date_array[2];
                }else{
                    this.extend_date = (parseInt(date_array[0])+rank_year+3) + '/' + date_array[1] + '/' + date_array[2];
                }
                console.log('extend_date: ' + this.extend_date);
            }
        }else{
            this.showExpiredDate=false;
            this.showExtendDate=false;
        }
    },
}" class="md:w-67/100 xl:w-75/100" x-init="$nextTick(() => {
    showDate();
})">
    <div class="relative mb-6 bg-white border border-gray-200 rounded-sm">
        <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">編輯星等
        </div>
        <div class="p-5 m-0 bg-white">
            {!! Form::model($data, ['route' => ['admin.dc-units.update-rank', $data->id], 'method' => 'put', 'files' =>
            true,'class' =>'flex flex-col w-full space-y-8 -mt-8']) !!}
            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('name', '社區名稱') !!}
                {!! Form::text('name', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'required',
                'disabled']) !!}
            </div>

            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('rank', '星等') !!}
                @if (Auth::user()->origin_role>2 && Auth::user()->origin_role!=6)
                {!! Form::text('rank', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'disabled']) !!}
                @else
                {!! Form::select('rank', $ranks, null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50
                w-full','x-model'=>'rank','@change'=>'showDate']) !!}
                @endif
            </div>

            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('rank_started_date', '星等生效日期') !!}
                @if (Auth::user()->origin_role>2 && Auth::user()->origin_role!=6)
                <input
                    class="w-full h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50"
                    x-model="rank_started_date" name="rank_started_date" type="date" id="rank_started_date" disabled>
                @else
                <input
                    class="w-full h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50"
                    x-model="rank_started_date" @change="showDate" name="rank_started_date" type="date"
                    id="rank_started_date" x-bind:required="rank!=='未審查'">
                @endif
            </div>
            <div class="flex flex-row flex-wrap text-center">
                {!! Form::label('rank_year', '星等有效年限') !!}
                @if (Auth::user()->origin_role>2 && Auth::user()->origin_role!=6)
                {!! Form::text('rank_year', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
                focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'disabled']) !!}
                @else
                {!! Form::select('rank_year', [1=>1,2=>2,3=>3,4=>4,5=>5], null, ['class' => 'h-12 px-4
                border-gray-300 rounded-md
                shadow-sm
                focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50
                w-full','x-model'=>'rank_year','@change'=>'showDate']) !!}
                @endif
            </div>
            <div x-show="showExpiredDate" class="flex flex-row flex-wrap text-center text-rose-600">
                <span>星等截止日期：</span>
                <span x-text="expired_date"></span>
            </div>
            <div class="flex flex-row flex-wrap space-x-6 text-center">
                {!! Form::label('date_extension', '星等生效日期是否展延') !!}
                <div class="flex flex-row flex-wrap space-x-2 text-center">
                    <?php
                                        $checked = $data->date_extension == '1'?true:false;
                                    ?>
                    @if (Auth::user()->origin_role>2 && Auth::user()->origin_role!=6)
                    <label class="radio-inline">{!! Form::radio('date_extension', '1', $checked, ['class'
                        =>
                        'border-gray-300 rounded-full bg-white
                        text-mainCyanDark','disabled','x-model'=>'date_extension']) !!} 是</label>
                    <label class="radio-inline">{!! Form::radio('date_extension', '0', !$checked, ['class'
                        =>
                        'border-gray-300 rounded-full bg-white
                        text-mainCyanDark','disabled','x-model'=>'date_extension']) !!} 否</label>
                    @else
                    <label class="radio-inline">{!! Form::radio('date_extension', '1', $checked, ['class' =>
                        'border-gray-300 rounded-full bg-white
                        text-mainCyanDark','@change'=>'showDate','x-model'=>'date_extension']) !!} 是</label>
                    <label class="radio-inline">{!! Form::radio('date_extension', '0', !$checked, ['class' =>
                        'border-gray-300 rounded-full bg-white
                        text-mainCyanDark','@change'=>'showDate','x-model'=>'date_extension']) !!} 否</label>
                    @endif

                </div>
            </div>
            <div x-show="showExtendDate" class="flex flex-row flex-wrap text-center">
                {!! Form::label('extension_date', '星等展延生效日期') !!}
                @if (Auth::user()->origin_role>2 && Auth::user()->origin_role!=6)
                <input
                    class="w-full h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50"
                    x-model="extension_date" name="extension_date" type="date" id="extension_date" disabled>
                @else
                <input
                    class="w-full h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50"
                    x-model="extension_date" @change="showDate" name="extension_date" type="date" id="extension_date">
                @endif

            </div>
            <div x-show="showExtendDate" class="flex flex-row flex-wrap text-center text-rose-600">
                <span>星等展延日期：</span>
                <span x-text="extend_date"></span>
            </div>


            {!! Form::submit('送出', ['class' => 'w-full cursor-pointer hover:bg-teal-400 text-white flex justify-center
            items-center h-10 bg-mainCyanDark
            rounded']) !!}
            {!! Form::close() !!}
        </div>
    </div>
</div>

<script src="{{ asset('scripts/genericPostForm.js') }}"></script>
@endsection