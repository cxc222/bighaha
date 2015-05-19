<?php
/**
 * 所属项目 商业版.
 * 开发者: 陈一枭
 * 创建日期: 14-9-11
 * 创建时间: 下午1:09
 * 版权所有 想天软件工作室(www.ourstu.com)
 */

namespace Ucenter\Widget;

require_once('ThinkPHP/Library/Vendor/PHPImageWorkshop/ImageWorkshop.php');
use Think\Controller;
use PHPImageWorkshop\Core\ImageWorkshopLayer;
use PHPImageWorkshop\ImageWorkshop;

class UploadAvatarWidget extends Controller
{

    public function render($uid = 0)
    {
        $this->assign('user', query_user(array('avatar256', 'avatar128', 'avatar64'), $uid));
        $this->assign('uid', $uid);
        $this->display(T('Application://Ucenter@Widget/uploadavatar'));
    }

    public function getAvatar($uid = 0, $size = 256)
    {
        $avatar = M('avatar')->where(array('uid' => $uid, 'status' => 1, 'is_temp' => 0))->getField('path');
        if ($avatar) {
            if (is_sae()) {
                $avatar_path = $avatar;
            } else {
                if (!is_bool(strpos($avatar, 'http://'))) {
                    return $avatar . '/thumbnail/' . $size . 'x' . $size . '!';
                } else {
                    $avatar_path = "/Uploads/Avatar$avatar";
                }
            }
            return $this->getImageUrlByPath($avatar_path, $size);
        } else {
            //如果没有头像，返回默认头像
            if($uid==session('temp_login_uid')||$uid==is_login()){
                $role_id = session('temp_login_role_id') ? session('temp_login_role_id') : get_role_id();
            }else{
                $role_id=query_user('show_role',$uid);
            }
            return $this->getImageUrlByRoleId($role_id, $size);
        }
    }

    private function getImageUrlByPath($path, $size)
    {
        //TODO 重新开启缩略
        $thumb = getThumbImage($path, $size, $size, 0, true);
        // $thumb['src']=$path;
        $thumb = $thumb['src'];
        if (!is_sae()) {
            $thumb = getRootUrl() . $thumb;
        }
        return $thumb;
    }

    /**
     * 根据角色获取默认头像
     * @param $role_id
     * @param $size
     * @return mixed|string
     * @author 郑钟良<zzl@ourstu.com>
     */
    private function getImageUrlByRoleId($role_id, $size)
    {
        $avatar_id=S('Role_Avatar_Id_'.$role_id);
        if(!$avatar_id){
            $map = getRoleConfigMap('avatar', $role_id);
            $avatar_id = D('RoleConfig')->where($map)->field('value')->find();
            S('Role_Avatar_Id_'.$role_id,$avatar_id,600);
        }
        if ($avatar_id) {
            if ($size != 0) {
                $path=getThumbImageById($avatar_id['value'], $size, $size);
            }else{
                $path=getThumbImageById($avatar_id['value']);
            }
        }else{//角色没有默认
            if ($size != 0) {
                $default_avatar = "Public/images/default_avatar.jpg";
                $path=$this->getImageUrlByPath($default_avatar, $size);
            } else {
                $path=getRootUrl() . "Public/images/default_avatar.jpg";
            }
        }
        return $path;
    }

    public function cropPicture($uid, $crop = null, $ext = 'jpg')
    {
        //如果不裁剪，则发生错误
        if (!$crop) {
            $this->error('必须裁剪');
        }

        $path = "/Uploads/Avatar/" . $uid . "/original." . $ext;
        //获取文件路径
        $fullPath = substr($path, 0, 1) == '/' ? '.' . $path : $path;
        $savePath = str_replace('original', 'crop', $fullPath);
        $returnPath = str_replace('original', 'crop', $path);
        $returnPath = substr($returnPath, 0, 1) == '/' ? '.' . $returnPath : $returnPath;

        //解析crop参数
        $crop = explode(',', $crop);
        $x = $crop[0];
        $y = $crop[1];
        $width = $crop[2];
        $height = $crop[3];
        //是sae则不需要获取全路径
        if (strtolower(C('PICTURE_UPLOAD_DRIVER')) == 'local') {
            //本地环境
            $image = ImageWorkshop::initFromPath($fullPath);
            //生成将单位换算成为像素
            $x = $x * $image->getWidth();
            $y = $y * $image->getHeight();
            $width = $width * $image->getWidth();
            $height = $height * $image->getHeight();
            //如果宽度和高度近似相等，则令宽和高一样
            if (abs($height - $width) < $height * 0.01) {
                $height = min($height, $width);
                $width = $height;
            }
            //调用组件裁剪头像
            $image = ImageWorkshop::initFromPath($fullPath);
            $image->crop(ImageWorkshopLayer::UNIT_PIXEL, $width, $height, $x, $y);
            $image->save(dirname($savePath), basename($savePath));
            //返回新文件的路径
            return $returnPath;
        } elseif (strtolower(C('PICTURE_UPLOAD_DRIVER')) == 'sae') {
            //sae
            //载入临时头像
            $f = new \SaeFetchurl();
            $img_data = $f->fetch($fullPath);
            $img = new \SaeImage();
            $img->setData($img_data);
            $img_attr = $img->getImageAttr();
            //生成将单位换算成为像素
            $x = $x * $img_attr[0];
            $y = $y * $img_attr[1];
            $width = $width * $img_attr[0];
            $height = $height * $img_attr[1];
            //如果宽度和高度近似相等，则令宽和高一样
            if (abs($height - $width) < $height * 0.01) {
                $height = min($height, $width);
                $width = $height;
            }
            $img->crop($x / $img_attr[0], ($x + $width) / $img_attr[0], ($y) / $img_attr[1], ($y + $height) / $img_attr[1]);
            $new_data = $img->exec();
            $storage = new \SaeStorage();
            $thumbFilePath = str_replace(C('UPLOAD_SAE_CONFIG.rootPath'), '', dirname($savePath) . '/' . basename($savePath));
            $thumbed = $storage->write(C('UPLOAD_SAE_CONFIG.domain'), $thumbFilePath, $new_data);
            //返回新文件的路径
            return $thumbed;
        } elseif (strtolower(C('PICTURE_UPLOAD_DRIVER')) == 'qiniu') {

            $imageInfo = file_get_contents($fullPath . '?imageInfo');
            $imageInfo = json_decode($imageInfo);

            //生成将单位换算成为像素
            $x = $x * $imageInfo->width;
            $y = $y * $imageInfo->height;
            $width = $width * $imageInfo->width;
            $height = $height * $imageInfo->height;
            $new_img = $fullPath . '?imageMogr2/crop/!' . $width . 'x' . $height . 'a' . $x . 'a' . $y;

            //返回新文件的路径
            return $new_img;
        }

    }


} 