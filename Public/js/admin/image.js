 /**
  * 图片上传操作
  */
  var uploadUrl = SCOPE.ajax_upload_image_url;
  var baseUrl = SCOPE.upload_base_url;
    // 图片上传部分
    $(function () {
        'use strict';
        // Change this to the location of your server-side upload handler:
        $('#fileupload').fileupload({
            url: uploadUrl,
            dataType: 'json',
            done: function (e, data) {
                // 这里是图片上传完成后的代码
                var path = data.result;
                $("#thumbnail").html('<div class="up-img"><img style="max-width: 500px;" src="'+baseUrl+path.path+'" /></div>');
                $("#img_url").html('<input type="hidden" name="thumb" value="'+baseUrl+ path.path +'" />');
                layer.msg('缩图上传成功！');
            },
            progressall: function (e, data) {
                // 这里处理图片正在上传的进度
            }
        }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');
    });