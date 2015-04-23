<?php
namespace Atlas\Controller;

use Think\Controller;
use Atlas\Api\AtlasApi;
use Think\Exception;
use Common\Exception\ApiException;

class IndexController extends BaseController {
	private $atlasApi;
	
	public function _initialize()
	{
		$this->atlasApi = new AtlasApi();
	}
	
    public function index(){
    	
    	$result = $this->atlasApi->listAllAtlas($page, 5);
    	print_r($result);
    	die();
    	$this->display();
    }
    
}