<?php

class UserController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public $layout = '//layouts/wechat';


	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		$id = Frame::getStringFromRequest ( "id" );
		$code = Frame::getStringFromRequest ( "code" );
		$openid = Yii::app()->request->cookies['steel_openId'];
		//$openid = "oJIm6t6DIbGQvTW9IFXc0svaR0qU";
		$user = WxUser::model()->find("openid = '{$openid}'");
		if(!$user)
		{
			$this->redirect(array("site/index?code={$code}"));
			exit;
		}
		$company = WxUserCompany::model()->find("user_id = {$user->id} and is_deleted = 0 order by is_default desc");
		$userinfo['id'] = $user->id;
		$userinfo['username'] = $user->username;
		$userinfo['phone'] = $user->phone;
		$userinfo['pic'] = $user->pic?$user->pic:"/weixin/skin/images/aper_1.png";
		$userinfo['company'] = $company->company;
		$userinfo['company_id'] = $company->id;
		$userinfo['news'] = 0;
		$userinfo['qq'] = $user->qq;
		$userinfo['fax'] = $user->fax;
		$userinfo['userphone'] = Yii::app()->params['phone'];//;PHONE;
		if($user->user_id){
			$userphone = User::model()->findByPk($user->user_id);
			if($userphone->phone){
				$userinfo['userphone'] = $userphone->phone;
			}
		}
		$this->render("index",array("userinfo"=>$userinfo));
	}

	/**
	 * 修改手机号码公司
	 */
	public function actionEdit()
	{
		$id = Frame::getStringFromRequest ( "id" );
		$code = Frame::getStringFromRequest ( "code" );
		$type = Frame::getStringFromRequest ( "type" );
		$checkcode = Frame::getStringFromRequest ( "checkcode" );
		$openid = Yii::app()->request->cookies['steel_openId'];
		//$openid = "oJIm6t6DIbGQvTW9IFXc0svaR0qU";
		$user = WxUser::model()->find("openid = '{$openid}'");
		$view = "edit_phone";
		if($type == 2)
		{
			$view = "edit_company";
			$company_id = Frame::getStringFromRequest ( "company_id" );
			$company = WxUserCompany::model()->find("user_id = {$user->id} and id = {$company_id}");
		}

		if(!$user)
		{
			$this->redirect(array("site/index?code={$code}"));
			exit;
		}
		if($_POST)
		{
			if($type == 2)
			{
				$co_name = Frame::getStringFromRequest ( "co_name" );
				$company->company = $co_name;
				if($company->update())
				{
					$re0 = array("code"=>0,"info"=>"修改成功");
					echo json_encode($re0);
					exit;
				}
				exit;
			}
			if($type == 3)
			{
				$co_name = Frame::getStringFromRequest ( "co_name" );
				$company = new WxUserCompany();
				$company->user_id = $user->id;
				$company->company = $co_name;
				$company->created_at = time();
				if($company->insert())
				{
					$re0 = array("code"=>0,"info"=>"添加成功");
					echo json_encode($re0);
					exit;
				}
				exit;
			}
			$phone = Frame::getStringFromRequest ( "phone" );
			//查询验证码
			$code = WxUserYz::model()->find("phone = '{$phone}' order by created_at desc");
			$re = array();
			if($code)
			{
				if(time() - $code->created_at > 30*60)
				{
					$re = array("code"=>2,"info"=>"验证码已失效");
				}
				else if($code->yz != $checkcode)
				{
					$re = array("code"=>2,"info"=>"验证码输入错误");
				}
			}
			else
			{
				$re = array("code"=>2,"info"=>"请输入验证码");
			}
			if($re)
			{
				echo json_encode($re);
				exit;
			}
			$info = WxUser::model()->find("phone = '{$phone}'");
			if($info)
			{
				$re0 = array("code"=>2,"info"=>"手机号已注册");
				echo json_encode($re0);
				exit;
			}
			$user->phone = $phone;
			if($user->update())
			{
				$re0 = array("code"=>0,"info"=>"修改成功");
				echo json_encode($re0);
				exit;
			}
			exit;
		}
		$userinfo['id'] = $user->id;
		$userinfo['phone'] = $user->phone;
		if($type == 2)
		{
			$userinfo['company'] = $company->company;
			$userinfo['company_id'] = $company->id;
		}
		if($type == 3)
		{
			$view = "edit_company";
			$userinfo['phone'] = "";
			$userinfo['company_id'] = 0;
		}
		$this->render($view,array("userinfo"=>$userinfo));
	}

	/**
	 * 修改qq
	 */
	public function actionEditInfo()
	{
		$type = Frame::getStringFromRequest ( "type" );
		$openid = Yii::app()->request->cookies['steel_openId'];
		//$openid = "oJIm6t6DIbGQvTW9IFXc0svaR0qU";
		$user = WxUser::model()->find("openid = '{$openid}'");
		$view = "edit_qq";
		if($type == 2)
		{
			$view = "edit_fax";
		}

		if(!$user)
		{
			$this->redirect(array("site/getUser"));
			exit;
		}
		if($_POST)
		{
			if($type == 2)
			{
				$fax = Frame::getStringFromRequest ( "fax" );
				$user->fax = $fax;
				if($user->update())
				{
					$re0 = array("code"=>0,"info"=>"修改成功");
					echo json_encode($re0);
					exit;
				}
				exit;
			}

			$qq = Frame::getStringFromRequest ( "qq" );
			$user->qq = $qq;
			if($user->update())
			{
				$re0 = array("code"=>0,"info"=>"修改成功");
				echo json_encode($re0);
				exit;
			}
			exit;
		}
		$userinfo['id'] = $user->id;
		$userinfo['qq'] = $user->qq;
		$userinfo['fax'] = $user->fax;

		$this->render($view,array("userinfo"=>$userinfo));
	}

	/**
	 * 公司列表
	 */
	public function actionCompanyList()
	{
		$company_id = Frame::getStringFromRequest ( "company_id" );
		$code = Frame::getStringFromRequest ( "code" );
		$openid = Yii::app()->request->cookies['steel_openId'];
		//$openid = "oJIm6t6DIbGQvTW9IFXc0svaR0qU";
		$user = WxUser::model()->find("openid = '{$openid}'");
		if(!$user)
		{
			$this->redirect(array("site/index?code={$code}"));
			exit;
		}
		$company = WxUserCompany::model()->findAll("user_id = {$user->id} and is_deleted = 0");

		$this->render("company_list",array("company"=>$company));
	}

	/**
	 * 公司删除
	 */
	public function actionCompanyDelete()
	{
		$company_id = Frame::getStringFromRequest ( "company_id" );
		$code = Frame::getStringFromRequest ( "code" );
		$type = Frame::getStringFromRequest ( "type" );
		$openid = Yii::app()->request->cookies['steel_openId'];
		//$openid = "oJIm6t6DIbGQvTW9IFXc0svaR0qU";
		$user = WxUser::model()->find("openid = '{$openid}'");
		if(!$user){
			$this->redirect(array("site/index?code={$code}"));
			exit;
		}
		$company = WxUserCompany::model()->find("id = {$company_id} and user_id = {$user->id}");
		if($company)
		{
			if($type == 1)
			{
				$db = Yii::app()->db;
				$sql = "update wx_user_company set is_default = 0 where user_id = {$user->id}";
				$re = $db->createCommand($sql)->execute();
				$company->is_default = 1;
				$company->update();
				$re0 = array("code"=>0,"info"=>"");
				echo json_encode($re0);
				exit;
			}
			$company->is_deleted = 1;
			$company->update();
			$re0 = array("code"=>0,"info"=>"");
			echo json_encode($re0);
			exit;
		}

	}

	/**
	 * 获取微信openid
	 */
	public function getOpenId($code)
	{
		include (dirname(__FILE__)."/../components/weixinConfig.php");
		$appid = WX_APPID;
		$appsecret = WX_APPSECRET;
		//$code = Frame::getStringFromRequest ( "code" );
		$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$appsecret&code=".$code."&grant_type=authorization_code";
		$accessToken = Frame::fcGET($url);
		$dataArr = json_decode($accessToken);var_dump($dataArr);
		$openId = $dataArr->openid;
		if(!empty($openId))
		{
			if(!empty(Yii::app()->request->cookies['steel_openId']))
			{
				unset(Yii::app()->request->cookies['steel_openId']);
			}
			$userSign=new CHttpCookie("steel_openId", $openId);
			$userSign->expire=time()+3600*24*30;
			Yii::app()->request->cookies['steel_openId']=$userSign;
		}
		else
		{
			$openId=Yii::app()->request->cookies['steel_openId'];
		}
		return $openId;
	}

	/**
	 * 获取用户信息
	 */
	public function getUserMessage($openId){
		//获取acces_token
		$token=self::getAccess_token();
		$getwxurl = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$token&openid=$openId&lang=zh_CN";
		$messageJson = Frame::fcGET($getwxurl);
		$messageArr = json_decode($messageJson,true);

		return $messageArr['nickname'];
	}

	/**
	 * 获取acces_token
	 */
	public static function getAccess_token(){
		$dir=dirname(__FILE__);
		$dirpath=$dir."/../wxuploads/temp";
		if(!is_dir($dirpath)){
			mkdir($dirpath);
			chmod($dirpath, 0777);
		}
		$filename=$dirpath."/access_token.json";
		if(!file_exists($filename)){
			$fp=fopen($filename, "w+");
			fclose($fp);
		}

		$tmp = file_get_contents($filename);
		$tmp = json_decode($tmp,true);
		//获取accessToken时间
		$lastChange = $tmp['expire_time'];
		$currentTime=time();
		if($lastChange < $currentTime){		//文件上一次修改时间,还没有超过2小时
			include (dirname(__FILE__)."/../components/weixinConfig.php");
			$appid = WX_APPID;
			$appsecret = WX_APPSECRET;
			$getwxurl = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
			$accessTokenJson = Frame::fcGET($getwxurl);
			$accessTokenArr = json_decode($accessTokenJson,true);
			$tokenArr = array();
			$token = $accessTokenArr["access_token"];
			$tokenArr["expire_time"] = time() + 7000;
			$tokenArr["access_token"] = $token;
			$tokenJson = json_encode($tokenArr);
			$fp = fopen($filename, "w+");
			fwrite($fp, $tokenJson);
			fclose($fp);
		}else{
			$token = $tmp["access_token"];
		}

		return $token;

	}
}