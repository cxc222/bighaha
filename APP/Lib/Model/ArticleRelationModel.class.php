<?php
Class ArticleRelationModel extends RelationModel{
	protected $tableName = 'article';
	protected $_link = array(
		'attr' => array(
			'mapping_type' => MANY_TO_MANY,
			'mapping_name' => 'attr',
			'foreign_key' => 'bid',
			'relation_foreign_key' => 'aid',
			'relation_table' => 'shangfox_article_attr',
			),

			'cate' => array(
				'mapping_type' => BELONGS_TO,
				'foreign_key' => 'cid',
				'mapping_fields' => 'name', //只读取其中的一个字段
				'as_fields' => 'name:cate'//将字段提取为上层字段命名为cate  将字段name 重命名为cate
			)

		);

	public function getArticles ($type,$limit){
		$field = array('del');
		$where = array('del'=>$type);
		return $this->field($field,true)->where($where)->relation(true)->limit($limit)->order('time DESC')->select();
	}

	//获取某子栏目的id
	public function getArticlesId ($type=0,$cid,$limit){
		$field = array('del');
		$where = array('del'=>$type,'cid'=>$cid);
		return $this->field($field,true)->where($where)->limit($limit)->relation(true)->select();
	}

}