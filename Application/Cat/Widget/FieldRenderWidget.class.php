<?php
namespace Cat\Widget;
use Think\Controller;

class FieldRenderWidget extends Controller
{
    public function render($data)
    {
        $rs = $data['str'];
        $field = $data['field'];
        $value = '';

        switch ($field['field']['input_type']) {
            case IT_SINGLE_TEXT: //单行文本
            case IT_MULTI_TEXT:
                $value = op_t($field['data'][0]);
                break;
            case IT_EDITOR:
                $value = op_h($field['data'][0]);
                break;
            //选择框

            case IT_SELECT:
                $value = op_t($field['data'][0]);
                break;
            case IT_PIC:
                $value = getThumbImageById($field['data'][0]);
                break;
            case IT_RADIO:
                $value = op_t($field['data'][0]);
                break;
            case IT_CHECKBOX:
               $value=$field['data'][0];

                //$value =  t($field['values']['data'][$field['data'][0]]);
                break;

        }

        if ($data['only_value']) //如果只是显示值，用于html文件形式渲染
        {
            return $value;
        }

        $rs = str_replace('{$' . $field['field']['name'] . '}', $value, $rs);

        return $rs;
    }
}