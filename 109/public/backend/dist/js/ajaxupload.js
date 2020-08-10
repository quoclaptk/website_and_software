var acp_name = 'hi';
$(document).ready(function () {
    toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": false,
      "progressBar": false,
      "rtl": false,
      "positionClass": "toast-top-center",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": 300,
      "hideDuration": 1000,
      "timeOut": 3000,
      "extendedTimeOut": 300,
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut"
    }
});

$(function () {
    'use strict';
    // Change this to the location of your server-side upload handler:
    $('.fileupload').click(function() {
        var url;
        var box_html;
        var type = $(this).data('type');
        var id = $(this).data('id');
        var system_file = $(this).data('system');
        if (typeof(system_file) != 'undefined' && typeof(id) != 'undefined') {
            url = '/ajaxupload/server/php/' + '?system_file=' + system_file + '&id=' + id;
            box_html = '#files_' + system_file + '_' + id + ' .ajaxfileimg';
        } else if (typeof(type)  != 'undefined' && typeof(id) != 'undefined') {
            url = '/ajaxupload/server/php/' + '?type=' + type + '&id=' + id;
            box_html = '#files_' + type + '_' + id + ' .ajaxfileimg';
        } 

        $('.fileupload').fileupload({
            url: url,
            dataType: 'json',
            done: function (e, data) {
                
                $.each(data.result.files, function (index, file) {
                    // console.log(file);
                    $('<div class="pull-left text-center img-upload-editor">').html('<p><img src="'+ file.url +'"></p><a href="javascript:;" data-url="'+ file.url +'" class="delete_img_upload_editor"><i class="fa fa-times"></i></a>').appendTo(box_html);
                    deleteFileUpload();
                });
        
            },
            /*progressall: function (e, data) {
                var progress = parseInt(data.loaded / data.total * 100, 10);
                $('#progress .progress-bar').css(
                    'width',
                    progress + '%'
                );
            }*/
        }).on('fileuploadfail', function (e, data) {
            console.log(data);
            $.each(data.files, function (index) {
                toastr.error('Bạn phải up 1 hình ảnh và dung lượng không quá 500kb');
                // $(data.context.children()[index])
                //     .append('<br>')
                //     .append(error);
            });
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
        if (!confirm('Bạn muốn xóa hình này?')) return false;
        var url = $(this).data('url');
        $.ajax({
            type: 'POST',
            url: '/' + acp_name + '/index/deleteFile',
            data: {'url':url},
            success:function(result) {
                if (result == 1) {
                    // alert('Xóa file thành công');
                    window.location.reload();
                } else {
                    alert('Fifle không tồn tại');
                }
            }
        })
    })
}