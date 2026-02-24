@extends('layouts.app')

@section('title', '防災士培訓')
@section('subtitle', '個人學習履歷')

@section('content')
<div x-data="{
    dp_scores:{{ json_encode($dp_scores) }},
    selectedCourse:'',
    selectCourse(e){
        if(e.target.value===''){
            this.selectedCourse='';
        }else{
            this.selectedCourse=parseInt(e.target.value);
        }
    }
}" class="flex flex-row items-center justify-center w-full">
    <div class="flex flex-col flex-1 w-full max-w-3xl pb-16 space-y-12">
        <div class="flex flex-row flex-wrap items-center justify-center w-full">
            <label class="flex flex-row items-center justify-start w-full space-x-2">
                <strong class="whitespace-nowrap">課程名稱</strong>
                <select id="course_id" @change="selectCourse"
                    class="flex-1 bg-white border-gray-300 rounded-md shadow-sm placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50">
                    @if (count($dp_courses) == 0)
                    <option value="">您尚無課程</option>
                    @else
                    <option value="">全部課程</option>
                    @foreach ($dp_courses as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                    @endif
                </select>
            </label>
        </div>
        <table class="w-full bg-white border shadow-lg text-mainAdminTextGrayDark">
            <thead>
                <tr class="text-white border-b rounded-t bg-mainGrayDark">
                    <th class="px-4 py-2 font-bold border-r last:border-r-0">課程名稱</th>
                    <th class="px-4 py-2 font-bold border-r last:border-r-0">課程成績</th>
                    <th class="px-4 py-2 font-bold border-r last:border-r-0">登錄時間</th>
                </tr>
            </thead>
            <tbody>
                <template x-if="selectedCourse===''">
                    <template x-for="[course_id,score_value] in Object.entries(dp_scores)">
                        <tr class="bg-white border-b odd:bg-gray-100 last:border-b-0 last:rounded-b">
                            <td class="px-4 py-2 text-center border-r last:border-r-0" x-text="score_value.name">
                            </td>
                            <td class="px-4 py-2 text-center border-r last:border-r-0" x-text="score_value.score">
                            </td>
                            <td class="px-4 py-2 text-center border-r last:border-r-0"
                                x-text="score_value.created_at.substring(0,10)">
                            </td>
                        </tr>
                    </template>
                </template>
                <template x-if="dp_scores.hasOwnProperty(selectedCourse)">
                    <tr class="bg-white border-b odd:bg-gray-100 last:border-b-0 last:rounded-b">
                        <td class="px-4 py-2 text-center border-r last:border-r-0"
                            x-text="dp_scores[selectedCourse].name">
                        </td>
                        <td class="px-4 py-2 text-center border-r last:border-r-0"
                            x-text="dp_scores[selectedCourse].score">
                        </td>
                        <td class="px-4 py-2 text-center border-r last:border-r-0"
                            x-text="dp_scores[selectedCourse].created_at.substring(0,10)">
                        </td>
                    </tr>
                </template>
                <template x-if="selectedCourse!=='' && !dp_scores.hasOwnProperty(selectedCourse)">
                    <tr class="bg-white border-b odd:bg-gray-100 last:border-b-0 last:rounded-b">
                        <td colspan="3" class="px-4 py-2 text-center border-r last:border-r-0 text-mainTextGray">
                            無資料
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('js')
@endsection