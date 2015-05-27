<?php
namespace Cat\Widget;

use Think\Controller;

/**字段信息展示渲染部件
 * Class DisplayRenderWidget
 */
class DisplayRenderWidget extends Controller
{
    public function render($data)
    {
        $content = '<span class="bld">' . $data['field']['alias'] . '</span>：';
        //检测是否过期隐藏
        if ($data['overed'] && $data['field']['over_hidden']) {
            $content .= '<span class="cred" id="' . $data['data']['field']['name'] . '">过期隐藏</span>';
            return $content;
        }
        $content .= '<span  id="' . $data['data']['field']['name'] . '">';
        switch ($data['field']['input_type']) {
            case IT_SINGLE_TEXT: //单行文本
            case IT_MULTI_TEXT: //多行文本
                $content .= op_h($data['data']['data'][0]);
                break;
            case IT_EDITOR: //编辑器
                $content .= "<br/>" . op_h($data['data']['data'][0]);
                break;
            case IT_DATE: //日期
                $content .= date('Y-m-d', $data['data']['data'][0]);
                //dump($data['data']['data'][0]);exit;
                break;
            //选择框
            case IT_SELECT: //下拉框
                $content .= op_t($data['data']['data'][0]);
                break;
            case IT_RADIO: //单选框
                $content .= op_t($data['data']['data'][0]);
                break;
            case IT_PIC:
                //单图片
                if (intval($data['data']['data'][0]) == 0)
                    return '';
                $content .= '<a class="pic_field" target="_blank" href="' . get_cover($data['data']['data'][0],'path') . '"><img title="点击查看大图"  class="pic_size" src="' . getThumbImageById($data['data']['data'][0], 100, 100) . '"></a>';
                break;
            case IT_CHECKBOX:
                $content .= $data['data']['data'][0] . '&nbsp;&nbsp;';

                break;


        }
        $content .= '</span>';
        echo $content;
    }
}