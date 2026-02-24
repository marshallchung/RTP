(function() {
  'use strict';
  var $, reportUrl, reportYear;

  $ = jQuery;

  reportYear = $('#js-year-input').val();

  reportUrl = $('#js-current-route').val();

  $('#js-year-select').on('change', function() {
    var year;
    year = $(this).val();
    return window.location.href = reportUrl + "/" + year;
  });

  if (reportYear) {
    $('#js-year-select').val(reportYear);
  }

}).call(this);

//# sourceMappingURL=reportsIndex.js.map
