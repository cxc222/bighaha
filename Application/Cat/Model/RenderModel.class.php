<?php
namespace Cat\Model;

use Think\Model;

class RenderModel extends Model
{
    public function renderInfo($info_id)
    {
        $info = D('cat_info')->find($info_id);
        $entity = D('cat_entity')->find($info['entity_id']);
        $tpl = $entity['use_list'];
        switch ($tpl) {
            case -1:
                $tpl_html = R('DefaultInfoLine/render', array(array('info' => $info)), 'Widget');
                break;
            case 0: //解析预置模板
                $tpl_html = $entity['tpl_list'];
                $tpl_html = $this->A($tpl_html, $info);
                break;
            default:
                //自定义模板，通过tpl的id来确定模板
                $tpl_html = $entity['tpl' . $tpl];
                $tpl_html = $this->A($tpl_html, $info);

        }
        return $tpl_html;
    }

    public function A($tpl, $info)
    {

        $info['data'] = D('Data')->getByInfoId($info['id']);

        $new_tpl = $this->replaceFieldData($tpl, $info);
        $new_tpl = $this->handleSysTags($new_tpl, $info);

        return $new_tpl;
    }


    /**拼接列表项数据
     * @param $tpl_html
     * @param $infos
     * @param $_class
     * @param $_type
     * @return string
     */
    public function renderInfoLi($tpl_html, $infos, $_class, $_type)
    {

        //拼接头
        $rs = '<div><ul class="' . $_class . '">';
        if ($_type == 'list') {
            $rel_data = $infos['data'];
        } else {
            $rel_data = $infos;
        }
//dump($rel_data);
        foreach ($rel_data as $v) {
            $v['data'] = D('Data')->getByInfoId($v['id']);
            //组装li标签
            $tpl_section = $this->replaceFieldData($tpl_html, $v);
            $tpl_section = $this->handleSysTags($tpl_section, $v);
            $rs .= '<li class="c">' . $tpl_section . "</li>";
        }

        //拼接尾，如果是list则加入分页
        if ($_type == 'list') {
            return $rs . '</ul></div><div class="page">' . $infos['html'] . "</div>";
        } else {
            return $rs . '</ul></div>';
        }
    }


    /**处理系统的标签
     * @param $tpl_section 待处理的原文
     * @param $v 数据
     * @return mixed
     */
    public function handleSysTags($tpl_section, $v)
    {

        $tpl_section = R('SysTagRender/render', array(array('tpl' => $tpl_section, 'info' => $v)), 'Widget');
        return $tpl_section;
    }

    /**替换变量
     * @param $str 用于替换的模板
     * @param $data 用于替换的数据
     */
    public function replaceFieldData($str, $data)
    {
        foreach ($data['data'] as $v) {

            $str = R('FieldRender/render', array(array('str' => $str, 'field' => $v)), 'Widget');
        }

        return $str;
    }


}