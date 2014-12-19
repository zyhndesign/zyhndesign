/*
* 图文混排文章js
* */
jQuery(document).ready(function($){

    /*
    * 编写图文混排页面的逻辑处理函数
    * */

    var zy_post_controller={

        /*
        * 清楚背景
        * */
        "zy_clear_background":function(){
            $("#zy_background_content").remove();
            $("#zy_background").val("");
            $("#zy_background_percent").text("");
            $("<img id='zy_background_content'  class='zy_background' src='" +
                zy_config.zy_template_url + "/images/backend/app/zy_default_background.png'>").
                appendTo($("#zy_background_container"));
        },

        /*
        * 判断文件名是否正确
        * */
        "zy_file_check":function(filename){
            var lastIndex = filename.lastIndexOf(".");
            filename = filename.substring(0, lastIndex);

            //只含有汉字、数字、字母、下划线不能以下划线开头和结尾
            var reg = /^(?!_)(?!.*?_$)[a-zA-Z0-9_\u4e00-\u9fa5]+$/;

            if(!reg.test(filename)){
                return false;
            }
            return true;
        },

        /*
        * 设置上传后的背景
        * */
        "zy_set_background":function(filename,extension,filepath){
            $("#zy_background_content").remove();
            var string = "";
            if (extension == "mp4") {
                string = "<video id='zy_background_content' class='zy_background' controls><source src='" +
                    filepath + "' type='video/mp4' /></video>";
                $("#zy_background_container").append(string);
            } else {
                string = "<img id='zy_background_content' class='zy_background' src='" + filepath + "'>";
                $("#zy_background_container").append(string);
            }
            $("#zy_background").val(filename);
        },

        /*
        * 上传背景
        * */
        "zy_backgroun_uploader":function(){
            var uploader_background = new plupload.Uploader({
                runtimes:"html5",
                multi_selection:false,
                max_file_size:"20mb",
                browse_button:"zy_upload_background_button",
                container:"zy_background_container",
                //flash_swf_url:'../wp-includes/js/plupload/plupload.flash.swf',
                url:ajaxurl,
                filters:[
                    {title:"Background files", extensions:"jpg,gif,png,jpeg,mp4"}
                ],
                multipart_params:{
                    action:"uploadfile",
                    user_id:zy_config.zy_user_id,
                    file_type:"zy_background"
                }
            });

            //初始化
            uploader_background.init();

            //文件添加事件
            uploader_background.bind("FilesAdded", function (up, files) {
                var filename = files[0].name;

                if (!zy_post_controller.zy_file_check(filename)) {
                    alert("文件名必须是数字下划线汉字字母,且不能以下划线开头。");

                    //删除文件
                    up.removeFile(files[0]);
                    return false;
                } else {
                    up.start();//开始上传
                }
            });

            //文件上传进度条事件
            uploader_background.bind("UploadProgress", function (up, file) {
                //$("#"+file.id+" b").html(file.percent + "%");
                jQuery("#zy_background_percent").html(file.percent + "%");
            });

            //出错事件
            uploader_background.bind("Error", function (up, err) {
                alert(err.message);
                up.refresh();
            });

            //上传完毕事件
            uploader_background.bind("FileUploaded", function (up, file, res) {
                //console.log(response.success+"路径："+response.url);
                var response = JSON.parse(res.response);
                if (response.success) {
                    var filename = response.data.filename;
                    var extension = filename.substr(filename.indexOf(".") + 1, filename.length - 1);

                    zy_post_controller.zy_set_background(filename,extension,response.data.url);

                } else {
                    alert(response.data.message);
                }
            });
        }
    };

    //清除背景
    $("#zy_upload_background_clear").click(function () {
        zy_post_controller.zy_clear_background();
    });

    zy_post_controller.zy_backgroun_uploader();

    $("#publish").click(function(){

        //判断缩略图
        var insideP=$("#postimagediv .inside .hide-if-no-js");
        if(insideP.length<=1){
            alert("没有上传缩略图！");
            return false;
        }else{
            /*用下面这种方案，应该是要异步加载并且使用when的，但是在这里，点击的时候图片已经加载了，所以直接获取就可以了。
             不过wordpress这里设置缩略图是异步的，只要设置了就保存了，如果不点击更新这里也是检测不到来的，但是缩略图已经设置了。
             上述问题留待后期解决（主要是在图片加载的时候就判断了）*/

            var image = new Image();//new一个image对象

            image.src=insideP.eq(0).find("img").attr("src");

            if(image.width!==image.height){
                alert("缩略图比例不是1:1");
                return false;
            }
        }
    });
});
