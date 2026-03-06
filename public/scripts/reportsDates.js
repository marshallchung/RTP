(function() {
  'use strict';
  var $;

  $ = jQuery;

  $('.js-datepicker').datepicker({
    todayHighlight: true,
    autoclose: true,
    format: 'yyyy-mm-dd'
  });

  $('.js-timepicker').timepicker({
    showMeridian: false
  });

}).call(this);

//# sourceMappingURL=reportsDates.js.map
