$(function () {
    bindAtlasEvent();
});

function bindAtlasEvent() {
	
	//点赞事件
	/*$(".dolove, .notlove").bind("click",function(){
		var pid = $(this).data("postid");
		if($(this).hasClass("dolove")){
			do_love(uid,pid);
		}else if($(this).hasClass("notlove")){
			do_nolove(uid,pid);
		}
		
		$(this).closest(".interactive").find(".dolove").unbind("click")//.find("a").css('cursor','default');
		$(this).closest(".interactive").find(".notlove").unbind("click")//.find("a").css('cursor','default');
		//console.log($(this).closest(".interactive").find(".notlove").find("a"));
	})*/
	
	//绑定事件
	$(document).on('click','.dolove, .notlove',function(){
		var $this = $(this), $type=1;
		if (MID == 0) {
			/*toast.error('请在登陆后再点顶。即将跳转到登陆页。', '温馨提示');
            setTimeout(function () {
                location.href = U('ucenter/member/login');
            }, 1500);*/
            return;
        }
		$postid = $(this).attr('data-postid');
		
		if($this.hasClass("dolove")){
			//do_love($postid,$this);
			$type = 1;
		}else if($(this).hasClass("notlove")){
			//do_nolove($postid,$this);
			$type = 2;
		}
		$.post(U('atlas/index/dolike'),{id:$postid,type:$type},function($res){
			if($res.status == false){
				toast.error('点赞失败。'+$res.info, '温馨提示');
				return false;
			}else{
				//toast.success('点赞成功。', '温馨提示');
				$this.find('.num').html($res.count);
				$this.removeClass('no_love');
				$this.addClass('love');
				$this.unbind("click");
			}
		});

	});
}