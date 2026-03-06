@extends('layouts.app')

@section('title', '重設密碼')

@section('content')
<div class="row justify-center mt-3">
    <div class="col-md-8">
        <h1>重設密碼</h1>
        <div class="relative flex flex-col bg-white border rounded">
            <div class="flex-auto p-5">
                <form role="form" method="POST" action="{{ route('password.request') }}">
                    {{ csrf_field() }}

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="form-group row">
                        <label for="email" class="col-md-2 col-form-label">信箱</label>

                        <div class="col-md-10">
                            <input id="email" type="email"
                                class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
                                value="{{ $email or old('email') }}" required autofocus>

                            @if ($errors->has('email'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password" class="col-md-2 col-form-label">密碼</label>

                        <div class="col-md-10">
                            <input id="password" type="password"
                                class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="password"
                                required>

                            @if ($errors->has('password'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password-confirm" class="col-md-2 col-form-label">確認密碼</label>
                        <div class="col-md-10">
                            <input id="password-confirm" type="password"
                                class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                name="password_confirmation" required>

                            @if ($errors->has('password_confirmation'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-10 ml-auto">
                            <button type="submit"
                                class="px-4 text-sm text-white rounded cursor-pointer py-1.5 bg-mainCyanDark hover:bg-teal-400">
                                <i class="fa fa-check" aria-hidden="true"></i> 重設密碼
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection