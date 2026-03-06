@extends('layouts.app')

@section('title', '防災士培訓 - 社團法人臺灣防災教育訓練學會查詢')

@section('content')
@include('dp.civilInfo', compact('data'))

<script src="{{ asset('scripts/genericPostForm.js') }}"></script>
@endsection