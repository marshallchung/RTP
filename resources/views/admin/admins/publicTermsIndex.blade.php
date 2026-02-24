@extends('admin.layouts.dashboard', [
'heading' => '民眾版簡介分類項目管理',
'breadcrumbs' => ['管理']
])

@section('title', '最新消息')
@section('inner_content')
<div x-data="{
    modalData:{
        mode:'',
        show:false,
        title:'',
        id:'',
    },
    closeModal(){
        this.modalData.mode='';
        this.modalData.show=false;
        this.modalData.title='';
        this.modalData.id='';
    },
    showModel(e){
        this.modalData.mode=e.target.dataset.mode;
        this.modalData.show=true;
        this.modalData.title=e.target.dataset.title;
        this.modalData.id=e.target.dataset.id;
    },
}" class="flex flex-col items-start justify-start w-full p-4 text-mainAdminTextGrayDark">
    <div class="w-full py-2 text-center">
        <button @click="showModel" type="button" data-mode="create" data-id="" data-title="" id="btnCreate"
            class="px-4 text-sm text-white rounded cursor-pointer py-1.5 bg-lime-500 hover:bg-lime-400">新增</button>
    </div>
    <table id="table" class="w-full max-w-3xl border shadow-lg text-mainAdminTextGrayDark">
        <thead>
            <tr class="border-b bg-mainLight">
                <th class="p-2 font-normal text-left border-r last:border-r-0">分類項目</th>
                <th class="p-2 font-normal text-left border-r last:border-r-0"></th>
            </tr>
        </thead>
        <tbody class="text-content text-mainAdminTextGrayDark">
            @foreach ($data as $topic)
            <tr year="{{ date('Y', strtotime($topic->created_at)) }}" class="border-b last:border-b-0">
                <td class="p-2 border-r last:border-r-0">{{ $topic->name }}</td>
                <td class="p-2 border-r last:border-r-0">
                    <div class="flex flex-row items-center justify-center space-x-2">
                        <button @click="showModel" type="button" data-mode="edit" data-id="{{ $topic->id }}"
                            data-title="{{ $topic->name }}"
                            class="flex items-center justify-center w-20 text-sm text-white h-9 bg-mainCyanDark hover:bg-teal-400"
                            did="{{ $topic->id }}">編輯</button>
                        <button @click="showModel" type="button" data-mode="delete" data-id="{{ $topic->id }}"
                            data-title="{{ $topic->name }}"
                            class="flex items-center justify-center w-20 text-sm text-white h-9 bg-rose-600 hover:bg-rose-500"
                            did="{{ $topic->id }}">刪除</button>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div x-show.transition="modalData.show"
        class="fixed inset-0 flex-col justify-start items-center pt-[30vh] bg-black/30 z-[10050] hidden"
        :class="{'hidden':!modalData.show,'flex':modalData.show}">
        <div @click.away="closeModal()"
            class="z-10 flex flex-col items-center justify-center w-full max-w-md bg-white rounded-lg shadow-lg text-mainGrayDark">
            <div class="flex flex-row items-center justify-center w-full px-6 py-4 rounded-t-lg bg-mainLight">
                <h4 class="modal-title"><span
                        x-text="modalData.mode === 'edit'?'修改':(modalData.mode === 'create'?'新增':'刪除')"></span><span>民眾版簡介分類項目</span>
                </h4>
            </div>
            <template x-if="modalData.mode === 'edit'">
                <form class="flex flex-col items-center justify-center w-full p-5 space-y-4" id="editForm" method="post"
                    action="{{ route('admin.admin.editPublicTerms') }}">
                    <div class="modal-body flex justify-center items-center min-h-[6rem]">
                        {{ csrf_field() }}
                        <input x-model="modalData.id" name="id" type="hidden" />
                        <input x-model="modalData.title" name="title" placeholder="分類項目"
                            class="w-64 h-12 px-4 border border-gray-300 rounded-md shadow-sm placeholder:text-mainGray focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50" />
                    </div>
                    <div class="flex flex-row items-center w-full pb-4 justify-evenly">
                        <button type="submit"
                            class="px-4 text-sm text-white rounded cursor-pointer py-1.5 bg-mainCyanDark hover:bg-teal-400">修改</button>
                        <button type="button" @click="closeModal"
                            class="px-4 text-sm rounded cursor-pointer py-1.5 bg-gray-100 hover:bg-gray-50 border border-gray-300 btnCloseModal">關閉</button>
                    </div>
                </form>
            </template>
            <template x-if="modalData.mode === 'create'">
                <form class="flex flex-col items-center justify-center w-full p-5 space-y-4" id="editForm" method="post"
                    action="{{ route('admin.admin.createPublicTerms') }}">
                    <div class="modal-body flex justify-center items-center min-h-[6rem] w-full">
                        {{ csrf_field() }}
                        <input x-model="modalData.title" name="title" placeholder="分類項目"
                            class="h-12 px-4 border border-gray-300 rounded-md shadow-sm w-72 placeholder:text-mainGray focus:border-cyan-300 focus:ring focus:ring-cyan-200 focus:ring-opacity-50" />
                    </div>
                    <div class="flex flex-row items-center w-full pb-4 justify-evenly">
                        <button type="submit"
                            class="px-4 text-sm text-white rounded cursor-pointer py-1.5 bg-mainCyanDark hover:bg-teal-400">新增</button>
                        <button type="button" @click="closeModal"
                            class="px-4 text-sm rounded cursor-pointer py-1.5 bg-gray-100 hover:bg-gray-50 border border-gray-300 btnCloseModal">關閉</button>
                    </div>
                </form>
            </template>
            <template x-if="modalData.mode === 'delete'">
                <form class="flex flex-col items-center justify-center w-full p-5 space-y-4" id="delForm" method="post"
                    action="{{ route('admin.admin.delPublicTerms') }}">
                    {{ csrf_field() }}
                    <div class="modal-body flex justify-center items-center min-h-[6rem]">
                        確定要刪除 <strong><span id="delTitle" x-text="'『'+modalData.title+'』'"></span></strong> 嗎？
                    </div>
                    <input x-model="modalData.id" name="id" type="hidden" />
                    <div class="flex flex-row items-center w-full pb-4 justify-evenly">
                        <button type="submit"
                            class="px-4 text-sm text-white rounded cursor-pointer py-1.5 bg-mainCyanDark hover:bg-teal-400">刪除</button>
                        <button type="button" @click="closeModal"
                            class="px-4 text-sm rounded cursor-pointer py-1.5 bg-gray-100 hover:bg-gray-50 border border-gray-300 btnCloseModal">關閉</button>
                    </div>
                </form>
            </template>
        </div>
    </div>
</div>

@endsection

@section('scripts')
@endsection