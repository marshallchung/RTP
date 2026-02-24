<div x-show="showDeleteModel" x-transition id="js-delete-modal"
    class="overflow-auto overflow-y-scroll fixed hidden inset-0 z-[1050] outline-0 bg-black/30"
    :class="{'block':showDeleteModel,'hidden':!showDeleteModel}">
    <div class="duration-700 w-[350px] my-8 mx-auto relative flipInX">
        <div class="relative bg-white rounded shadow modal-content">
            <h5 class="bg-orange-600 text-white mb-5 pt-5 pb-4 rounded-tl rounded-tr text-center min-h-[16.5px]">
                請注意!</h5>
            <div class="px-5 text-center modal-body text-mainGrayDark">此動作執行後不能恢復，確定要刪除嗎?</div>
            <div class="flex flex-row items-center justify-center py-6 space-x-8 rounded-bl rounded-br modal-footer">
                <button type="button" @click="showDeleteModel=false"
                    class="flex items-center justify-center w-20 text-sm border rounded h-9 bg-mainLight text-mainTextGray">取消</button>
                <button type="submit"
                    class="flex items-center justify-center w-20 text-sm text-white bg-orange-600 border rounded hover:bg-orange-500 h-9 text-mainTextGray">確定</button>
            </div>
        </div>
    </div>
</div><?php /**PATH /Users/Marshall/Downloads/RTP-main/resources/views/admin/layouts/partials/genericDeleteForm.blade.php ENDPATH**/ ?>