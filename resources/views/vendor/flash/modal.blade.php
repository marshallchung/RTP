<div id="flash-overlay-modal" class="modal fade {{ isset($modalClass) ? $modalClass : '' }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                <h4 class="modal-title">{{ $title }}</h4>
            </div>

            <div class="modal-body">
                <p>{!! $body !!}</p>
            </div>

            <div class="modal-footer">
                <button type="button"
                    class="flex items-center justify-center w-20 h-10 bg-gray-100 border border-gray-300 text-mainAdminTextGrayDark cursor-pointer hover:bg-gray-50 "
                    data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>