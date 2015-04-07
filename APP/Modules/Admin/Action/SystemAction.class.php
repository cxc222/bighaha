<?php
	class SystemAction extends CommonAction{

		public function verify(){
			$this->display();
		}

		public function updateVerify(){
			if(F('verify',$_POST,CONF_PATH)){
				$this->success('修改成功',U(GROUP_NAME.'/System/verify'));
			}else{
				$this->error('修改失败,请修改'.CONF_PATH.'verify.php权限');
			}
		}

		public function webconfig(){
			$this->display();
		}

		public function updateWebconfig(){
			if(F('webconfig',$_POST,CONF_PATH)){
				$this->success('修改成功',U(GROUP_NAME.'/System/webconfig'));
			}else{
				$this->error('修改失败,请修改'.CONF_PATH.'webconfig.php权限');
			}
		}



	}


?>