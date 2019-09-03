<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public $layout = '//layouts/wechat';

	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * 
	 */
	public function actionGetUser()
	{
		include (dirname(__FILE__)."/../components/weixinConfig.php");
		
		$host = Yii::app()->request->getHostInfo();
		$REDIRECT_URI = urlencode($host."/wechat.php/site/index");
		
		
		$appid = WX_APPID;
		$appsecret = WX_APPSECRET;
		
		$weixin_url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appid}&redirect_uri={$REDIRECT_URI}&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect";
		$this->redirect($weixin_url);
		return false;
	}
	
	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		//$this->render('index');
		$code = Frame::getStringFromRequest ( "code" );
		$openid = $this->getOpenId($code);
		$re_info = WxUser::model()->find("openid = '{$openid}'");
		if($re_info)
		{
			$this->redirect(array("user/index?id={$re_info->id}&code={$code}"));
			exit;
		}
		$this->redirect(array("site/register?code={$code}"));
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-Type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * 注册
	 */
	public function actionRegister()
	{
		if($_POST)
		{
			$name = Frame::getStringFromRequest ( "name" );
			$phone = Frame::getStringFromRequest ( "phone" );
			$checkcode = Frame::getStringFromRequest ( "checkcode" );
			$yq_code = Frame::getStringFromRequest ( "yq_code" );
			$qq = Frame::getStringFromRequest ( "qq" );
			$fax = Frame::getStringFromRequest ( "fax" );
			$co_name = Frame::getStringFromRequest ( "co_name" );
			$co_address = Frame::getStringFromRequest ( "co_address" );
			$openid = Yii::app()->request->cookies['steel_openId'];
			//查询手机号码
			$re_info = WxUser::model()->find("phone = '{$phone}' or openid = '{$openid}'");
			if($re_info)
			{
				$re0 = array("code"=>2,"info"=>"手机号已注册");
				echo json_encode($re0);
				exit;
			}
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
			$re1 = array("code"=>1,"info"=>"注册失败");
			$wxinfo = $this->getUserMessage($openid);
			$info = new WxUser();
			$info->username = $name;
			$info->openid = $openid;//$this->getOpenId($code1);
			$info->loginname = $wxinfo['nickname'];
			$info->pic = $wxinfo['headimgurl'];
			$info->phone = $phone;
			$info->created_at = time();
			$info->yq_code = $yq_code;
			$info->qq = $qq;
			$info->fax = $fax;
			if($yq_code)
			{
				$user = User::model()->find("invit_code = '{$yq_code}'");
				if($user)
				{
					$info->user_id = $user->id;
				}
			}
			if($info->save())
			{
				if($co_name){
					$co_info = new WxUserCompany();
					$co_info->user_id = $info->id;
					$co_info->company = $co_name;
					$co_info->address = $co_address;
					$co_info->is_default = 1;
					$company = DictCompany::model()->find("name = '$co_name'");
					if($company){
						$co_info->company_id = $company->id;
					}
					$co_info->created_at = time();
					if($co_info->save())
					{
						$re1 = array("code"=>0,"info"=>"");
					}
				}
				$re1 = array("code"=>0,"info"=>"");//&type=1&area_id=1
				$cookie = Yii::app()->request->getCookies();
				if($cookie['quoted_id']->value){
					$re1 = array("code"=>0,"info"=>$cookie['quoted_id']->value);
				}
				if($cookie['area_id']->value){
					$re1["info"] = $re1["info"]."&area_id=".$cookie['area_id']->value;
				}
			}
			echo json_encode($re1);
			exit;
		}
		$this->render('register');
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

	/**
	 * 获取短信验证码
	 */
	public function actionGetCode()
	{
		$phone = Frame::getStringFromRequest ( "phone" );
		$content1 = rand(1000,9999);
		$content = "#code#=".$content1;
		$addTime = time();
		$backTime = time() - 10800;
		//发送次数大于5，不予发送
		$nu = WxUserYz::model()->count("(phone = '{$phone}') and created_at between {$backTime} and {$addTime}");
		if ($nu && $nu >= 5){
			echo 0;
			exit;
		}
		$re = sendMsg($phone,$content,"1382467");
		if($re)
		{
			//记录
			$code = new WxUserYz();
			$code->phone = $phone;
			$code->yz = $content1;
			$code->created_at = time();
			$code->save();

			$log = new LogDetail();
			$log->table_name = "wx_user_yz";
			$log->newValue = '{"phone":"'.$phone.'","code":"'.$content1.'"}';
			$log->created_at = time();
			$log->created_by = 0;
			$log->insert();

			echo 1;
		}
		else
		{
			echo 0;
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
		$dataArr = json_decode($accessToken);
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

		return $messageArr;
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