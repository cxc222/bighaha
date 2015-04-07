<?php
Class TagLibHd extends TagLib{

	protected $tags = array(
		'nav' => array('attr'=> 'limit,order','close'=> 1),
		'hot' => array('attr'=> 'limit','close'=> 1)
	);

	public function _nav($attr,$content){
		$attr = $this->parseXmlAttr($attr);
		$str ="<?php 
			\$_nav_cate = M('cate')->order(\"{$attr['order']}\")->select();
			import('Class.Category',APP_PATH);
			\$_nav_cate = Category::unlimitedForLayer(\$_nav_cate);
			foreach (\$_nav_cate as \$_nav_cate_v) :
				extract(\$_nav_cate_v);
				\$url = U('/category-'.\$id);
		?>";
		$str .= $content;
		$str .= '<?php endforeach; ?>';
		return $str;	
	}

	public function _hot($attr,$content){
		$attr = $this->parseXmlAttr($attr);
		$limit = $attr['limit'];
		$str = '<?php ';
		$str .= '$field = array("id","title","click");';
		$str .= '$_hot_blog = M("blog")->field($filed)->order("click DESC")->limit('.$limit.')->select();';
		$str .= 'foreach($_hot_blog as $_hot_value):';
		$str .= 'extract($_hot_value);';
		$str .= '$url = U("/".$id);?>';
		$str .= $content;
		$str .= '<?php endforeach; ?>';
		return $str;	
	}

}
