admin.delField = function (p_field_id, p_alias) {
    if (confirm('是否删除字段' + p_alias + '？')) {
        $.post(U('cat/Admin/doDelField'), {'field_id': p_field_id}, function (msg) {
            location.reload();
        }, 'json');
    }

};
admin.delEntity = function (p_entity_id, p_alias) {
    if (confirm('是否删除实体' + p_alias + '？')) {
        $.post(U('cat/Admin/doDelEntity'), {'entity_id': p_entity_id}, function (msg) {
            location.reload();
        }, 'json');
    }

};
admin.delInfo = function (p_info_id, p_alias) {
    if (confirm('是否删除信息' + p_alias + '？')) {
        $.post(U('cat/Admin/doDelInfo'), {'info_id': p_info_id}, function (msg) {
            location.reload();
        }, 'json');
    }

};
admin.delCom = function (p_com_id) {
    if (confirm('是否删除该评论？')) {
        $.post(U('cat/Admin/doDelCom'), {'com_id': p_com_id}, function (msg) {
            location.reload();
        }, 'json');
    }
};
admin.active = function (p_info_id) {
    if (confirm('通过审核？')) {
        $.post(U('cat/Admin/doActive'), {info_id: p_info_id,active:1}, function (msg) {
            location.reload();
        }, 'json');
    }
};
admin.unactive = function (p_info_id) {
    if (confirm('通过审核？')) {
        $.post(U('cat/Admin/doActive'), {info_id: p_info_id,active:0}, function (msg) {
            location.reload();
        }, 'json');
    }
};
admin.changeTopmost = function (p_info_id, topmost) {
    $.post(U('cat/Admin/doTopmost'), {info_id: p_info_id, topmost:topmost}, function (msg) {
        location.reload();
    }, 'json');
}
admin.changeRecom = function (p_info_id, recom) {
    $.post(U('cat/Admin/doRecom'), {info_id: p_info_id, recom:recom}, function(msg) {
        location.reload();
    }, 'json');
}


admin.delInfos = function(info_id){
    if("undefined" == typeof(info_id) || info_id=='') info_id = admin.getChecked();
    if(info_id==''){
        toast.error('请选择要删除的信息');return false;
    }
    if(confirm('确定要删除选中的信息吗？')){
        $.post(U('cat/Admin/doDelInfos'),{info_id:info_id},function(msg){
            location.reload();
        },'json');
    }
};
/*广告位整合部分*/
admin.delAd = function (p_ad_id, p_alias) {
    if (confirm('是否删除广告' + p_alias + '？')) {
        $.post(U('cat/Admin/doDelAd'), {'ad_id': p_ad_id}, function (msg) {
            location.reload();
        }, 'json');
    }
};

function adspace_on_change_type() {
    var v = $(this).val();
    if(v == 1 || v == 0) {
        $('#dl_content_html').show();
        $('#dl_content_code').hide();
        for(var i=0;i<6;i++) {
            $('#dl_content_picture' + i).hide();
            $('#dl_content_link' + i).hide();
        }
    } else if(v == 2) {
        $('#dl_content_html').hide();
        $('#dl_content_code').show();
        for(var i=0;i<6;i++) {
            $('#dl_content_picture' + i).hide();
            $('#dl_content_link' + i).hide();
        }
    } else if(v == 3) {
        $('#dl_content_html').hide();
        $('#dl_content_code').hide();
        for(var i=0;i<6;i++) {
            $('#dl_content_picture' + i).show();
            $('#dl_content_link' + i).show();
        }
    }
}

function adspace_on_change_picture() {
}

$(function() {
    $('#dl_display_type input').change(adspace_on_change_type);
    $('#dl_content_picture input[type="file"]').change(adspace_on_change_picture);
    adspace_on_change_type();
});
/*广告位end*/
