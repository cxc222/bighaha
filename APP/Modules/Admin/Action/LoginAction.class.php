<?php
	class LoginAction extends Action {
		
		//登录页面视图
		public function index(){
			$this->display();
		}

		//登录表单
		public function login(){
			if(!IS_POST) halt('页面不存在');
			if(I('code','','strtolower') != session('verify')) $this->error("验证码错误");
			$db = M('user');
			$user = $db->where(array('username'=>I('username')))->find();
			if (!$user || $user['password'] != I('password','','md5')) {
				$this->error("帐号或者密码错误");
			}

			//更新最后一次的时间和ip
			$data = array(
				'id' => $user['id'],
				'logintime' => time(),
				'loginip' => get_client_ip()
				);
			$db->save($data);

			session('uid',$user['id']);
			session('username',$user['username']);
			session('logintime',date('Y-m-d H:i:s'),$user['logintime']);
			session('loginip',$user['loginip']);
			redirect(__GROUP__.'/Index/index');

		}

		public function verify(){
			import('Class.Image',APP_PATH);
			Image::verify();
		}



	}


?>