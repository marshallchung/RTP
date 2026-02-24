@extends('admin.layouts.dashboard', [
'heading' => '計畫規範與相關資料',
'breadcrumbs' => [
['計畫規範與相關資料', route('admin.uploads.index')],
'編輯'
]
])

@section('title', $data->name)

<?php
    $data->title = $data->name;
?>

@section('inner_content')
<div class="flex flex-row flex-wrap">
    @include('admin.layouts.partials.genericView', ['data' => $data, 'files' => $data->files])
</div>
@endsection