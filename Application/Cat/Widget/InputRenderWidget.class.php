<?php
namespace Cat\Widget;

use Think\Controller;

/**字段输入渲染部件
 * Class InputRenderWidget
 */
class InputRenderWidget extends Controller
{
    public function render($data)
    {
        $content = '<label class="col-xs-2 control-label" for="' . $data['field']['name'] . '">';

        if ($data['field']['can_empty'] == 0) {
            $content .= '<span class="c_empty">*</span>';
        }
        $content .= $data['field']['alias'];


        $content .= '</label><div class="col-xs-5"> ';
        $values = parseOption($data['field']['option']);
        unset($v);
        $data['field']['values'] = $values;
        if ($data['info_id'] != 0) {
            if ($data['field']['name'] == 'over_time') {
                $info = D('cat_info')->find($data['info_id']);
                $data['field_value'] = $info['over_time'];
            } else {
                $map_filed_val['info_id'] = $data['info_id'];
                $map_filed_val['field_id'] = $data['field']['id'];
                $field_vals = D('cat_data')->where($map_filed_val)->limit(1)->select();

                $data['field_value'] = $field_vals[0]['value'];
            }

        }

        switch ($data['field']['input_type']) {
            case IT_SINGLE_TEXT:
                $tpl = 'single_text';
                break;
            case IT_MULTI_TEXT:
                $tpl = 'multi_text';
                break;
            case IT_SELECT:
                $tpl = 'select';
                break;
            case IT_DATE:
                $tpl = 'date';
                break;
            case IT_EDITOR:
                $tpl = 'editor';
                break;
            case IT_RADIO:
                $tpl = 'radio';
                break;
            case IT_PIC:
                $tpl = 'pic';
                break;

            case IT_CHECKBOX:
                $tpl = 'checkbox';
                breal;
        }

        $this->assign($data);
        $content .= $this->fetch('Widget/InputRender/' . $tpl);


        $content .= '';
        if ($data['field']['tip'] != '') {
            $content .= ' <span class="help-block">*' . $data['field']['tip'] . '</span>';
        }

        if ($data['field']['over_hidden']) {
            $content .= '<span class="help-block">*该内容过期自动隐藏</span>';
        }
        $content .= "</ul></div>";
        return '<div class="form-group">' . $content . '</div>';
    }


}