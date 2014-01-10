<?php
class SinaControl extends Control{
	/**
	 * 默认执行
	 * @return [type] [description]
	 */
	public function index(){
		header ( 'Content-type:text/xml;charset:utf-8;filename:新浪代理.xml' ); // 定义文件头
		echo "<?xml version=\"1.0\" encoding=\"utf-8\" ?>\n"; // 输出XML格式
		if ($this->_get("id")){
			echo $this->listpage ( $this->_GET ("id") )["xml"];
		} elseif ( $this->_GET ('vname') ) {
			echo $this->listpage ( $this->_GET ('vname') )["vName"];
		} else {
			$url = file_data ( 'http://www.m1905.com/vod/rank/t0a99o3.shtml');
			$preg = '/<dt class="li03 oh"><a href="http:\/\/www.m1905.com\/vod\/info\/(\d{4,8})\.shtml" target="_blank" title=".*" class=" pl28">.*<\/a><\/dt>/iUs';
			preg_match_all ( $preg, $url, $arr );
			foreach ( $arr [1] as $value ) {
				$xml .= $this->listpage ( $value )["xml_m"];
			}
			echo "<list>\n" . $xml . '</list>';
		}
	}
	/**
	 * 合并列表
	 * @param  string $id 单个影片ID
	 * @return [type]     [description]
	 */
	public function merge(){
		$id = $this->_get("id");
		$data = file_data('http://v.iask.com/v_play.php?vid='.$id);
		$obj = simplexml_load_string($data);
		$arr = $obj->durl;
		$xml = '<m starttype="0" label="" type="2" bytes="'.$obj->framecount.'" duration="'.$obj->timelength.'" bg_video="{xywh:[0,0,100P,100P]}" >'."\n";
		foreach ($arr as $val){
			$xml.= '<u bytes="" duration="'.$val->length.'" src="'.$val->url."?start={start_bytes}\" />\n";
		}
		echo $xml.'</m>';
	}
	/**
	 * 生成列表
	 * @param  string $id 视频ID
	 * @return array     视频列表及视频名称
	 */
	public function listpage($id){
		if (preg_match("/^\d+$/iUs", $id)){
			$xml = '<m type="merge" src="'.$this->merge($id).'" label="'."\" />\n";
		}else {
			$page = file_data("http://video.sina.com.cn/movie/detail/".$id);
			preg_match("/name:'(.*)',/iUs", $page, $vName);
			$vName = $vName[1];
			preg_match_all('/<div class="pic">.*<\/div>.*<a href="(.*)" target="_blank" id="exp_\d+" rel="\d+">(.*)<\/a><\/li>/iUs', $page, $arr);
			$arr = array_combine($arr[1], $arr[2]);
			$xml = "";
			foreach ($arr as $key=>$value){
				preg_match("/m\/[0-9a-z]*_(\d+)\.html/iUs", $key, $vid);
				$xml .= '<m type="merge" src="'.U("proxy",array("id"=>$vid[1])).'" label="'.$vName." ".$value."\" />\n";
			}
		}
		return array("xml"=>"<list>\n".$xml."</list>","vName"=>$vName);
	}
}
?>