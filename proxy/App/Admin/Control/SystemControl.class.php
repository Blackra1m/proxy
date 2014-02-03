<?php
/**
 * 系统管理控制器
 */
class SystemControl extends CommonControl{
	/**
	 * 网站配置
	 */
	public function web_set(){
		$this->display();
	}
	/**
	 * 播放器配置
	 */
	public function player_set(){
		$this->display();
	}
	/**
	 * 上传配置
	 */
	public function upload_set(){
		$this->display();
	}
	/**
	 * 修改后台用户密码
	 */
	public function passwd(){
		if(IS_POST){
			$passwdF = Q("post.passwdF", null, "trim,htmlspecialchars");
			$passwdS = Q("post.passwdS", null, "trim,htmlspecialchars");
			if(strlen($passwdF)<6)
				$this->error("密码不得少于6位！");
			if($passwdF != $passwdS)
				$this->error("两次密码不相同！");
			$aid = Q("session.aid", null, "intval");
			M("admin")->where(array("aid"=>$aid))->save(array("password"=>md5($passwdF)));
			$this->success("修改成功！");
		}
		$this->display();
	}
}
?>