<?php

namespace Cat\Widget;

use Think\Controller;

/**信息列表部件
 * Class InfoListWidget
 */
class InfoListWidget extends Controller
{
    private $_class = 'cat_ul_list'; //设置的类
    private $_type = '';

    /**
     * @param mixed $data
     * tpl 整数 模板
     * type list or limit 模板构造型
     * num 显示的记录条数
     * order 排序
     * entity_id 实体的id，用于查询
     * name 实体的名，用于查询，优先级低于entity_id
     * @return string
     */
    public function render($data)
    {


        define('NORES', '');
        /*初始化所有的参数*/
        // 从$data中读取参数
        $this->_type = isset($data['type']) ? $data['type'] : 'limit';
        $this->_class = isset($data['class']) ? $data['class'] : 'cat_ul_list';
        $tpl = isset ($data['tpl']) ? $data['tpl'] : -1;
        $num = isset($data['num']) ? $data['num'] : 10;
        $data['order'] = op_t($data['order']);
        $order = $data['order'] != '' ? $data['order'] : 'update_time desc,create_time desc';
        $recom = isset($data['recom']) ? $data['recom'] : false;
        // 置顶排序
        $order = 'top desc,' . $order;
        $map = array();
        if ($recom) {
            $map['recom'] = 1;
        }
        if(I('post.title','','op_t')){
            $map['title']=array('like','%'.I('post.title','','op_t').'%');
        }
        /*初始化所有的参数end*/
        $map['status'] = 1;
        /*获取到查询的条件*/
        $entity = $this->getEntity($data);
        $map['entity_id'] = $entity['id'];
        /*获取到查询的条件end*/


        $filted_ids = null; //初始的情况下是不做限定的
        if (isset($data['map']) && count($data['map'])) {
            /*清除干扰条件*/
            $map_params = $this->unsetOtherParm($data);
            /*清除干扰条件end*/

            foreach ($map_params as $param_name => $param_value) {

                if ($param_value == '' || $param_name == 'p') {
                    //如果这个参数是没有值的，就略过
                    continue;
                }
                /*查出field的field_id*/
                $field_map['name'] = $param_name;
                $field_map['entity_id'] = $map['entity_id'];
                $search_field = D('cat_field')->where($field_map)->find();
                $field_id = $search_field['id'];
                /*查出field的field_idend*/
                $data_map['field_id'] = $field_id;
                if ($search_field['input_type'] == IT_SINGLE_TEXT || $search_field['input_type'] == IT_MULTI_TEXT || $search_field['input_type'] == IT_EDITOR) {
                    $data_map['value'] = array('like', '%' . $param_value . '%');
                } else if ($search_field['input_type'] == IT_CHECKBOX) { //处理多选框，无法实现隔项选择查询的功能
                    //重新升序整理数组
                    $value_array = explode(',', $param_value);
                    sort($value_array, SORT_NUMERIC);
                    $sear_value = implode(',', $value_array);
                    $data_map['value'] = array('like', '%' . $sear_value . '%');
                } else {
                    $data_map['value'] = $param_value;
                }
                if ($filted_ids != null) {
                    //如果不是第一次被过滤，就需要把ids作为条件，在此基础上查询
                    $data_map['id'] = array('in', implode(',', $filted_ids));
                }
                //进行查询
                $cat_data = D('cat_data')->where($data_map)->select();

                //更新info_ids
                if (count($cat_data) == 0) {
                    echo NORES;
                    return;
                }
                $filted_ids = getSubByKey($cat_data, 'info_id');

            }
        }

        if ($filted_ids != null) {
            //如果不为nul，意味着已经被前面的查询影响到了，就需要把过滤结果更新到条件中
            $map['info_id'] = array('in', implode(',', $filted_ids));
            if ($filted_ids == '') {
                echo NORES; //如果发现已经受影响，并且为空
                return;
            }

        }
        //获取数据
        if ($this->_type == 'list') {
            $infos = D('Info')->getList($map, $num, $order);
            $info_count = count($infos['data']);
        } else {
            $infos = D('Info')->getLimit($map, $num, $order);
            $info_count = count($infos);
        }

        /*确定模板*/
        if ($tpl == -1) {
            //如果模板未设置，则使用entity所设定的list模板
            $tpl = $entity['use_list'];
        }
        /*确定模板end*/


        //根据获取到的模板id来渲染
        switch ($tpl) {
            case 'recom': // 推荐信息模板
                $tpl_html = R('RecomLiTpl/render', array(array('infos' => $infos, 'class' => $this->_class, 'type' => $this->_type, 'entity' => $entity)), 'Widget');
                break;
            case -1: //自动生成

                if ($info_count == 0) {
                    echo NORES;
                    return;
                }
                $tpl_html = R('DefaultLiTpl/render', array(array('infos' => $infos, 'class' => $this->_class, 'type' => $this->_type)),'Widget');
                break;
            case 0: //解析预置模板
                if ($info_count == 0) {
                    echo NORES;
                }
                if (strpos($tpl, '.html')) {
                    foreach ($infos['data'] as &$v) {
                        $v['user'] = query_user(array('nickname', 'space_url'), $v['uid']);
                    }
                    unset($v);


                    $tpl_html = $this->fetch('Application/cat/Tpl/default/Tpls/' . $tpl, array('infos' => $infos));
                    break;
                }
                $tpl_html = $entity['tpl_list'];
                $tpl_html = D('Render')->renderInfoLi($tpl_html, $infos, $this->_class, $this->_type);
                break;
            default: //自定义模板，通过tpl的id来确定模板
                $tpl_html = $entity['tpl' . $tpl];
                $tpl_html = D('Render')->renderInfoLi($tpl_html, $infos, $this->_class, $this->_type);

        }

        echo $tpl_html;
    }


    /**自动构建模板
     * @return string
     */
    public function buildTpl()
    {
        return '';
    }

    /**
     * @param $data
     * @return mixed
     */
    public function unsetOtherParm($data)
    {
        $li = $data['map'];
        unset($li['name']);
        unset($li['entity_id']);
        unset($li['app']);
        unset($li['act']);
        unset($li['mod']);
        unset($li['page']);
        unset($li['title']);
        unset($li['__hash__']);
        return $li;
    }


    private function getEntity($data)
    {
        if (intval($data['entity_id']) != 0) {
            //获取预置的模板
            $entity = D('cat_entity')->find(intval($data['entity_id']));
            return $entity;
        } else {
            //通过name查到entity,和entity的id
            $map_t['name'] = $data['name'];
            $entity = D('cat_entity')->where($map_t)->find();
            return $entity;
        }
    }
}