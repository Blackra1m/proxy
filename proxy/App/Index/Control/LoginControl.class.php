<?php
/**
 * 登录控制器
 */
class LoginControl extends Control{
	/**
	 * AJAX 异步登录验证
	 */
	public function ajax_login(){
		if(!IS_AJAX)
			$this->error("页面不存在");
		$username = Q("post.username");
		$pwd = Q("post.pwd", null, "md5");
		$passwd = M("user")->field("password")->where(array("username"=>$username))->find();
		if($pwd != $passwd["password"]){
			echo 0;
		} else {
			echo 1;
		}
	}
	/**
	 * 登录
	 */
	public function login(){
		if(!IS_POST)
			$this->error("页面不存在！");
		$username = Q("post.username");
		$pwd = Q("post.pwd", null, "md5");
		$user = M("user")->where(array("username"=>$username))->field("password,userlock,uid,userunion,usergroup")->find();
		if(empty($user))
			$this->error("用户不存在！");
		if($pwd != $user["password"])
			$this->error("用户名或者密码错误！");
		if($user["userlock"] == 1)
			$this->error("您已经被锁定，请联系管理员！");
		//$this->eve_exp($user["uid"]);
		$loginData = array(
			"logintime"	=> time(),
			"loginip" => ip::getClientIp(),
		);
		$qqau = Q("post.qqau");
		if($qqau)
			$loginData["qqau"] = $qqau;
		M("user")->where(array("uid"=>$user["uid"]))->save($loginData);
		// p($_POST);
		$auto = Q("post.auto");
		if($auto == "on")
			setcookie(session_name(), session_id(), C("COOKIE_TIME"), "/");
		session("username", $username);
		session("uid", $user["uid"]);
		session("userunion",$user["userunion"]);
		session("usergroup",$user["usergroup"]);
		$this->success("登录成功！正在跳转...");

	}
	/* //每天登录增加经验
	private function eve_exp($uid){
		//获得当天时间戳
		$zero = strtotime(date("Y-m-d"));
		//获得用户上次登录时间
		$logintime = M("user")->where(array("uid"=>$uid))->getField("logintime");
		//时间比对
		if($logintime< $zero){
			M("user")->inc("exp", "uid=$uid", C("LV_LOGIN"));
		}
	} */
	/**
	 * 退出登录
	 * @return [type] [description]
	 */
	public function out(){
		session(null);
		session_destroy();
		$this->success("退出成功！",U(__WEB__));
	}
}