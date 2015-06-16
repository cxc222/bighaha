<?php

/**通过ID获取到图片的缩略图
 * @param        $cover_id 图片的ID
* @param int    $width 需要取得的宽
* @param string $height 需要取得的高
* @param int    $type 图片的类型，qiniu 七牛，local 本地, sae SAE
* @param bool   $replace 是否强制替换
* @return string
* @auth zff
*/
function getAtlasThumbImageById($cover_id, $width = 100, $height = 'auto', $type = 0, $replace = false)
{
	$picture=S('picture_'.$cover_id);
	if(empty($picture)){
		$picture = M('Picture')->where(array('status' => 1))->getById($cover_id);
		S('picture_'.$cover_id,$picture);
	}

	if (empty($picture)) {
		return getRootUrl() . 'Public/images/nopic.png';
	}
	switch ($picture['type']) {
		case 'qiniu':
			$height = $height=='auto'?0:$height;
			$qiniuConfig = C('UPLOAD_QINIU_CONFIG');
			if(stripos($picture['path'],'imageMogr2') !== false){
				$picture['path'] = $picture['path'] . '/thumbnail/'. $width . 'x' . $height;
			}else{
				$width = $width? 'w/' . $width :'';
				$height = $height ? '/h/' . $height : '';
				$picture['path'] = $picture['path'] . '?imageView/' .$type.'/'. $width . $height;
			}
			return 'http://'.$qiniuConfig['domain'].'/'.$picture['path'];
			//return $picture['path'];
			break;
		case 'local':
			$attach = getThumbImage($picture['path'], $width, $height, $type, $replace);
			$attach['src'] = getRootUrl() . $attach['src'];
			return $attach['src'];
		case 'sae':
			$attach = getThumbImage($picture['path'], $width, $height, $type, $replace);
			return $attach['src'];
		default:
			return $picture['path'];
	}

}

/**
 * 使用七牛模块
 * 
 * @param $type 0=imageView2, 1=imageMogr2
 */
function getAtlasQiniuImageById($cover_id, $type = 0, $parameters){
    $picture=S('picture_'.$cover_id);
    if(empty($picture)){
        $picture = M('Picture')->where(array('status' => 1))->getById($cover_id);
        S('picture_'.$cover_id,$picture);
    }
    if (empty($picture)) {
        return getRootUrl() . 'Public/images/nopic.png';
    }
    
    switch ($picture['type']) {
        case 'qiniu':
            $qiniuConfig = C('UPLOAD_QINIU_CONFIG');
            
            if($type == 0){
                //imageView2
                $parameters ? $picture['path'] = $picture['path'] . '?imageView/'.$parameters : '';
            }else{
                //imageMogr2
                $parameters ? $picture['path'] = $picture['path'] . '?imageMogr2/'.$parameters : '';
            }
            //return $picture['path'];
            break;
    }
    return 'http://'.$qiniuConfig['domain'].'/'.$picture['path'];
}
