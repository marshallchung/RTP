@extends('admin.layouts.dashboard', [
'heading' => '身份切換',
'breadcrumbs' => ['身份切換']
])

@section('title', '身份切換')

@section('inner_content')
<div class="flex flex-row flex-wrap">
    <form action="{{ route('admin.admin.switchRole') }}" method="post" />
    {{ csrf_field() }}
    <div class="md:w-67/100 xl:w-75/100">
        <div class="border-gray-200 mb-6 relative bg-white border rounded-sm">
            <div class="text-mainAdminTextGrayDark bg-gray-100 border-gray-200 border-b-2 pb-2 px-5 pt-3 relative">編輯
            </div>
            <div class="bg-white m-0 p-5">
                <div class="flex flex-row flex-wrap text-center">
                    <span><strong>當前身份</strong></span> &nbsp;
                    <span>{{ $role_now->display_name }}</span>
                </div>
                <div class="flex flex-row flex-wrap text-center">
                    <span><strong>原始身份</strong></span> &nbsp;
                    <span>{{ $role_origin->display_name }}</span>
                </div>
                <div class="flex flex-row flex-wrap text-center">
                    <label for="role">切換身份對象</label>
                    <select id="role"
                        class="inline-block w-auto align-middle bg-white border-gray-300 rounded-md shadow-sm placeholder:text-mainTextGray focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50"
                        name="roleId_switchTo">
                        @foreach ($roles as $role)
                        @if ($role->id >= $role_origin->id )
                        <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                        @endif
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="xl:w-25/100 xl:float-left md:w-1/3 relative">
        <div class="text-mainAdminTextGrayDark bg-gray-100 border-gray-200 border-b-2 pb-2 px-5 pt-3 relative">切換身份
        </div>
        <div class="bg-white m-0 p-5">
            <input type="submit" class="btn btn-block btn-lg btn-primary" value="切換" />
        </div>
    </div>
    </form>
</div>

<div id="js-token" class="hidden">{{ csrf_token() }}</div>
@endsection

@section('scripts')
<script src="{{ asset('scripts/generalIndex.js') }}"></script>
@endsection