	$(function(){
		$('#submitform').click(function(){
			$('#submitform').toggleClass('hover');
			$('.submitBox').slideToggle();
		})
		
		$('#tosubmitforma').click(function(){
			$('#submitform').toggleClass('hover');
			$('.submitBox').slideToggle();
		})

	})
	function change_code(obj){
		$("#code").attr("src",URL+Math.random());
		return false;
	}
	var URL = '/Index/Index/verify/';


function submitcheck(){
	if (document.submitform.sitename.value == "" || document.submitform.sitename.value == "网站名") {
		alert("请填写网站名。");
		document.submitform.sitename.focus();
		return false;
	}

	if (document.submitform.siteurl.value == "" || document.submitform.siteurl.value == "http://") {
		alert("请填写网站url。");
		document.submitform.siteurl.focus();
		return false;
	}
	if (document.submitform.user.value == "" || document.submitform.user.value == "您的称呼") {
		alert("请填写您的称呼。");
		document.submitform.user.focus();
		return false;
	}

	//验证邮箱
	var str = document.submitform.email.value;
	var result=str.match(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/); 
	if(result==null){
		alert('邮箱格式不正确');
		document.submitform.email.focus();
		return false; 
		
	} 

	if (document.submitform.qq.value == "" || document.submitform.qq.value == "联系QQ") {
		alert("请填写您的QQ。");
		document.submitform.qq.focus();
		return false;
	}

	if (document.submitform.code.value == "" || document.submitform.code.value.length != 4) {
		alert("请正确输入验证码");
		document.submitform.code.focus();
		return false;
	}

	//验证手机号码
	// var tel = document.submitform.tel.value;
	// var resulttel=tel.match(/1[3-9]+\d{9}/); 
	// if(resulttel==null){
	// 	alert('手机格式不正确');
	// 	return false; 
	// } 
	return true;
}

