<?php

namespace Common\Api;

use Common\Exception\ApiException;

class Api {
	protected function apiSuccess($message, $extra = array()) {
		return $this->apiReturn ( true, $message, $extra );
	}
	protected function apiError($message) {
		throw new ApiException ( $message );
		return null; // 这句话是为了消除IDE的警告
	}
	protected function apiReturn($success, $message, $extra) {
		$result = array (
				'success' => boolval ( $success ),
				'message' => strval ( $message ) 
		);
		$result = array_merge ( $result, $extra );
		return $result;
	}
	
	/**
	 * 获取用户信息, 非关键数据
	 * @param unknown $uid
	 */
	protected function getUserStructure($uid)
	{
		//请不要在这里增加用户敏感信息，可能会暴露用户隐私
		$fields = array('uid', 'nickname', 'avatar32', 'avatar64', 'avatar128', 'avatar256', 'avatar512','score');
		return query_user($fields, $uid);
	}
}