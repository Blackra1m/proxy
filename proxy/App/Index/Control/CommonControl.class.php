<?php
/**
 * 公共控制器
 */
class CommonControl extends Control{
	/**
	 * 加载页面
	 */
	public function url(){
		if (!IS_GET)
			$this->error("页面不存在！");
		echo file_data($this->_get("url"));
	}
}
?>