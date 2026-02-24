@extends('admin.layouts.dashboard', [
'heading' => '編輯培訓資源',
'breadcrumbs' => [
['培訓資源', route('admin.dp-resources.index')],
'編輯'
]
])

@section('title', $data->name)

@section('inner_content')
<div class="flex flex-row flex-wrap">
    {!! Form::model($data, ['method' => 'PUT', 'files' => true, 'route' => ['admin.dp-resources.update', $data->id]])
    !!}
    @include('admin.dp-resources.partials.form', [
    'showDelete' => true,
    'customEditPermission' => 'DP-resources-manage',
    ])
    {!! Form::close() !!}

    {!! Form::model($data, ['method' => 'DELETE', 'route' => ['admin.dp-resources.destroy', $data->id]]) !!}
    @include('admin.layouts.partials.genericDeleteForm')
    {!! Form::close() !!}
</div>
@endsection