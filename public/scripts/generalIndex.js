(function() {
  'use strict';
  var $;

  $ = jQuery;

  $('.js-toggle-active-btn').on('click', function() {
    var btn, route, state, token;
    btn = $(this);
    route = btn.data('route');
    state = btn.hasClass('label-success') ? true : false;
    token = $('#js-token').text();
    return $.ajax({
      method: 'POST',
      url: route,
      data: {
        _method: 'PUT',
        _token: token,
        active: state === true ? 0 : 1
      }
    }).done(function(data) {
      return btn.toggleClass('label-success').text(state === true ? '否' : '是');
    });
  });

}).call(this);

//# sourceMappingURL=generalIndex.js.map
