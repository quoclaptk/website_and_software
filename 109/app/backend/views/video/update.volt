{{ partial('partials/content_header') }}

{{ stylesheet_link('backend/video/css/jquery.fileupload.css') }}

<section class="content" style="position:relative;">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <div class="box box-primary" style="position: static">
                <div class="box-header with-border">
                    <h3 class="box-title">Edit video</h3>
                </div><!-- /.box-header -->
                {{ content() }}
                <!-- form start -->
                {{ form('role':'form','enctype':'multipart/form-data','id':'form-fixed') }}
                <div class="row">
                    <div class="col-md-8">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="name">Tên</label>
                                {{ form.render("name",{'class':'form-control','id':'name'}) }}
                                {{ form.messages('name') }}
                            </div>

                            <div class="form-group">
                                <label for="category_video_id">Danh mục</label>
                                {{ form.render("category_video_id",{'class':'form-control','id':'category_video_id', 'style':'max-width:50%'}) }}
                                {{ form.messages('category_video_id') }}
                            </div>

                            <div class="form-group">
                                <label for="file">Tên video hiện tại:</label>
                                {{ video.file }}
                                {#<video width="400" id="video" controls="controls" poster="{{ url('files/default/video.png') }}">#}
                                    {#<source src="{{ url('files/video/' ~  video.file) }}">#}
                                {#</video>#}
                            </div>

                            <div class="form-group">
                                <label for="photo">Hình ảnh</label>
                                {{ image('files/video_media/media/'~ video.folder ~ '/' ~ video.photo , 'width':'100', 'style':'margin-top:10px') }}
                            </div>

                            <div class="form-group">
                                <label for="sort">Thứ tự</label>
                                {{ form.render("sort",{'class':'form-control','id':'sort','style':'width:50px'}) }}
                                {{ form.messages('sort') }}
                            </div>

                            <div class="form-group">
                                <label for="active">Active</label>
                                {{ form.render("active") }}
                            </div>

                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="title">Title</label>
                                {{ form.render("title",{'class':'form-control','id':'title'}) }}
                            </div>

                            <div class="form-group">
                                <label for="keywords">Keywords</label>
                                {{ form.render("keywords",{'class':'form-control','id':'keywords'}) }}
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>
                                {{ form.render("description",{'class':'form-control','id':'description'}) }}
                            </div>

                        </div>
                    </div>
                </div>

                <div id="files_video" class="files_video">
                    <input name="file" id="file_video" type="hidden" value="" />
                    <input name="photo" id="img_video" type="hidden" value="" />
                </div>

                <div class="box-footer" style="position: absolute;bottom: 15px;left: 15px;z-index: 9">
                    {{ submit_button("Save", "class": "btn btn-primary", "data-type":"save") }}
                    {#{{ submit_button("Save & New", "class": "btn btn-primary", "name": "save_new", "data-type-new":"save") }}#}
                    {#{{ submit_button("Save & Close", "class": "btn btn-primary", "name": "save_close", "data-type":"save-close") }}#}
                    {{ link_to("acp/video", "Exit", "class": "btn btn-danger") }}
                    {{ link_to("acp/video/create", "Create new", "class": "btn btn-success") }}
                </div>
                {{ endform() }}


                <div class="form-group col-xs-12" style="background: #fff; padding-top: 10px;padding-bottom: 60px">
                    <span class="btn btn-success fileinput-button">
                        <i class="glyphicon glyphicon-plus"></i>
                        <span>Select files...</span>
                        <!-- The file input field used as target for the file upload widget -->
                        <input id="fileupload" type="file" name="files[]" multiple>
                    </span>
                    <br>
                    <br>
                    <!-- The global progress bar -->
                    <div id="progress" class="progress">
                        <div class="progress-bar progress-bar-success"></div>
                    </div>
                    <!-- The container for the uploaded files -->
                    <div id="files" class="files"></div>

                    <video id="video" width="600" controls="controls" poster="{{ url('files/default/video.png') }}">
                        <source id="tv_main_channel" src="{{ url('files/video/'~ video.folder ~ '/') ~ video.file }}">
                    </video>
                    <div style="clear:both"></div>
                    <button id="capture" style="margin-bottom: 10px">Chụp ảnh</button>
                    <div id="output">
                        <div class="image"></div>
                        <div style="clear:both"></div>
                        <div class="input"></div>
                    </div>
                </div>

            </div><!-- /.box -->
        </div>
    </div>
</section>


{{javascript_include('backend/video/js/vendor/jquery.ui.widget.js')}}
{{javascript_include('backend/video/js/jquery.iframe-transport.js')}}
{{javascript_include('backend/video/js/jquery.fileupload.js')}}

<style>
    #output .image{
        margin-bottom: 10px;;
    }
    #output .image img{
        float: left;
        width: 120px;
        margin: 0 10px 10px 0;
    }
    #output .input input{
        float: left;
        width: 120px;
        margin: 0 10px 10px 0;
    }
</style>

<script>
    /*jslint unparam: true */
    /*global window, $ */
    $(function () {
        'use strict';
        // Change this to the location of your server-side upload handler:
        var tv_main_channel = $('#tv_main_channel');
        var url = window.location.hostname === 'blueimp.github.io' ?
                '//jquery-file-upload.appspot.com/' : '{{ url('jqueryupload/php/') }}';
        $('#fileupload').fileupload({
            url: url,
            dataType: 'json',
            done: function (e, data) {
                $.each(data.result.files, function (index, file) {
                    $('<p/>').text(file.name).appendTo('#files');
                    tv_main_channel.attr('src', '{{ url("jqueryupload/php/files/")}}' +file.name );
                    $('#video').load();

                    $('#files_video #file_video').val(file.name);
                });
            },
            progressall: function (e, data) {
                var progress = parseInt(data.loaded / data.total * 100, 10);
                $('#progress .progress-bar').css(
                        'width',
                        progress + '%'
                );
            }
        }).prop('disabled', !$.support.fileInput)
                .parent().addClass($.support.fileInput ? undefined : 'disabled');

    });

    (function() {
        "use strict";

        var video, $output;
        var scale = 0.7;

        var initialize = function() {
            $output = $("#output");
            video = $("#video").get(0);
            $("#capture").click(captureImage);
        };

        var captureImage = function() {
            var canvas = document.createElement("canvas");
            canvas.width = video.videoWidth * scale;
            canvas.height = video.videoHeight * scale;
            canvas.getContext('2d')
                    .drawImage(video, 0, 0, canvas.width, canvas.height);

            var img = document.createElement("img");
            img.src = canvas.toDataURL("image/png");
            $("#output .image").prepend(img);
            $('#files_video #img_video').val(img.src);
            //$("#output .input").append('<input class="photo_video" name="photo_video" onclick="show()" type="radio" value="'+img.src+'">');
        };

        $(initialize);

    }());


</script>


