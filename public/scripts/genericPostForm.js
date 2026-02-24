(function () {
    'use strict';
    window.init.push(function () {
        if ($('html').hasClass('ie8') === false) {
            tinymce.init({
                content_css: "/css/tinymce.css",
                selector: '.js-wysiwyg',
                language: 'zh_TW',
                branding: false,
                plugins: 'autolink image link media table hr advlist lists textcolor colorpicker help anchor wordcount searchreplace visualblocks visualchars charmap emoticons code paste',
                // plugins: 'print preview fullpage searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount imagetools contextmenu colorpicker textpattern help',
                toolbar1: 'formatselect fontselect | bold italic underline strikethrough forecolor backcolor | link image media | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent | removeformat',
                image_advtab: true,
                height: 500,
                font_formats: '新細明體=PMingLiU; 標楷體=DFKai-sb; 微軟正黑體=Microsoft JhengHei; Andale Mono=andale mono,times; Arial=arial,helvetica,sans-serif; Arial Black=arial black,avant garde; Book Antiqua=book antiqua,palatino; Comic Sans MS=comic sans ms,sans-serif; Courier New=courier new,courier; Georgia=georgia,palatino; Helvetica=helvetica; Impact=impact,chicago; Symbol=symbol; Tahoma=tahoma,arial,helvetica,sans-serif; Terminal=terminal,monaco; Times New Roman=times new roman,times; Trebuchet MS=trebuchet ms,geneva; Verdana=verdana,geneva; Webdings=webdings; Wingdings=wingdings,zapf dingbats',
                paste_data_images: true,
                paste_as_text: true
            });
        }
        return $('.js-remove-file').on('click', function (event) {
            var file, id, removedFiles, removedFilesInput;
            event.preventDefault();
            removedFilesInput = $('#js-removed-files');
            removedFiles = JSON.parse(removedFilesInput.val());
            file = $(this).parents('.well');
            id = file.data('id');
            removedFiles.push(id);
            removedFilesInput.val(JSON.stringify(removedFiles));
            return file.remove();
        });
    });

}).call(this);

//# sourceMappingURL=genericPostForm.js.map
