@extends('admin.layouts.dashboard', [
'heading' => '縣市順序管理',
'breadcrumbs' => ['管理']
])

@section('title', '縣市順序管理')

@section('inner_content')
<div class="flex flex-col items-start justify-start w-full p-4 text-mainAdminTextGrayDark">
    <table id="table" class="w-full max-w-3xl border shadow-lg text-mainAdminTextGrayDark">
        <thead>
            <tr class="border-b bg-mainLight">
                <th class="p-2 font-normal text-left border-r last:border-r-0"></th>
                <th class="p-2 font-normal text-left border-r last:border-r-0"><input type="submit" form="editForm"
                        class="px-4 text-sm text-white rounded cursor-pointer py-1.5 bg-mainCyanDark hover:bg-teal-400"
                        value="修改順序" /></th>
            <tr class="border-b bg-mainLight">
                <th class="p-2 font-normal text-left border-r last:border-r-0">縣市名稱</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0">排序</th>
            </tr>
        </thead>
        <tbody class="text-content text-mainAdminTextGrayDark">
            <form class="flex flex-row flex-wrap text-center" id="editForm" method="post"
                action="{{ route('admin.admin.editCountyOrder') }}">
                {{ csrf_field() }}
                <input name="id" type="hidden" />
                @foreach ($data as $topic)
                <tr year="{{ date('Y', strtotime($topic->created_at)) }}" class="border-b last:border-b-0">
                    <td class="p-2 border-r last:border-r-0">{{ $topic->name }}</td>
                    <td class="p-2 border-r last:border-r-0">
                        <input name="countyList[{{ $topic->id }}]" id="test{{ $topic->id }}"
                            class="h-10 px-2 text-sm border-gray-300 rounded-md shadow-sm w-52 focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50"
                            value="{{ $topic->sort_order }}">
                    </td>
                </tr>
                @endforeach
            </form>
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    $(function() {
			$('#table').on('click', '.edit', function() {
				var title = $(this).parent().prev().text();
				var data_id = $(this).attr('did');
				$('#editForm').find('[name="title"]').val(title);
				$('#editForm').find('[name="id"]').val(data_id);
				$('#editTitle').text(title);
				$('#divEdit').show();
			});

			$('#table').on('click', '.delete', function() {
				var title = $(this).parent().prev().text();
				var data_id = $(this).attr('did');
				$('#delTitle').text(title);
				$('#delForm').find('[name="id"]').val(data_id);
				$('#divDel').show();
			});

			$('body').on('click', '.btnCloseModal', function() {
				$(this).closest('.modal').hide();
			});

			$('#btnCreate').click(function() {
				$('#divCreate').show();
			});
		});
</script>
<script src="{{ asset('scripts/generalIndex.js') }}"></script>
@endsection