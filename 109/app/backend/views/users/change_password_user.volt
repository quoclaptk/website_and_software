<div class="cart-box-container">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">{{ title_bar }}</h4>
    </div>

    {{ flashSession.output() }}

    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="password">Mật khẩu</label>
                    <input type="hidden" value="{{ id }}" id="sub-id-change">
                    <input type="text" name="password" id="pass-sub-change" class="form-control">
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary" id="submit-change-pass" name="ok">Lưu</button>
        {{ link_to("javascript:;", "Đóng", "class": "btn btn-danger", "data-dismiss":"modal") }}
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('#submit-change-pass').click(function() {
            var id = $('#sub-id-change').val();
            var password = $('#pass-sub-change').val();
            if (password == '') {
                alert('Bạn chưa nhập mật khẩu');
                $('#pass-sub-change').focus();
                return false;
            }

            $.ajax({
                type: 'POST',
                url: '/hi/users/changePasswordUserPost',
                data: {'password':password, 'id':id},
                success: function(result) {
                    if (result == 1) {
                        alert('Đổi mật khẩu thành công');
                        window.location.reload();
                    }
                }
            })
        })
    })
</script>
