/**
 * Created by Administrator on 2017/1/1.
 */

function upload(img,fileid){
    var btn=$("#"+img+" .btn");
    var btn2=$("#"+img+" .btnspan");
    var btnbg=$("#"+img+" .btnbg");
    var bar = $("#"+img+" .bar");
    var percent = $("#"+img+" .percent");
    var progress = $("#"+img+" .progress");
    $("#"+fileid).wrap("<form id='"+fileid+"myupload' action='/Public/upload/action.php' method='post' enctype='multipart/form-data'></form>");
    $("#"+fileid).change(function(){
        $("#"+fileid+"myupload").ajaxSubmit({
            dataType:  'json',
            beforeSend: function() {

                progress.show();
                var percentVal = '0%';
                bar.width(percentVal);
                percent.html(percentVal);
                btn2.html("上传中...");
            },
            uploadProgress: function(event, position, total, percentComplete) {
                var percentVal = percentComplete + '%';
                bar.width(percentVal);
                percent.html(percentVal);
            },
            success: function(data) {

                //files.html("<b>"+data.name+"("+data.size+"k)</b> <span class='delimg' rel='"+data.pic+"'>删除</span>");
                var imgurl = "/Public/upload/files/"+data.pic;

                //showimg.html("<img src='"+imgurl+"'>");
                $("#"+img+"url").val(imgurl);

                
                btn2.html("");

                btnbg.attr('src',imgurl);
                $('#big'+img).prop('href',imgurl);
                $('#big'+img).show();

            },
            error:function(xhr){

                btn2.html("上传失败");
                bar.width('0')
                files.html(xhr.responseText);
            }

        });
    });



}