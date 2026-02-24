(function () {
    'use strict';
    var $;

    $ = jQuery;

    window.init.push(function () {
        return $('.js-dropzone').dropzone({
            url: 'foobar',
            paraName: 'file'
        });
    });

}).call(this);

//# sourceMappingURL=reportForm.js.map