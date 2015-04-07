<?php

	class Category{
		//组合一维数组
		Static public function unlimitedForLevel($cate,$html='--',$pid=0,$level=0){
			$arr = array();
			foreach ($cate as $v) {
				if($v['pid'] == $pid){
					$v['level'] = $level+1;
					$v['html'] = str_repeat($html,$level);
					$arr[] = $v;
					$arr = array_merge($arr,self::unlimitedForLevel($cate,$html,$v['id'],$level+1));
				}
			}
			return $arr;
		}

		//组合多维数组
		static public function unLimitedForLayer($cate,$name='child',$pid=0){
			$arr = array();
			foreach ($cate as $v) {
				if ($v['pid'] == $pid) {
					$v[$name] = self::unLimitedForLayer($cate,$name,$v['id']);
					$arr[] = $v;
				}
			}
			return $arr;
		}

		//传递一下子分类id，返回所有父级分类
		static public function getParents($cate,$id){
			$arr = array();
			foreach ($cate as $v) {
				if ($v['id'] == $id) {
					$arr[] = $v;
					$arr = array_merge(self::getParents($cate,$v['pid']),$arr);
				}
			}
			return $arr;
		}

		//传递个父级id，返回所有子分类id
		static public function getChildsId($cate,$pid){
			$arr = array();
			foreach ($cate as $v) {
				if ($v['pid'] == $pid) {
					$arr[] = $v['id'];
					$arr = array_merge($arr,self::getChildsId($cate,$v['id']));
				}
			}
			return $arr;
		}


		//传递个父级id，返回所有子分类
		static public function getChilds($cate,$pid){
			$arr = array();
			foreach ($cate as $v) {
				if ($v['pid'] == $pid) {
					$arr[] = $v;
					$arr = array_merge($arr,self::getChildsId($cate,$v['id']));
				}
			}
			return $arr;
		}

		//传递id，返回所有同级分类
		static public function getSister($cate,$pid){
			$arr = array();
			foreach ($cate as $v) {
				if ($v['pid'] == $pid) {
					$arr[] = $v;
				}
			}
			return $arr;
		}



	}
?>