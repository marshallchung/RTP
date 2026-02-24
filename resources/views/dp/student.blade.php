@extends('layouts.app')

@section('title', '防災士培訓')
@section('subtitle', '個人資料修改')

@section('content')
<div x-data="{
    onSubmit(e){
        var formData = new FormData(e.target);
        var object = {};
        formData.forEach(function(value, key){
            object[key] = value;
        });
        fetch(e.target.action,{
            method:'PUT',
            body:JSON.stringify(object),
            headers: {
                'Accept': 'application.json',
                'Content-Type': 'application.json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
        })
        .then((res) => {
            return res.json()
        })
        .then((data) => {
            if (data.error) {
                alert(data.error);
                return;
            }else if (data.ok) {
                alert('修改成功!');
                location.reload();
            }else if (data.attempting_page) {
                window.location = data.attempting_page;
            } else {
                window.location = '{{ url('/') }}';
            }
        }).catch(function(error) {
            if (error.status == 429) {
                alert('嘗試登入次數過多，請稍後再試。');
            }else if (error.status == 419) {
                alert('頁面逾期，請重新輸入');
                location.reload();
            }else{
                alert('伺服器錯誤: ' + error.message);
            }
        });
    },
}" class="flex flex-row items-center justify-center w-full">
    <div class="flex flex-col flex-1 w-full max-w-xl pb-16 space-y-12">
        <div class="relative p-5 mb-6 bg-white text-mainAdminTextGrayDark">
            <form method="PUT" action="{{ route('dp.student.update') }}" @submit.prevent="onSubmit"
                accept-charset="UTF-8" enctype="multipart/form-data"
                class="flex flex-col items-center justify-center w-full space-y-6">
                <label class="flex flex-col items-start justify-start w-full space-y-2" for="password">
                    <span>密碼</span>
                    <input id="password" type="password" name="password"
                        class="w-full px-4 bg-white border border-gray-300 rounded-md shadow-sm h-11 placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50" />
                </label>
                <label class="flex flex-col items-start justify-start w-full space-y-2" for="TID">
                    <span>身份證字號</span>
                    <input id="TID" type="text" name="TID" value="{{ $data['TID'] }}"
                        class="w-full px-4 bg-gray-100 border border-gray-300 rounded-md shadow-sm h-11 placeholder:text-mainTextGray focus:border-gray-300 focus:ring focus:ring-transparent focus:ring-opacity-0"
                        readonly />
                </label>
                <label class="flex flex-col items-start justify-start w-full space-y-2" for="name">
                    <span>姓名（必填）</span>
                    <input id="name" type="text" name="name" value="{{ $data['name'] }}"
                        class="w-full px-4 bg-white border border-gray-300 rounded-md shadow-sm h-11 placeholder:text-mainTextGray focus:border-rose-300 focus:ring focus:ring-rose-200 focus:ring-opacity-50"
                        required />
                </label>
                <label class="flex flex-col items-start justify-start w-full space-y-2" for="birth">
                    <span>出生年（西元）</span>
                    <input id="birth" type="text" name="birth" value="{{ $data['birth'] }}"
                        class="w-full px-4 bg-gray-100 border border-gray-300 rounded-md shadow-sm h-11 placeholder:text-mainTextGray focus:border-gray-300 focus:ring focus:ring-transparent focus:ring-opacity-0"
                        readonly />
                </label>
                <div class="flex flex-row items-start justify-start w-full space-x-4">
                    {!! Form::label('gender', '性別') !!}
                    <div class="flex flex-row space-x-2 text-center">
                        <?php
                            $male = false;
                            $female = false;
                            if ($data->gender == '男') $male = true;
                            if ($data->gender == '男') $female = true;
                            ?>
                        <label class=" text-mainTextGray">{!! Form::radio('gender', '男', $male, ['disabled','class' =>
                            'border-gray-300
                            rounded-full bg-white text-mainCyanDark']) !!}
                            男</label>
                        <label class="text-mainTextGray">{!! Form::radio('gender', '女', $female, ['disabled','class' =>
                            'border-gray-300
                            rounded-full bg-white text-mainCyanDark']) !!}
                            女</label>
                    </div>
                </div>
                <label class="flex flex-col items-start justify-start w-full space-y-2" for="field">
                    <span>工作領域（必填）</span>
                    <select id="field" name="field" value="{{ $data['field'] }}"
                        class="w-full px-4 bg-white border border-gray-300 rounded-md shadow-sm h-11 placeholder:text-mainTextGray focus:border-rose-300 focus:ring focus:ring-rose-200 focus:ring-opacity-50"
                        required>
                        @foreach ($fieldOption as $key=>$field)
                        <option value="{{ $key }}" {{ $key===$data['field']?'selected':'' }}>{{ $field }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="flex flex-col items-start justify-start w-full space-y-2" for="phone">
                    <span>市內電話（必填）</span>
                    <input id="phone" type="text" name="phone" value="{{ $data['phone'] }}"
                        class="w-full px-4 bg-white border border-gray-300 rounded-md shadow-sm h-11 placeholder:text-mainTextGray focus:border-rose-300 focus:ring focus:ring-rose-200 focus:ring-opacity-50"
                        required />
                </label>
                <label class="flex flex-col items-start justify-start w-full space-y-2" for="mobile">
                    <span>行動電話（必填）</span>
                    <input id="mobile" type="text" name="mobile" value="{{ $data['mobile'] }}"
                        class="w-full px-4 bg-white border border-gray-300 rounded-md shadow-sm h-11 placeholder:text-mainTextGray focus:border-rose-300 focus:ring focus:ring-rose-200 focus:ring-opacity-50"
                        required />
                </label>
                <label class="flex flex-col items-start justify-start w-full space-y-2" for="email">
                    <span>E-mail（必填）</span>
                    <input id="email" type="text" name="email" value="{{ $data['email'] }}"
                        class="w-full px-4 bg-white border border-gray-300 rounded-md shadow-sm h-11 placeholder:text-mainTextGray focus:border-rose-300 focus:ring focus:ring-rose-200 focus:ring-opacity-50"
                        required />
                </label>
                <label class="flex flex-col items-start justify-start w-full space-y-2" for="address">
                    <span>居住地址（必填）</span>
                    <input id="address" type="text" name="address" value="{{ $data['address'] }}"
                        class="w-full px-4 bg-white border border-gray-300 rounded-md shadow-sm h-11 placeholder:text-mainTextGray focus:border-rose-300 focus:ring focus:ring-rose-200 focus:ring-opacity-50"
                        required />
                </label>
                <label class="flex flex-col items-start justify-start w-full space-y-2" for="community">
                    <span>所屬村里或社區（必填）</span>
                    <input id="community" type="text" name="community" value="{{ $data['community'] }}"
                        class="w-full px-4 bg-white border border-gray-300 rounded-md shadow-sm h-11 placeholder:text-mainTextGray focus:border-rose-300 focus:ring focus:ring-rose-200 focus:ring-opacity-50"
                        required />
                </label>
                <label class="flex flex-col items-start justify-start w-full space-y-2" for="county_id">
                    <span>所屬縣市</span>
                    <select id="county_id" name="county_id" value="{{ $data['county_id'] }}"
                        class="w-full px-4 bg-white border border-gray-300 rounded-md shadow-sm h-11 placeholder:text-mainTextGray focus:border-rose-300 focus:ring focus:ring-rose-200 focus:ring-opacity-50"
                        required>
                        @foreach ($counties as $key=>$countY)
                        <option value="{{ $key }}" {{ $key===$data['county_id']?'selected':'' }}>{{ $countY }}</option>
                        @endforeach
                    </select>
                </label>
                <button type="submit"
                    class="flex items-center justify-center w-full h-12 text-white rounded cursor-pointer bg-mainBlueDark">送出</button>
            </form>
        </div>
    </div>
</div>
@endsection
@section('js')
@endsection