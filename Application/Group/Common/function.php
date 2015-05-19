<?php
function is_joined($group_id)
{
    return D('Group/GroupMember')->getIsJoin(is_login(),$group_id);
}

function get_group_name($group_id){
    $group =  D('Group')->getGroup($group_id);
    return $group['title'];
}

function group_is_exist($group_id){
    $group =  D('Group')->getGroup($group_id);
    return $group ? true : false;
}

function post_is_exist($post_id){
    $post =  D('GroupPost')->getPost($post_id);
    return $post ? true : false;
}

function get_group_type($group_id){
    $group =  D('Group')->getGroup($group_id);
    return get_type_name($group['type_id']);

}

function get_type_name($type_id){
    $type =  D('GroupType')->getGroupType($type_id);
    return $type['title'];

}

function get_post_category($id){
    $cate =  D('GroupPostCategory')->getPostCategory($id);
    return $cate['title'];
}


function get_lou($k)
{
    $lou = array(
        2 => '沙发',
        3 => '板凳',
        4 => '地板'
    );
    !empty($lou[$k]) && $res = $lou[$k];
    empty($lou[$k]) && $res = $k . '楼';
    return $res;
}

function check_is_bookmark($post_id){
    return D('GroupBookmark')->exists(is_login(), $post_id);
}


function get_group_admin($group_id){
    return D('GroupMember')->getGroupAdmin($group_id);
}

function get_post_admin($post_id){
    $post = D('GroupPost')->getPost($post_id);
    $uids = get_group_admin($post['group_id']);
    $uids[] = $post['uid'];
    return array_unique($uids);
}

function get_reply_admin($reply_id){
    $reply = D('GroupPostReply')->getReply($reply_id);
    $post = D('GroupPost')->getPost($reply['post_id']);
    $uids = get_group_admin($post['group_id']);
    $uids[] = $reply['uid'];
    return array_unique($uids);
}


function get_lzl_admin($lzl_id){
    $lzl = D('GroupLzlReply')->getLzlReply($lzl_id);
    $post = D('GroupPost')->getPost($lzl['post_id']);
    $uids = get_group_admin($post['group_id']);
    $uids[] = $lzl['uid'];
    return array_unique($uids);
}


function get_group_creator($group_id){
    $group = D('Group')->getGroup($group_id);
    return $group['uid'];

}



function filter_post_content($content){
    $content = op_h($content);
    $content = limit_picture_count($content);
    $content = op_h($content);
    return $content;
}

function limit_picture_count($content){
  return   D('ContentHandler')->limitPicture($content,modC('GROUP_POST_IMG_COUNT',10,'GROUP'));
}



function cut_str($search,$str,$place=''){
   switch($place){
       case 'l':
           $result = preg_replace('/.*?'.addcslashes(quotemeta($search),'/').'/','',$str);
           break;
       case 'r':
           $result = preg_replace('/'.addcslashes(quotemeta($search),'/').'.*/','',$str);
           break;
       default:
           $result =  preg_replace('/'.addcslashes(quotemeta($search),'/').'/','',$str);
   }
    return $result;
}