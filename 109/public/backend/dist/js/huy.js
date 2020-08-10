var base_url = window.location.origin + '/';
var acp_name = 'hi';
var prefixUrl = '/';
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
    updateNoteSubdomain();
    updateNoteCustomerMessages();
    updateNoteFormItem();
    updateNoteOrderTab();
});

function updateNoteSubdomain() {
	$('.update-subdomain-note').change(function() {
        var note = $(this).val();
        var id = $(this).data('id');
        $.ajax({
            type: 'POST',
            url: '/' + acp_name + '/subdomain/updateValue' + prefixUrl,
            data: {'id':id, 'note':note},
            success:function(result) {
                if (result == 1) {
                    toastr.success('Cập nhật giá trị thành công');
                } else {
                    toastr.error('Cập nhật giá trị thất bại');
                }
            }
        })
    })
}

function updateNoteCustomerMessages() {
  $('.update-subdomain-note-2').change(function() {
        var note = $(this).val();
        var id = $(this).data('id');
        var title = 'customMess';
        $.ajax({
            type: 'POST',
            url: '/' + acp_name + '/orders/updateValue' + prefixUrl,
            data: {'id':id, 'note':note, 'title': title},
            success:function(result) {
                if (result == 1) {
                    toastr.success('Cập nhật giá trị thành công');
                } else {
                    toastr.error('Cập nhật giá trị thất bại');
                }
            }
        })
    })
}

function updateNoteOrderTab() {
  $('.note-order-form-item').change(function() {
        var note = $(this).val();
        var id = $(this).data('id');
        var title = 'order'
        $.ajax({
            type: 'POST',
            url: '/' + acp_name + '/orders/updateValue' + prefixUrl,
            data: {'id':id, 'note':note, 'title' : title},
            success:function(result) {
                if (result == 1) {
                    toastr.success('Cập nhật giá trị thành công');
                } else {
                    toastr.error('Cập nhật giá trị thất bại');
                }
            }
        })
    })
}

function updateNoteFormItem() {
  $('.update-subdomain-note-3').change(function() {
        var note = $(this).val();
        var id = $(this).data('id');
        var title = 'formItem';
        $.ajax({
            type: 'POST',
            url: '/' + acp_name + '/orders/updateValue' + prefixUrl,
            data: {'id':id, 'note':note, 'title': title},
            success:function(result) {
                if (result == 1) {
                    toastr.success('Cập nhật giá trị thành công');
                } else {
                    toastr.error('Cập nhật giá trị thất bại');
                }
            }
        })
    })
}

