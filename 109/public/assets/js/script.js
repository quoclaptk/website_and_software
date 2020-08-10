$(window).scroll(function() {
    $(this).scrollTop() > 300 ? $("#back-top").fadeIn() : $("#back-top").fadeOut();
    $(this).scrollTop() > 80 ? $('.main_menu_2').addClass('fixed') :  $('.main_menu_2').removeClass('fixed');
});

var prefixUrl = '/';

$("#back-top a").click(function() {
    return $("body,html").animate({
        scrollTop: 0
    }, 800), !1
});

$('.box_product_hot_slide').owlCarousel({
    loop:true,
    margin:30,
    nav:false,
    navText: ["",""],
    dots:true,
    autoplay:true,
    autoplayTimeout:2500,
    autoplayHoverPause:true,
    responsive:{
        0:{
            items:1,
            dots:false,
            loop:false,
        },
        380:{
            items:1,
            dots:false
        },
        480:{
            items:2,
        },
        768:{
            items:3,
        },
        992:{
            items:4
        }
    }
});

if (!$('.box_logo_company').hasClass('box_logo_company_center')) {
    $('.box_logo_company').owlCarousel({
        loop:true,
        margin:30,
        nav:true,
        navText: ["",""],
        dots:false,
        autoplay:true,
        autoplayTimeout:4000,
        autoplayHoverPause:true,
        responsive:{
            0:{
                items:1,
                dots:false,
                loop:false,
                nav:false
            },
            320:{
                items:2,
                dots:false
            },
            480:{
                items:2,
            },
            768:{
                items:3,
            },
            992:{
                items:4
            },
            1200:{
                items:6
            }
        }
    });
}


$('.box_logo_company_center').owlCarousel({
    loop:true,
    margin:15,
    nav:true,
    navText: ["",""],
    dots:false,
    autoplay:true,
    autoplayTimeout:4000,
    autoplayHoverPause:true,
    responsive:{
        0:{
            items:1,
            dots:false,
            loop:false,
            nav:false
        },
        320:{
            items:2,
            dots:false
        },
        480:{
            items:2,
        },
        768:{
            items:3,
        },
        992:{
            items:4
        },
        1200:{
            items:5
        }
    }
});

$('#box_logo_company_inner').owlCarousel({
    loop:true,
    margin:30,
    nav:false,
    dots:false,
    autoplay:true,
    autoplayTimeout:4000,
    autoplayHoverPause:true,
    responsive:{
        0:{
            items:1,
            dots:false,
            loop:false,
            nav:false
        },
        320:{
            items:2,
            dots:false
        },
        480:{
            items:2,
        },
        768:{
            items:3,
        },
        992:{
            items:4
        },
        1200:{
            items:6
        }
    }
});

$('.list_product_index').owlCarousel({
    loop:true,
    margin:35,
    nav:true,
    dots:false,
    autoplay:false,
    autoplayTimeout:4000,
    autoplayHoverPause:true,
    responsive:{
        0:{
            items:1,
            loop:false,
            nav:false
        },
        420:{
            items:2,
        },
        480:{
            items:2,
        },
        768:{
            items:3,
        },
        992:{
            items:4
        },
        1200:{
            items:4
        }
    }
});

$('.list_product_care').owlCarousel({
    loop:false,
    margin:30,
    nav:true,
    dots:true,
    autoplay:false,
    autoplayTimeout:4000,
    autoplayHoverPause:true,
    responsive:{
        0:{
            items:1,
            nav:false,
            loop:false,
            dots:false,
        },
        420:{
            items:2,
            nav:false
        },
        480:{
            items:2,
            nav:false
        },
        768:{
            items:3,
            nav:false
        },
        992:{
            items:3
        },
        1200:{
            items:4
        }
    }
})

$(function() {
    $('.lazy').lazy({
        placeholder: "data:image/gif;base64,R0lGODlhEALAPQAPzl5uLr9Nrl8e7..."
    });
});

$(window).on('load',function(){
    var flagModal = 1;
    if (flagModal == 1) {
        setTimeout(function () {
            $('#modalItemYcbg').modal('show');
            flagModal = 0;
        },  15000);
    }
});

var lang = $('input[name=language]').val();
var langMessage = $('input[name=languageMessage]').val();
messageString = localStorage.getItem('messages_' + langMessage);
var messages = {};
if (messageString == null) {
    $.getJSON( "/messages/"+ langMessage +".json", function( data ) {
        $.each( data, function( key, val ) {
            messages[key] = val;   
        });
        messageString = JSON.stringify(data);
        localStorage.setItem('messages_' + langMessage, messageString);
    });
} else {
    $.each( JSON.parse(messageString), function( key, val ) {
        messages[key] = val;   
    });
}

$(document).ready(function(){
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
      "extendedTimeOut": 1000,
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut"
    }
    add_to_cart();
    add_to_cart_qty();
    update_cart();
    delete_cart();
    admin_login();
    category_sub_down();
    newsletter();
    customer_message();
    send_info_frm_ycbg();
    // mic_support();
    datePickerFormItem();
    collapse_menu_nav();
    showChildMenuTop2();
    support_request_popup();
    send_customer_comment();
    send_contact();
    searchSubdomain();
    pagination_ajax();
    sendFormSupportRequest();
    add_alias_sub();
    createDomain();
    marqueeSlider();
});

function createDomain() {
    var domainRegExp = /^[a-z-0-9]*$/;
    $('#btn-create-domain').click(function(e) {
        e.preventDefault();
        var domain = $('input[name=domain]').val();
        var username = $('input[name=r_username]').val();
        var password = $('input[name=r_password]').val();
        var email = $('input[name=r_email').val();
        if(email == '') {
        	$('input[name=r_email]').focus();
            toastr.error(''+ messages.email_is_required +'');
            return false;
        } else if(!emailRegExp.test(email)){
        	$('input[name=r_email]').focus();
            toastr.error(''+ messages.email_not_in_correct_format +'');
            return false;
        }

        var phone = $('input[name=r_phone').val();
        if(phone == ''){
        	$('input[name=r_phone]').focus();
            toastr.error(''+ messages.phone_is_required +'');
            return false;
        } else if(!$.isNumeric(phone)){
        	$('input[name=r_phone]').focus();
            toastr.error(''+ messages.phone_not_in_correct_format +'');
            return false;
        }

        var facebook = $('input[name=r_facebook]').val();
        if(facebook == ''){
            toastr.error('Bạn phải nhập Facebook');
            $('input[name=r_facebook]').focus();
            return false;
        }
        
        if (domain == '') {
            toastr.error('Bạn chưa nhập tên miền');
            $('input[name=domain]').focus();
            return false;
        } else if (!domainRegExp.test(domain)) {
            toastr.error('Tên miền phải nhập chữ thường viết liền không dấu');
            $('input[name=domain]').focus();
            return false;
        }

        if (username == '') {
            $('input[name=r_username]').focus();
            toastr.error('Bạn chưa nhập tên đăng nhập');
            return false;
        } else if (!domainRegExp.test(username)) {
            toastr.error('Tên đăng nhập phải nhập chữ thường viết liền không dấu');
            $('input[name=r_username]').focus();
            return false;
        }

        if (password == '') {
            $('input[name=r_password]').focus();
            toastr.error('Bạn chưa nhập tên mật khẩu');
            return false;
        }

        $.ajax({
            type: 'POST',
            url: 'subdomain-exist' + prefixUrl,
            data: {'domain':domain},
            success:function(response) {
                if (response != 0) {
                    toastr.error('Tên miền đã tồn tại trên hệ thống. Vui lòng nhập tên miền khác!');
                    $('input[name=domain]').focus();
                    return false;
                }
            }
        });

        $.ajax({
            type: 'POST',
            url: 'user-exist' + prefixUrl,
            data: {'username':username},
            success:function(response) {
                if (response != 0) {
                    toastr.error('Tên đăng nhập đã tồn tại trên hệ thống. Vui lòng nhập tên đăng nhập khác!');
                    $('input[name=r_username]').focus();
                    return false;
                }
            }
        });

        $.ajax({
            type: 'POST',
            url: 'user-email-exist' + prefixUrl,
            data: {'email':email},
            success:function(response) {
                if (response != 0) {
                    toastr.error('Email đã được đăng ký. Vui lòng nhập email khác!');
                    $('input[name=r_email]').focus();
                    return false;
                }
            }
        });

        var l = Ladda.create(this);
        l.start();
        $.ajax({
            type: 'POST',
            url: 'subdomain-create' + prefixUrl,
            data: {'domain':domain, 'username':username, 'password':password, 'email':email, 'phone':phone, 'facebook':facebook},
            success:function(response) {
                let res = JSON.parse(response);
                if (res.url) {
                    toastr.success('Tạo website thành công!');
                    setTimeout(function () {
                       window.location.href = res.url
                       flag = 0;
                    }, 1500);
                    
                }
            }
        }).always(function() { l.stop();});

        return false;
    })
}

function support_request_popup() {
    $(".contentheader").click(function(){
        $('#supportRequestModal').modal('show');
        sendFormSupportRequest();
    })
}

var emailRegExp = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.([a-z]){2,4})$/;
function sendFormSupportRequest() {
    $('.btn-send-mic').click(function(e) {
        e.preventDefault();
        var formName = $(this).data('form');
        var form = $('form[name='+ formName +']');
        var formData = {name:"", phone:"", email:"", comment:""};  
        if (form.find('input[name=c_mgs_name]').length > 0) {
            var c_mgs_name = form.find('input[name=c_mgs_name]').val();
            if(c_mgs_name == ''){
                form.find('.errorMicName').html('<span>'+ messages.name_is_required +'</span>');
            }else{
                form.find('.errorMicName').html('');
                formData.name = c_mgs_name; 
            }
        }
        
        if (form.find('input[name=c_mgs_phone]').length > 0) {
            var c_mgs_phone = form.find('input[name=c_mgs_phone]').val();
            if(c_mgs_phone == ''){
                form.find('.errorMicPhone').html('<span>'+ messages.phone_is_required +'</span>');
            }else if(!$.isNumeric(c_mgs_phone)){
                form.find('.errorMicPhone').html('<span>'+ messages.phone_not_in_correct_format +'</span>');
                
            }else{
                form.find('.errorMicPhone').html('');
                formData.phone = c_mgs_phone;
            }
        }

        if (form.find('textarea[name=c_mgs_comment]').length > 0) {
            var c_mgs_comment = form.find('textarea[name=c_mgs_comment]').val();        
            if(c_mgs_comment == ''){
                form.find('.errorMicComment').html('<span>'+ messages.question_is_required +'</span>');
            }else{
                form.find('.errorMicComment').html('');
                formData.comment = c_mgs_comment;
            }
        }
       
        if((typeof(c_mgs_name) != 'undefined' && c_mgs_name == '') || (typeof(c_mgs_phone) != 'undefined' && (c_mgs_phone == '' || !($.isNumeric(c_mgs_phone))) || (typeof(c_mgs_comment) != 'undefined' && c_mgs_comment == ''))) return false;
        var l = Ladda.create(this);
        l.start();
        $.ajax({
            url: form.attr('action') + prefixUrl,
            type: 'POST',
            data: form.serialize(),
            success: function(result) {
                send_mail('yêu cầu tư vấn', formData);
                if (result == 1) {
                    $('#mic_support_message_' + formName).html('<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>'+ messages.success +'</strong> '+ messages.messages_thanks +'</div>');
                } else {
                    $('#mic_support_message_' + formName).html('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>'+ messages.error +'</strong> '+ messages.please_resend_message +'</div>');
                }
                form.find("input[type=text], input[type=email], textarea").val("");;
            }            
        }).always(function() { l.stop();});
        return false;
    });
}

function showChildMenuTop2() {
    $('#nav-menu-top-2 > li').hover(function() {
        $(this).addClass('over menu-active');
    }, function() {
        $(this).removeClass('over menu-active');
      })
}

function collapse_menu_nav(){
    var view_more = 'Xem thêm';
    var collapse = 'Thu gọn';
    $('#show_more_cate').click(function() {
        if($('.nav-main .menuItem').hasClass('hide')){
            $(this).children('span').text(collapse);
            $('#show_more_cate i').attr('class', 'fa fa-minus-circle');
            $('.nav-main .menuItem.hide').addClass('show');
            $('.nav-main .menuItem').removeClass('hide');
        }else{
            $('.nav-main .menuItem.show').addClass('hide');
            $('.nav-main .menuItem').removeClass('show');
            $(this).children('span').text(view_more);
            $('#show_more_cate i').attr('class', 'fa fa-plus-circle');
        }
    });
}

function add_to_cart() {
    $(".add-to-cart, .icon-giohang").click(function(){
        var numberCartItem = $('.number_cart_item');
        var id  = $(this).data("id");
        var name  = $(this).data("name");
        var cartType = $(this).data('cart-type');
        var numberItem = numberCartItem.text();

        var url = '/gio-hang/';
        if (lang != 'vi') {
            url = '/' + lang + '/cart'
        }
        
        $.ajax({
            type: "POST",
            url: "/add-to-cart" + prefixUrl,
            data: {"id":id},
            success:function(result){
                if(result == "success") {
                    if (cartType == 1) {
                        window.location.href = url;
                    } else {
                        toastr.options = {
                          "positionClass": "toast-top-right",
                        }

                        toastr.success('Thêm ' + name + ' vào giỏ hàng thành công');
                        numberCartItem.text(parseInt(numberItem) + 1);
                    }
                }
            }
        })
    });
}

function add_to_cart_qty() {
    $("#add_to_cart").click(function(){
        var options  = $(this).data("options");
        var id  = $(this).data("id");
        var qty  = $('input[name=product_qty]').val();
        var url = '/gio-hang/';
        if (lang != 'vi') {
            url = '/' + lang + '/cart'
        }

        $.ajax({
            type: "POST",
            url: "/add-to-cart" + prefixUrl,
            data: {"id":id, 'qty':qty, 'options':options},
            success:function(result){
                if(result == "success") {
                    window.location.href = url;
                }
            }
        })
    });
}

function update_cart() {
    $("input[name=cart_qty]").bind("change",function(event){
        var qty = $(this).val();
        var id = $(this).data("id");
        var price = $(this).data("price");
        var currency = $(this).data("currency");
    	$.ajax({
            type: "POST",
            url: "/update-cart" + prefixUrl,
            data: {"id":id, "qty":qty, "currency":currency, "price":price},
            success:function(result){
                if(result == "success") {
                    window.location.reload();
                }
            }
        })
    })
}

function delete_cart() {
    $(".event-remove-item").click(function(){
        if(confirm(messages.alert_delete_cart)) {
            var rowid = $(this).data("rowid");
            $.ajax({
                type: "POST",
                url: "/delete-cart" + prefixUrl,
                data: {"rowid":rowid},
                success:function(result){
                    if(result == "success") {
                        window.location.reload();
                    }
                }
            })
        } 
    })
}

function admin_login() {
    $('#adm_btn_left').click(function(e) {
        e.preventDefault();
        var c_username = $('input[name=adm_username_left]').val();
        var c_password = $('input[name=adm_password_left]').val();

        if(c_username == ''){
            $('#error_adm_login_left').html('<div class="text-danger">'+ messages.username_is_required +'</div>');
            return false;
        }else{
            $('#error_adm_login_left').html('');
        }

        if(c_password == ''){
            $('#error_adm_login_left').html('<div class="text-danger">'+ messages.password_is_required +'</div>');
            return false;
        }else{
            $('#error_adm_login_left').html('');
        }

        if(c_username == '' || c_password == '') return false;
        $.ajax({
            url: '/admin-login' + prefixUrl,
            type: 'POST',
            data: $('#frm_adm_login_left').serialize(),
             beforeSend: function(){
                $('#adm_btn_left').val(''+ messages.sending +'');
            },
            success: function(response) {
                if (response == 0) {
                    $('#error_adm_login_left').html('<div class="text-danger">'+ messages.fail_username_or_password +'</div>');
                    $('#adm_btn_left').val(''+ messages.login +'');
                    $('#frm_adm_login_left').find("input[type=password]").val("");
                } else {
                    window.location.href = "/hi"
                }
            }            
        });
        return false;
    });

    $('#adm_btn_right').click(function(e) {
        e.preventDefault();
        var c_username = $('input[name=adm_username_right]').val();
        var c_password = $('input[name=adm_password_right]').val();

        if(c_username == ''){
            $('#error_adm_login_right').html('<div class="text-danger">'+ messages.username_is_required +'</div>');
            return false;
        }else{
            $('#error_adm_login_right').html('');
        }

        if(c_password == ''){
            $('#error_adm_login_right').html('<div class="text-danger">'+ messages.password_is_required +'</div>');
            return false;
        }else{
            $('#error_adm_login_right').html('');
        }

        if(c_username == '' || c_password == '') return false;
        $.ajax({
            url: '/admin-login' + prefixUrl,
            type: 'POST',
            data: $('#frm_adm_login_right').serialize(),
             beforeSend: function(){
                $('#adm_btn_right').val(''+ messages.sending +'');
            },
            success: function(response) {
                if (response == 0) {
                    $('#error_adm_login_right').html('<div class="text-danger">'+ messages.fail_username_or_password +'</div>');
                    $('#adm_btn_right').val(''+ messages.login +'');
                    $('#frm_adm_login_right').find("input[type=password]").val("");
                } else {
                    window.location.href = "/hi"
                }
            }            
        });
        return false;
    });

    $('#adm_btn_header').click(function(e) {
        e.preventDefault();
        var c_username = $('input[name=adm_username_header]').val();
        var c_password = $('input[name=adm_password_header]').val();

        if(c_username == ''){
            $('#error_adm_login_header').html('<div class="text-danger">'+ messages.username_is_required +'</div>');
            return false;
        }else{
            $('#error_adm_login_header').html('');
        }

        if(c_password == ''){
            $('#error_adm_login_header').html('<div class="text-danger">'+ messages.password_is_required +'</div>');
            return false;
        }else{
            $('#error_adm_login_header').html('');
        }

        if(c_username == '' || c_password == '') return false;
        $.ajax({
            url: '/admin-login' + prefixUrl,
            type: 'POST',
            data: $('#frm_adm_login_header').serialize(),
             beforeSend: function(){
                $('#adm_btn_header').val(''+ messages.sending +'');
            },
            success: function(response) {
                if (response == 0) {
                    $('#error_adm_login_header').html('<div class="text-danger">'+ messages.fail_username_or_password +'</div>');
                    $('#adm_btn_header').val(''+ messages.login +'');
                    $('#frm_adm_login_header').find("input[type=password]").val("");
                } else {
                    window.location.href = "/hi"
                }
            }            
        });
        return false;
    });

    $('#adm_btn_footer').click(function(e) {
        e.preventDefault();
        var c_username = $('input[name=adm_username_footer]').val();
        var c_password = $('input[name=adm_password_footer]').val();

        if(c_username == ''){
            $('#error_adm_login_footer').html('<div class="text-danger">'+ messages.username_is_required +'</div>');
            return false;
        }else{
            $('#error_adm_login_footer').html('');
        }

        if(c_password == ''){
            $('#error_adm_login_footer').html('<div class="text-danger">'+ messages.password_is_required +'</div>');
            return false;
        }else{
            $('#error_adm_login_footer').html('');
        }

        if(c_username == '' || c_password == '') return false;
        $.ajax({
            url: '/admin-login' + prefixUrl,
            type: 'POST',
            data: $('#frm_adm_login_footer').serialize(),
             beforeSend: function(){
                $('#adm_btn_footer').val(''+ messages.sending +'');
            },
            success: function(response) {
                if (response == 0) {
                    $('#error_adm_login_footer').html('<div class="text-danger">'+ messages.fail_username_or_password +'</div>');
                    $('#adm_btn_footer').val(''+ messages.login +'');
                    $('#frm_adm_login_footer').find("input[type=password]").val("");
                } else {
                    window.location.href = "/hi"
                }
            }            
        });
        return false;
    });

    $('#adm_btn_center').click(function(e) {
        e.preventDefault();
        var c_username = $('input[name=adm_username_center]').val();
        var c_password = $('input[name=adm_password_center]').val();

        if(c_username == ''){
            $('#error_adm_login_center').html('<div class="text-danger">'+ messages.username_is_required +'</div>');
            return false;
        }else{
            $('#error_adm_login_center').html('');
        }

        if(c_password == ''){
            $('#error_adm_login_center').html('<div class="text-danger">'+ messages.password_is_required +'</div>');
            return false;
        }else{
            $('#error_adm_login_center').html('');
        }

        if(c_username == '' || c_password == '') return false;
        $.ajax({
            url: '/admin-login' + prefixUrl,
            type: 'POST',
            data: $('#frm_adm_login_center').serialize(),
             beforeSend: function(){
                $('#adm_btn_center').val(''+ messages.sending +'');
            },
            success: function(response) {
                if (response == 0) {
                    $('#error_adm_login_center').html('<div class="text-danger">'+ messages.fail_username_or_password +'</div>');
                    $('#adm_btn_center').val(''+ messages.login +'');
                    $('#frm_adm_login_center').find("input[type=password]").val("");
                } else {
                    window.location.href = "/hi"
                }
            }            
        });
        return false;
    });

    $('#btn_system_login').click(function(e) {
        e.preventDefault();
        var c_username = $('input[name=l_username]').val();
        var c_password = $('input[name=l_password]').val();

        if(c_username == ''){
            toastr.error(messages.username_is_required);
            return false;
        }

        if(c_password == ''){
            toastr.error(messages.password_is_required);
            return false;
        }

        if(c_username == '' || c_password == '') return false;
        $.ajax({
            url: '/system-login' + prefixUrl,
            type: 'POST',
            data: $('form[name=frm_system_login]').serialize(),
             beforeSend: function(){
                $('#btn_system_login').val(''+ messages.sending +'');
            },
            success: function(response) {
                let res = JSON.parse(response);
                if (res.status == 500) {
                    toastr.error(messages.fail_username_or_password);
                    $('#btn_system_login').val(''+ messages.login +'');
                } else {
                    window.location.href = res.url;
                }
            }            
        });

        return false;
    });
}

function category_sub_down() {
    $(".subDropdown").on("click", function() {
        $(this).toggleClass("plus"), 
        $(this).toggleClass("minus"), 
        $(this).parent().find("ul").first().slideToggle()
    })
}

function newsletter() {
    $('.btn-send-newsletter').click(function(e){
        e.preventDefault();
        var position = $(this).data('position');
        var emailInput = $('input[name=newsletter_email_'+ position +']');
        var email = $('input[name=newsletter_email_'+ position +']').val();
        if(email == '') {
            toastr.error(''+ messages.email_is_required +'');
            return false;
        } else if(!emailRegExp.test(email)){
            toastr.error(''+ messages.email_not_in_correct_format +'');
            return false;
        }
        var l = Ladda.create(this);
        l.start();
        $.ajax({
            type: 'POST',
            url: $('#frm-newsletter-' + position).attr('action') + prefixUrl,
            data: {'email':email},
            success:function(result){
                if (result == 1) {
                    toastr.success(''+ messages.register_success +'');
                    send_mail('email đăng ký');
                    emailInput.val('');
                } else if (result == -1) {
                    toastr.warning(''+ messages.email_already_registered +'');
                    emailInput.val('');
                }
            }
       }).always(function() { l.stop();});
    })
}

function customer_message()
{
    $(window).resize(function() {
      if ($(window).width() > 767) {
        var flag = 1;
        if (flag == 1) {
            setTimeout(function () {
               $('.btn-customer-message').trigger('click');
               flag = 0;
            }, 7000);
        }
      }
    });
    

    $('.btn-customer-message').click(function(){
        if($(this).hasClass('onlick')){
            $('.form_customer_message').css({'height':'0'});
            $(this).removeClass('onlick');
            $('.sms-mobile').removeClass('onlick');
            $('.shrink_icon').removeClass('fa-angle-down');
            $('.shrink_icon').addClass('fa-angle-up');
            $('.icon_offline_button').css({'display':'inline-block'});
            if ($(window).width() < 768) {
                $('.customer_message_mobile').css({'bottom':'-33px'});
            }
            // $('.btn-customer-message').css({'width':'85%'});
        }else{
            $('.form_customer_message').css({'height':'auto'});
            $(this).addClass('onlick');
            $('.sms-mobile').addClass('onlick');
            $('.shrink_icon').removeClass('fa-angle-up');
            $('.shrink_icon').addClass('fa-angle-down');
            $('.icon_offline_button').css({'display':'none'});
            if ($(window).width() < 768) {
                $('.customer_message_mobile').css({'bottom':'30px'});
            }
            // $('.btn-customer-message').css({'width':'100%'});
        }
    });

    $('.sms-mobile').click(function() {
        if($(this).hasClass('onlick')){
            $('.form_customer_message').css({'height':'0'});
            $(this).removeClass('onlick');
            $('.btn-customer-message').removeClass('onlick');
            $('.shrink_icon').removeClass('fa-angle-down');
            $('.shrink_icon').addClass('fa-angle-up');
            $('.icon_offline_button').css({'display':'inline-block'});
            $('.customer_message_mobile').css({'bottom':'-33px'});
            // $('.btn-customer-message').css({'width':'85%'});
        }else{
            $('.form_customer_message').css({'height':'auto'});
            $(this).addClass('onlick');
            $('.btn-customer-message').addClass('onlick');
            $('.shrink_icon').removeClass('fa-angle-up');
            $('.shrink_icon').addClass('fa-angle-down');
            $('.icon_offline_button').css({'display':'none'});
            if($(this).hasClass('sms-mobile-2')){
                $('.customer_message_mobile').css({'bottom':'36px'});
            } else {
                $('.customer_message_mobile').css({'bottom':'30px'});
            }
            // $('.btn-customer-message').css({'width':'100%'});
        }
    })

    $('#btn-send-cmgs').click(function(e) {
        var formData = {name:"", phone:"", email:"", comment:""};
        e.preventDefault();
        if ($('form[name=frm_customer_message]').find('input[name=c_mgs_name]').length > 0) {
            var c_mgs_name = $('form[name=frm_customer_message]').find('input[name=c_mgs_name]').val();
            if(c_mgs_name == ''){
                $('#errorCMgsName').html('<span>'+ messages.name_is_required +'</span>');
            }else{
                $('#errorCMgsName').html('');
                formData.name = c_mgs_name;
            }
        }
        
        if ($('form[name=frm_customer_message]').find('input[name=c_mgs_phone]').length > 0) {
            var c_mgs_phone = $('form[name=frm_customer_message]').find('input[name=c_mgs_phone]').val();
            if(c_mgs_phone == ''){
                $('#errorCMgsPhone').html('<span>'+ messages.phone_is_required +'</span>');
            }else if(!$.isNumeric(c_mgs_phone)){
                $('#errorCMgsPhone').html('<span>'+ messages.phone_not_in_correct_format +'</span>');
            }else{
                $('#errorCMgsPhone').html('');
                formData.phone = c_mgs_phone;
            }
        }

        if ($('form[name=frm_customer_message]').find('textarea[name=c_mgs_comment]').length > 0) {
            var c_mgs_comment = $('form[name=frm_customer_message]').find('textarea[name=c_mgs_comment]').val();        
            if(c_mgs_comment == ''){
                $('#errorCMgsComment').html('<span>'+ messages.question_is_required +'</span>');
            }else{
                $('#errorCMgsComment').html('');
                formData.comment = c_mgs_comment;
            }
        }
       
        if((typeof(c_mgs_name) != 'undefined' && c_mgs_name == '') || (typeof(c_mgs_phone) != 'undefined' && (c_mgs_phone == '' || !($.isNumeric(c_mgs_phone))) || (typeof(c_mgs_comment) != 'undefined' && c_mgs_comment == ''))) return false;
        var l = Ladda.create(this);
        l.start();
        $.ajax({
            url: $('form[name=frm_customer_message]').attr('action') + prefixUrl,
            type: 'POST',
            data: $('form[name=frm_customer_message]').serialize(),
            success: function(result) {
                send_mail('tin nhắn', formData);
                if (result == 1) {
                    $('#customer_message_success').html('<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>'+ messages.success +'</strong> '+ messages.messages_thanks +'</div>');
                } else {
                    $('#customer_message_success').html('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>'+ messages.error +'</strong> '+ messages.please_resend_message +'</div>');
                }
                $('form[name=frm_customer_message]').find("input[type=text], input[type=email], textarea").val("");;
            }            
        }).always(function() { l.stop();});
        return false;
    });

    $('.btn-send-cmgs-module').click(function(e) {
        e.preventDefault();
        var formData = {
            name: "",
            phone: "",
            email: "",
            comment: "",
            boxOptionSelect: "",
            workProvince: "",
            birthday: "",
            homeTown: "",
            voice: "",
            address: "",
            portraitImage: "",
            certificateImage: "",
            graduationYear: "",
            major: "",
            level: "",
            gender: "",
            forte: "",
            class: "",
            otherRequest: "",
            salary: ""
        };
        var position = $(this).data('position');

        if ($('form[name=frm_customer_message_'+ position +']').find('select[name=c_mgs_box_option_select]').length > 0) {
            var c_mgs_box_option_select = $('form[name=frm_customer_message_'+ position +']').find('select[name=c_mgs_box_option_select]').val();
            if(c_mgs_box_option_select == ''){
                $('#errorCMgsBoxSelectOption' + position).html('<span>'+ messages.province_is_required +'</span>');
            }else{
                $('#errorCMgsBoxSelectOption' + position).html('');
                formData.boxOptionSelect = c_mgs_box_option_select;
            }
        }

        if ($('form[name=frm_customer_message_'+ position +']').find('select[name=c_mgs_work_province]').length > 0) {
            var c_mgs_work_province = $('form[name=frm_customer_message_'+ position +']').find('select[name=c_mgs_work_province]').val();
            if(c_mgs_work_province == ''){
                $('#errorCMgsWorkProvince' + position).html('<span>'+ messages.province_is_required +'</span>');
            }else{
                $('#errorCMgsWorkProvince' + position).html('');
                formData.workProvince = c_mgs_work_province;
            }
        }

        if ($('form[name=frm_customer_message_'+ position +']').find('input[name=c_mgs_name]').length > 0) {
            var c_mgs_name = $('form[name=frm_customer_message_'+ position +']').find('input[name=c_mgs_name]').val();
            if(c_mgs_name == ''){
                $('#errorCMgsName' + position).html('<span>'+ messages.name_is_required +'</span>');
            }else{
                $('#errorCMgsName' + position).html('');
                formData.name = c_mgs_name;
            }
        }

        if ($('form[name=frm_customer_message_'+ position +']').find('input[name=c_mgs_birthday]').length > 0) {
            var c_mgs_birthday = $('form[name=frm_customer_message_'+ position +']').find('input[name=c_mgs_birthday]').val();
            if(c_mgs_birthday == ''){
                $('#errorCMgsBirthday' + position).html('<span>'+ messages.birthday_is_required +'</span>');
            }else{
                $('#errorCMgsBirthday' + position).html('');
                formData.birthday = c_mgs_birthday;
            }
        }

        if ($('form[name=frm_customer_message_'+ position +']').find('select[name=c_mgs_home_town]').length > 0) {
            var c_mgs_home_town = $('form[name=frm_customer_message_'+ position +']').find('select[name=c_mgs_home_town]').val();
            if(c_mgs_home_town == ''){
                $('#errorCMgsHomeTown' + position).html('<span>'+ messages.hometown_is_required +'</span>');
            }else{
                $('#errorCMgsHomeTown' + position).html('');
                formData.homeTown = c_mgs_home_town;
            }
        }

        if ($('form[name=frm_customer_message_'+ position +']').find('select[name=c_mgs_voice]').length > 0) {
            var c_mgs_voice = $('form[name=frm_customer_message_'+ position +']').find('select[name=c_mgs_voice]').val();
            if(c_mgs_voice == ''){
                $('#errorCMgsVoice' + position).html('<span>'+ messages.voice_is_required +'</span>');
            }else{
                $('#errorCMgsVoice' + position).html('');
                formData.voice = c_mgs_voice;
            }
        }

        if ($('form[name=frm_customer_message_'+ position +']').find('input[name=c_mgs_address]').length > 0) {
            var c_mgs_address = $('form[name=frm_customer_message_'+ position +']').find('input[name=c_mgs_address]').val();
            if(c_mgs_address == ''){
                $('#errorCMgsAddress' + position).html('<span'+ messages.address_is_required +'</span>');
            }else{
                $('#errorCMgsAddress' + position).html('');
                formData.address = c_mgs_address;
            }
        }

        if ($('form[name=frm_customer_message_'+ position +']').find('input[name=c_mgs_email]').length > 0) {
            var c_mgs_email = $('form[name=frm_customer_message_'+ position +']').find('input[name=c_mgs_email]').val();
            if(c_mgs_email != '' && !emailRegExp.test(c_mgs_email)){
                $('#errorCMgsEmail' + position).html('<span>'+ messages.email_not_in_correct_format +'</span>');
            }else{
                $('#errorCMgsEmail' + position).html('');
            }
            formData.email = c_mgs_email;
        }
        
        if ($('form[name=frm_customer_message_'+ position +']').find('input[name=c_mgs_phone]').length > 0) {
            var c_mgs_phone = $('form[name=frm_customer_message_'+ position +']').find('input[name=c_mgs_phone]').val();
            if(c_mgs_phone == ''){
                $('#errorCMgsPhone' + position).html('<span>'+ messages.phone_is_required +'</span>');
            }else if(!$.isNumeric(c_mgs_phone)){
                $('#errorCMgsPhone' + position).html('<span>'+ messages.phone_not_in_correct_format +'</span>');
                
            }else{
                $('#errorCMgsPhone' + position).html('');
                formData.phone = c_mgs_phone;
            }
        }

        if ($('form[name=frm_customer_message_'+ position +']').find('input[name=c_mgs_portrait_image]').length > 0) {
            var c_mgs_portrait_image = $('form[name=frm_customer_message_'+ position +']').find('input[name=c_mgs_portrait_image]').val();
            if(c_mgs_portrait_image == ''){
                $('#errorCMgsPortraitImage' + position).html('<span>'+ messages.image_is_required +'</span>');
            }else{
                $('#errorCMgsPortraitImage' + position).html('');
                formData.portraitImage = c_mgs_portrait_image;
            }
        }

        if ($('form[name=frm_customer_message_'+ position +']').find('input[name=c_mgs_certificate_image]').length > 0) {
            var c_mgs_certificate_image = $('form[name=frm_customer_message_'+ position +']').find('input[name=c_mgs_certificate_image]').val();
            if(c_mgs_certificate_image == ''){
                $('#errorCMgsCertificateImage' + position).html('<span>'+ messages.certificat_is_required +'</span>');
            }else{
                $('#errorCMgsCertificateImage' + position).html('');
                formData.certificateImage = c_mgs_certificate_image;
            }
        }

        if ($('form[name=frm_customer_message_'+ position +']').find('input[name=c_mgs_college_address]').length > 0) {
            var c_mgs_college_address = $('form[name=frm_customer_message_'+ position +']').find('input[name=c_mgs_college_address]').val();
            if(c_mgs_college_address == ''){
                $('#errorCMgsCollegeAddress' + position).html('<span>'+ messages.training_school_is_required +'</span>');
            }else{
                $('#errorCMgsCollegeAddress' + position).html('');
                formData.collegeAddress = c_mgs_college_address;
            }
        }

        if ($('form[name=frm_customer_message_'+ position +']').find('input[name=c_mgs_major]').length > 0) {
            var c_mgs_major = $('form[name=frm_customer_message_'+ position +']').find('input[name=c_mgs_major]').val();
            if(c_mgs_major == ''){
                $('#errorCMgsMajor' + position).html('<span>'+ messages.major_is_required +'</span>');
            }else{
                $('#errorCMgsMajor' + position).html('');
                formData.major = c_mgs_major;
            }
        }

        if ($('form[name=frm_customer_message_'+ position +']').find('input[name=c_mgs_graduation_year]').length > 0) {
            var c_mgs_graduation_year = $('form[name=frm_customer_message_'+ position +']').find('input[name=c_mgs_graduation_year]').val();
            if(c_mgs_graduation_year == ''){
                $('#errorCMgsGraduationYear' + position).html('<span>'+ messages.graduation_year_is_required +'</span>');
            }else{
                $('#errorCMgsGraduationYear' + position).html('');
                formData.graduationYear = c_mgs_graduation_year;
            }
        }


        if ($('form[name=frm_customer_message_'+ position +']').find('select[name=c_mgs_level]').length > 0) {
            var c_mgs_level = $('form[name=frm_customer_message_'+ position +']').find('select[name=c_mgs_voice]').val();
            if(c_mgs_level == ''){
                $('#errorCMgsLevel' + position).html('<span>'+ messages.level_is_required +'</span>');
            }else{
                $('#errorCMgsLevel' + position).html('');
                formData.level = c_mgs_level;
            }
        }

        if ($('form[name=frm_customer_message_'+ position +']').find('select[name=c_mgs_gender]').length > 0) {
            var c_mgs_gender = $('form[name=frm_customer_message_'+ position +']').find('select[name=c_mgs_gender]').val();
            if(c_mgs_gender == ''){
                $('#errorCMgsGender' + position).html('<span>'+ messages.gender_is_required +'</span>');
            }else{
                $('#errorCMgsGender' + position).html('');
                formData.gender = c_mgs_gender;
            }
        }

        if ($('form[name=frm_customer_message_'+ position +']').find('input[name=c_mgs_forte]').length > 0) {
            var c_mgs_forte = $('form[name=frm_customer_message_'+ position +']').find('input[name=c_mgs_forte]').val();
            if(c_mgs_forte == ''){
                $('#errorCMgsForte' + position).html('<span>'+ messages.advantages_is_required +'</span>');
            }else{
                $('#errorCMgsForte' + position).html('');
                formData.forte = c_mgs_forte;
            }
        }

        if ($('form[name=frm_customer_message_'+ position +']').find('input[name="c_mgs_subjects[]"]').length > 0) {
            var c_mgs_subjects = $('form[name=frm_customer_message_'+ position +']').find('input[name="c_mgs_subjects[]"]:checked').length;
            if(c_mgs_subjects == 0){
                $('#errorCMgsSubjects' + position).html('<span>'+ messages.subjects_is_required +'</span>');
            }else{
                $('#errorCMgsSubjects' + position).html('');
                formData.subjectsCustommer = c_mgs_subjects;
            }
        }

        if ($('form[name=frm_customer_message_'+ position +']').find('input[name="c_mgs_class[]"]').length > 0) {
            var c_mgs_class = $('form[name=frm_customer_message_'+ position +']').find('input[name="c_mgs_class[]"]:checked').length;
            if(c_mgs_class == 0){
                $('#errorCMgsClass' + position).html('<span>'+ messages.class_is_required +'</span>');
            }else{
                $('#errorCMgsClass' + position).html('');
                formData.class = c_mgs_class;
            }
        }

        if ($('form[name=frm_customer_message_'+ position +']').find('input[name="c_mgs_training_time[]"]').length > 0) {
            var c_mgs_training_time = $('form[name=frm_customer_message_'+ position +']').find('input[name="c_mgs_training_time[]"]:checked').length;
            if(c_mgs_training_time == 0){
                $('#errorCMgsTrainingTime' + position).html('<span>'+ messages.training_time_is_required +'</span>');
            }else{
                $('#errorCMgsTrainingTime' + position).html('');
                formData.trainingTime = c_mgs_training_time;
            }
        }
        
        if ($('form[name=frm_customer_message_'+ position +']').find('textarea[name=c_mgs_comment]').length > 0) {
            var c_mgs_comment = $('form[name=frm_customer_message_'+ position +']').find('textarea[name=c_mgs_comment]').val();
            if(c_mgs_comment == ''){
                $('#errorCMgsComment' + position).html('<span>'+ messages.question_is_required +'</span>');
            }else{
                $('#errorCMgsComment' + position).html('');
                formData.comment = c_mgs_comment;
            }
        }

        if ($('form[name=frm_customer_message_'+ position +']').find('textarea[name=c_mgs_salary]').length > 0) {
            var c_mgs_salary = $('form[name=frm_customer_message_'+ position +']').find('textarea[name=c_mgs_salary]').val();
            if(c_mgs_salary == ''){
                formData.salary = c_mgs_salary;
            }
        }

        if ($('form[name=frm_customer_message_'+ position +']').find('textarea[name=c_mgs_other_request]').length > 0) {
            var c_mgs_other_request = $('form[name=frm_customer_message_'+ position +']').find('textarea[name=c_mgs_other_request]').val();
            if(c_mgs_other_request == ''){
                formData.otherRequest = c_mgs_other_request;
            }
        }
        
       
        if((typeof(c_mgs_name) != 'undefined' && c_mgs_name == '') 
            || (typeof(c_mgs_phone) != 'undefined' && (c_mgs_phone == '' || !($.isNumeric(c_mgs_phone)))) 
            || (typeof(c_mgs_email) != 'undefined' && c_mgs_email !='' && !emailRegExp.test(c_mgs_email))
            || (typeof(c_mgs_comment) != 'undefined' && c_mgs_comment == '')
            || (typeof(c_mgs_birthday) != 'undefined' && c_mgs_birthday == '')
            || (typeof(c_mgs_box_option_select) != 'undefined' && c_mgs_box_option_select == '')
            || (typeof(c_mgs_work_province) != 'undefined' && c_mgs_work_province == '')
            || (typeof(c_mgs_home_town) != 'undefined' && c_mgs_home_town == '')
            || (typeof(c_mgs_voice) != 'undefined' && c_mgs_voice == '')
            || (typeof(c_mgs_portrait_image) != 'undefined' && c_mgs_portrait_image == '')
            || (typeof(c_mgs_college_address) != 'undefined' && c_mgs_college_address == '')
            || (typeof(c_mgs_graduation_year) != 'undefined' && c_mgs_graduation_year == '')
            || (typeof(c_mgs_level) != 'undefined' && c_mgs_level == '')
            || (typeof(c_mgs_gender) != 'undefined' && c_mgs_gender == '')
            || (typeof(c_mgs_forte) != 'undefined' && c_mgs_forte == '')
            || (typeof(c_mgs_subjects) != 'undefined' && c_mgs_subjects == 0)
            || (typeof(c_mgs_class) != 'undefined' && c_mgs_class == 0)
            || (typeof(c_mgs_training_time) != 'undefined' && c_mgs_training_time == 0)
        ) {
            return false;
        }
        var l = Ladda.create(this);
        l.start();
        $.ajax({
            url: $('form[name=frm_customer_message_'+ position +']').attr('action') + prefixUrl,
            type: 'POST',
            data: $('form[name=frm_customer_message_'+ position +']').serialize(),
            success: function(result) {
                send_mail('tin nhắn', formData);
                if (result == 1) {
                    $('#customer_message_module_success_' + position).html('<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>'+ messages.success +'</strong> '+ messages.messages_thanks +'</div>');
                } else {
                    $('#customer_message_module_success_' + position).html('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>'+ messages.error +'</strong> '+ messages.please_resend_message +'</div>');
                }
                $('form[name=frm_customer_message_'+ position +']').find("input[type=text], input[type=email], textarea").val("");;
            }            
        }).always(function() { l.stop();});
        return false;
    })
}

function send_info_frm_ycbg() {
    $('#btn-send-ycbg').click(function(e) {
        e.preventDefault();
        var formData = {name:"", phone:"", email:"", comment:"", place_pic:"", place_arrive:"", day:""};
        var frm_popup = $('form[name=frm_item_ycbg]');
        var id = frm_popup.find($('input[name=frm_item_id]')).val();
        /*if ($('input[name=frm_item_name_'+ id +']').length > 0) {
            var name = $('input[name=frm_item_name_'+ id +']').val();
            if(name == ''){
                toastr.error(''+ messages.please_enter +' họ tên của bạn');
                $('input[name=frm_item_name_'+ id +']').focus();
                return false;
            }
        }*/
        var name = $('input[name=frm_item_name_'+ id +']').val();
        formData.name = name;

        if (frm_popup.find('input[name=frm_item_phone_'+ id +']')) {
            var phone = frm_popup.find('input[name=frm_item_phone_'+ id +']').val();
            if(phone == ''){
                toastr.error(''+ messages.phone_is_required +'');
                frm_popup.find('input[name=frm_item_phone_'+ id +']').focus();
                return false;
            }else if(!$.isNumeric(phone)){
                toastr.error(''+ messages.phone_not_in_correct_format +'');
                 frm_popup.find('input[name=frm_item_phone_'+ id +']').focus();
                return false;
            }
            formData.phone = phone;
        }

        if (frm_popup.find('input[name=frm_item_email_'+ id +']').length > 0) {
            var email = frm_popup.find('input[name=frm_item_email_'+ id +']').val();
            if(email != '' && !emailRegExp.test(email)){
                toastr.error(''+ messages.email_not_in_correct_format +'');
                frm_popup.find('input[name=frm_item_email_'+ id +']').focus();
                return false;
            }
            formData.email = email;
        }

        if (frm_popup.find($('select[name=frm_item_class_'+ id +']')).length > 0) {
            var studentClass = frm_popup.find('select[name=frm_item_class_'+ id +']').val();
            if(studentClass == ''){
                toastr.error(''+ messages.class_is_required +'');
                frm_popup.find('select[name=frm_item_class_'+ id +']').focus();
                return false;
            }
            formData.studentClass = studentClass;
        }

        if (frm_popup.find($('input[name=frm_item_subjects_'+ id +']')).length > 0) {
            var subjects = frm_popup.find('input[name=frm_item_subjects_'+ id +']').val();
            if(subjects == ''){
                toastr.error(''+ messages.subjects_learning_is_required +'');
                frm_popup.find('input[name=frm_item_subjects_'+ id +']').focus();
                return false;
            }
            formData.subjects = subjects;
        }
        
        if (frm_popup.find($('input[name=frm_item_student_number_'+ id +']')).length > 0) {
            var studentNumber = frm_popup.find('input[name=frm_item_student_number_'+ id +']').val();
            if(studentNumber == ''){
                toastr.error(''+ messages.student_number_is_required +'');
                frm_popup.find('input[name=frm_item_student_number_'+ id +']').focus();
                return false;
            }
            formData.studentNumber = studentNumber;
        }

        if (frm_popup.find($('input[name=frm_item_learning_level_'+ id +']')).length > 0) {
            var learningLevel = frm_popup.find('input[name=frm_item_learning_level_'+ id +']').val();
            if(learningLevel == ''){
                toastr.error(''+ messages.learning_level_is_required +'');
                frm_popup.find('input[name=frm_item_learning_level_'+ id +']').focus();
                return false;
            }
            formData.learningLevel = learningLevel;
        }

        if (frm_popup.find($('select[name=frm_item_learning_time_'+ id +']')).length > 0) {
            var learningTime = frm_popup.find('select[name=frm_item_learning_time_'+ id +']').val();
            if(learningTime == ''){
                toastr.error(''+ messages.learning_time_is_required +'');
                frm_popup.find('select[name=frm_item_learning_level_'+ id +']').focus();
                return false; 
            }
            formData.learningTime = learningTime;
        }

        if (frm_popup.find($('input[name=frm_item_learning_day_'+ id +']')).length > 0) {
            var learningDay = frm_popup.find('input[name=frm_item_learning_day_'+ id +']').val();
            if(learningDay == ''){
                toastr.error(''+ messages.learning_day_is_required +'');
                frm_popup.find('input[name=frm_item_learning_day_'+ id +']').focus();
                return false; 
            }
            formData.learningDay = learningDay;
        }

        if (frm_popup.find($('select[name=frm_item_request_'+ id +']')).length > 0) {
            var request = frm_popup.find('select[name=frm_item_request_'+ id +']').val();
            if(request == ''){
                toastr.error(''+ messages.request_is_required +'');
                frm_popup.find('select[name=frm_item_request_'+ id +']').focus();
                return false;  
            }
            formData.request = request;
        }

        if (frm_popup.find('input[name=frm_item_place_pic_'+ id +']').length > 0) {
            var place_pic = frm_popup.find('input[name=frm_item_place_pic_'+ id +']').val();
            var place_pic_name = frm_popup.find('input[name=frm_item_place_pic_'+ id +']').data('name');
            if(place_pic == ''){
                toastr.error(''+ messages.please_enter +' '+ place_pic_name +'');
                frm_popup.find('input[name=frm_item_place_pic_'+ id +']').focus();
                return false;
            }
            formData.place_pic = place_pic;
        }

        if (frm_popup.find('input[name=frm_item_place_arrive_'+ id +']').length > 0) {
            var place_arrive = frm_popup.find('input[name=frm_item_place_arrive_'+ id +']').val();
            var place_arrive_name = frm_popup.find('input[name=frm_item_place_arrive_'+ id +']').data('name');
            if(place_arrive == ''){
                toastr.error(''+ messages.please_enter +' '+ place_arrive_name +'');
                frm_popup.find('input[name=frm_item_place_arrive_'+ id +']').focus();
                return false;
            }
            formData.place_arrive = place_arrive;
        }

        if (frm_popup.find('input[name=frm_item_day_'+ id +']').length > 0) {
            var day = frm_popup.find('input[name=frm_item_day_'+ id +']').val();
            var day_name = frm_popup.find('input[name=frm_item_day_'+ id +']').data('name');
            if(day == ''){
                toastr.error(''+ messages.please_select +' '+ day_name +'');
                frm_popup.find('input[name=frm_item_day_'+ id +']').focus();
                return false;
            }
            formData.day = day;
        }
       
        var l = Ladda.create(this);
        l.start();
        $.ajax({
            url: frm_popup.attr('action') + prefixUrl,
            type: 'POST',
            data: frm_popup.serialize(),
            success: function(result) {
                send_mail(frm_popup.data('name'), formData);
                if (result == 1) {
                    toastr.success(''+ messages.request_is_sended +'');
                } else {
                    toastr.error(''+ messages.request_is_sended +'');
                }
                frm_popup.find("input[type=text], input[type=email], textarea").val("");
                $('#modalItemYcbg').modal('toggle');
            }            
        }).always(function() { l.stop();});
        return false;
    });

    $("form[name='frm_item_ycbg_module']").submit(function(e) {
        e.preventDefault();
        var frm = $(this);
        var formData = {name:"", phone:"", email:"", comment:"", place_pic:"", place_arrive:"", day:"", learningLevel:"", learningTime:"", learningDay:"", request:"", studentNumber: "", subjects:"", teacherCode:"", studentClass:""};
        var position = $(this).data('position');
        var data = new FormData(this);
        var id = frm.find($('input[name=frm_item_id]')).val();
        if (frm.find($('input[name=frm_item_name_'+ id +']')).length > 0) {
            var name = frm.find('input[name=frm_item_name_'+ id +']').val();
            if(name == ''){
                $('#errorFrmItemName' + position).html('<span>'+ messages.name_is_required +'</span>');
            }else{
                $('#errorFrmItemName' + position).html('');
                formData.name = name;
            }
        }
        
        if (frm.find($('input[name=frm_item_phone_'+ id +']')).length > 0) {
            var phone = frm.find('input[name=frm_item_phone_'+ id +']').val();
            if(phone == ''){
                $('#errorFrmItemPhone' + position).html('<span>'+ messages.phone_is_required +'</span>');
            }else if(!$.isNumeric(phone)){
                $('#errorFrmItemPhone' + position).html('<span>'+ messages.phone_not_in_correct_format +'</span>');
            }else{
                $('#errorFrmItemPhone' + position).html('');
                formData.phone = phone;
            }
        }

        if (frm.find($('input[name=frm_item_email_'+ id +']')).length > 0) {
            var email = frm.find('input[name=frm_item_email_'+ id +']').val();
            if(email != '' && !emailRegExp.test(email)){
                $('#errorFrmItemEmail' + position).html('<span>'+ messages.email_not_in_correct_format +'</span>'); 
            }else{
                $('#errorFrmItemEmail' + position).html('');
                formData.email = email;
            }
        }

        if (frm.find($('select[name=frm_item_class_'+ id +']')).length > 0) {
            var studentClass = frm.find('select[name=frm_item_class_'+ id +']').val();
            if(studentClass == ''){
                $('#errorFrmItemClass' + position).html('<span>'+ messages.class_is_required +'</span>'); 
            }else{
                $('#errorFrmItemClass' + position).html('');
                formData.studentClass = studentClass;
            }
        }

        if (frm.find($('input[name=frm_item_subjects_'+ id +']')).length > 0) {
            var subjects = frm.find('input[name=frm_item_subjects_'+ id +']').val();
            if(subjects == ''){
                $('#errorFrmItemSubjects' + position).html('<span>'+ messages.subjects_learning_is_required +'</span>'); 
            }else{
                $('#errorFrmItemSubjects' + position).html('');
                formData.subjects = subjects;
            }
        }
        
        if (frm.find($('input[name=frm_item_student_number_'+ id +']')).length > 0) {
            var studentNumber = frm.find('input[name=frm_item_student_number_'+ id +']').val();
            if(studentNumber == ''){
                $('#errorFrmItemStudentNumber' + position).html('<span>'+ messages.student_number_is_required +'</span>'); 
            }else{
                $('#errorFrmItemStudentNumber' + position).html('');
                formData.studentNumber = studentNumber;
            }
        }

        if (frm.find($('input[name=frm_item_learning_level_'+ id +']')).length > 0) {
            var learningLevel = frm.find('input[name=frm_item_learning_level_'+ id +']').val();
            if(learningLevel == ''){
                $('#errorFrmItemLearningLevel' + position).html('<span>'+ messages.learning_level_is_required +'</span>'); 
            }else{
                $('#errorFrmItemLearningLevel' + position).html('');
                formData.learningLevel = learningLevel;
            }
        }

        if (frm.find($('select[name=frm_item_learning_time_'+ id +']')).length > 0) {
            var learningTime = frm.find('select[name=frm_item_learning_time_'+ id +']').val();
            if(learningTime == ''){
                $('#errorFrmItemLearningTime' + position).html('<span>'+ messages.learning_time_is_required +'</span>'); 
            }else{
                $('#errorFrmItemLearningTime' + position).html('');
                formData.learningTime = learningTime;
            }
        }

        if (frm.find($('input[name=frm_item_learning_day_'+ id +']')).length > 0) {
            var learningDay = frm.find('input[name=frm_item_learning_day_'+ id +']').val();
            if(learningDay == ''){
                $('#errorFrmItemLearningDay' + position).html('<span>'+ messages.learning_day_is_required +'</span>'); 
            }else{
                $('#errorFrmItemLearningDay' + position).html('');
                formData.learningDay = learningDay;
            }
        }

        if (frm.find($('select[name=frm_item_request_'+ id +']')).length > 0) {
            var request = frm.find('select[name=frm_item_request_'+ id +']').val();
            if(request == ''){
                $('#errorFrmItemRequest' + position).html('<span>'+ messages.request_is_required +'</span>'); 
            }else{
                $('#errorFrmItemRequest' + position).html('');
                formData.request = request;
            }
        }

        var teacherCode = frm.find('input[name=teacher_code_'+ id +']').val();
        formData.teacherCode = teacherCode;

        if (frm.find($('input[name=frm_item_place_pic_'+ id +']')).length > 0) {
            var place_pic = frm.find('input[name=frm_item_place_pic_'+ id +']').val();
            var place_pic_name = frm.find('input[name=frm_item_place_pic_'+ id +']').data('name');
            if(place_pic == ''){
                $('#errorFrmItemPlacePic' + position).html('<span>'+ messages.please_enter +' '+ place_pic_name +'</span>');
            } else {
                $('#errorFrmItemPlacePic' + position).html('');
                formData.place_pic = place_pic;
            }
        }

        if (frm.find($('input[name=frm_item_place_arrive_'+ id +']')).length > 0) {
            var place_arrive = frm.find('input[name=frm_item_place_arrive_'+ id +']').val();
            var place_arrive_name = frm.find('input[name=frm_item_place_arrive_'+ id +']').data('name');
            if(place_arrive == ''){
                $('#errorFrmItemPlaceArrive' + position).html('<span>'+ messages.please_enter +' '+ place_arrive_name +'</span>');
            } else {
                $('#errorFrmItemPlaceArrive' + position).html('');
                formData.place_arrive = place_arrive;
            }
        }

        if (frm.find($('input[name=frm_item_day_'+ id +']')).length > 0) {
            var day = frm.find('input[name=frm_item_day_'+ id +']').val();
            var day_name = frm.find('input[name=frm_item_day_'+ id +']').data('name');
            if(day == ''){
                $('#errorFrmItemDay' + position).html('<span>'+ messages.please_select +' '+ day_name +'</span>');
            } else {
                $('#errorFrmItemDay' + position).html('');
                formData.day = day;
            }
        }

        if (frm.find($('input[name=frm_item_file_'+ id +']')).length > 0) {
            var fileInput = frm.find($('input[name=frm_item_file_'+ id +']'));
            var fileInputVal = fileInput.val();
            var fileData = fileInput.prop('files')[0];
            var ext = fileInputVal.split('.').pop();
            var validFile = true;
            if (fileInputVal != '') {
                if (ext != 'pdf' && ext != 'docx' && ext != 'doc' && ext != 'xls' && ext != 'xlsx') {
                    $('#errorFrmItemFile' + position).html('<span>'+ messages.file_in_correct_format +'</span>');
                    validFile = false;
                } else if (fileData.size > 5 * 1024 * 1024) {
                    $('#errorFrmItemFile' + position).html('<span>'+ messages.file_to_large +'</span>');
                    validFile = false;
                } else{
                    $('#errorFrmItemFile' + position).html('');
                    validFile = true;
                }
            }
        }

        if ((typeof(name) != 'undefined' && name == '') 
            || (typeof(phone) != 'undefined' && (phone == '' || !($.isNumeric(phone)))) 
            || (typeof(email) != 'undefined' && email !='' && !emailRegExp.test(email)) 
            || (typeof(place_pic) != 'undefined' && place_pic == '') 
            || (typeof(place_arrive) != 'undefined' && place_arrive == '') 
            || (typeof(studentClass) != 'undefined' && studentClass == '')
            || (typeof(subjects) != 'undefined' && subjects == '')
            || (typeof(studentNumber) != 'undefined' && studentNumber == '')
            || (typeof(learningLevel) != 'undefined' && learningLevel == '')
            || (typeof(learningTime) != 'undefined' && learningTime == '')
            || (typeof(learningDay) != 'undefined' && learningDay == '')
            || (typeof(request) != 'undefined' && request == '')
            || (typeof(validFile) != 'undefined' && !validFile)
        ) {
            return false;
        }
        var l = Ladda.create(this);
        l.start();

        $.ajax({
            url: frm.attr('action') + prefixUrl,
            type: 'POST',
            data: data,
            success: function (result) {
                send_mail(frm.data('name'),  formData);
                if (result == 1) {
                    $('#form_item_module_success_' + position).html('<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>'+ messages.success +'</strong> '+ messages.request_is_sended +'</div>');
                } else {
                    $('#form_item_module_success_' + position).html('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>'+ messages.error +'</strong> '+ messages.error_try_again +'</div>');
                }
                frm.find("input[type=text], input[type=email], input[type=file], textarea").val("");
            },
            cache: false,
            contentType: false,
            processData: false
        }).always(function() { l.stop();});
        return false;
    });

    $('.btn-send-form-fast-register').click(function(e) {
        e.preventDefault();
        var formData = {name:"", phone:"", email:"", comment:"", place_pic:"", place_arrive:"", day:"", hour:"", method:""};
        var frm = $('form[name=frm_fast_register]')
        var id = frm.find($('input[name=frm_item_id]')).val();

        if (frm.find($('input[name=frm_item_name_'+ id +']')).length > 0) {
            var name = frm.find('input[name=frm_item_name_'+ id +']').val();
            if(name == ''){
                $('#errorFrmFastRegName').html('<span>'+ messages.name_is_required +'</span>');
            } else {
                $('#errorFrmFastRegName').html('');
                formData.name = name;
            }
        }
        
        if (frm.find($('input[name=frm_item_phone_'+ id +']')).length > 0) {
            var phone = frm.find('input[name=frm_item_phone_'+ id +']').val();
            if(phone == ''){
                $('#errorFrmFastRegPhone').html('<span>'+ messages.phone_is_required +'</span>');
            }else if(!$.isNumeric(phone)){
                $('#errorFrmFastRegPhone').html('<span>'+ messages.phone_not_in_correct_format +'</span>');
            }else{
                $('#errorFrmFastRegPhone').html('');
                formData.phone = phone;
            }
        }

        if (frm.find($('input[name=frm_item_email_'+ id +']')).length > 0) {
            var email = frm.find('input[name=frm_item_email_'+ id +']').val();
            if(email != '' && !emailRegExp.test(email)){
                $('#errorFrmFastRegEmamil').html('<span>'+ messages.email_not_in_correct_format +'</span>'); 
            }else{
                $('#errorFrmFastRegEmamil').html('');
                formData.email = email;
            }
        }

        if (frm.find($('select[name=frm_item_method_'+ id +']')).length > 0) {
            var method = frm.find('select[name=frm_item_method_'+ id +']').val();
            if(method == ''){
                $('#errorFrmFastRegMethod').html('<span>'+ messages.method_class_required +'</span>'); 
            }else{
                $('#errorFrmFastRegMethod').html('');
                formData.method = method;
            }
        }

        if (frm.find($('input[name=frm_item_day_'+ id +']')).length > 0) {
            var day = frm.find('input[name=frm_item_day_'+ id +']').val();
            var day_name = frm.find('input[name=frm_item_day_'+ id +']').data('name');
            if(day == ''){
                $('#errorFrmFastRegDay').html('<span>'+ messages.please_select +' '+ day_name +'</span>');
            } else {
                $('#errorFrmFastRegDay').html('');
                formData.day = day;
            }
        }

        if (frm.find($('select[name=frm_item_hour_one_'+ id +']')).length > 0) {
            var hour = frm.find('select[name=frm_item_hour_one_'+ id +']').val();
            if(hour == ''){
                $('#errorFrmFastRegHour').html('<span>'+ messages.time_receive_class_required +'</span>'); 
            }else{
                $('#errorFrmFastRegHour').html('');
                formData.hour = hour;
            }
        }

        if (frm.find($('input[name=frm_item_number_ticket_'+ id +']')).length > 0) {
            var number_ticket = frm.find('input[name=frm_item_number_ticket_'+ id +']').val();
            if(number_ticket == ''){
                $('#errorFrmFastRegNumberTicket').html('<span>'+ messages.number_ticket_required +'</span>');
            }else if(!$.isNumeric(number_ticket)){
                $('#errorFrmFastRegNumberTicket').html('<span>'+ messages.error_format +'</span>');
            }else{
                $('#errorFrmFastRegNumberTicket').html('');
                formData.numberTicket = number_ticket;
            }
        }

        if (frm.find($('input[name=frm_item_start_time_'+ id +']')).length > 0) {
            var start_time = frm.find('input[name=frm_item_start_time_'+ id +']').val();
            if(start_time == ''){
                formData.startTime = start_time;
            }
        }

        if (frm.find($('input[name=frm_item_end_time_'+ id +']')).length > 0) {
            var end_time = frm.find('input[name=frm_item_end_time_'+ id +']').val();
            if(end_time == ''){
                formData.endTime = end_time;
            }
        }

        if (frm.find($('input[name=frm_item_comment_'+ id +']')).length > 0) {
            var comment = frm.find('input[name=frm_item_comment_'+ id +']').val();
            if(comment == ''){
                formData.comment = comment;
            }
        }
        
        if ((typeof(phone) != 'undefined' && (phone == '' || !($.isNumeric(phone)))) 
            || (typeof(email) != 'undefined' && email !='' && !emailRegExp.test(email)) 
            || (typeof(name) != 'undefined' && name == '') 
            || (typeof(method) != 'undefined' && method == '') 
            || (typeof(day) != 'undefined' && day == '') 
            || (typeof(hour) != 'undefined' && hour == '')
            || (typeof(numberTicket) != 'undefined' && (numberTicket == '' || !($.isNumeric(numberTicket))))
        ) {
            return false;
        }
        var l = Ladda.create(this);
        l.start();
        $.ajax({
            url: frm.attr('action') + prefixUrl,
            type: 'POST',
            data: frm.serialize(),
            success: function(result) {
                send_mail(frm.data('name'),  formData);
                if (result == 1) {
                    toastr.success(''+ messages.register_success_message +'');
                } else {
                    toastr.error(''+ messages.error_try_again +'');
                }
                frm.find("input[type=text], input[type=email], textarea, select").val("");
            }            
        }).always(function() { l.stop();});
        return false;
    })
}

function send_customer_comment() {
    $("form[name='frm_customer_comment']").submit(function(e) {
        e.preventDefault();
        var formData = {name:"", phone:"", email:"", comment:"", address:"", address:""};
        var data = new FormData(this);
        var frm = $('form[name=frm_customer_comment]');

        if (frm.find($('input[name=cc_f_name]')).length > 0) {
            var name = frm.find('input[name=cc_f_name]').val();
            if(name == ''){
                $('#errorFrmCCFName').html('<span>'+ messages.name_is_required +'</span>');
            } else {
                $('#errorFrmCCFName').html('');
                formData.name = name;
            }
        }
        
        if (frm.find($('input[name=cc_f_phone]')).length > 0) {
            var phone = frm.find('input[name=cc_f_phone]').val();
            if(phone == ''){
                $('#errorFrmCCFPhone').html('<span>'+ messages.phone_is_required +'</span>');
            }else if(!$.isNumeric(phone)){
                $('#errorFrmCCFPhone').html('<span>'+ messages.phone_not_in_correct_format +'</span>');
            }else{
                $('#errorFrmCCFPhone').html('');
                formData.phone = phone;
            }
        }

        if (frm.find($('input[name=cc_f_email]')).length > 0) {
            var email = frm.find('input[name=cc_f_email]').val();
            if(email != '' && !emailRegExp.test(email)){
                $('#errorFrmCCFEmail').html('<span>'+ messages.email_not_in_correct_format +'</span>'); 
            }else{
                $('#errorFrmCCFEmail').html('');
                formData.email = email;
            }
        }

        if (frm.find($('textarea[name=cc_f_comment]')).length > 0) {
            var comment = frm.find('textarea[name=cc_f_comment]').val();
            if(comment == ''){
                $('#errorFrmCCFComment').html('<span>'+ messages.comment_is_required +'</span>'); 
            } else {
                $('#errorFrmCCFComment').html('');
                formData.comment = comment;
            }
        }

        if (frm.find($('input[name=cc_f_address]')).length > 0) {
            var address = frm.find('input[name=cc_f_address]').val();
            if(address == ''){
                formData.address = comment;
            }
        }

        if (frm.find($('input[name=file_images_custom_cmts]')).length > 0) {
            var fileInput = frm.find($('input[name=file_images_custom_cmts]'));
            var fileInputVal = fileInput.val();
            var fileData = fileInput.prop('files')[0];
            var fileType = fileData['type'];
            var validImageTypes = ["image/gif", "image/jpeg", "image/png"];
            var validFile = true;
            if (fileInputVal != '') {
                if (!validImageTypes.includes(fileType)) {
                    $('#errorFrmItemFileCmt').html('<span>'+ messages.file_in_correct_format +'</span>');
                    validFile = false;
                } else if (fileData.size > 5 * 1024 * 1024) {
                    $('#errorFrmItemFileCmt').html('<span>'+ messages.file_to_large +'</span>');
                    validFile = false;
                } else{
                    $('#errorFrmItemFileCmt').html('');
                    validFile = true;
                }
            }
        }
        
        if ((typeof(name) != 'undefined' && name == '') 
            || (typeof(phone) != 'undefined' && (phone == '' || !($.isNumeric(phone)))) 
            || (typeof(email) != 'undefined' && email !='' && !emailRegExp.test(email)) 
            || (typeof(comment) != 'undefined' && comment == '')
            || (typeof(validFile) != 'undefined' && !validFile)
        ) {
            return false;
        }

        var imgPreview = frm.find('.img_review');
        var l = Ladda.create(this);
        l.start();
        $.ajax({
            url: frm.attr('action') + prefixUrl,
            type: 'POST',
            data: data,
            success: function(result) {
                send_mail(frm.data('name'),  formData);
                if (result == 1) {
                    $('#customer_comment_success').html('<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>'+ messages.success +'</strong> '+ messages.thanks_customer +'</div>');
                    
                    // frm.find($("#files_cdn_customer_photo").html(''));
                } else {
                    $('#customer_comment_success').html('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>'+ messages.error +'</strong> '+ messages.error_try_again +'</div>');
                }
                frm.find("input[type=text], input[type=email],input[type=file], textarea").val("");
                imgPreview.attr('src', '#');
                imgPreview.hide();
            },
            cache: false,
            contentType: false,
            processData: false         
        }).always(function() { l.stop();});
        return false;
    })
}

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('.img_review').attr('src', e.target.result);
            $('.img_review').show();
        };

        reader.readAsDataURL(input.files[0]);
    }
}

function send_contact() {
    $('.btn-send-contact-module').click(function(e) {
        e.preventDefault();
        var formData = {
            name: "",
            phone: "",
            email: "",
            comment: "",
            subject: "",
            address: "",
        };
        var position = $(this).data('position');

        if ($('form[name=frm_md_contact_'+ position +']').find('input[name=name]').length > 0) {
            var name = $('form[name=frm_md_contact_'+ position +']').find('input[name=name]').val();
            if(name == ''){
                $('#errorContactName' + position).html('<span>'+ messages.name_is_required +'</span>');
            }else{
                $('#errorContactName' + position).html('');
                formData.name = name;
            }
        }

        if ($('form[name=frm_md_contact_'+ position +']').find('input[name=email]').length > 0) {
            var email = $('form[name=frm_md_contact_'+ position +']').find('input[name=email]').val();
            if(email != '' && !emailRegExp.test(email)){
                $('#errorContactEmail' + position).html('<span>'+ messages.email_not_in_correct_format +'</span>');
            }else{
                $('#errorContactEmail' + position).html('');
            }
            formData.email = email;
        }
        
        if ($('form[name=frm_md_contact_'+ position +']').find('input[name=phone]').length > 0) {
            var phone = $('form[name=frm_md_contact_'+ position +']').find('input[name=phone]').val();
            if(phone == ''){
                $('#errorContactPhone' + position).html('<span>'+ messages.phone_is_required +'</span>');
            }else if(!$.isNumeric(phone)){
                $('#errorContactPhone' + position).html('<span>'+ messages.phone_not_in_correct_format +'</span>');
                
            }else{
                $('#errorContactPhone' + position).html('');
                formData.phone = phone;
            }
        }

        if ($('form[name=frm_md_contact_'+ position +']').find('input[name=address]').length > 0) {
            var address = $('form[name=frm_md_contact_'+ position +']').find('input[name=address]').val();
            if(address == ''){
                $('#errorContactAddress' + position).html('<span>'+ messages.name_is_required +'</span>');
            }else{
                $('#errorContactAddress' + position).html('');
                formData.address = address;
            }
        }
        
       
        if((typeof(name) != 'undefined' && name == '') 
            || (typeof(phone) != 'undefined' && (phone == '' || !($.isNumeric(phone)))) 
            || (typeof(email) != 'undefined' && email !='' && !emailRegExp.test(email))
            || (typeof(address) != 'undefined' && address == '')
        ) {
            return false;
        }
        var l = Ladda.create(this);
        l.start();
        $.ajax({
            url: $('form[name=frm_md_contact_'+ position +']').attr('action') + prefixUrl,
            type: 'POST',
            data: $('form[name=frm_md_contact_'+ position +']').serialize(),
            success: function(result) {
                send_mail('liên hệ', formData);
                if (result == 1) {
                    $('#contact_module_success_' + position).html('<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>'+ messages.success +'</strong> '+ messages.send_contact_success +'</div>');
                } else {
                    $('#contact_module_success_' + position).html('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>'+ messages.error +'</strong> '+ messages.error_try_again +'</div>');
                }
                $('form[name=frm_md_contact_'+ position +']').find("input[type=text], input[type=email], textarea").val("");;
            }            
        }).always(function() { l.stop();});
        return false;
    })
}

function send_mail(title, formData) {
    if (formData != null) {
        var data  = {'title':title, 'formData':formData}
    } else {
        var data = {'title':title}
    }
    $.ajax({
        type: 'POST',
        url: '/send-mail' + prefixUrl,
        data: data
    })
}

function mic_support() {
    $(".contentheader").click(function(){
        if ($('.livesupport').hasClass('livesupport-left')) {
            if ($('.livesupport').hasClass('livesupportOn')) {
                $('.livesupport').animate({left: -350}, 1200);
                $('.livesupport').removeClass('livesupportOn');
            } else {
                $('.livesupport').animate({left: 0}, 1200);
                $('.livesupport').addClass('livesupportOn');
            }  
        } else {
            if ($('.livesupport').hasClass('livesupportOn')) {
                $('.livesupport').animate({right: -350}, 1200);
                $('.livesupport').removeClass('livesupportOn');
            } else {
                $('.livesupport').animate({right: 0}, 1200);
                $('.livesupport').addClass('livesupportOn');
            }
        } 
    });


    $('#btn-send-mic').click(function(e) {
        e.preventDefault();
        var formData = {name:"", phone:"", email:"", comment:""};
        if ($('form[name=frm_mic_support]').find('input[name=c_mgs_name]').length > 0) {
            var c_mgs_name = $('form[name=frm_mic_support]').find('input[name=c_mgs_name]').val();
            if(c_mgs_name == ''){
                $('#errorMicName').html('<span>'+ messages.name_is_required +'</span>');
            }else{
                $('#errorMicName').html('');
                formData.name = c_mgs_name;
            }
        }
        
        if ($('form[name=frm_mic_support]').find('input[name=c_mgs_phone]').length > 0) {
            var c_mgs_phone = $('form[name=frm_mic_support]').find('input[name=c_mgs_phone]').val();
            if(c_mgs_phone == ''){
                $('#errorMicPhone').html('<span>'+ messages.phone_is_required +'</span>');
            }else if(!$.isNumeric(c_mgs_phone)){
                $('#errorMicPhone').html('<span>'+ messages.phone_not_in_correct_format +'</span>');
                
            }else{
                $('#errorMicPhone').html('');
                formData.phone = c_mgs_phone;
            }
        }

        if ($('form[name=frm_mic_support]').find('textarea[name=c_mgs_comment]').length > 0) {
            var c_mgs_comment = $('form[name=frm_mic_support]').find('textarea[name=c_mgs_comment]').val();        
            if(c_mgs_comment == ''){
                $('#errorMicComment').html('<span>'+ messages.question_is_required +'</span>');
            }else{
                $('#errorMicComment').html('');
                formData.comment = c_mgs_comment;
            }
        }
       
        if((typeof(c_mgs_name) != 'undefined' && c_mgs_name == '') || (typeof(c_mgs_phone) != 'undefined' && (c_mgs_phone == '' || !($.isNumeric(c_mgs_phone))) || (typeof(c_mgs_comment) != 'undefined' && c_mgs_comment == ''))) return false;
        var l = Ladda.create(this);
        l.start();
        $.ajax({
            url: $('form[name=frm_mic_support]').attr('action') + prefixUrl,
            type: 'POST',
            data: $('form[name=frm_mic_support]').serialize(),
            success: function(result) {
                send_mail('yêu cầu tư vấn', formData);
                if (result == 1) {
                    $('#mic_support_message').html('<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>'+ messages.success +'</strong> '+ messages.messages_thanks +'</div>');
                } else {
                    $('#mic_support_message').html('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><strong>'+ messages.error +'</strong> '+ messages.please_resend_message +'</div>');
                }
                $('form[name=frm_mic_support]').find("input[type=text], input[type=email], textarea").val("");;
            }            
        }).always(function() { l.stop();});
        return false;
    });
}

function datePickerFormItem() {
    $('.form-item-datepicker').datetimepicker({
        startDate: new Date(),
        format: 'dd/mm/yyyy',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0
    });

    $('.form-fast-register-datepicker').datetimepicker({
        startDate: new Date(),
        format: 'dd/mm/yyyy',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0
    });

    $('.customer-message-datepicker').datetimepicker({
        format: 'dd/mm/yyyy',
        endDate: new Date(),
        changeMonth: true,
        changeYear: true,
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0
    });

    $('.form-item-start-time').datetimepicker({
        startDate: new Date(),
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        showMeridian: 1,
        format: 'dd-mm-yyyy hh:ii'
    });

    $('.form-item-end-time').datetimepicker({
        startDate: new Date(),
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        showMeridian: 1,
        format: 'dd-mm-yyyy hh:ii'
    });
}

function searchSubdomain() {
    $('#btn-search-subdomain').click(function(e) {
        e.preventDefault();
        var keyword = $('#subdomainSearchFrm').find('input[name=keyword]').val();
        if (keyword == '') {
            toastr.error(''+ messages.keyword_is_required +'');
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
                    $('#subdomainListResult').html('<div class="alert alert-warning">'+ messages.not_found_result +'</div>');
                    $('#nav-tabs-list-subdomain').show();
                }
            }
        }).always(function() {
            l.stop();
        })
    })
}

function pagination_ajax() {
    $('.pagination-ajax a').click(function(e) {
        e.preventDefault();
        var url = $(this).data('url') + prefixUrl;
        var page = $(this).data('page');
        var html_id = $(this).closest('.pagination-ajax').data('id');
        $.ajax({
            type: 'POST',
            url: url,
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

function add_alias_sub() {
    $(".sub-domain").bind("change keyup input",function() {
        var obj = $(this).val();
        $(".sub-username").val(obj);
        // $(".sub-password").val(obj);
    });
}

function marqueeSlider() {
    $('.ctrlpg_6_Image1').click(function() {
        var marquee = $(this).closest('.banner-vertical');
        var marqueeContent = marquee.find('.marquee-content');
        marqueeContent.removeClass('marquee-2');
        marqueeContent.addClass('marquee-1');
    });

    $('.ctrlpg_6_Image2').click(function() {
        var marquee = $(this).closest('.banner-vertical');
        var marqueeContent = marquee.find('.marquee-content');
        marqueeContent.removeClass('marquee-1');
        marqueeContent.addClass('marquee-2');
    });
}
    
$(document).ready(function(){
    $("#mobile-menu").mobileMenu({
            MenuWidth: 250,
            SlideSpeed: 300,
            WindowsMaxWidth: 767,
            PagePush: !0,
            FromLeft: !0,
            Overlay: !0,
            CollapseMenu: !0,
            ClassName: "mobile-menu"
        })

    $("ul.accordion li.parent, ul.accordion li.parents, ul#magicat li.open").each(function() {
        $(this).append('<em class="open-close">&nbsp;</em>')
    }), $("ul.accordion, ul#magicat").accordionNew(), $("ul.accordion li.active, ul#magicat li.active").each(function() {
        $(this).children().next("div").css("display", "block")
    })

})

var isTouchDevice = "ontouchstart" in window || 0 < navigator.msMaxTouchPoints;
jQuery(window).on("load", function() {
        isTouchDevice && jQuery("#nav a.level-top").on("click", function() {
            if ($t = jQuery(this), $parent = $t.parent(), $parent.hasClass("parent")) {
                if (!$t.hasClass("menu-ready")) return jQuery("#nav a.level-top").removeClass("menu-ready"), $t.addClass("menu-ready"), !1;
                $t.removeClass("menu-ready")
            }
        })
    }), jQuery(window).scroll(function() {
        1 < jQuery(this).scrollTop() ? $("nav").addClass("sticky") : jQuery("nav").removeClass("sticky")
    }),
    function(s) {
        s.fn.extend({
            accordion: function(i) {
                var t = s.extend({
                        accordion: "true",
                        speed: 300,
                        closedSign: "[+]",
                        openedSign: "[-]"
                    }, i),
                    e = s(this);
                e.find("li").each(function() {
                    0 != s(this).find("ul").size() && (s(this).find("a:first").after("<em>" + t.closedSign + "</em>"), "#" == s(this).find("a:first").attr("href") && s(this).find("a:first").on("click", function() {
                        return !1
                    }))
                }), e.find("li em").on("click", function() {
                    0 != s(this).parent().find("ul").size() && (t.accordion && (s(this).parent().find("ul").is(":visible") || (parents = s(this).parent().parents("ul"), visible = e.find("ul:visible"), visible.each(function(e) {
                        var n = !0;
                        parents.each(function(i) {
                            return parents[i] == visible[e] ? n = !1 : void 0
                        }), n && s(this).parent().find("ul") != visible[e] && s(visible[e]).slideUp(t.speed, function() {
                            s(this).parent("li").find("em:first").html(t.closedSign)
                        })
                    }))), s(this).parent().find("ul:first").is(":visible") ? s(this).parent().find("ul:first").slideUp(t.speed, function() {
                        s(this).parent("li").find("em:first").delay(t.speed).html(t.closedSign)
                    }) : s(this).parent().find("ul:first").slideDown(t.speed, function() {
                        s(this).parent("li").find("em:first").delay(t.speed).html(t.openedSign)
                    }))
                })
            }
        })
    }(jQuery),
    function(c) {
        c.fn.extend({
            accordionNew: function() {
                return this.each(function() {
                    function i(i, e) {
                        c(i).parent(o).siblings().removeClass(t).children(a).slideUp(r), c(i).siblings(a)[e || s](!("show" != e) && r, function() {
                            c(i).siblings(a).is(":visible") ? c(i).parents(o).not(n.parents()).addClass(t) : c(i).parent(o).removeClass(t), "show" == e && c(i).parents(o).not(n.parents()).addClass(t), c(i).parents().show()
                        })
                    }
                    var n = c(this),
                        e = "accordiated",
                        t = "active",
                        s = "slideToggle",
                        a = "ul, div",
                        r = "fast",
                        o = "li";
                    if (n.data(e)) return !1;
                    c.each(n.find("ul, li>div"), function() {
                        c(this).data(e, !0), c(this).hide()
                    }), c.each(n.find("em.open-close"), function() {
                        c(this).on("click", function() {
                            i(this, s)
                        }), c(this).bind("activate-node", function() {
                            n.find(a).not(c(this).parents()).not(c(this).siblings()).slideUp(r), i(this, "slideDown")
                        })
                    });
                    var l = location.hash ? n.find("a[href=" + location.hash + "]")[0] : n.find("li.current a")[0];
                    l && i(l, !1)
                })
            }
        }), c(function() {
            function n() {
                var i = c('.navbar-collapse form[role="search"].active');
                i.find("input").val(""), i.removeClass("active")
            }
            c('header, .navbar-collapse form[role="search"] button[type="reset"]').on("click keyup", function(i) {
                (27 == i.which && c('.navbar-collapse form[role="search"]').hasClass("active") || "reset" == c(i.currentTarget).attr("type")) && n()
            }), c(document).on("click", '.navbar-collapse form[role="search"]:not(.active) button[type="submit"]', function(i) {
                i.preventDefault();
                var e = c(this).closest("form"),
                    n = e.find("input");
                e.addClass("active"), n.focus()
            }), c(document).on("click", '.navbar-collapse form[role="search"].active button[type="submit"]', function(i) {
                i.preventDefault();
                var e = c(this).closest("form").find("input");
                c("#showSearchTerm").text(e.val()), n()
            })
        })
    }(jQuery);