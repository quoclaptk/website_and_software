/*$(function () {
    'use strict';
    var url = window.location.hostname === 'blueimp.github.io' ?
                '//jquery-file-upload.appspot.com/' : '/ajaxupload/server/php/';
    
    $('#fileuploadLogo').fileupload({
        url: url,
        dataType: 'json',
        done: function (e, data) {
            console.log(data.result.files);
            console.log(data.result.files[0]);
            $('#fileuploadLogoResult').html('<p><img src="'+ data.result.files[0].url +'" class="img-responsive"></p>');    
            $('input[name="banner_logo"]').val(data.result.files[0].name);
        },
    }).prop('disabled', !$.support.fileInput).parent().addClass($.support.fileInput ? undefined : 'disabled');

    $('#fileuploadBannerBgr').fileupload({
        url: url,
        dataType: 'json',
        done: function (e, data) {
            console.log(data.result.files);
            console.log(data.result.files[0]);
            $('#fileuploadBannerBgrResult').html('<p><img src="'+ data.result.files[0].url +'" class="img-responsive"></p>');    
            $('input[name="banner_bgr"]').val(data.result.files[0].name);
        },
    }).prop('disabled', !$.support.fileInput).parent().addClass($.support.fileInput ? undefined : 'disabled');

    $('#fileuploadBanner1').fileupload({
        url: url,
        dataType: 'json',
        done: function (e, data) {
            console.log(data.result.files);
            console.log(data.result.files[0]);
            $('#fileuploadBanner1Result').html('<p><img src="'+ data.result.files[0].url +'" class="img-responsive"></p>');
            $('input[name="banner_1"]').val(data.result.files[0].name);   
        },
    }).prop('disabled', !$.support.fileInput).parent().addClass($.support.fileInput ? undefined : 'disabled');

    $('#fileuploadBanner2').fileupload({
        url: url,
        dataType: 'json',
        done: function (e, data) {
            console.log(data.result.files);
            console.log(data.result.files[0]);
            $('#fileuploadBanner2Result').html('<p><img src="'+ data.result.files[0].url +'" class="img-responsive"></p>');
            $('input[name="banner_2"]').val(data.result.files[0].name);    
        },
    }).prop('disabled', !$.support.fileInput).parent().addClass($.support.fileInput ? undefined : 'disabled');

    $('#fileuploadBanner3').fileupload({
        url: url,
        dataType: 'json',
        done: function (e, data) {
            console.log(data.result.files);
            console.log(data.result.files[0]);
            $('#fileuploadBanner3Result').html('<p><img src="'+ data.result.files[0].url +'" class="img-responsive"></p>');
            $('input[name="banner_3"]').val(data.result.files[0].name);  
        },
    }).prop('disabled', !$.support.fileInput).parent().addClass($.support.fileInput ? undefined : 'disabled');
});
*/
$(document).ready(function(){
    $('#create_banner').click(function(){
        var banner_logo = $('input[name=banner_logo]').val();
        var banner_bgr = $('input[name=banner_bgr]').val();
        var banner_1 = $('input[name=banner_1]').val();
        var banner_2 = $('input[name=banner_2]').val();
        var banner_3 = $('input[name=banner_3]').val();
        var banner_html = $('input[name=banner_html]:checked').val();

        if (banner_logo == '') {
            alert('Bạn chưa up logo');
            return false;
        }

        if (banner_bgr == '') {
            alert('Bạn chưa up hình hình nền');
            return false;
        }

        if (banner_1 == '') {
            alert('Bạn chưa up hình hình công ty 1');
            return false;
        }

        if (banner_2 == '') {
            alert('Bạn chưa up hình hình công ty 2');
            return false;
        }

        if (banner_3 == '') {
            alert('Bạn chưa up hình hình công ty 3');
            return false;
        }

        $.ajax({
            type: 'POST',
            url: '/hi/banner_html/createBanner',
            data: {'banner_logo':banner_logo, 'banner_bgr':banner_bgr, 'banner_1':banner_1, 'banner_2':banner_2, 'banner_3':banner_3, 'banner_html':banner_html},
            success:function(result){
                console.log(result);
                $('#fileuploadLogoResult, #fileuploadBannerBgrResult, #fileuploadBanner1Result, #fileuploadBanner2Result, #fileuploadBanner3Result').html('');
                $('input[name=banner_logo]').val('');
                $('input[name=banner_bgr]').val('');
                $('input[name=banner_1]').val('');
                $('input[name=banner_2]').val('');
                $('input[name=banner_3]').val('');
                loadBanner();
            }
        })
    });


    /*$('.select_banner_html').bind('load', function(){
        var iframeID = $(this).attr('id');
        $(this).contents().unbind(); 
        $(this).contents().bind('click', function(){
          $('.select_banner_html').removeClass('active');
          $('#' + iframeID).addClass('active');
        });
    });*/


    editCss();
    add_css();
    copy_css();
})

function editCss() {
    $('.openModalEditCss').click(function(){
        var id = $(this).data('id');
        $('.modal-body').load('/hi/banner_html/editCss', {'id':id},function(result){
            $('#myModalEditCss').modal({show:true});
            $('#form-edit-css').attr('action', '/hi/banner_html/saveCss/' + id);
            save_css();
        });
    });
}

function save_css() {
    $('#save-css').click(function(){
        var action = $('#form-edit-css').attr('action');
        var css = $('#css').val();
        $.ajax({
            type: 'POST', 
            url: action, 
            data: {'css':css},
            success:function(result) {
                // load_banner();
                $('#myModalEditCss').modal('hide');
                window.location.reload();
            }
        });
    })
}

function loadBanner() {
    $('#list_banner_html').load('/hi/banner_html/loadBanner', function(){
        editCss();
    });
}

function add_css() {
    $('#add_css').click(function() {
        $.ajax({
            type: 'POST', 
            url: '/hi/banner_html/add', 
            success:function() {
                load_banner();
            }
        });
    })
}

function load_banner() {
    $('#load_baner_html').load('/hi/banner_html/list', function(){
        add_css();
        editCss();
    });
}

function copy_css() {
    $('input[name=banner_html_copy_id]').on('ifChecked', function(event) {
        var id = $(this).val();
        if (confirm('Bạn muốn copy css của banner này?')) {
            $.ajax({
                type: 'POST',
                url: '/hi/banner_html/copy',
                data: {'id':id},
                success:function(result) {
                    alert('Copy css thành công !');
                    window.location.reload();
                }
            })
        }
    })
}
