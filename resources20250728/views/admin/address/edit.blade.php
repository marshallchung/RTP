@extends('admin.layouts.dashboard', [
'heading' => '通訊錄',
'breadcrumbs' => [
'通訊錄',
['資料更新', route('admin.address.manage')],
'編輯'
]
])

@section('title', '通訊錄')

@section('inner_content')
<div x-data="{
    showDeleteModel:false,
}" class="flex flex-row items-start justify-start w-full">
    {!! Form::model($address, ['route' => ['admin.address.update', $address->id], 'method' => 'put','class'=>'flex
    flex-row w-full items-start justify-start px-4 px-4']) !!}

    <div class="relative w-full max-w-4xl">
        <div class="relative mb-6 bg-white border border-gray-200 rounded-sm">
            <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">編輯
            </div>
            <div class="flex flex-col p-5 m-0 space-y-6 bg-white">
                @include('admin.address.partials.form')
            </div>
        </div>
    </div>

    {!! Form::close() !!}
</div>
@endsection