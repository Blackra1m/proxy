<?php
/**
 * 优酷视频采集控制器
 */
class YoukuControl extends CommonControl {
	/**
	 * 默认执行
	 */
	public function index() {
		header ( 'Content-type:text/xml;charset:utf-8;filename:优酷代理.xml' ); // 定义文件头
		echo "<?xml version=\"1.0\" encoding=\"utf-8\" ?>\n"; // 输出XML格式
		if ($id = Q ( "get.id" ) ) { // 是否向地址栏传递URL参数
			$xml = $this->cache_collect("youku_" . $id);
			if ($xml != 1 && !$xml) {
				echo $xml;
			}else{
				if (Q ( "get.link" )) {
					$xml = $this->listpage ( $id, true );
				}else{
					$xml = $this->listpage ( $id ); // 写入XML内容
				}
				$xml = $xml["xml"];
				$this->cache_collect($id, 1, $xml, "youku_");
				echo $xml;
			}
		} elseif (Q ( "get.page" )) { // 是否向地址栏传递PAGE参数
			$top = file_data ( 'http://tv.youku.com/top/' ); // 采集电视剧排行页面
			preg_match_all ( '#<a title="(.*)" href="http://www.youku.com/show_page/id_(\w+\-*\=*).html" charset="[0-9\-]+" target="_blank">.*</a>#iUs', $top, $arrTop ); // 正则表达式
			$combine = array_combine ( $arrTop [1], $arrTop [2] );
			$xml = "";
			foreach ( $combine as $key => $value ) { // 循环输出剧集XML列表
				$lists = $this->listpage ( 'http://www.youku.com/show_page/id_' . $value . '.html', $value );
				$xml .= '<m label="' . $key . "\">\n" . $lists["xmlm"] . "</m>\n";
			}
			echo "<list>\n" . $xml . '</list>';
		} elseif (Q ( "get.vname" )) {
			$vName = $this->listpage ( Q ( "get.vname" ) );
			echo $vName["vName"];
		}  else { // 没有向地址栏进行传递参数
			for($i = 1; $i <= 10; $i ++) { // 循环输出电视剧排行
				$lists .= '<m list_src="' . U("Data/Youku/index", array( "page" => $i )) . '" label="优酷电视剧 第' . $i . "页\" />\n";
			}
			echo "<list>\n" . $lists . '</list>';
		}
	}
	/**
	 * 生成列表
	 * @param  string $id 视频ID
	 * @param blean $link 是否使用合并插件
	 * @return array     视频列表及视频名称
	 */
	public function listpage( $id, $link = false ) {
		if (preg_match("/^[0-9a-z]+$/",$id,$arr)){
			$page = file_data("http://www.youku.com/show_page/id_".$id.".html");
			preg_match ( '/<span class="name">(.*)<\/span>/iUs', $page, $arr );
			$vName = $arr [1];
			$xml = "";
			if(!preg_match_all( '/<li data="reload_(\d+)"/iUs', $page, $arr))
				$arr[1][0] = 1;
			$preg = '/<a\s*(?:charset=".*" title="(.*)")?\s*href="http:\/\/v\.youku\.com\/v_show\/id_(\w+)\.html"\s*(?:title="(.*)"\s*charset=".*")?\s*target="_blank">.*<\/a><\/li>/iUs';
			foreach ($arr[1] as $value){
				$url = file_data( "http://www.youku.com/show_episode/id_" . $id . ".html?dt=json&divid=reload_" . $value . "&__rt=1&__ro=reload_" . $value );
				preg_match_all($preg, $url, $ar);
				if (empty($ar[1][0]))
					$ar[1] = $ar[3];
				$combine = array_combine($ar[1], $ar[2]);
				foreach ($combine as $k => $v) {
					if( $link ){
						$xml .= '<m type="merge" src="' . U("data/youku/merge", array( "id" => $v )) . '" label="' . $k . '" />'."\n";
					}else{
						$xml .= '<m type="youku" src="' . $v . '" label="' . $k . '" />'."\n";
					}
				}
			}
		}else {
			$page = file_data ( "http://v.youku.com/v_show/id_".$id.".html" );
			preg_match ( '/<title>(.*)<\/title>/iUs', $page, $arr );
			$vName = explode("—", $arr [1]);
			$vName = $vName[0];
			if ($link) {
				$xml = '<m type="merge" src="' . U("data/youku/merge", array( "id" => $id )) . '" label="' . $vName . '" />'."\n";
			}else{
				$xml = '<m type="youku" src="' . $id . '" label="' . $vName . "\" />\n";
			}
		}
		return array("xmlm"=>$xml,"xml"=>"<list>\n" . $xml . '</list>',"vName"=>$vName);
	}
	/**
	 * 插件代理所用
	 */
	public function proxy() {
		if (Q ( "get.id" ))
			$this->error("页面不存在！");
		echo file_data ( 'http://v.youku.com/player/getPlayList/VideoIDS/' . Q ( "get.id" ) );
	}
	/**
	 * 无插件获取真实地址
	 */
	public function links(){
		if ($id = Q ( "get.id" ))
			header("Location: http://m.youku.com/wap/pvs?id=".$id."&format=3gphd");
	}
	/**
	 * 合并插件
	 */
	public function merge(){
		if (!$id = Q ( "get.id" ))
			$this->error("页面不存在！");
		//$md5 = "YUhSMGNEb3ZMM3B0Y0RRdVlXeHBZWEJ3TG1OdmJTOTVhMmhrTG5Cb2NEOTJhV1E5"; //超清
		$md5 = "YUhSMGNEb3ZMM3B0Y0RRdVlXeHBZWEJ3TG1OdmJTOTVheTV3YUhBL2RtbGtQUT09"; //高清
		//$md5 = "YUhSMGNEb3ZMM3B0Y0RRdVlXeHBZWEJ3TG1OdmJTOTVhM05rTG5Cb2NEOTJhV1E5"; //标清
		$md5 = base64_decode(base64_decode($md5));
		$src= ''.$md5.''.$id.'';
		$str = file_data($src);
		$json = json_decode( $str );
		$vidEncoded = $json->vidEncoded;
		$streamsizes = $json->streamsizes;
		$seconds = $json->seconds;
		$vlist = $json->vlist;
		$xml = '<m starttype="0" label="' . $vidEncoded . '" type="mp4" bytes="' . $streamsizes . '" duration="'.$seconds.'" bg_video="" lrc="">'."\n";
		foreach ($vlist as $value) {
			$xml .= '<u bytes="' . $value->size . '" duration="' . $value->seconds . '" src="' . $value->link . '?start={start_seconds}" />'."\n";
		}
		$xml .= '</m>';
		header ( 'Content-type:text/xml;charset:utf-8;filename:优酷真实代理.xml' );
		echo $xml;
	}
}
?>