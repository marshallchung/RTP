<div class="flex flex-row flex-wrap text-center">
    {!! Form::label('name', '縣市') !!}
    @if($user->type != 'district')
    {!! Form::select('county_id', $counties, request('county_id'), ['class' => 'h-12 px-4 border-gray-300 rounded-md
    shadow-sm focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
    @else
    <span class="form-control form-control-static">{{ $user->name }}</span>
    {!! Form::hidden('county_id', $user->id) !!}
    @endif
</div>

<div class="flex flex-row flex-wrap text-center">
    {!! Form::label('unit', '單位') !!}
    {!! Form::text('unit', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300
    focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'required']) !!}
</div>

<div class="flex flex-row flex-wrap text-center">
    {!! Form::label('title', '職稱') !!}
    {!! Form::text('title', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300
    focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
</div>

<div class="flex flex-row flex-wrap text-center">
    {!! Form::label('name', '姓名') !!}
    {!! Form::text('name', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300
    focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full', 'required']) !!}
</div>

<div class="flex flex-row flex-wrap text-center">
    {!! Form::label('phone', '公務電話') !!}
    {!! Form::text('phone', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300
    focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
</div>

<div class="flex flex-row flex-wrap text-center">
    {!! Form::label('mobile', '行動電話 (選填)') !!}
    {!! Form::text('mobile', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300
    focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
</div>

<div class="flex flex-row flex-wrap text-center">
    {!! Form::label('email', '電子郵件') !!}
    {!! Form::text('email', null, ['class' => 'h-12 px-4 border-gray-300 rounded-md shadow-sm focus:border-cyan-300
    focus:ring focus:ring-cyan-200 focus:ring-opacity-50 w-full']) !!}
</div>
{!! Form::submit('送出', ['class' => 'w-full cursor-pointer hover:bg-teal-400 text-white flex justify-center items-center
h-12 bg-mainCyanDark rounded'])
!!}