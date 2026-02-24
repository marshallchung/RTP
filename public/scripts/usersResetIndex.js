(function() {
  'use strict';
  var $;

  $ = jQuery;

  $('.js-show-confirm').on('click', function() {
    var confirmBtn, showConfirmBtn;
    showConfirmBtn = $(this);
    confirmBtn = showConfirmBtn.parents('form').find(':submit');
    showConfirmBtn.addClass('hidden');
    return confirmBtn.removeClass('hidden');
  });

}).call(this);

//# sourceMappingURL=usersResetIndex.js.map
