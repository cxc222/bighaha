  //创建ajax引擎
  function getXmlHttpObject(){
    var myXmlHttpRequest;

    //不同的浏览器对象方法一不样
    if(window.ActiveXObject){
      xmlHttpRequest = new ActiveXObject('Microsoft.XMLHTTP');
    }else{
      xmlHttpRequest = new XMLHttpRequest();
    }
    return xmlHttpRequest;
  }

  //写一个函数来快捷获取id
  function getId(id){
   return document.getElementById(id);
  }

  //顶
  function ding(){
   myXmlHttpRequest = getXmlHttpObject();
   //怎么判断是否成功
   if (myXmlHttpRequest) {
    var url = "/Index/"+type+"/ding";
    var data = "id="+aid;
    myXmlHttpRequest.open('post',url,true); //打开请求
    myXmlHttpRequest.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    //使用回调 chuli 函数名
    myXmlHttpRequest.onreadystatechange = function run(){
      //判断是否成功
      if (myXmlHttpRequest.readyState==4) {
        if (myXmlHttpRequest.status == 200) {
          //数据处理
          var res_obj = eval("("+myXmlHttpRequest.responseText+")");
          if(res_obj.count == 0) {
            alert("哇噢，大爷您已经顶过了噢！");
            //getId('ding').innerText = res_obj.count;
          }else{
            getId('ding').innerHTML = res_obj.count;
          }
          
        };
      };
    };
    //如果是post，则填写数据
    myXmlHttpRequest.send(data);
   }
  }


  //踩
  function cai(){
   myXmlHttpRequest = getXmlHttpObject();
   //怎么判断是否成功
   if (myXmlHttpRequest) {
    var url = "/Index/"+type+"/cai";
    var data = "id="+aid;
    myXmlHttpRequest.open('post',url,true); //打开请求
    myXmlHttpRequest.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    //使用回调 chuli 函数名
    myXmlHttpRequest.onreadystatechange = function run(){

      //判断是否成功
      if (myXmlHttpRequest.readyState==4) {
        if (myXmlHttpRequest.status == 200) {
          //数据处理
          var res_obj = eval("("+myXmlHttpRequest.responseText+")");
          if(res_obj.count == 0) {
            //getId('cai').innerText = res_obj.count;
            alert("亲，麻烦您不要在踩了嘛~~");
          }else if(res_obj.count > 0){
            getId('cai').innerHTML = res_obj.count;
          }
        };
      };
    };
    //如果是post，则填写数据
    myXmlHttpRequest.send(data);
   }
  }  