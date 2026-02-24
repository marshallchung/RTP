@extends('admin.layouts.dashboard', [
'heading' => '新增深耕概要',
'breadcrumbs' => [
['深耕概要', route('admin.static-page.index')],
'新增'
]
])

@section('title', '新增深耕概要')

@section('inner_content')
<div class="flex flex-row flex-wrap">
    {!! Form::open(['method' => 'POST', 'route' => 'admin.static-page.store']) !!}
    @include('admin.static-page.partials.form', ['showDelete' => false])
    {!! Form::close() !!}
</div>
@endsection