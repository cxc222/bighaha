<?php
namespace Atlas\Controller;
use Think\Controller;
/**
 * 文件控制器
 * 主要用于下载模型的文件上传和下载
 */

class AtlasfileController extends Controller
{
    /**
     * 上传图片
     * @author zff
     */
    public function uploadAtlasPicture()
    {
        //TODO: 用户登录检测

        /* 返回标准数据 */
        $return = array('status' => 1, 'info' => '上传成功', 'data' => '');

        /* 调用文件上传组件上传文件 */
        $Picture = D('Admin/Picture');
        $pic_driver = C('PICTURE_UPLOAD_DRIVER');
        $info = $Picture->upload(
            $_FILES,
            C('PICTURE_UPLOAD'),
            C('PICTURE_UPLOAD_DRIVER'),
            C("UPLOAD_{$pic_driver}_CONFIG")
        );
        //TODO:上传到远程服务器
        
        /* 记录图片信息 */
        if ($info) {
            $return['status'] = 1;
            if ($info['Filedata']) {
                $return = array_merge($info['Filedata'], $return);
            }
            if ($info['download']) {
                $return = array_merge($info['download'], $return);
            }
            /*适用于自动表单的图片上传方式*/
            if ($info['file'] || $info['files']) {
                $return['data']['file'] = $info['file']?$info['file']:$info['files'];
            }
            /*适用于自动表单的图片上传方式end*/
            $aWidth=intval($_GET['width']);
            $aHeight=intval($_GET['height']);
            if($aHeight<=0){
                $aHeight='auto';
            }
            if($aWidth>0){
                $return['path_self']=getAtlasThumbImageById($return['id'],$aWidth,$aHeight);
            }
        } else {
            $return['status'] = 0;
            $return['info'] = $Picture->getError();
        }


        /* 返回JSON数据 */
        $this->ajaxReturn($return);
    }
}