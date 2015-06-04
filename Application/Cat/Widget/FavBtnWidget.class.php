<?php
namespace Cat\Widget;
use Think\Controller;
/**收藏按钮
 * Class FavBtnWidget
 */
class FavBtnWidget extends Controller
{
    public function render($data)
    {
        $info = $data['info'];
        $info_id = isset($data['info_id']) ? $data['info_id'] : $info['id'];
        if (!D('Fav')->checkFav(is_login(), $info_id)) {
            //未收藏，就收藏

            $tpl_section = '<a id="cat_btn_fav_' . $info_id . '" title="收藏" class="cat_btn_fav c_fav_likebf" onclick="doFav(' . $info_id . ')" >&nbsp;</a>';
            return $tpl_section;

        } else {
            //已收藏，就取消收藏

            $tpl_section = '<a id="cat_btn_fav_' . $info_id . '" title="取消收藏" class="cat_btn_fav c_fav_liked" onclick="doFav(' . $info_id . ')" >&nbsp;</a>';
            return $tpl_section;


        }

    }
    public function display($data){
        $info = $data['info'];
        $info_id = isset($data['info_id']) ? $data['info_id'] : $info['id'];
        $tpl_section='';
        if (!D('Fav')->checkFav(is_login(), $info_id)) {
            //未收藏，就收藏

            $tpl_section = '<a id="cat_btn_fav_' . $info_id . '" title="收藏" class="cat_btn_fav c_fav_likebf" onclick="doFav(' . $info_id . ')" >&nbsp;</a>';
            echo $tpl_section;

        } else {
            //已收藏，就取消收藏

            $tpl_section = '<a id="cat_btn_fav_' . $info_id . '" title="取消收藏" class="cat_btn_fav c_fav_liked" onclick="doFav(' . $info_id . ')" >&nbsp;</a>';
            echo $tpl_section;


        }
    }
}