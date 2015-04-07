<?php
Class GifRelationModel extends RelationModel{
	protected $tableName = 'gif';
	protected $_link = array(
		'attr' => array(
			'mapping_type' => MANY_TO_MANY,
			'mapping_name' => 'attr',
			'foreign_key' => 'bid',
			'relation_foreign_key' => 'aid',
			'relation_table' => 'shangfox_gif_attr',
			),

			'gifcate' => array(
				'mapping_type' => BELONGS_TO,
				'foreign_key' => 'cid',
				'mapping_fields' => 'name', //只读取其中的一个字段
				'as_fields' => 'name:cate'//将字段提取为上层字段命名为cate  将字段name 重命名为cate
			)

		);

	public function getGifs ($type=0){
		$field = array('del');
		$where = array('del'=>$type);
		return $this->field($field,true)->where($where)->relation(true)->order('id')->select();
	}

	//获取某子栏目的id
	public function getGifsId ($type=0,$cid){
		$field = array('del');
		$where = array('del'=>$type,'cid'=>$cid);
		return $this->field($field,true)->where($where)->relation(true)->select();
	}

}