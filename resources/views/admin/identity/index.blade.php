@extends('admin.layouts.dashboard', [
'heading' => '身分切換',
'breadcrumbs' => ['身分切換']
])

@section('title', '身分切換')

@section('inner_content')
<div class="flex flex-row w-full px-4 text-content text-mainAdminTextGrayDark">
    {!! Form::open(['route' => 'admin.identity.change-identity','class'=>'w-full flex flex-row']) !!}
    <div class="flex-1 pr-6">
        <div class="relative mb-6 bg-white border border-gray-200 rounded-sm">
            <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">編輯
            </div>
            <div class="flex flex-col items-start justify-start w-full p-5 m-0 space-y-4 bg-white">
                <div class="flex flex-row items-end space-x-2">
                    <label class="font-bold">當前身分</label>
                    <span>{{ $user->name }}</span>
                </div>
                @if($originIdentityUser)
                <div class="flex flex-row items-end space-x-2">
                    <label class="font-bold">原始身分</label>
                    <span>{{ $originIdentityUser->name }}</span>
                    <a href="{{ route('admin.identity.change-identity-back') }}" class="text-mainBlueDark">[按此還原]</a>
                </div>
                @endif
                <div class="flex flex-col w-full space-y-1">
                    <label class="font-bold">切換身分對象</label>
                    {!! Form::select('change_to', [null => ' - 下拉選擇身分 - '] + $user->changeable_identities, null,
                    ['required', 'class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300
                    focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="relative xl:w-25/100 xl:float-left md:w-1/3">
        <div class="relative mb-6 bg-white border border-gray-200 rounded-sm">
            <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">切換身分
            </div>
            <div class="p-5 m-0 bg-white">
                {!! Form::submit('切換身分', ['class' => 'w-full text-white flex justify-center items-center h-10
                bg-mainCyanDark rounded']) !!}
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>
@endsection