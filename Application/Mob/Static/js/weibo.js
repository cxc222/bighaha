$(document).ready(function () {
    forward();
    del();
    doforward();
    submit();
    support();
    bigimg();
    bind_page();
    add_img();

});

//转发的弹窗
function forward() {
    $('.forward').magnificPopup({
        type: 'ajax',
        overflowY: 'scroll',
        modal: true,
        callbacks: {
            ajaxContentAdded: function () {
                console.log(this.content);
            }
        }
    })
}
//转发微博
var doforward=function ()  {
    $('#cancel').click(function () {
        $('.mfp-close').click();
    });

    $('#conf').click(function () {
        var data = $("#forward").serialize();
        var url = $("#forward").attr('data-url');
        $.post(url, data, function (msg) {
            if (msg.status == 1) {
                $('.mfp-close').click();
                $(".ulclass").prepend(msg.html);
                toast.success('转发成功!');
                forward();
                support();
            } else {
                toast.error(msg.info);
            }
        }, 'json');
    })
}


//图片轮播
var bigimg = function () {
    $('.img-content').each(function () {
        $(this).magnificPopup({
            delegate: 'div',
            type: 'image',
            tLoading: '正在载入 #%curr%...',
            mainClass: 'mfp-img-mobile',
            gallery: {
                enabled: true,
                navigateByImgClick: true,
                preload: [0, 1]
            },
            image: {
                tError: '<a href="%url%">图片 #%curr%</a> 无法被载入.',

                verticalFit: true
            }
        });
    });
};

//点赞
var support = function () {
    $('.support').unbind('click');
    $('.support').click(function () {
        var weibo_id = $(this).attr('weibo_id');
        var user_id = $(this).attr('user_id');
        var url = $(this).attr('url');
        $.post(url, {id: weibo_id, uid: user_id}, function (msg) {
            if (msg.status == 1) {
                toast.success('谢谢您的支持!');
                setTimeout(function () {
                    window.location.reload();
                },1000);


            } else {

                toast.error(msg.info);
            }

        }, 'json')
    });
}

//微博页面评论
var submit=function(){
    $('.submit').unbind('click');
    $('.submit').click(function () {
        var weibo_conetnet = $(this).parent('#comment').find('.content').val();
        var weibo_Id = $(this).attr('weiboId');
        var url = $(this).attr('url');
        $.post(url, {weiboId: weibo_Id, weibocontent: weibo_conetnet}, function (msg) {
            if (msg.status == 1) {
                $(".addmore").prepend(msg.html);
                toast.success('评论成功!');
                $('#comment_content_text').val('');
                del();
                comment();
            } else {
                toast.error(msg.info);
            }
        }, 'json')
    });
}

//删除评论
var del=function(){
    $('.delete').unbind('click');
    $('.delete').click(function () {
        if (confirm("你确定要删除此评论吗？")) {
            var comment_id = $(this).attr('comment-id');
            var weibo_id = $(this).attr('weibo-id');
            var url = $(this).attr('url');
            $.post(url, {commentId: comment_id, weiboId: weibo_id}, function (msg) {
                if (msg.status) {
                    setTimeout(function () {
                        window.location.reload();
                    }, 1000);
                    toast.success('删除成功!');

                } else {
                    toast.error(msg.info);
                }
            }, 'json')
        }
    });
}
//查看更多微博
var page = 1;
function bind_page() {
    $('#getmore').unbind('click');
    $('#getmore').click(function(){
        var url=  $(this).attr('data-url');

        $("#getmore").html("查看更多...");
        $.post(url, {page: page + 1}, function (msg) {

            if (msg.status) {
                $(".ulclass").append(msg.html);
                page++;
                forward();
                support();

            } else {
                $("#getmore").html("全部加载完成！");
                $(".look-more").delay(3000).hide(0);
            }
        });
    });
    $('#getmorefocus').unbind('click');
    $('#getmorefocus').click(function(){
        var url=  $(this).attr('data-url');

        $("#getmorefocus").html("查看更多...");
        $.post(url, {page: page + 1}, function (msg) {

            if (msg.status) {
                $(".ulclass").append(msg.html);
                page++;
                forward();
                support();
            } else {
                $("#getmorefocus").html("全部加载完成！");
                $(".look-more").delay(3000).hide(0);
            }
        })
    });
}


//上传图片
function add_img() {
    $('#fileupload').fileupload({

        done: function (e, result) {
            var $fileInput = $(this);
            if (result.result.status == 0) {
                alert('上传失败')
            }
            var src = result.result.data.file.path;

            var ids = $('#img_ids').val();

            if (!ids == null) {
                $('.show_cover').hide();
            } else {
                $('.show_cover').show();
            }

            $("#cover_url").html('');
            $("#cover_url").html('<img src="' + src + '"style="width:100px;height:100px"  data-role="weibo_cover" >');
            upAttachVal('add', result.result.data.file.id, $('#img_ids'));

            var ids = ids.split(',');
            if (ids.length >= 9) {
                $('#fileupload').attr("disabled", "disabled");

                alert('最多发送九张');
            }
            console.log($.inArray(result.result.data.file.id, ids) >= 0);
            if ($.inArray(result.result.data.file.id, ids) >= 0) {
                alert('暂不能重复发送')
            } else {
                createBox(result, $fileInput);
            }

        }
    })

    $('#fileupload').click(function () {
        var ids = $('#img_ids').val();
        var ids = ids.split(',');
        if (ids.length >= 9) {
            $('#fileupload').attr("disabled", "disabled");
            alert('最多发送九张');
        }
    });
}
function removeLi($li, file_id) {
    upAttachVal('remove', file_id, $('#img_ids'));
    if ($li.siblings('li').length <= 0) {
        $li.parents('.parentFileBox').remove();
    } else {
        $li.remove();
    }
    var ids = $('#img_ids').val();

    var ids = ids.split(',');

    if (ids.length <= 9) {
        $('#fileupload').removeAttr("disabled");
    }
}
//创建文件操作div;
function createBox(result, $fileInput) {
    var file_id = result.result.data.file.id;
    var $parentFileBox = $fileInput.next('.parentFileBox');
    var src = result.result.data.file.path;


    //添加父系容器;
    if ($parentFileBox.length <= 0) {

        var div = '<div class="parentFileBox"style="z-index: 1000">\
						<ul class="fileBoxUl"></ul>\
					</div>';
        $fileInput.after(div);
        $parentFileBox = $fileInput.next('.parentFileBox');

    }

    //添加子容器;
    var li = '<li id="fileBox_' + file_id + '" class="diyUploadHover am-fl" > \
					<div class="viewThumb">\
					<a class="del-btn am-icon-close"style="position:absolute;  right: 5px;"></a>		\
						<img src="' + src + '">\
					</div> \
				</li>';

    $parentFileBox.children('.fileBoxUl').append(li);
    //父容器宽度;
    var $width = $('.fileBoxUl>li').length * 180;
    var $maxWidth = $fileInput.parent().width();
    $width = $maxWidth > $width ? $width : $maxWidth;
    $parentFileBox.width($width);

    var $fileBox = $parentFileBox.find('#fileBox_' + file_id);

    var $Cancel = $fileBox.find('.del-btn').click(function () {

        var $li = $(this).parents('li');
        removeLi($li, file_id);
    });
}
var upAttachVal = function (type, attachId, obj) {
    var $attach_ids = obj;
    var attachVal = $attach_ids.val();
    var attachArr = attachVal.split(',');
    var newArr = [];

    for (var i in attachArr) {
        if (attachArr[i] !== '' && attachArr[i] !== attachId.toString()) {
            newArr.push(attachArr[i]);
        }
    }
    type === 'add' && newArr.push(attachId);

    if (newArr.length <= 9) {
        $attach_ids.val(newArr.join(','));
        return newArr;
    } else {
        return false;
    }

};

