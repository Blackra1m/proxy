<?php
class SohuControl extends Control {
	/**
	 * 默认执行
	 * @return [type] [description]
	 */
	public function index() {
		header ( 'Content-type:text/xml;charset:utf-8;filename:搜狐代理.xml' ); // 定义文件头
		echo "<?xml version=\"1.0\" encoding=\"utf-8\" ?>\n"; // 输出XML格式
		$fname = 'http://' . $_SERVER ["SERVER_NAME"] . $_SERVER ["PHP_SELF"]; // 文件HTTP完整路径
		if ($this->_GET ( 'id' )) { // 是否向地址栏传递URL参数
			echo $this->listpage ( $this->_GET ( 'id' ) )["xml"]; // 写入XML内容
		} elseif ($this->_GET ( 'hottel' )) { // 是否向地址栏传递PAGE参数
			$interface = file_data ( 'http://tv.sohu.com/frag/vrs_inc/phb_tv_day_' . $this->_GET ( 'hottel' ) . '.js' ); // 采集电视剧页面
			$interface = substr ( $interface, 18 );
			$obj = json_decode ( preg_replace ( '/,\s*([\]}])/m', '\\1', $interface ) ); // JSON解码
			$videos = $obj->videos;
			foreach ( $videos as $value ) {
				$sid = $value->sid;
				$tvName = $value->tv_name;
				$xml .= '<m label="' . $tvName . "\">\n" . $this->listpage ( $sid )["xml"] . "</m>\n";
			}
			echo "<list>\n" . $xml . '</list>';
		} elseif ($this->_GET ( 'vname' )) {
			echo $this->listpage ( $this->_GET ( 'vname' ) )["vName"];
		} else { // 没有向地址栏进行传递参数
			$interface = file_data ( 'http://tv.sohu.com/frag/vrs_inc/phb_tv_day_10.js' ); // 采集电视剧页面
			$interface = substr ( $interface, 18 );
			$obj = json_decode ( preg_replace ( '/,\s*([\]}])/m', '\\1', $interface ) ); // JSON解码
			$videos = $obj->videos;
			foreach ( $videos as $value ) {
				$sid = $value->sid;
				$tvName = $value->tv_name;
				$xml .= '<m label="' . $tvName . "\">\n" . $this->listpage ( $sid )["xml"] . "</m>\n";
			}
			echo "<list>\n" . $xml . '</list>';
		}
	}
	/**
	 * 生成列表
	 * @param  string $sid 视频ID
	 * @return array     视频列表及视频名称
	 */
	public function listpage($sid) {
		$interface = file_data ( 'http://hot.vrs.sohu.com/pl/videolist?playlistid=' . $sid ); // 采集接口
		$obj = json_decode ( preg_replace ( '/,\s*([\]}])/m', '\\1', iconv("gbk", "utf-8", $interface ) ) ); // JSON解码
		$vName = $obj->albumName;
		$videos = $obj->videos; // 获取视频内容数组
		$xml = "";
		foreach ( $videos as $value ) { // 遍历数组
			$vid = $value->vid; // 获取单个视频ID
			$vnames = $value->name; // 获取单个视频名称
			$xml .= '<m type="sohu" src="' . $vid . '" label="' . $vnames . "\" />\n"; // 写入XML内容
		}
		return array("xml"=>"<list>\n" . $xml . '</list>',"vName"=>$vName); // 输出剧集头和剧集列表
	}
}
?>