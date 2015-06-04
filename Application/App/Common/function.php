<?php

/**
 * @author: caipeichao
 */
require_once(APP_PATH . 'Home/Common/function.php');


/**帖子内容图片分离
 * @param $aContent
 * @return mixed
 */
function text_part($aContent)
{

    $arr=array();
    preg_match_all("/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.png]))[\'|\"].*?[\/]?>/", $aContent, $arr); //匹配所有的图片

    $data ['image'] = $arr[1];
    $data['content'] = op_t($aContent);

    return $data;
}