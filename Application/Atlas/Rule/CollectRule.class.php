<?php
/**
 * 基础采集配置
 * 
 */
namespace Atlas\Rule;
use Atlas\Model;

class CollectRule {
	public $curl;
	public $snoopy;
	public $atlasCollectionModel;
	public $RuleClass;
	
	function __construct() {
		Vendor ( 'Curl.Curl' );
		require_once ('ThinkPHP/Library/Vendor/Snoopy/Snoopy.class.php');
		require_once ('ThinkPHP/Library/Vendor/simplehtmldom/simple_html_dom.php');
		
		$this->curl = new \Curl\Curl ();
		$this->snoopy = new \Snoopy ();
		$this->atlasCollectionModel = D('Atlas/Atlas_collection');
	}
	
	/**
	 * 工场模式加载采集类
	 * 
	 */
	function execute($aId){
		$atlasCollection = $this->atlasCollectionModel->find($aId);
		
		/* $url = $atlasCollection['url'];
		$page_suffix = '{page}';
		$page_Count = $atlasCollection['page'];	//页码 */
		
		$atlasCollectionData['id'] = $aId;
		$atlasCollectionData['url'] = $atlasCollection['url'];
		$atlasCollectionData['page_count'] = $atlasCollection['page'];
		$atlasCollectionData['page_suffix'] = '{page}';
		$atlasCollectionData['start_id'] = $atlasCollection['start_id'];
		
		$class = '\Atlas\Rule\\'.$atlasCollection['className'];
		$this->RuleClass = new $class();
		$this->RuleClass->executeRule($atlasCollectionData);
		
		print_r($this->RuleClass);
		die();
	}
	
}