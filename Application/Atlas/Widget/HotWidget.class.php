<?php
namespace Atlas\Widget;
use Think\Controller;
use Atlas\Api;
use Atlas;
class HotWidget extends Controller{
    
    /*
     * 获取热门的图集  like_count
     * 
     */
    public function lists($timespan = 604800, $limit = 6){
        
        $map['status']=1;
        $map['addtime']=array('gt',time()-$timespan);//一周以内
        $map['like_count'] = array('gt',50);
        
        $AtlasModel = D('Atlas/Atlas');
        $atlasCount = $AtlasModel->where($map)->count();
        
        if($atlasCount >= $limit){
            //列出来
            $ids = $AtlasModel->where($map)->limit($limit)->getField('id',true);
        }else{
            //随机取几条
            $randMap['status']=1;
            $randMap['addtime']=array('gt',time()-$timespan);//一周以内
            $ids = $AtlasModel->where($randMap)->limit($limit)->order("rand()")->getField('id',true);
        }
        $AtlasApi = new Atlas\Api\AtlasApi();
        foreach ($ids as $id){
            $lists[] = $AtlasApi->getAtlas($id);
        }
        $this->assign('lists', $lists);
        $this->display('Widget/hot');
    }
}