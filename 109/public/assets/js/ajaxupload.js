var acp_name = 'hi';

$(function () {
    'use strict';
    $('.fileupload').click(function() {
        var url = '/ajaxupload/server/php/' + '?type=' + $(this).data('type') + '&id=' + $(this).data('id') + '&folder=' + $(this).data('folder');
        var box_html = $('#files_' + $(this).data('type') + '_' + $(this).data('id'));
        $('.fileupload').fileupload({
            url: url,
            dataType: 'json',
            done: function (e, data) {
                // console.log(data.result.files[0]);
                var file = data.result.files[0];
                box_html.html('');
                box_html.html('<p><img src="'+ file.url +'"></p><a href="javascript:;" data-url="'+ file.url +'" data-type="'+ $(this).data('type') +'" data-id="'+ $(this).data('id') +'" class="delete_img_upload_editor"><i class="fa fa-times"></i></a>');
                if ($(this).data('id') == 'portrait') {
                    $('input[name=c_mgs_portrait_image]').val(file.url);
                }
                if ($(this).data('id') == 'certificate') {
                    $('input[name=c_mgs_certificate_image]').val(file.url);
                }
                if ($(this).data('id') == 'customer_photo') {
                    $('input[name=cc_f_photo]').val(file.url);
                }
                if ($(this).data('id') == 'usually_question') {
                    $('input[name=us_photo]').val(file.url);
                }

                deleteFileUpload();
            },
        }).prop('disabled', !$.support.fileInput)
            .parent().addClass($.support.fileInput ? undefined : 'disabled');
    })
    
});

$(document).ready(function() {
    deleteFileUpload();
})

function deleteFileUpload()
{
    $('.delete_img_upload_editor').click(function() {
        var url = $(this).data('url');
        var box_html = $('#files_' + $(this).data('type') + '_' + $(this).data('id'));
        var id = $(this).data('id');
        $.ajax({
            type: 'POST',
            url: '/' + acp_name + '/index/deleteFile',
            data: {'url':url},
            success:function(result) {
                if (result == 1) {
                    box_html.html('');
                    if (id == 'portrait') {
                        $('input[name=c_mgs_portrait_image]').val('');
                    }
                    if (id == 'certificate') {
                        $('input[name=c_mgs_certificate_image]').val('');
                    }
                    if (id == 'certificate') {
                        $('input[name=cc_f_photo]').val('');
                    }
                } else {
                    console.log('Fifle không tồn tại');
                }
            }
        })
    })
}