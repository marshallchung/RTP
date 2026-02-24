@extends('admin.layouts.dashboard', [
'heading' => '各縣市深耕計畫網頁位址管理',

'breadcrumbs' => ['管理']
])

@section('title', '最新消息')

@section('inner_content')
<div class="flex flex-row flex-wrap">
    <table id="table" class="w-full bg-white border shadow-lg text-mainAdminTextGrayDark">
        <thead>
            <tr>
                <td class="p-2 border-r last:border-r-0">縣市</td>
                <td class="p-2 border-r last:border-r-0">網址</td>
                <td class="p-2 border-r last:border-r-0">功能</td>
            </tr>
        </thead>
        <tbody class="text-content text-mainAdminTextGrayDark">
            @foreach ($data as $topic)
            <tr year="{{ date('Y', strtotime($topic->created_at)) }}">
                <td class="p-2 border-r last:border-r-0">{{ $topic->name }}</td>
                <td class="p-2 border-r last:border-r-0"><a href="{{ $topic->url }}">{{ $topic->url }}</a></td>
                <td class="p-2 border-r last:border-r-0">
                    <a class="flex items-center justify-center w-20 text-sm text-white h-9 bg-mainCyanDark hover:bg-teal-400"
                        did="{{ $topic->id }}">編輯</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div id="divEdit" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">修改縣市網址</h5>
            </div>
            <form class="flex flex-row flex-wrap text-center" id="editForm" method="post"
                action="{{ route('admin.admin.editPublicUrls') }}">
                <div class="modal-body">
                    {{ csrf_field() }}
                    <input name="id" type="hidden" />
                    <label id="name"></label>
                    <input name="url" />
                </div>
                <div class="modal-footer">
                    <input type="submit"
                        class="px-4 text-sm text-white rounded cursor-pointer py-1.5 bg-mainCyanDark hover:bg-teal-400" />
                    <a
                        class="px-4 text-sm text-white rounded cursor-pointer py-1.5 bg-mainCyanDark hover:bg-teal-400 btnCloseModal">關閉</a>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="divDel" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">刪除工作項目</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            確定要刪除<p id="delTitle"></p>嗎？
            <form class="flex flex-row flex-wrap text-center" id="delForm" method="post"
                action="{{ route('admin.admin.delPublicTerms') }}">
                {{ csrf_field() }}
                <input name="id" type="hidden" />
                <div class="modal-footer">
                    <input type="submit"
                        class="px-4 text-sm text-white rounded cursor-pointer py-1.5 bg-mainCyanDark hover:bg-teal-400" />
                    <a
                        class="px-4 text-sm text-white rounded cursor-pointer py-1.5 bg-mainCyanDark hover:bg-teal-400 btnCloseModal">關閉</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    $(function() {
			$('#table').on('click', '.edit', function() {
				var url = $(this).parent().prev().text();
				var name = $(this).parent().prev().prev().text();
				var data_id = $(this).attr('did');
				$('#name').text(name);
				$('#editForm').find('[name="url"]').val(url);
				$('#editForm').find('[name="id"]').val(data_id);
				$('#divEdit').show();
			});

			$('body').on('click', '.btnCloseModal', function() {
				$(this).closest('.modal').hide();
			});
		});
</script>
<script src="{{ asset('scripts/generalIndex.js') }}"></script>
@endsection