@extends('admin.layouts.dashboard', [
'heading' => '資料公開時間',
'breadcrumbs' => [
'優選範本公開時間',
'資料公開時間'
]
])

@section('title', '資料公開時間')

@section('inner_content')
<div class="relative w-full max-w-3xl p-4">
    @include('admin.common.datetimepicker')
    <table class="w-full bg-white border shadow-lg text-mainAdminTextGrayDark">
        <thead>
            <tr class="border-b bg-mainLight">
                <th class="w-12 p-2 font-normal text-left border-r last:border-r-0">年</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">開始日期</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">即將逾期通知日期</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">逾期警告日期</th>
                <th class="w-20 p-2 font-normal text-left border-r last:border-r-0">編輯</th>
            </tr>
        </thead>
        <tbody class="text-content text-mainAdminTextGrayDark">
            @foreach ($publicDates as $publicDate)
            <?php
                if ($publicDate->year < 2018) continue;
            ?>
            {!! Form::open(['method' => 'POST', 'route' => ['admin.sample-report.date.update', $publicDate->id]]) !!}
            <tr class="border-b last:border-b-0 odd:bg-white even:bg-mainLight">
                <td class="p-2 align-middle border-r last:border-r-0">{{ $publicDate->year }}</td>
                <td class="p-2 border-r last:border-r-0">
                    {!! Form::hidden('year', $publicDate->year) !!}
                    {!! Form::input('text', 'date', date('Y-m-d', strtotime($publicDate->public_date)),
                    ['@click'=>"\$dispatch('showdate',{element:\$el})",
                    'class'=>'h-10 text-sm px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300 focus:ring
                    focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
                </td>
                <td class="p-2 border-r last:border-r-0">
                    {!! Form::input('text', 'expire_soon_date', date('Y-m-d', strtotime($publicDate->expire_soon_date)),
                    ['@click'=>"\$dispatch('showdate',{element:\$el})",
                    'class'=>'h-10 text-sm px-4 border-gray-300 rounded-md shadow-sm
                    focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
                </td>
                <td class="p-2 border-r last:border-r-0">
                    {!! Form::input('text', 'expire_date', date('Y-m-d', strtotime($publicDate->expire_date)),
                    ['@click'=>"\$dispatch('showdate',{element:\$el})",
                    'class'=>'h-10 text-sm px-4 border-gray-300 rounded-md shadow-sm
                    focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
                </td>
                <td class="p-2 border-r last:border-r-0">{!! Form::submit('編輯', ['class' => 'flex items-center
                    justify-center w-20 h-9 text-sm text-white bg-mainCyanDark',
                    'style' => 'margin-top: 0;']) !!}
                </td>
            </tr>
            {!! Form::close() !!}
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<script src="{{ asset('scripts/reportsDates.js') }}"></script>
@endsection