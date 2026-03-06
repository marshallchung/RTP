@extends('admin.layouts.dashboard', [
'heading' => '改密碼',
'breadcrumbs' => ['改密碼']
])

@section('title', '改密碼')

@section('inner_content')
<div class="relative w-full max-w-xl p-4 text-content text-mainAdminTextGrayDark">
    @if ($change_default || $next_change)
    <div class="p-5 mb-4 border-l-2 border-l-orange-400 bg-orange-50/70">
        @if ($next_change && !$change_default)
        <p class="m-0 text-orange-600">您已三個月未更換密碼，為維護資訊安全，請立即變更密碼</p>
        @elseif ($change_default)
        <p class="m-0 text-orange-600">新帳號，為維護資訊安全，請立即變更密碼</p>
        @endif
    </div>
    @endif
    <div class="p-5 mb-4 border-l-2 border-l-sky-400 bg-sky-50/70">
        <ul class="flex flex-col space-y-2 list-disc list-inside ">
            <li>密碼長度12字元以上，包含英文大小寫、數字，以及特殊字元。</li>
            <li>使用者必須定期更換密碼，且至少不可以與前3次使用過之密碼相同。</li>
            <li>帳號鎖定機制，帳號登入進行身分鑑別失敗達5次後，至少15分鐘內不允許該帳號繼續嘗試登入。</li>
        </ul>
    </div>

    </template>
    {!! Form::open(['route' => 'admin.users.password.store','class'=>"flex flex-col items-start justify-start w-full
    space-y-4"]) !!}
    <div class="flex flex-row flex-wrap w-full text-center">
        {!! Form::label('password', '新密碼') !!}
        {!! Form::password('password', ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300
        focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
    </div>
    <div class="flex flex-row flex-wrap w-full text-center">
        {!! Form::label('password-confirm', '確認密碼') !!}
        {!! Form::password('password-confirm', ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm
        focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
    </div>
    <div class="flex flex-row justify-between w-full">
        {!! Form::submit('送出', ['class' => 'px-4 text-sm text-white rounded cursor-pointer py-1.5 bg-mainCyanDark
        hover:bg-teal-400']) !!}
    </div>
    {!! Form::close() !!}
</div>
@endsection