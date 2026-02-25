<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    /**
     *
     * @param type string 'insertAfter' or 'insertBefore'
     * @param entityName
     * @param id
     * @param positionId
     */
    var changePosition = function (requestData) {
        $.ajax({
            'url': '<?php echo e(route('sort')); ?>',
            'type': 'POST',
            'data': requestData,
            'success': function (data) {
                if (data.success) {
                    // console.log('Saved!');
                } else {
                    console.error(data.errors);
                }
            },
            'error': function () {
                console.error('Something wrong!');
            }
        });
    };
    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var $sortableTable = $('.sortable');
        if ($sortableTable.length > 0) {
            $sortableTable.sortable({
                handle: '.sortable-handle',
                axis: 'y',
                update: function (a, b) {

                    var entityName = $(this).data('entityname');
                    var $sorted = b.item;

                    var $previous = $sorted.prev();
                    var $next = $sorted.next();

                    if ($previous.length > 0) {
                        changePosition({
                            // parentId: $sorted.data('parentid'),
                            type: 'moveAfter',
                            entityName: entityName,
                            id: $sorted.data('itemid'),
                            positionEntityId: $previous.data('itemid')
                        });
                    } else if ($next.length > 0) {
                        changePosition({
                            // parentId: $sorted.data('parentid'),
                            type: 'moveBefore',
                            entityName: entityName,
                            id: $sorted.data('itemid'),
                            positionEntityId: $next.data('itemid')
                        });
                    } else {
                        console.error('Something wrong!');
                    }
                },
                cursor: "move"
            });
        }
    });
</script>
<?php /**PATH /Users/Marshall/Desktop/RTP-main/resources/views/admin/layouts/partials/sortableScript.blade.php ENDPATH**/ ?>