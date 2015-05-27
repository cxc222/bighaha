<?php
function get_all_compat_version()
{
    return array(
        '200' => 'V2 开发者预览版【2.0.0】',
        '210'=>'V2 RC版【2.1.0】',
        '220'=>'V2 正式版【2.2.0】'
    );
}

function decode_compat($version)
{
    $version_list = get_all_compat_version();
    $result = array();
    foreach ($version as $v) {
        $result[] = $version_list[$v];
    }
    return $result;
}

function decode_compat_to_array_from_db($version)
{
    $version = str_replace('[', '', $version);
    $version = str_replace(']', '', $version);
    $version = explode(',', $version);
    return $version;
}

function decode_compat_by_str($version)
{
    $version = str_replace('[', '', $version);
    $version = str_replace(']', '', $version);
    $version = explode(',', $version);
    $versions = decode_compat($version);
    $return = '';
    foreach ($versions as $tag) {
        $return .= '<span class="label label-success" style="margin:5px">' . $tag . '</span>';
    }
    return $return;
}

function get_type_select($entity = 1)
{

    $type = D('AppstoreType')->where(array('status' => 1, 'entity' => $entity))->select();
    $options = array();
    $options[''] = '- 选择一个分类 -';
    foreach ($type as $v) {
        $options[$v['id']] = $v['title'];
    }
    return $options;
}

function display_fee($fee)
{
    if ($fee == 0) {
        return '免费';
    } else {
        return '￥ ' . $fee . ' 元';
    }
}

function display_cover($cover, $width = 90, $height = 90, $class = '')
{
    if (intval($cover) == 0) {
        $img = C('TMPL_PARSE_STRING.__IMG__');
        echo <<<Eof
 <img class="appstore_cover$class" src="{$img}/no_icon.png"/>
Eof;
    } else {
        $cover = getThumbImageById($cover, $width, $height);
        echo <<<Eof
<img class="appstore_cover$class" src = "{$cover}"/>
Eof;

    }
}

function display_download($id, $class = 'btn btn-primary')
{
    if (intval($id) == 0) {
        return '';
    }
    $version = D('AppstoreVersion')->find(intval($id));
    if (intval($version['fee']) != 0) {
        //TODO 判断付费
        return '付费版本';
    }
    if (intval($version['pack']) == 0) {
        return '未上传';
    }
    return '<a class="' . $class . '" href="' . U('index/download', array('id' => $id)) . '" target="_blank">下载</a>';
}

function display_download_times($times)
{
    if (0 && $times < 50) {
        return '少于50';
    } elseif ($times > 999) {
        return '999+';
    } else {
        return $times;
    }
}

function display_version($version_name, $label = false)
{
    if (text($version_name) == '') {
       $return=  $label == true ? '<span class="label">暂无新版</span>' : '暂无新版';
    } else {
        $return= $label == true ? '<span class="label label-success">' . text($version_name) . '</span>' : text($version_name);
    }
    return $return;
}

function is_tip($uid)
{
    $setting = D('AppstoreDeveloper')->find($uid);
    return !$setting['refuse_message'] || !$setting;
}