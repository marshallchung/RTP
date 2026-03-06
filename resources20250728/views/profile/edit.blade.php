@extends('layouts.app')

@section('title', '編輯個人資料')

@section('content')
<div class="row justify-center mt-3">
    <div class="col-md-8">
        <a href="{{ route('profile') }}"
            class="text-white bg-mainGrayDark border-mainTextGrayDark rounded-md py-2 px-4">
            <i class="fa fa-arrow-left" aria-hidden="true"></i> 個人資料
        </a>
        <h1>編輯個人資料</h1>
        <div class="relative flex flex-col bg-white border rounded">
            <div class="flex-auto p-5">
                <form role="form" method="POST" action="{{ route('profile.update') }}">
                    {{ csrf_field() }}
                    {{ method_field('put') }}

                    <div class="form-group row">
                        <label for="email" class="col-md-2 col-form-label">信箱</label>

                        <div class="col-md-10">
                            <input id="email" type="email"
                                class="inline-block w-auto align-middle bg-white border-gray-300 rounded-md shadow-sm placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50"
                                value="{{ $user->email }}" readonly>
                            <small class="form-text text-gray-400">信箱作為帳號使用，故無法修改</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name" class="col-md-2 col-form-label">名稱</label>

                        <div class="col-md-10">
                            <input id="name" type="text"
                                class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name"
                                value="{{ $user->name }}" required autofocus>

                            @if ($errors->has('name'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-10 ml-auto">
                            <button type="submit"
                                class="px-4 text-sm text-white rounded cursor-pointer py-1.5 bg-mainCyanDark hover:bg-teal-400">
                                <i class="fa fa-check" aria-hidden="true"></i> 確認
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection