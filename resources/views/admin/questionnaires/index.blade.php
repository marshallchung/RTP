<?php
    $user = Auth::user();
    $createQuestionnaires = $user->hasPermission('create-questionnaires');
?>
@extends('admin.layouts.dashboard', [
'heading' => '績效評估自評表列表',
'breadcrumbs' => ['績效評估自評表列表']
])

@section('title', '績效評估自評表列表')

@section('inner_content')
<div class="flex flex-col items-end justify-start w-full p-4 space-y-4">
    <table class="w-full bg-white border text-mainAdminTextGrayDark border-mainGray">
        <thead>
            <tr class="border-b bg-mainLight">
                <th class="w-20 p-2 font-normal text-left border-r last:border-r-0">上線中</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">標題</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">作者</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">{{ $createQuestionnaires?'建立日期':'填報截止日期'
                    }}
                </th>
                @if ($createQuestionnaires)
                <th class="p-2 font-normal text-left border-r last:border-r-0">題目管理</th>
                @endif
                @if ($user->origin_role > 2)
                <th class="p-2 font-normal text-left border-r last:border-r-0">管理</th>
                @endif
            </tr>
        </thead>
        <tbody class="text-content text-mainAdminTextGrayDark sortable">
            @foreach ($data as $item)
            <tr class="border-b last:border-b-0 odd:bg-white even:bg-mainLight">
                <td class="p-2 text-center border-r last:border-r-0">
                    <span
                        class="{{ (strtotime($item->date_from) <= time() && strtotime($item->date_to) >= time()) ? 'text-lime-500' : 'text-mainTextGray' }}">{{
                        (strtotime($item->date_from) <= time() && strtotime($item->date_to) >= time()) ? '是' : '否'
                            }}</span>
                </td>
                <td class="p-2 border-r last:border-r-0">
                    @if ($createQuestionnaires)
                    <a href="{{ route('admin.questionnaire.edit', ['questionnaire' => $item->id]) }}"
                        class="text-mainBlueDark">{{ $item->title }}</a>
                    @else
                    {{ $item->title }}
                    @endif
                </td>
                <td class="p-2 border-r last:border-r-0">{{ $item->author->name }}</td>
                <td class="p-2 border-r last:border-r-0">
                    {{ $createQuestionnaires?$item->created_at:date('Y-m-d', strtotime($item->date_to)) }}
                </td>
                @if ($createQuestionnaires)
                <td class="p-2 border-r last:border-r-0">
                    <a class="flex items-center justify-center w-24 h-8 text-sm text-white rounded cursor-pointer bg-lime-600 hover:bg-lime-500"
                        href="{{ route('admin.questions.show', [
                                                        'questionnaire_id' => $item->id
                                                    ]) }}">題目管理</a>
                </td>
                @endif
                @if ($user->origin_role > 2)
                <td class="p-2 border-r last:border-r-0">
                    {{-- <a class="btn btn-success"
                        href="{{ route('admin.questionnaire.panel', ['questionnaire_id' => $item->id]) }}">查看作答</a> --}}
                    <?php
                            $questionnaire_user = $item->users()->where('user_id', $user->id)->first();
                        ?>
                    @if ((strtotime($item->date_from) <= time() && strtotime($item->date_to) >= time()) ||
                        ($questionnaire_user && $questionnaire_user->pivot->status == 2))
                        @if ($item->users()->where('users.id', $user->id)->first() === null)
                        <a class="flex items-center justify-center w-16 h-8 text-sm text-white rounded cursor-pointer bg-lime-600 hover:bg-lime-500"
                            href="{{ route('admin.questionnaire.answer', [
                                    'account_id' => Auth::user()->id,
                                    'questionnaire_id' => $item->id
                                ]) }}">作答</a>
                        @else
                        <a class="flex items-center justify-center w-20 h-8 text-sm text-white bg-orange-600 rounded cursor-pointer hover:bg-orange-500"
                            href="{{ route('admin.questionnaire.answer', [
                                    'account_id' => Auth::user()->id,
                                    'questionnaire_id' => $item->id
                                ]) }}">編輯作答</a>
                        @endif
                        @endif
                </td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<script src="{{ asset('scripts/generalIndex.js') }}"></script>
@endsection