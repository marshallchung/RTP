<a href="{{ route('user.show', $id) }}"
    class="px-4 text-sm text-white rounded cursor-pointer py-1.5 bg-mainCyanDark hover:bg-teal-400" title="會員資料">
    <i class="fa fa-search" aria-hidden="true"></i>
</a>
<a href="{{ route('user.edit', $id) }}"
    class="px-4 text-sm text-white rounded cursor-pointer py-1.5 bg-mainCyanDark hover:bg-teal-400" title="編輯會員">
    <i class="w-3 h-3 i-fa6-solid-pen-to-square" aria-hidden="true"></i>
</a>
{!! Form::open(['route' => ['user.destroy', $id], 'style' => 'display: inline', 'method' => 'DELETE', 'onSubmit' =>
"return confirm('確定要刪除此會員嗎？');"]) !!}
<button type="submit" class="btn btn-danger" title="刪除會員">
    <i class="i-fa6-solid-rotate" aria-hidden="true"></i>
</button>
{!! Form::close() !!}