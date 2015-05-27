/**
 * Created by Administrator on 2015-5-23.*/
    //弹窗评论
 var comment=function(){
    $('.atcomment').magnificPopup({
        type: 'ajax',
        overflowY: 'scroll',
        modal: true,
        callbacks: {
            ajaxContentAdded: function () {
                console.log(this.content);
            }
        }
    });
}

var addcomment=function () {

    $('#cancel').click(function () {
        $('.mfp-close').click();
    });
    $('#confirm').click(function () {
        var data = $("#at_comment").serialize();
        var url = $("#at_comment").attr('data-url');

        $.post(url, data, function (msg) {
            if (msg.status == 1) {
                $('.mfp-close').click();
                $(".addmore").prepend(msg.html);
                toast.success('评论成功!');
                del();
                comment();
            } else {
                toast.error(msg.info);
            }
        }, 'json');
    })
};



//以下都是表情包
var insertFace = function (obj) {
    var url=obj.attr('data-url');
    $('.XT_insert').css('z-index', '1000');
    $('.XT_face').remove();
    var html = '<div class="XT_face  XT_insert"><div class="triangle sanjiao"></div><div class="triangle_up sanjiao"></div>' +
        '<div class="XT_face_main"><div class="XT_face_title"><span class="XT_face_bt" style="float: left">常用表情</span>' +
        '<a onclick="close_face()" class="XT_face_close">X</a></div><div id="face" style="padding: 10px;"></div></div></div>';
    obj.parents('.weibo_post_box').find('#emot_content').html(html);
    getFace(obj.parents('.weibo_post_box').find('#emot_content'),'miniblog',url);
};
var face_chose = function (obj) {
    var textarea = obj.parents('.weibo_post_box').find('textarea');
    textarea.focus();
    textarea.val(textarea.val()+'['+obj.attr('title')+']');

    var pos = getCursortPosition(textarea[0]);
    var s = textarea.val();
    if (obj.attr('data-type') == 'miniblog') {
        textarea.val(s.substring(0, pos) + '[' + obj.attr('title') + ']' + s.substring(pos));
        setCaretPosition(textarea[0], pos + 2 + obj.attr('title').length);
    } else {
        textarea.val(s.substring(0, pos) + '[' + obj.attr('title') + ':' + obj.attr('data-type') + ']' + s.substring(pos));
        setCaretPosition(textarea[0], pos + 3 + obj.attr('title').length + obj.attr('data-type').length);
    }


}
var getFace = function (obj,miniblog,url) {
    $.post(url, {pkg:'miniblog'}, function (res) {
        var expression = res.expression;
        var _imgHtml = '';
        if(miniblog.length > 0){
            for (var k in expression) {
                _imgHtml += '<a href="javascript:void(0)" data-type="' + expression[k].type + '" title="' + expression[k].title + '" onclick="face_chose($(this))";><img src="' + expression[k].src + '" width="24" height="24" /></a>';
            }
            _imgHtml += '<div class="c"></div>';
        }else{
            _imgHtml = '获取表情失败';
        }
        obj.find('#face').html(_imgHtml);


    }, 'json');
}
var close_face = function () {
    $('.XT_face').remove();
}


