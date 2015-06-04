<?php
namespace Atlas\Model;
use Admin\Model\PictureModel;
use Think\Upload\Driver\Qiniu\QiniuStorage;

class AtlasPictureModel extends PictureModel{
	
	//删除图片
	public function del($image_ids){
		$image_ids = is_array($image_ids) ? $file : explode(',', $image_ids);
		$qiniu = new QiniuStorage(C("UPLOAD_QINIU_CONFIG"));
		
		foreach ($image_ids as $id){
			$img = M('Picture')->find($id);
			if($img['type'] == 'qiniu'){
				//七牛
				$qiniu->del($img['path']);
			}
			unlink($img['path']);
			//移除数据
			M('Picture')->delete($id);
		}
	}
}