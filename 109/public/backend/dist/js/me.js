var base_url = window.location.origin + '/';
var acp_name = 'hi';
var prefixUrl = '/';

function delete_all(controller, options = null){
    if (typeof options.type !== 'undefined') {
        var input_name = $("input[name='select_all_"+ options.type +"']");
    } else {
        var input_name = $("input[name='select_all']");
    }
    
    var listid="";
    input_name.each(function(){
        if (this.checked) listid = listid+","+this.value;
    })
    listid=listid.substr(1);	 
    if (listid=="") { toastr.error("Bạn chưa chọn mục nào"); return false;}
    hoi= confirm("Bạn có chắc chắn muốn xóa?");
    if (hoi==true) document.location = base_url + acp_name + "/" + controller + "/_deletemulty?listid=" + listid;
}

function show_all(controller, page){
    var listid="";
    $("input[name='select_all']").each(function(){
        if (this.checked) listid = listid+","+this.value;
    })
    listid=listid.substr(1);	 
    if (listid=="") { toastr.error("Bạn chưa chọn mục nào"); return false;}

    document.location = base_url + acp_name + "/" + controller + "/showmulty/" + page + "?listid=" + listid;
}

function hide_all(controller, page){
    var listid="";
    $("input[name='select_all']").each(function(){
        if (this.checked) listid = listid+","+this.value;
    })
    listid=listid.substr(1);	 
    if (listid=="") { toastr.error("Bạn chưa chọn mục nào"); return false;}

    document.location = base_url + acp_name + "/" + controller + "/hidemulty/" + page + "?listid=" + listid;
}

function up_streaming(controller){
    var listid="";
    $("input[name='select_all']").each(function(){
        if (this.checked) listid = listid+","+this.value;
    })
    listid=listid.substr(1);	 
    if (listid=="") { toastr.error("Bạn chưa chọn mục nào"); return false;}

    document.location = base_url + acp_name + "/" + controller + "/creatStreamMulty?listid=" + listid;
}

function delete_all_type(controller, type, page){
    if(!confirm("Xác nhận xóa?")) return false;
    var listid="";
    $("input[name='select_all']").each(function(){
        if (this.checked) listid = listid+","+this.value;
    })
    listid=listid.substr(1);	 
    if (listid=="") { toastr.error("Bạn chưa chọn mục nào"); return false;}

    document.location = base_url + acp_name + "/" + controller + "/_deletemulty/" + type + "/" + page + "?listid=" + listid;
}

function show_all_type(controller, type, page){
    var listid="";
    $("input[name='select_all']").each(function(){
        if (this.checked) listid = listid+","+this.value;
    })
    listid=listid.substr(1);	 
    if (listid=="") { toastr.error("Bạn chưa chọn mục nào"); return false;}

    document.location = base_url + acp_name + "/" + controller +  "/showmulty/" + type + "/" + page + "?listid=" + listid;
}

function hide_all_type(controller, type, page){
    var listid="";
    $("input[name='select_all']").each(function(){
        if (this.checked) listid = listid+","+this.value;
    })
    listid=listid.substr(1);	 
    if (listid=="") { toastr.error("Bạn chưa chọn mục nào"); return false;}

    document.location = base_url + acp_name + "/" + controller +  "/hidemulty/" + type + "/" + page + "?listid=" + listid;
}

function validateURL(textval) {
    var urlregex = new RegExp(
        "^(http:\/\/|https:\/\/|ftp:\/\/|www.){1}([0-9A-Za-z]+\.)");
    return urlregex.test(textval);
}

function close_modal(){
    $('#model-cart-box').on('hidden.bs.modal', function () {
        document.location.reload();
    });
}

function reloadPage()
{
    return window.location.reload();
}

$(document).ready(function() {
    $('#load_news').click(function() {
        var id_site = $('#select_site').val();
        if(id_site == ''){
            toastr.error('Bạn chưa chọn site!');
            return false;
        }

        var url = $('#link_get_content').val();
        if(url == ''){
            toastr.error('Bạn chưa nhập link!');
            return false;
        }
        if(validateURL(url) == false){
            toastr.error('URL không đúng định dạng.');
            return false;
        }
        $.ajax({
            type: 'GET',
            url: base_url + '/' + acp_name + '/url_config/loadnews/'+id_site + prefixUrl,
            data: {'url':url},
            dataType:'json',
            beforeSend: function(){
                $('.load_pag_inx').fadeIn();
            },
            success: function(data){
                toastr.success(data.error);
                $('#select_article').html(data.content);
                $('.load_pag_inx').fadeOut();
            }
        });
        return false;
    });

    // $('.alert').fadeOut(10000);


    $('#select_article').change(function(){
        var id_site = $('#select_site').val();
        var title = $(this).val();
        var summary = $(this).find(':selected').data('summary');
        var img = $(this).find(':selected').data('img');
        var imgLarge = img;
        $.ajax({
            type: 'POST',
            url: base_url +  '/' + acp_name + '/url_config/getimgreplace' + prefixUrl,
            data: {'id':id_site},
            success: function(result){
                if(result != ''){
                    result = jQuery.parseJSON(result);
                    imgLarge = img.replace(result.tag, result.str);
                }
            },
            async: false
        });
        var href = $(this).find(':selected').data('href');
        var site = $(this).find(':selected').data('site');
        var first_href = href.split('/');
        first_href = first_href[0];
        if(first_href != 'http:' && first_href != 'https:'){
            href = site + href;
        }

        $('#name').val(title);
        $('#title').val(title);
        $('#keywords').val(title);
        $('#description').val(summary);
        $('#summary').val(summary);
        $('#img_get_content').val(imgLarge);
        $('#img_get_content_show').html('<img width="100" src="'+imgLarge+'">');
        $.ajax({
            type: 'GET',
            url: base_url +  '/' + acp_name + '/url_config/loadcontent' + prefixUrl,
            data: {'id':id_site,'url':href},
            success: function(data){
                CKEDITOR.instances['content'].setData(data);
                CKEDITOR.instances['content'].updateElement();
            }
        });
        return false;
    });    
});

function addSubdomainUser() {
    $('.add-subdomain-user').on('click',function() {
        var id = $(this).data('id');
        $('#modalAddUser').find('.modal-body').load('/' + acp_name + '/subdomain/addUser', {'id':id}, function(result){
            $('#modalAddUser').modal({show:true});
            $('#modalAddUser').find('form').attr('action', '/' + acp_name + '/subdomain/saveUser/' + id);
        });
        saveUserSubdomain();
    });
}


function addSubdomainUserManage() {
    $('.add-subdomain-user-manage').on('click',function() {
        var id = $(this).data('id');
        $('#modalAddUserManage').find('.modal-body').load('/' + acp_name + '/subdomain/addUserManage', {'id':id}, function(result){
            $('#modalAddUserManage').modal({show:true});
            $('#modalAddUserManage').find('form').attr('action', '/' + acp_name + '/subdomain/saveUserManage/' + id);
        });
        saveUserSubdomainMange();
    });
}

function saveUserSubdomain() {
    $('#save-user-subdomain').click(function() {
        var action = $('#form-save-user').attr('action');
        $.ajax({
            type: 'POST',
            url: action + prefixUrl,
            data: $('#form-save-user').serialize(),
            success: function (result) {
                toastr.success('Thêm tên miền quản lý thành công!');
                window.location.reload();
            }
        });
        return false;
    })
}

function saveUserSubdomainMange() {
    $('#save-user-subdomain-manage').click(function() {
        var action = $('#form-save-user-manage').attr('action');
        $.ajax({
            type: 'POST',
            url: action + prefixUrl,
            data: $('#form-save-user-manage').serialize(),
            success: function (result) {
                toastr.success('Thêm user quản lý thành công!');
                window.location.reload();
            }
        });
        return false;
    })
}

$(document).ready(function () {
    $('body').on('click', '.create_website', function (e) {
        e.preventDefault();

        var url = $(this).data('url');

        if (!$('#model-cart-box').length) {
            var arr_model = [
                '<div class="modal fade has-loading" id="model-cart-box" tabindex="-1" role="dialog" aria-labelledby="ModalCartBox">',
                '<div class="modal-dialog modal-md" role="document">',
                '<div class="modal-content"></div>',
                '</div>',
                '</div>'
            ];

            $('body').append(arr_model.join(''));
            $('#model-cart-box').find('.modal-content').html('');
        }
        
        $('#model-cart-box').modal({show: true, backdrop: 'static', keyboard: false})  

        $.get(url).done(function(r) {
            if (!isJSON(r)) {
            	$('#model-cart-box').find('.modal-content').html(r);
            }
            
            add_alias_sub();
            submitCreateWesite();
        })
        .fail(function() {
            toastr.error('Error');
        });
    });

    
    $('body').on('click', '.add-domain', function (e) {
        e.preventDefault();

        var url = $(this).data('url');

        if (!$('#model-cart-box').length) {
            var arr_model = [
                '<div class="modal fade has-loading" id="model-cart-box" tabindex="-1" role="dialog" aria-labelledby="ModalCartBox">',
                '<div class="modal-dialog modal-md" role="document">',
                '<div class="modal-content"></div>',
                '</div>',
                '</div>'
            ];

            $('body').append(arr_model.join(''));
        }

        $('#model-cart-box').modal({show: true, backdrop: 'static', keyboard: false})  

        $.get(url).done(function(r) {
            $('#model-cart-box').find('.modal-content').html(r);
        })
            .fail(function() {
                toastr.error('Error');
            });
    });

    $('body').on('click', '.edit-domain', function (e) {
        e.preventDefault();

        var url = $(this).data('url');

        if (!$('#model-cart-box').length) {
            var arr_model = [
                '<div class="modal fade has-loading" id="model-cart-box" tabindex="-1" role="dialog" aria-labelledby="ModalCartBox">',
                '<div class="modal-dialog modal-md" role="document">',
                '<div class="modal-content"></div>',
                '</div>',
                '</div>'
            ];

            $('body').append(arr_model.join(''));
        }

        $('#model-cart-box').modal('show').addClass('loading');

        $.get(url).done(function(r) {
            $('#model-cart-box').removeClass('loading');
            $('#model-cart-box').find('.modal-content').html(r);
        })
            .fail(function() {
                toastr.error('Error');
            });
    });

    $('body').on('click', '.add-expired-domain', function (e) {
        e.preventDefault();

        var url = $(this).data('url');

        if (!$('#model-cart-box').length) {
            var arr_model = [
                '<div class="modal fade has-loading" id="model-cart-box" tabindex="-1" role="dialog" aria-labelledby="ModalCartBox">',
                '<div class="modal-dialog modal-md" role="document">',
                '<div class="modal-content"></div>',
                '</div>',
                '</div>'
            ];

            $('body').append(arr_model.join(''));
        }

        $('#model-cart-box').modal('show').addClass('loading');

        $.get(url).done(function(r) {
            $('#model-cart-box').removeClass('loading');
            $('#model-cart-box').find('.modal-content').html(r);
        })
            .fail(function() {
                toastr.error('Error');
            });
    });

    $('body').on('click', '.change-pass-user', function (e) {
        e.preventDefault();

        var url = $(this).data('url');

        if (!$('#model-cart-box').length) {
            var arr_model = [
                '<div class="modal fade has-loading" id="model-cart-box" tabindex="-1" role="dialog" aria-labelledby="ModalCartBox">',
                '<div class="modal-dialog modal-md" role="document">',
                '<div class="modal-content"></div>',
                '</div>',
                '</div>'
            ];

            $('body').append(arr_model.join(''));
        }

        $('#model-cart-box').modal('show').addClass('loading');

        $.get(url).done(function(r) {
            $('#model-cart-box').removeClass('loading');
            $('#model-cart-box').find('.modal-content').html(r);
        })
            .fail(function() {
                toastr.error('Error');
            });
    });

    $('body').on('submit', '#form-add-domain', function (e) {
        e.preventDefault();
        var form = $(this);
        var l = Ladda.create( document.querySelector( '.btn-add-domain' ) );
        l.start();
        $.ajax({
            type: 'POST',
            url: form.attr('action') + prefixUrl,
            data: form.serialize(),
            beforeSend:function() {
                $('#model-cart-box').find('.close').attr('disabled', 'disabled');
                $('#model-cart-box').find('.btn-close-form-domain').attr('disabled', 'disabled');
            },
            success: function (html) {
                $('#model-cart-box').find('.modal-content').html(html);
                $('#model-cart-box').find('.close').attr('disabled', 'false');
                $('#model-cart-box').find('.btn-close-form-domain').attr('disabled', 'false');
                close_modal();
            }
        }).always(function() {
            l.stop();
        })
    });

    $('body').on('submit', '#form-change-pass', function (e) {
        e.preventDefault();

        var form = $(this);

        $('#model-cart-box').addClass('loading');
        $.ajax({
            type: 'POST',
            url: form.attr('action') + prefixUrl,
            data: form.serialize(),
            success: function (r) {
                $('#model-cart-box').removeClass('loading');
                $('#model-cart-box .modal-content').html(r);
                close_modal();
            }
        })
    });

    $('body').on('click', '.config_module_css', function (e) {
        e.preventDefault();

        var url = $(this).data('url');
        var id = $(this).data('id');

        if (!$('#model-cart-box').length) {
            var arr_model = [
                '<div class="modal fade has-loading" id="model-cart-box" tabindex="-1" role="dialog" aria-labelledby="ModalCartBox">',
                '<div class="modal-dialog modal-md" role="document">',
                '<div class="modal-content"></div>',
                '</div>',
                '</div>'
            ];

            $('body').append(arr_model.join(''));
        }

        $('#model-cart-box').modal('show').addClass('loading');

        $.ajax({
            type: 'POST',
            url: url + prefixUrl,
            data: {'id':id},
            success:function (html) {
                $('#model-cart-box').removeClass('loading');
                $('#model-cart-box').find('.modal-content').html(html);
                $('.my-colorpicker1').colorpicker()
            }
        })


    });

    $('body').on('submit', '#form-add-expired-domain', function (e) {
        e.preventDefault();

        var form = $(this);

        $('#model-cart-box').addClass('loading');
        $.ajax({
            type: 'POST',
            url: form.attr('action') + prefixUrl,
            data: form.serialize(),
            success: function (r) {
                if (r == 0) {
                    toastr.error('Bạn không đủ tiền để gia hạn website. Vui lòng nạp thêm tiền hoặc liên hệ nhà phát triển.');
                    return false;
                }

                toastr.success('Gia hạn website thành công');
                $('#model-cart-box').modal('toggle');
                window.location.reload();
                return false;
            }
        })
    });

    // setLayout();
})

function submitCreateWesite() {
	$('#form-create-domain').submit(function (e) {
        e.preventDefault();

        var form = $(this);

        $('#model-cart-box').addClass('loading');
        $.ajax({
            type: 'POST',
            url: form.attr('action') + prefixUrl,
            data: form.serialize(),
            beforeSend:function(){
                $('#form-subdomain-loading').show();
            },
            success: function (result) {
            	$('#model-cart-box').removeClass('loading');
                if (!isJSON(result)) {
                    $('#model-cart-box .modal-content').html(result);
                    submitCreateWesite();
                }
                add_alias_sub();
            	try {
            		if (result != '') {
            			if (isJSON(result)) {
            				var json = JSON.parse(result);
            				if (json.code == 1) {
						        window.location.href = '/' + acp_name;
						        
                                /*$.ajax({
                                    type: 'POST',
                                    url: '/' + acp_name + '/subdomain/createCronJobs',
                                    data: {'id':json.id},
                                    success:function(res){
                                        if (res == 0) {
                                            $('#form-subdomain-loading').hide();
                                            toastr.success('Tạo website thành công!');
                                            window.location.href = '/' + acp_name;
                                        } else {
                                            toastr.error('Đã xảy ra lỗi . Vui lòng thử lại!');
                                            window.location.href = '/' + acp_name;
                                        }
                                    }
                                });*/
						    }
            			}
            		}
				}
				catch (err) {
				  console.log(err);
				}
                
            }
        })
    });
}

$(function () {
    //Colorpicker
    $('.my-colorpicker1').colorpicker()
    //color picker with addon
    $('.my-colorpicker2').colorpicker();

    $('.sidebar-menu').slimScroll({
        height: $(window).height() - 64 + 'px',
        color: '#fff'
    });

    /*
    $('#collapse_left').slimScroll({
        height: '500px',
        color: '#fff'
    });

    $('#collapse_center').slimScroll({
        height: '564px',
        color: '#fff'
    });

    $('#collapse_right').slimScroll({
        height: '500px',
        color: '#fff'
    });
    */
})

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

    //auto size textarea
    autosize($('textarea'));

    add_alias();
    setLayout();
    selectLayoutConfigModule();
    add_module_position();
    tmp_layout_show();
    tmp_layout_hide();
    deleteBackgroundPhoto();
    resetPageCss();
    setBgrDefault();
    select_background();
    save_position();
    enable_logo_text();
    delete_tmp_layout_module();
    saveNote();
    saveNoteCustomerMessage();
    saveNoteFormItem();
    saveNoteOrderPage();
    subdomain_filter();
    changeBalaceUser();
    viewUserHistory();
    viewUserHistoryTranfer();
    dateExpired();
    changeDomainInterface();
    checkDeleteProductDetail();
    searchSubdomain();
    copyWebsite();
    changeSharePrice();
    updateCopyright();
    addSubdomainUser();
    addSubdomainUserManage();
    viewConfigGuide();
    viewUrlAccessIp();
    saveIpNote();
    saveContactNote();
    updateProductPrice();
    pagination_ajax();
    ajax_rating();
    updateProductElementDetailColor();
    activeSsl();
    // checkDuplicate();

    $('#submit-posttype-page').click(function () {
        if($('.menu-item-checkbox:checked').length == 0){
            toastr.error('Bạn chưa chọn trang nào');
            return false;
        }
    });

    $('#submit-posttype-page-static').click(function () {
        var static_name = $('#static_name').val();
        var static_url = $('#static_url').val();
        if(static_name == ''){
            toastr.error('Bạn chưa nhập tên menu');
            return false;
        }
        if(validateURL(static_url) == false){
            toastr.error('URL không đúng định dạng.');
            return false;
        }
    })
});

function pagination_ajax() {
    $('.pagination-ajax a').click(function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var page = $(this).data('page');
        var html_id = $(this).closest('.pagination-ajax').data('id');
        $.ajax({
            type: 'POST',
            url: url + prefixUrl,
            data: {'page':page},
            beforeSend:function() {
            },
            success:function(html) {
                $('#' + html_id).html(html);
                pagination_ajax();
            }
        })
    })
}

function searchSubdomain() {
    $('#btn-search-subdomain').click(function(e) {
        e.preventDefault();
        var keyword = $('#subdomainSearchFrm').find('input[name=keyword]').val();
        if (keyword == '') {
            toastr.error('Bạn chưa nhập từ khóa');
            $('#subdomainSearchFrm').find('input[name=keyword]').focus();
            return false;
        }
        var l = Ladda.create(this);
        l.start();
        $.ajax({
            type: 'POST',
            url: $('#subdomainSearchFrm').attr('action') + prefixUrl,
            data: {'keyword':keyword},
            success:function(result) {
                if(result != '') {
                    $('#subdomainListResult').html(result);
                    $('#nav-tabs-list-subdomain').hide();
                } else {
                    $('#subdomainListResult').html(`<div class="alert alert-warning">Không tìm thấy kết quả nào</div>`);
                    $('#nav-tabs-list-subdomain').show();
                }
                changeDomainInterface();
                copyWebsite();
                ajax_rating();
            }
        }).always(function() {
            l.stop();
        })
    })
}

function changeDomainInterface() {
    $('.change_domain_interface').click(function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var currentUrl = $(this).data('current');
        var copyUrl = $(this).data('copy');
        swal({
          title: 'Đổi giao diện website '+ currentUrl +' giống như ' + copyUrl + '?',
          text: 'Chú ý: Giao diện hiện tại của '+ currentUrl +' sẽ mất',
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'OK',
          cancelButtonText: 'Hủy',
          cancelButtonColor: '#d33'
        },  function(isConfirm) {
            if (!isConfirm) return;
            window.location.href = url;
        })
    })
}

function checkDeleteProductDetail() {
    $('.prdConfirmDelete').click(function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        swal({
          title: 'Chú ý!',
          text: "Bạn sẽ bị xóa toàn bộ nội dung trong chi tiết sản phẩm thuộc menu này!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'OK',
          cancelButtonText: 'Hủy',
          cancelButtonColor: '#d33'
        },  function(isConfirm) {
            if (!isConfirm) return;
            window.location.href = url;
        })
    })
}

function activeSsl() {
    $('.btn-active-ssl').click(function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var amount = $(this).data('amount');
        var domain = $(this).data('domain');
        swal({
          title: 'Chú ý!',
          text: 'Bạn sẽ bị trừ '+ amount +' khi kích hoạt ssl cho tên miền '+ domain,
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'OK',
          cancelButtonText: 'Hủy',
          cancelButtonColor: '#d33'
        },  function(isConfirm) {
            if (!isConfirm) return;
            window.location.href = url;
        })
    })
}

function dateExpired() {
    $('.expired_date').datepicker({
        format: 'dd/mm/yyyy',
        autoclose: true
    }).on('changeDate', function(e) {
        var date = $(this).val();
        var id = $(this).data('id');
        $.ajax({
            type:'POST',
            url: '/' + acp_name + '/subdomain/changeDateExpired' + prefixUrl,
            data: {'id':id, 'date':date},
            success:function(result){
                if (result == 1) {
                    toastr.success('Cập nhật ngày hết hạn thành công');
                } else {
                    toastr.error('Error');
                }
            }
        });
    });
}

function saveNote() {
    $('#btn-save-note').click(function() {
        var note = [];
        $('textarea[name^="note"]').each(function(){
            var value = $(this).val();
            var name = $(this).attr('name');
            var id = parseInt(name.match(/[0-9]+/));
            note.push({'id':id, 'value':value});
        });
        console.log(note);
        $.ajax({
            type: 'POST',
            url: '/' + acp_name + '/subdomain/saveNote' + prefixUrl,
            data: {'note':note},
            success:function(result) {
                if (result == 1) {
                    toastr.success('Cập nhật ghi chú thành công');
                } else {
                    toastr.error('Error');
                }
            }
        })
    })
}

function saveNoteCustomerMessage() {
    $('#btn-save-note-customer-message').click(function() {
        var note = [];
        $('textarea[name^="customer_message_note"]').each(function(oneTag){
            var value = $(this).val();
            var name = $(this).attr('name');
            var id = parseInt(name.match(/[0-9]+/));
            note.push({'id':id, 'value':value});
        });
        console.log(note);
        $.ajax({
            type: 'POST',
            url: '/' + acp_name + '/customer_message/saveNote' + prefixUrl,
            data: {'note':note},
            success:function(result) {
                if (result == 1) {
                    toastr.success('Cập nhật ghi chú thành công');
                } else {
                    toastr.error('Error');
                }
            }
        })
    })
}

function saveNoteFormItem() {
    $('.btn-save-note-item').click(function() {
        var note = [];
        $('textarea[name^="form_item_note"]').each(function(){
            var value = $(this).val();
            var name = $(this).attr('name');
            var id = parseInt(name.match(/[0-9]+/));
            note.push({'id':id, 'value':value});
        });
        $.ajax({
            type: 'POST',
            url: '/' + acp_name + '/form_item/saveNote' + prefixUrl,
            data: {'note':note},
            success:function(result) {
                if (result == 1) {
                    toastr.success('Cập nhật ghi chú thành công');
                } else {
                    toastr.error('Error');
                }
            }
        })
    })
}

function saveNoteOrderPage()
{
    $('#btn-save-note-order-page').click(function() {
        var note = [];
        $('textarea[name^="customer_message_note"]').each(function(oneTag){
            var value = $(this).val();
            var name = $(this).attr('name');
            var id = parseInt(name.match(/[0-9]+/));
            note.push({'id':id, 'value':value});
        });
        $.ajax({
            type: 'POST',
            url: '/' + acp_name + '/customer_message/saveNote' + prefixUrl,
            data: {'note':note}
        });

        var note = [];
        $('textarea[name^="form_item_note"]').each(function(){
            var value = $(this).val();
            var name = $(this).attr('name');
            var id = parseInt(name.match(/[0-9]+/));
            note.push({'id':id, 'value':value});
        });
        $.ajax({
            type: 'POST',
            url: '/' + acp_name + '/form_item/saveNote' + prefixUrl,
            data: {'note':note}
        });

        toastr.success('Lưu ghi chú thành công!');
    });
}

function selectLayoutConfigModule() {
    $('input[type=radio][name=layout_id]').on('ifChecked', function(event){
        var layout = $(this).val();
        load_module_position(layout);
        $('#layout_id').html(layout);
    });
}

function add_module_position() {
    $('.add-to-module').click(function () {
        var position = $(this).data('position');
        var layout = $(this).data('layout');

        if($('.module_checkbox_'+ position +'_'+ layout +':checked').length == 0){
            toastr.error('Bạn chưa chọn module nào');
            return false;
        }

        var module_select = [];
        $('input[name="module_'+ position +'_'+ layout +'[]"]:checked').each(function(i){
            module_select[i] = $(this).val();
        });

        $.ajax({
            type: 'POST',
            url:  base_url + acp_name + "/module_item/addModuleToPosition" + prefixUrl,
            data: {'position': position, 'layout': layout, 'module_select': module_select},
            success: function (result) {
                if (result == 1) {
                    toastr.success('Thêm module thành công');
                    window.location.href= '/' + acp_name + '/setting/layout?active=' + layout
                }
            }
        })

    })
}

function load_module_position(layout) {
    $.ajax({
        type: 'POST',
        url:  base_url + acp_name + "/module_item/loadModulePosition" + prefixUrl,
        data: {'layout': layout},
        success: function (html) {
            $('#box_module_item').html(html);
            add_module_position();
            tmp_layout_show();
            tmp_layout_hide();
            deleteBackgroundPhoto();
            resetPageCss();
            setBgrDefault();
            select_background();
            save_position();

            //Colorpicker
            $('.my-colorpicker1').colorpicker();
            //color picker with addon
            $('.my-colorpicker2').colorpicker();
            
        }
    })
}

function save_position() {
    $('.btn-save-position').click(function(){
        var layout = $(this).data('layout');
        var position = $(this).data('position');
        var input_module_key = [];
        $('input[name="input_module_key_'+ position +'_'+layout+'[]"]:checked').each(function(i){
            input_module_key[i] = $(this).val();
        });
        var sort_module_key = $('input[name^=sort_module_key_'+ position +'_'+ layout +']').map(function(idx, elem) {
            return $(elem).val();
        }).get();
        $.ajax({
            type: 'POST',
            url: '/' + acp_name + '/tmp_layout_module/savePosition' + prefixUrl,
            data: {'layout':layout, 'position':position, 'sort_module_key':sort_module_key},
            success: function(result){
                if(result == 1) {
                    toastr.success('Cập nhật dữ liệu thành công');
                }
            }
        })
    })
}

function delete_tmp_layout_module() {
    $('.btn-delete-position').click(function(){
        var layout = $(this).data('layout');
        var position = $(this).data('position');
        var input_module_key = [];
        $('input[name="input_module_key_'+ position +'_'+ layout +'[]"]:checked').each(function(i){
            input_module_key[i] = $(this).val();
        });

        if(input_module_key.length == 0) {
            toastr.error('Bạn chưa chọn module nào');
            return false;
        }

        if (!confirm('Bạn muốn xóa module này?')) return false;

        $.ajax({
            type: 'POST',
            url: '/' + acp_name + '/tmp_layout_module/deleteMulty' + prefixUrl,
            data: {'layout':layout, 'position':position, 'input_module_key':input_module_key},
            success: function(result){
                if(result == 1) {
                    toastr.success('Xóa module thành công');
                    window.location.href= '/' + acp_name + '/setting/layout?active=' + layout
                }
            }
        })
    })
}

function tmp_layout_show() {
    $('.tmp_layout_show').click(function () {
        var layout = $(this).data('layout');
        var id = $(this).data('id');
        $.ajax({
            type: 'POST',
            url:  base_url + acp_name + "/tmp_layout_module/show" + prefixUrl,
            data: {'id': id},
            success: function (result) {
                if (result == 1) {
                    toastr.success('Hiện module thành công');
                    load_module_position(layout);
                }
            }
        })
    })
}

function tmp_layout_hide() {
    $('.tmp_layout_hide').click(function () {
        var layout = $(this).data('layout');
        var id = $(this).data('id');
        $.ajax({
            type: 'POST',
            url:  base_url + acp_name + "/tmp_layout_module/hide" + prefixUrl,
            data: {'id': id},
            success: function (result) {
                if (result == 1) {
                    toastr.success('Ẩn module thành công');
                    load_module_position(layout);
                }
            }
        })
    })
}

function deleteBackgroundPhoto() {
    $('.deletebackgroundPhoto i, .deletebackgroundPhoto p').click(function () {
        var layout = $(this).parent().data('layout');
        var id = $(this).parent().data('id');
        if (!confirm('Bạn có xóa hình ảnh Background?')) return false;
        $.ajax({
            type: 'POST',
            url:  base_url + acp_name + "/background/deletePhoto" + prefixUrl,
            data: {'id': id},
            success: function (result) {
                if (result == 1) {
                    toastr.success('Xóa hình ảnh background thành công');
                    $('#imageBackgorund').html('');
                    // load_module_position(layout);
                }
            }
        })
    })
}

function resetPageCss() {
    $('#resetPageCss').click(function () {
        var layout = $(this).data('layout');
        var id = $(this).data('id');
        if (!confirm('Bạn có muốn khôi phục thiết lập gốc?')) return false;
        $.ajax({
            type: 'POST',
            url:  base_url + acp_name + "/layout/resetPageCssAjax" + prefixUrl,
            data: {'id': id, 'layout':layout},
            success: function (result) {
                if (result == 1) {
                    toastr.success('Khôi phục thiết lập gốc thành công');
                    load_module_position(layout);
                }
            }
        })
    })
}

function selectAll(obj){
    var checkboxes = $('ul.list').find(':checkbox');
    if ($('.menu-item-checkbox:checked').length != $('.menu-item-checkbox').length){
        $('.menu-item-checkbox').parent('.icheckbox_square-blue').addClass('checked');
        checkboxes.prop('checked', true);
    }
    else{
        $('.menu-item-checkbox').parent('.icheckbox_square-blue').removeClass('checked');
        checkboxes.prop('checked', false);
    }
}

function update_layout_module_sort(id) {
    var sort = $('#sort_' + id).val();
    $.ajax({
        type: 'POST',
        url:  base_url + acp_name + "/layout/updatesort" + prefixUrl,
        data: {'id': id, 'sort':sort},
        success: function (r) {
            toastr.success('Cập nhật thứ tự module thành công');
            // window.location.reload();
        }
    })
}

function setLayout() {
    $('input[type=radio][name=selectLayout]').on('ifChecked', function(event){
        var layout_id = $(this).val();
        var subdomain_id = $(this).data('subdomain');
        var name = $(this).data('name');
        $.ajax({
            type: 'POST',
            url: '/' + acp_name + '/setting/setLayout' + prefixUrl,
            data: {'layout_id':layout_id, 'subdomain_id':subdomain_id},
            success: function (result) {
                console.log(result);
                if (result == 1) {
                    toastr.success('Chọn layout ' + layout_id + ' thành công cho tên miền ' + name);
                    window.location.href = '/' + acp_name + '/setting/layout?active=' + layout_id;
                }
            }
        })
    });
}

function setBgrDefault()
{
    $('.set_bgr_color').click(function(){
        var color = $(this).data('color');
        $('input[name=color]').val(color);
        $('.set_bgr_color').removeClass('active');
        $(this).addClass('active');
    });

    $('.set_bgr_text_color').click(function(){
        var color = $(this).data('color');
        $('input[name=text_color]').val(color);
        $('.set_bgr_text_color').removeClass('active');
        $(this).addClass('active');
    });

    $('.set_bgr_container').click(function(){
        var color = $(this).data('color');
        $('input[name=container]').val(color);
        $('.set_bgr_container').removeClass('active');
        $(this).addClass('active');
    });

    $('.set_bgr_bar_web_bgr').click(function(){
        var color = $(this).data('color');
        $('input[name=bar_web_bgr]').val(color);
        $('.set_bgr_bar_web_bgr').removeClass('active');
        $(this).addClass('active');
    });

    $('.set_bgr_bar_web_color').click(function(){
        var color = $(this).data('color');
        $('input[name=bar_web_color]').val(color);
        $('.set_bgr_bar_web_color').removeClass('active');
        $(this).addClass('active');
    });

    $('.set_bgr_txt_web_color').click(function(){
        var color = $(this).data('color');
        $('input[name=txt_web_color]').val(color);
        $('.set_bgr_txt_web_color').removeClass('active');
        $(this).addClass('active');
    });

    $('.set_bgr_container').click(function(){
        var color = $(this).data('color');
        $('input[name=container]').val(color);
        $('.set_bgr_container').removeClass('active');
        $(this).addClass('active');
    })
}

function select_background() {  
    $('.bgr_select a').click(function(){
        var bgr = $(this).data('bgr');
        $('.bgr_select a').removeClass('active');
        $(this).addClass('active');
        $('input[name=brg_select]').val(bgr)
    });
}

function enable_logo_text() {
    $('input[type=radio][name=enable_logo_text]').on('ifChanged', function(event){
        var value = $(this).val();
        if (value == 0) {
            $('#image_logo').show();
            $('#text_logo').hide();
        } else {
            $('#image_logo').hide();
            $('#text_logo').show();
        }
    });
}

function locdau(str){
    str= str.toLowerCase();
    str= str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g,"a");
    str= str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g,"e");
    str= str.replace(/ì|í|ị|ỉ|ĩ/g,"i");
    str= str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g,"o");
    str= str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g,"u");
    str= str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g,"y");
    str= str.replace(/đ/g,"d");
    str= str.replace(/!|@|\$|%|\’|\^|\*|\(|\)|\+|\=|\<|\>|\?|\/|,|\.|\:|\'| |\"|\&|\#|\[|\]|\;|\||\{|\}|~/g,"-");
    str= str.replace(/^\-+|\-+$/g,"-");
    str= str.replace(/\\/g,"-");
    str= str.replace(/-+-/g,"-");
    str= str.replace("-–-","-");
    str= str.replace("-–","-");

    return str;
}

function add_alias()
{
    $(".set_url").bind("change keyup input",function() {
        var obj = $(this).val();
        var als = to_slug(obj);
        $("input[name=slug]").val(als);
    });

}

function add_alias_sub()
{
    $(".sub-domain").bind("change keyup input",function() {
        var obj = $(this).val();
        $(".sub-username").val(obj);
        // $(".sub-password").val(obj);
    });
}

function to_slug(str)
{
    // Chuyển hết sang chữ thường
    str = str.toLowerCase();     
 
    // xóa dấu
    str = str.replace(/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/g, 'a');
    str = str.replace(/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/g, 'e');
    str = str.replace(/(ì|í|ị|ỉ|ĩ)/g, 'i');
    str = str.replace(/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/g, 'o');
    str = str.replace(/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/g, 'u');
    str = str.replace(/(ỳ|ý|ỵ|ỷ|ỹ)/g, 'y');
    str = str.replace(/(đ)/g, 'd');
 
    // Xóa ký tự đặc biệt
    str = str.replace(/([^0-9a-z-\s])/g, '');
 
    // Xóa khoảng trắng thay bằng ký tự -
    str = str.replace(/(\s+)/g, '-');
    str = str.replace(/(--+)/g, '-');
 
    // xóa phần dự - ở đầu
    str = str.replace(/^-+/g, '');
 
    // xóa phần dư - ở cuối
    str = str.replace(/-+$/g, '');
 
    // return
    return str;
}

function subdomain_filter()
{
    $('#subdomain_filter').bind("change",function() {
        var keyword = $(this).val();
        $.ajax({
            type: 'POST',
            url: '/' + acp_name + '/index/search' + prefixUrl,
            data: {'keyword':keyword},
            success:function(html) {
                if ($('#notiDomainExpire').length > 0) {
                    $('#notiDomainExpire').hide();
                }
                
                $('#subdomain_list').html(html);
                addSubdomainUser();
                addSubdomainUserManage();
                changeBalaceUser();
                viewUserHistory();
                viewUserHistoryTranfer();
                dateExpired();
                changeSharePrice();
                updateCopyright();
                ajax_rating();
                activeSsl();
                updateNoteSubdomain();
            }
        })
    });
}

function changeBalaceUser() {
    $('input[name=user_balance_txt]').change(function() {
        var id = $(this).data('id');
        var balance = $(this).val();
        if (balance == '') {
            toastr.error('Bạn chưa nhập số tiền');
            return false;
        } else if ($.isNumeric(balance) === false) {
            toastr.error('Định dạng không đúng. Bạn chỉ được nhập số');
            return false;
        }

        $.ajax({
            type: 'POST',
            url: '/' + acp_name + '/users/changeBalance' + prefixUrl,
            data: {'id':id, 'balance':balance},
            success:function(result) {
                if (result == 1) {
                    toastr.success('Cập nhật số dư thành công');
                } else {
                    toastr.error('Đã xảy ra lỗi . Vui lòng thử lại');
                }
            }
        })
    })
}

function changeSharePrice() {
    $('input[name=share_price]').change(function() {
        var id = $(this).data('id');
        var price = $(this).val();
        if (price == '') {
            toastr.error('Bạn chưa nhập số tiền');
            return false;
        } else if ($.isNumeric(price) === false) {
            toastr.error('Định dạng không đúng. Bạn chỉ được nhập số');
            return false;
        }

        $.ajax({
            type: 'POST',
            url: '/' + acp_name + '/subdomain/changeSharePrice' + prefixUrl,
            data: {'id':id, 'price':price},
            success:function(result) {
                if (result == 1) {
                    toastr.success('Cập nhật giá chia sẻ website thành công');
                } else {
                    toastr.error('Đã xảy ra lỗi . Vui lòng thử lại');
                }
            }
        })
    })
}

function updateCopyright() {
    $('input[name=copyright_subdomain]').change(function() {
        var id = $(this).data('id');
        var type = $(this).data('type');
        var value = $(this).val();
        if (value != '') {
            $.ajax({
                type: 'POST',
                url: '/' + acp_name + '/subdomain/updateCopyright' + prefixUrl,
                data: {'id':id, 'type':type, 'value':value},
                success:function(result) {
                    if (result == 1) {
                        toastr.success('Cập nhật thành công');
                    } else {
                        toastr.error('Đã xảy ra lỗi . Vui lòng thử lại');
                    }
                }
            })
        }
        
    })
}

function saveIpNote()
{
    $('textarea[name=ip_note]').change(function() {
        var ip = $(this).data('ip');
        var note = $(this).val();
        $.ajax({
            type: 'POST',
            url: '/' + acp_name + '/system/saveIpNote' + prefixUrl,
            data: {'ip':ip, 'note':note},
            success:function(result) {
                console.log(result);
                if (result == 1) {
                    toastr.success('Lưu ghi chú thành công');
                } else {
                    toastr.error('Đã xảy ra lỗi. Vui lòng thử lại');
                }
            }
        })
    })
}

function saveContactNote()
{
    $('textarea[name=contact_note]').change(function() {
        var id = $(this).data('id');
        var note = $(this).val();
        $.ajax({
            type: 'POST',
            url: '/' + acp_name + '/contact/saveNote' + prefixUrl,
            data: {'id':id, 'note':note},
            success:function(result) {
                console.log(result);
                if (result == 1) {
                    toastr.success('Lưu ghi chú thành công');
                } else {
                    toastr.error('Đã xảy ra lỗi. Vui lòng thử lại');
                }
            }
        })
    })
}

function viewUserHistory() {
    $('.btn-user-history').on('click',function() {
        var id = $(this).data('id');
        $('#modalViewUserHistory').find('.modal-body').load('/' + acp_name + '/users/viewUserHistory', {'id':id}, function(result){
            $('#modalViewUserHistory').modal({show:true});
        });
    });
}

function viewUrlAccessIp(elment) {
    $('.btn-view-url-access').on('click',function() {
        var ip = $(this).data('ip');
        var url = $(this).data('url');
        $('#modalViewUrlIp').modal({show:true});
        $('#modalViewUrlIp').find('.modal-title').html('Danh sách link truy cập <strong class="text-danger">' + ip + '</strong>')
        $('#modalViewUrlIp').find('.modal-body').load('/' + acp_name + '/system/viewListUrlIp', {'url':url}, function(result){
            $('#modalViewUrlIp').modal({show:true});
        });
    });
}

function viewUserHistoryTranfer() {
    $('.btn-user-history-transfer').on('click',function() {
        var id = $(this).data('id');
        $('#modalViewUserHistoryTransfer').find('.modal-body').load('/' + acp_name + '/users/viewUserHistoryTransfer', {'id':id}, function(result){
            $('#modalViewUserHistoryTransfer').modal({show:true});
        });
    });
}

function viewConfigGuide() {
    $('.view-config-guide').on('click',function() {
        var id = $(this).data('id');
        $('#modalViewConfigGuide').find('.modal-body').load('/' + acp_name + '/config_item/viewGuide', {'id':id}, function(result){
            $('#modalViewConfigGuide').modal({show:true});
        });
    });
}

function updateProductPrice()
{
    $('input.update-product-price').change(function() {
        var price = $(this).val();
        var type = $(this).data('type');
        var id = $(this).data('id');
        // if(Math.floor(price) == price && $.isNumeric(price)) {
            $.ajax({
                type: 'POST',
                url: '/' + acp_name + '/product/updatePrice' + prefixUrl,
                data: {'id':id, 'type':type, 'price':price},
                success:function(result) {
                    console.log(result);
                    if (result == 1) {
                        toastr.success('Cập nhật giá thành công');
                    }
                }
            })
        /*} else {
            toastr.error('Giá phải nhập định dạng >= 0');
            $(this).focus();
            return false;
        }*/
        
    });

    $('input.update-product-code').change(function() {
        var code = $(this).val();
        var id = $(this).data('id');
        var type = $(this).data('type');
        $.ajax({
            type: 'POST',
            url: '/' + acp_name + '/product/updateValue' + prefixUrl,
            data: {'id':id, 'code':code, 'type':type},
            success:function(result) {
                console.log(result);
                if (result == 1) {
                    toastr.success('Cập nhật giá trị thành công');
                }
            }
        })
    })
}

function checkDuplicate() {
    $('.btn-check-duplicate').click(function(e) {
        e.preventDefault();
        var data = {'id':$(this).data('id'),'table':$(this).data('table'),'url':$('input[name=slug]').val()}
        $.ajax({
            type: 'POST',
            url: '/' + acp_name + '/index/checkUrlDuplicate' + prefixUrl,
            data: data,
            success: function(result) {
                if (result == 0) {
                    var new_url = $('input[name=slug]').val() + '-' + makeid();
                    $('input[name=slug]').val(new_url);
                }
                $('form#form-fixed').submit();
            }
        })
    })

}

function makeid() {
  var text = "";
  var possible = "0123456789";

  for (var i = 0; i < 3; i++)
    text += possible.charAt(Math.floor(Math.random() * possible.length));

  return text;
}

function copyWebsite() {
    $('.copy-website').click(function() {
        //var websiteName = $(this).data('name');
        var id = $(this).data('id');
        $('#modalFormCopyWebsite').modal({show: true, backdrop: 'static', keyboard: false});
        //$('#modalFormCopyWebsite').find('#website-copy-name').attr('href','http://' + websiteName).html(websiteName);
        $('#modalFormCopyWebsite').find('.modal-content').load('/' + acp_name + '/subdomain/createFromDomainId/' + id,  function() {
            add_alias_sub();
            copyWebsiteSubmit();
        })
    })
}

function copyWebsiteSubmit() {
	$('#form-create-domain').submit(function(e) {
        e.preventDefault();
        var form = $(this);
        $.ajax({
            type: 'POST',
            url: form.attr('action') + prefixUrl,
            data: form.serialize(),
            beforeSend:function(){
                $('#form-subdomain-loading').show();
            },
            success: function (result) {
                if (result != 1) {
                	if (!isJSON(result)) {
                    	$('#modalFormCopyWebsite .modal-content').html(result);
                    	add_alias_sub();
                    }
                    copyWebsiteSubmit();
                }
                try {
                    if (result != '') {
                        if (isJSON(result)) {
                            var json = JSON.parse(result);
                            if (json.code == 1) {
                                window.location.href = '/' + acp_name;                                        
                                /*$.ajax({
                                    type: 'POST',
                                    url: '/' + acp_name + '/subdomain/createCronJobs',
                                    data: {'id':json.id},
                                    beforeSend:function(){
				                        $('#form-subdomain-loading').show();
				                    },
                                    success:function(res) {
                                    	if (res == 0) {
                                    		$('#form-subdomain-loading').hide();
                                        	toastr.success('Tạo website thành công!');
                                        	window.location.href = '/' + acp_name;
                                    	} else {
                                            toastr.error('Đã xảy ra lỗi . Vui lòng thử lại!');
                                            window.location.href = '/' + acp_name;
                                        }
                                    }
                                });*/

                                return false;
                            }
                        }
                    }
                }
                catch (err) {
                  console.log(err);
                }
                
            }
        })
    });
}

function ajax_rating() {
    $('.star-rating').rating(function(vote, event){
        $('.star-rating').one('click', function() {
            var subdomain_id = $(this).data('subdomain');
            var user_id = $(this).data('user');
            var total = $(this).data('total');
            var mark = $(this).data('mark');
            var new_rate = parseInt(total) - parseInt(mark) + parseInt(vote);
            $.ajax({
                type: "POST",
                url: '/' + acp_name + '/subdomain/updateRating' + prefixUrl,
                data: {'subdomain_id':subdomain_id, 'user_id':user_id, 'rate': vote},
                success:function(response) {
                    toastr.success('Bạn đã đánh giá là web đẹp, những web được đánh giá sẽ được xếp lên trên');
                    $('label.total_rate_' + subdomain_id).html(new_rate);
                    return false;
                },
                error:function(err) {
                    console.log(err)
                }
            });

            return false;
        });
    
        
    });
}

function updateProductElementDetailColor() {
    $('input[name=pelm_color]').change(function() {
        var id = $(this).data('id');
        var color = $(this).val();
        $.ajax({
            type: 'POST',
            url: '/' + acp_name +  '/product_element_detail/updateColor' + prefixUrl,
            data: {'id':id, 'color':color},
            success:function(result) {
                if (result == 1) {
                    $('.pelm_color_' + id).css({'background':color})
                }
            }
        })
    })
}

function isJSON(str) {
    if( typeof( str ) !== 'string' ) { 
        return false;
    }
    try {
        JSON.parse(str);
        return true;
    } catch (e) {
        return false;
    }
}

function FormatNumber(obj) {
    var strvalue;
    if (eval(obj)) strvalue = eval(obj).value;
    else strvalue = obj;
    var num;
    num = strvalue.toString().replace(/\$|\,/g, '');
    num = num.replace(".", "");
    if (isNaN(num)) num = "";
    sign = (num == (num = Math.abs(num)));
    num = Math.floor(num * 100 + 0.50000000001);
    num = Math.floor(num / 100).toString();
    for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3); i++) num = num.substring(0, num.length - (4 * i + 3)) + ',' + num.substring(num.length - (4 * i + 3));
    //return (((sign)?'':'-') + num);
    eval(obj).value = (((sign) ? '' : '-') + num);
}

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57)) return false;
    return true;
}

function copyToClipboard(element) {
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val($(element).text()).select();
  document.execCommand("copy");
  $temp.remove();
  toastr.success('Copy thành công');
}
