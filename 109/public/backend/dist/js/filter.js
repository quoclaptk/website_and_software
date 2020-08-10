var acp_name = 'hi';

$(document).ready(function() {
    $('#formNotSubmit').on('keyup keypress', function(e) {
      var keyCode = e.keyCode || e.which;
      if (keyCode === 13) { 
        e.preventDefault();
        return false;
      }
    });
    filter();
})

function filter() {
    $('#btn-filter').click(function(e) {
        e.preventDefault();
        var keyword = $('#formNotSubmit').find('input[name=keyword]').val();
        if (keyword == '') {
            toastr.error('Bạn chưa nhập từ khóa');
            $('#formNotSubmit').find('input[name=keyword]').focus();
            return false;
        }
        elmFilter();
    })
}

function elmFilter() {
    var controller = $('input[name=controller]').val();
    var keyword = $('input[name=keyword]').val();
    var category = $('select[name=category]').val();

    if (category == 0) {
        if (keyword != '') {
            var url = '/' + acp_name + '/' + controller + '?keyword=' + keyword;
        } else {
            var url = '/' + acp_name + '/' + controller;
        }
    }
    if(keyword != '' || category != 0) {
        if (keyword != '' && category == 0) {
            var url = '/' + acp_name + '/' + controller + '?keyword=' + keyword;
        }

        if (keyword != '' && category != 0) {
            var url = '/' + acp_name + '/' + controller + '?keyword=' + keyword+ '&category=' + category;
        }

        if (keyword == '' && category != 0) {
            var url = '/' + acp_name + '/' + controller + '?category=' + category;
        }
    }

    window.location.href = url;
}