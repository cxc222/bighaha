$(function(){
    if(is_login()){
        $('[data-role="reply_button"]').click(function(){
            var query=$('#answer_form').serialize();
            var url=$('#answer_form').attr('action');
            $.post(url,query,function(msg){
                if(msg.status){
                    if(msg.url!=undefined){
                        toast.success(msg.info+'页面即将跳转~');
                        setTimeout(function(){
                            window.location.href=msg.url;
                        },1500);
                    }else{
                        toast.success(msg.info);
                        setTimeout(function(){
                            window.location.reload();
                        },1500);
                    }
                }else{
                    handleAjax(msg);
                }
            },'json');
        });
        $('[data-role="answer-support"]').click(function(){
            var answer_id=$(this).attr('data-id');
            var that=$(this);
            $.post(U('Question/Answer/support'),{answer_id:answer_id,type:1},function(msg){
                if(msg.status){
                    var num=parseInt(that.children('.num').text())+1;
                    that.children('.num').html(num);
                    that.addClass('already_do');
                    that.parent('.support_block').children('.butt').removeClass('can_do');
                    that.parent('.support_block').children('.butt').unbind('click');
                    toast.success('支持成功！');
                }else{
                    handleAjax(msg);
                }
            },'json');
        });
        $('[data-role="answer-oppose"]').click(function(){
            var answer_id=$(this).attr('data-id');
            var that=$(this);
            $.post(U('Question/Answer/support'),{answer_id:answer_id,type:0},function(msg){
                if(msg.status){
                    var num=parseInt(that.children('.num').text())+1;
                    that.children('.num').html(num);
                    that.addClass('already_do');
                    that.parent('.support_block').children('.butt').removeClass('can_do');
                    that.parent('.support_block').children('.butt').unbind('click');
                    toast.success('反对成功！');
                }else{
                    handleAjax(msg);
                }
            },'json');
        });
        $('[data-role="set-best"]').click(function(){
            var answer_id=$(this).attr('data-id');
            var question_id=$(this).attr('data-question-id');
            $.post(U('Question/Answer/setBest'),{answer_id:answer_id,question_id:question_id},function(msg){
                if(msg.status){
                    toast.success('设置成功！页面即将跳转~');
                    setTimeout(function(){
                        window.location.reload();
                    },1500);
                }else{
                    handleAjax(msg);
                }
            },'json');
        });
    }
});