<?php
namespace Store\Model;

use Think\Model;

class CategoryModel extends Model
{
    protected $tableName = 'store_category';

    public function getCatAll()
    {
        $cats = $this->where('pid=0')->order('sort desc')->limit(9)->select();
        foreach ($cats as $key => $v) {
            $cats[$key]['children'] = $this->where('pid =' . $v['id'])->limit(3)->order('sort desc')->select();
            foreach ($cats[$key]['children'] as &$ccd) {
                $ccd['children'] = $this->where('pid=' . $ccd['id'])->order('sort desc')->select();
            }
            unset($ccd);
        }
        return $cats;
    }


    public function getAllBortherCats($type)
    {
        if ($type == 0) {
            return $this->getAllChildrenCats(0);
        } else {
            $categoryModel = $this;
            $category = $categoryModel->find($type);
            if (!$category['pid']) {
                return $this->getAllChildrenCats(0);
            } else {
                $category_parent = $categoryModel->find($category['pid']);
                if (!$category_parent['pid']) { //父分类的父分类不存在
                    return $this->getAllChildrenCats(0);
                } else if ($category_parent['pid']) {

                    return $this->getAllChildrenCats($category_parent['pid']);
                } else {
                    return $this->getAllChildrenCats($category_parent['id']);
                }
            }


        }
    }

    /**获取到Menu 目录树，带缓存
     * @return bool|mixed
     * @auth 陈一枭
     */
    public function getMenuTree($isHome=false)
    {
        if($isHome){
            $cats = S('store_cats_home');
            if (empty($cats)) {
                $cats = $this->getAllChildrenCats(0,9);
                S('store_cats_home', $cats, 600);
            }
            return $cats;
        }else{
            $cats = S('store_cats');
            if (empty($cats)) {
                $cats = $this->getAllChildrenCats(0,100);
                S('store_cats', $cats, 600);
            }
            return $cats;
        }

    }
    public function clearCache(){
        S('store_cats_home',null);
        S('store_cats', null);
    }

    public function getAllChildrenCats($pid = 0,$limit=9)
    {

        $cats = $this->where(array('pid' => $pid,'status'=>1))->order('sort desc')->limit($limit)->select();
        if (!$cats) {
            return false;
        }
        foreach ($cats as $key => $v) {
            $cats[$key]['children'] = $this->getAllChildrenCats($v['id']);
        }
        return $cats;
    }

    /**获得分类树
     * @param int  $id
     * @param bool $field
     * @return array
     * @auth 陈一枭
     */
    public function getTree($id = 0, $field = true)
    {
        /* 获取当前分类信息 */
        if ($id) {
            $info = $this->info($id);
            $id = $info['id'];
        }

        /* 获取所有分类 */
        $map = array('status' => array('gt', -1));
        $list = $this->field($field)->where($map)->order('sort desc')->select();
        $list = list_to_tree($list, $pk = 'id', $pid = 'pid', $child = '_', $root = $id);


        /* 获取返回数据 */
        if (isset($info)) { //指定分类则返回当前分类极其子分类
            $info['_'] = $list;
        } else { //否则返回所有分类
            $info = $list;
        }

        return $info;
    }

    /**
     * 获取分类详细信息
     * @param  milit   $id 分类ID或标识
     * @param  boolean $field 查询字段
     * @return array     分类信息
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function info($id, $field = true)
    {
        /* 获取分类信息 */
        $map = array();
        if (is_numeric($id)) { //通过ID查询
            $map['id'] = $id;
        } else { //通过标识查询
            $map['name'] = $id;
        }
        return $this->field($field)->where($map)->find();
    }


    /**分类树到下拉列表项
     * @param $tree
     * @param $level
     * @auth 陈一枭
     */
    public function getSelect($tree)
    {
        $parentSelect = array(0=>'-顶级分类-');
        foreach ($tree as $v) {
            $parentSelect[$v['id']] = $this->getLevelDecoration($this->getLevel($v['pid'], 0)) . $v['title'];
            if ($v['_'])
                $parentSelect = $parentSelect + $this->getSelect($v['_']);
        }
        return $parentSelect;
    }

    public function getLevelDecoration($level)
    {
        $str = '';
        for ($i = 1; $i < $level; $i++) {
            $str .= '—';
        }
        return $str;
    }

    /**获取到分类等级
     * @param $categoryId
     * @auth 陈一枭
     */
    public function getLevel($categoryId, $level = 0)
    {
        //查到父分类
        $parent = $this->where(array('id' => $categoryId))->find();
        $level += 1;
        if ($parent) {
            return $this->getLevel($parent['pid'], $level);
        } else {
            return $level;
        }
    }

    /**得到顶级分类ID
     * @param $id
     * @return mixed
     * @auth 陈一枭
     */
    public function getTopId($id)
    {
        $cat = $this->find($id);
        if ($cat['pid'] != 0) {
            return $this->getTopId($cat['pid']);
        } else {
            return $id;
        }
    }
}