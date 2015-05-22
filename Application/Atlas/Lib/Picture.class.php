<?php
/**
 * picture 处理类
 * 
 */
namespace Atlas\Lib;
use Think\Upload;
use Think\Upload\Driver\Qiniu\QiniuStorage;
class Picture{
	/**
	 * 移动文件上传
	 */
	public function moveUpload($files, $setting, $driver = 'Qiniu', $config = null) {
	    
		$Picture = D('Picture');
		$qiniuConfig = C ( 'QINIU_CONFIG' );
		$qiniuStorage = new QiniuStorage ( $qiniuConfig );
		$savepath = 'Uploads/atlas/';
	
		$file = array (
				'name' => 'file',
				'fileName' => $savepath . basename ( $files ['name'] ),
				'fileBody' => file_get_contents ( iconv ( 'UTF-8', 'GB2312', $files ['tmp_name'] ) )
		);
		// $config = array();
		$info = $qiniuStorage->upload ( $config, $file );
		if ($info ['key']) {
			$value ['md5'] = md5_file ( $file ['tmp_name'] );
			$value ['sha1'] = sha1_file ( $file ['tmp_name'] );
				
			/* 记录文件信息 */
			if (strtolower ( $driver ) == 'sae') {
				$value ['path'] = $config ['rootPath'] . 'Picture/' . $savepath . basename ( $files ['name'] ); // 在模板里的url路径
			} else {
				if (strtolower ( $driver ) != 'local') {
					$value ['path'] = $savepath . basename ( $files ['name'] );
				} else {
					$value ['path'] = (substr ( $setting ['rootPath'], 1 ) . $savepath . basename ( $files ['name'] )); // 在模板里的url路径
				}
			}
			$value ['type'] = strtolower ( $driver );
				
			if ($Picture->create ( $value ) && ($id = $Picture->add ())) {
				$info ['id'] = $id;
			} else {
				// TODO: 文件上传成功，但是记录文件信息失败，需记录日志
				unset ( $info [$key] );
			}
			return $info; // 文件上传成功
		} else {
			$this->error = '七牛上传失败';
			return false;
		}
	}
}