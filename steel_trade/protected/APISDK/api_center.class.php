<?php
require_once(dirname(__FILE__).'/config.php');
function get_config($var,$path=''){
	global $$var;
	return $$var;
}
class api_center{
	public $appid;
	public $appsecret;
	public $api_center_host;
	public $cookie_host;
	public $api_center_cookie_name;
	/**
	 * Class constructor
	 * 
	 */
	public function __construct()
	{
		//todo
		$this->appid=get_config("appid");
		$this->appsecret=get_config("appsecret");
		$this->api_center_host=get_config("api_center_host");
		$this->cookie_host=get_config("cookie_host");
		$this->api_center_cookie_name=get_config("api_center_cookie_name");
		
		
	}
	
	
	
	/**
	 * 是否登录验证
	 * 自动读取域名COOKIE，不存在就跳转到接口中心登录页
	 * */
	public function isLogin(){
		$cookie=$_COOKIE[$this->api_center_cookie_name];
		$backurl=$this->getFullUrl();
		if(!$cookie)
		{
			//$this->goLogin($this->appid,$backurl);
			return false;
		}
		return true;
	}
	
	/**
	 * 跳转登录页 
	 * */
	public function goLogin($appid,$backurl="",$statusCode=302){
		$url=$this->api_center_host."/index.php/site/login?";
		if($appid)
		{
			$url.="AppId=".$appid;
		}
		if($backurl)
		{
			$url.="&BackUrl=".$backurl;
		}
		header('Location: http://'.$url, true, $statusCode);
		exit();
	}
	
	/**
	 * 登录授权
	 */
	public function loginAuthorization($username, $password) 
	{
		$result = $this->requestByCurl("http://".$this->api_center_host."/index.php/site/apiLogin", array(
				'appid' => $this->appid, 
				'username' => $username, 
				'password' => $password
		));
		return $result;
	}

	/**
	 * 退出登录
	 * */
	public function loginOut(){
		setcookie($this->api_center_cookie_name,"",time()-3600,"/",$this->cookie_host);
		//$backurl=$this->getFullUrl();
		
		$this->goLogin($this->appid,"");
	}
	
	/**
	 * 获取完整URL
	 * */
	public function getFullUrl()
	{
		$thishost=$this->getHost();
		if($thishost!=$this->api_center_host)
		{
			$backurl="http://".$this->getHost().$this->getLocalUrl();
		}
		return $backurl;
	}
	
	/**
	 * 获取当前域名
	 * */
	private function getHost()
	{
		return $_SERVER['HTTP_HOST'];
	}
	
	/**
	 * 获取当前URL地址
	 * */
	private function getLocalUrl()
	{
		return $_SERVER["REQUEST_URI"];
	}
	
	/**
	 * 验证单据
	 * */
	public function getResult($json)
	{
		$getjson=json_decode($json);
		if($getjson->Security->appid!=$this->appid || $getjson->Security->appsecret!=$this->appsecret)
		{
			return false;
		}
		return $json;
	}
	
	/**
	 * 推送单据
	 * param1:推送内容数组
	 * param2:操作类型
	 * param3:推送类型：如search等
	 * param4:用户ID
	 * */
	public function pushForm($json,$system,$type,$unid,$push_id)
	{
		
		if($unid && trim($system) && trim($json))
		{
			$time=time();
			$descript=new Descript();
			$body=new Body();
			$content=new Content();
			$serialNumber=$this->create_guid();
			$security=new Security();
			$md51_str = $this->appid.$system.$this->appsecret;
			$security->AppId=$this->appid;
			$security->AppSecret=$this->appsecret;
			$security->System=$system;
			$security->Time=$time;
			$descript->Version="1.0.0.0";
			$descript->SerialNumber=$serialNumber;
			$descript->Unid=$unid;
			$md51 = md5($md51_str);
			$security->Token = md5($time.$serialNumber.$md51);
			$request=new Reqeust();
			$request->Descript=$descript;
			$request->Security=$security;
			$content->Tables[]=json_decode($json);
			$body->Content=$content;
			$body->RevisionTime=time();
			$body->Verb=$type;
			$body->SendId=$push_id;
			$request->Body=$body;
			$json_str=json_encode($request);
//			$data["json"] = $json_str;
			//接口中心的接收数据地址
			//$posturl=$this->api_center_host."/";
			//$result=$this->requestByCurl($posturl, $data);
			$sdf = $security->Token;
			
			$res=$json_str;
			return $res;
		}
		
		return false;
	}
	
	/**
	 * 推送单据
	 * param1:查询内容数组
	 * */
	public function searchForm($json)
	{
		if($json)
		{
			$jsonres=json_decode($json);
			$time=time();
			$descript=new Descript();
			$apisearchbody=new ApiSearchBody();
			$content=new Content();
			$serialNumber=$this->create_guid();
			$security=new Security();
			$md51_str = $this->appid."search".$this->appsecret;
			$security->AppId=$this->appid;
			$security->AppSecret=$this->appsecret;
			$security->System="search";
			$security->Time=$time;
			$descript->Version="1.0.0.0";
			$descript->SerialNumber=$serialNumber;
			$md51 = md5($md51_str);
			$security->Token = md5($time.$serialNumber.$md51);
			$apisearchbody->FormId=$jsonres->No;
			$request=new Reqeust();
			$request->Descript=$descript;
			$request->Security=$security;
			$request->Body=$apisearchbody;
			$json_str=json_encode($request);
			$data["json"] = $json_str;
			//接口中心的查询地址
			$posturl=$this->api_center_host."/index.php/interface/search";
			$result=$this->requestByCurl($posturl, $data);
			
			return $result;
		}
		
		return false;
	}
	
	private function create_guid() {
		$charid = strtoupper(md5(uniqid(mt_rand(), true)));
		$hyphen = chr(45);// "-"
		$uuid = substr($charid, 0, 8).$hyphen
		.substr($charid, 8, 4).$hyphen
		.substr($charid,12, 4).$hyphen
		.substr($charid,16, 4).$hyphen
		.substr($charid,20,12);
		return $uuid;
	}
	
	private function requestByCurl($remote_server,$post_string,$use_post=true){
		if(function_exists('curl_init')){
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL,$remote_server);
			if($use_post){
				curl_setopt($ch,CURLOPT_POST, 1);
				curl_setopt($ch,CURLOPT_POSTFIELDS,$post_string);
			}
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
			$data = curl_exec($ch);
			curl_close($ch);
			return $data;
		}else{
			return '请先安装curl';
		}
	}
}

class Content{
	public $Tables;
}

class Body{
	public $Verb;
	public $Content;
	public $RevisionTime;
}
class ApiSearchBody{
	public $FormId;
}
class Descript{
	public $Version;
	public $SerialNumber;
	public $Unid;
}
class Security{
	public $Token;
	public $AppId;
	public $AppSecret;
	public $Time;
	public $System;
}
class Reqeust{
	public $Security;
	public $Descript;
	public $Body;
}
class Table{
	public $Columns;
	public $Records;
}
class Record{
	public $Fields;
}