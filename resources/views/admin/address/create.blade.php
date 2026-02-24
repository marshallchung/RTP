@extends('admin.layouts.dashboard', [
'heading' => '通訊錄',
'breadcrumbs' => [
'通訊錄',
['資料更新', route('admin.address.manage')],
'新增'
]
])

@section('title', '通訊錄')

@section('inner_content')
<div x-data="{
    showDeleteModel:false,
}" class="flex flex-row items-start justify-start w-full">
    {!! Form::open(['method' => 'POST', 'route' => 'admin.address.store','class'=>'flex
    flex-col w-full items-start justify-start p-4 max-w-3xl']) !!}
    <div class="w-full alert alert-info">
        內政部消防署非常重視您的隱私權，為維護您個人資料之安全性，謹遵循「個人資料保護法」規範。<br>
        保護您個人資料的隱私是本署的責任，有關於您的個人資料與隱私權，本署將依個人資料保護法及相關法令規定，並在保護個人隱私的原則下有限度的運用。<br>
        本通訊錄個人資料，係供業務聯繫使用，僅提供具備進入「災害防救深耕計畫資訊網業務人員版」權限之人員下載查詢，非經當事人同意，絕不轉做其他用途，亦不會揭露任何資訊，並遵循本署個人資料安全控管相關規定辦理。
    </div>
    <div class="flex flex-row flex-wrap w-full">
        <div class="relative w-full mb-6 bg-white border border-gray-200 rounded-sm">
            <div class="relative px-5 pt-3 pb-2 bg-gray-100 border-b-2 border-gray-200 text-mainAdminTextGrayDark">新增
            </div>
            <div class="flex flex-col w-full p-5 m-0 space-y-4 bg-white">
                @include('admin.address.partials.form')
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>
@endsection