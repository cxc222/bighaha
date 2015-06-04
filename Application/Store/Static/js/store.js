function doFav(info_id) {
    if (MID != 0) {
        $.post(U('store/Index/doFav'), {'id': info_id}, function (msg) {
            var btn = $('#store_btn_fav_' + info_id);
            if (msg.status == 1) {
                toast.success('收藏成功。');

                btn.removeClass('c_fav_likebf');
                btn.addClass('c_fav_liked');
                btn.attr('title', '取消收藏');
                btn.text('取消收藏')

            }
            else if (msg.status == 2) {
                toast.success('取消收藏成功。');
                btn.attr('title', '收藏');
                btn.addClass('c_fav_likebf');
                btn.removeClass('c_fav_liked');
                btn.text('加入收藏')
            }
            else if (msg.status == 3) {
                toast.error('不能收藏自己发布的内容。');
            }
            else {
                toast.error('未知情况，处理失败。');
            }
        }, 'json');
    }
    else {
        toast.error('请登陆后收藏。');
    }
}
function get_s_Infos(obj, entity_id, uid, info_id) {
    $(obj).parent().parent().find('li').removeClass('c_ul_s_active');
    $(obj).parent().addClass('c_ul_s_active');
    $('.c_ul_s_infos').load(
        U('cat/Index/_get_s_infos') + '&entity_id=' + entity_id
            + '&info_id=' + info_id + '&uid=' + uid);
}
function get_Infos(obj, entity_id) {
    $(obj).parent().parent().find('li').removeClass('c_ul_s_active');
    $(obj).parent().addClass('c_ul_s_active');
    $('.c_ul_s_infos').load(
        U('cat/Center/_get_infos') + '&entity_id=' + entity_id);
    $('#hd_info_id').val(0);
}
function set_selected(obj, info_id) {
    $(obj).parent().parent().find('li').removeClass('c_ul_s_active');
    $(obj).parent().addClass('c_ul_s_active');
    $('#hd_info_id').val(info_id);
}

function delInfo(id) {
    var ok = confirm('确定删除该商品？此操作无法恢复。');
    if (ok) {
        $.post(U('store/Index/delInfo'), {
            id: id
        }, function (msg) {
            if (msg.status) {
               toast.success('删除成功。','温馨提示');
                setTimeout(function () {
                    location.href = U('store/center/selling');
                }, 1000);
            } else {
                toast.error('删除失败。','错误提示');
            }
        }, 'json');
    }
}


/*M.addEventFns({
 'post_com': {
 click: function () {
 if (MID == 0) {
 ui.error('请登陆后发表评论。');
 return;
 }
 var oArgs = M.getEventArgs(this);
 $.post(U('cat/Index/_doCom'), {
 content: editor.getContent(),
 info_id: oArgs.id
 }, function (msg) {
 if (msg.status) {
 editor.setContent('');
 $('#c_no_com').html('');
 $('#c_com').prepend(msg.data);
 ui.success('评论发表成功。');

 } else {
 ui.error('评论发表失败。');
 }
 }, 'json');
 }
 },
 'del_info': {
 click: function () {
 var oArgs = M.getEventArgs(this);
 ui.box.query('确定删除该信息？', '删除信息', function () {

 $.post(U('cat/Index/_delInfo'), {
 info_id: oArgs.id
 }, function (msg) {
 if (msg.status) {
 ui.box.close();
 ui.success('删除成功。');
 setTimeout(function () {
 location.href = U('cat/Index/li') + '&name='
 + oArgs.entity;
 }, 1000);
 } else {
 ui.error('删除失败。');
 }

 }, 'json');
 });
 }
 },
 'send_info': {
 click: function () {
 var oArgs = M.getEventArgs(this);
 ui.box.show($('#box_send_entitys').html(), '选择发送的信息');
 }
 },
 'read_info': {
 click: function () {

 var oArgs = M.getEventArgs(this);
 $.post(U('cat/Center/_doRead'), {
 send_id: oArgs.id
 }, 'json');
 }
 },
 'get_back': {
 click: function (obj) {
 var oArgs = M.getEventArgs(this);
 $.post(U('cat/Center/_doGetBack'), {
 send_id: oArgs.id
 }, function (msg) {
 if (msg.status) {
 ui.success('撤回成功！');
 $('#s_' + oArgs.id).remove();
 } else {
 ui.error('撤回失败。');
 }
 }, 'json');
 }
 },
 'post_info': {
 click: function () {
 if ($('#search_uids').val() == '') {
 ui.error('请选择信息接收者。');
 return;
 }
 if ($('#hd_info_id').val() == 0) {
 ui.error('请选择发送的信息。');
 return;
 }
 editor.sync();
 $('#frm_main').submit();
 }
 },
 'show_cart': {
 click: function () {
 ui.box.load(U('cat/Center/_cart'), '购物车');
 }
 },
 'close_order': {
 click: function () {
 var oArgs = M.getEventArgs(this);
 ui.box.query('是否取消本订单？关闭后将不可恢复。', '订单', function () {
 $.post(U('cat/Center/_close_order'), {order_id: oArgs.id}, function (msg) {
 if (msg.status) {
 ui.box.close();
 ui.success('取消订单成功。');
 setTimeout("location.reload()", 1000);
 }
 else {
 msg.box.close();
 ui.error('订单关闭失败。');
 }
 }, 'json');
 });

 }
 },
 'mksure_order': {
 click: function () {
 var oArgs = M.getEventArgs(this);
 ui.box.query('确定已经收到货。', '订单', function () {
 $.post(U('cat/Center/_mksure_order'), {order_id: oArgs.id}, function (msg) {
 if (msg.status) {
 ui.box.close();
 ui.success('确认成功。');
 setTimeout("location.reload()", 1000);
 }
 else {
 ui.box.close();
 ui.error('确认失败。');
 }
 }, 'json');
 });

 }
 },
 'mksure_pay_order': {
 click: function () {
 var oArgs = M.getEventArgs(this);
 ui.box.query('确认买家已经支付？如果对方仍未支付，确认后又不发货，可能收到来自卖家的投诉。', '确认付款', function () {
 $.post(U('cat/Center/_mksure_pay_order'), {order_id: oArgs.id}, function (msg) {
 if (msg.status) {
 ui.box.close();
 ui.success('确认成功。');
 setTimeout("location.reload()", 1000);
 } else {
 ui.box.close();
 ui.error('确认失败，请联系管理员。');
 }
 }, 'json')
 });
 }
 }

 });

 */
/*sotre new*/
function pay_order(order_id) {
    var ok = confirm('是否使用账户余额支付本订单？');
    if (ok) {

    }
}