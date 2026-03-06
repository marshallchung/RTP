(function() {
  'use strict';
  var $;

  $ = jQuery;

  $('.js-remove-file').on('click', function(event) {
    event.preventDefault();
    return $('#js-file-input').removeClass('hidden');
  });

}).call(this);

//# sourceMappingURL=uploadForm.js.map
