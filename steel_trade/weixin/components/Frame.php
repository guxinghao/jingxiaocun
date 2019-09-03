<?php
class Frame {
	public static function DRLogout($msg = 'test') {
		var_dump ( $msg );
	}
	
	// 保存图片
	public static function saveImage($postName) {
		if (empty ( $_FILES [$postName] ['name'] ))
			return '';
		$up = CUploadedFile::getInstanceByName ( $postName );
		return Frame::createFile ( $up, "images", "create" );
	}
	// 保存文件
	public static function createFile($upload, $type, $act, $imgurl = '') {
		if (! empty ( $imgurl ) && $act === 'update') {
			// 更新文件前删除旧文件
			$deleteFile = Yii::app ()->basePath . '/../' . $imgurl;
			if (is_file ( $deleteFile ))
				unlink ( $deleteFile );
		}
		$dirPath = '/uploads/' . $type . '/' . date ( 'Y-m', time () );
		$uploadDir = dirname ( __FILE__ ) . '/..' . $dirPath;
		self::recursionMkDir ( $uploadDir );
		$imgname = time () . '-' . rand () . '.' . $upload->extensionName;
		// 图片展示路径
		$imageurl = $dirPath . '/' . $imgname;
		// 存储使用绝对路径
		$uploadPath = $uploadDir . '/' . $imgname;
		if ($upload->saveAs ( $uploadPath )) {
			return $imageurl;
		} else {
			return null;
		}
	}
	private static function recursionMkDir($dir) {
		if (! is_dir ( $dir )) {
			self::recursionMkDir ( dirname ( $dir ) );
			mkdir ( $dir, 0777 );
		}
	}
	
	// 生成
	public static function createUUID() {
		if (function_exists ( 'com_create_guid' )) {
			return com_create_guid ();
		} else {
			mt_srand ( ( double ) microtime () * 10000 ); // optional for php 4.2.0 and up.
			$charid = strtoupper ( md5 ( uniqid ( rand (), true ) ) );
			$hyphen = chr ( 45 ); // "-"
			$uuid = chr ( 123 ) . 			// "{"
			substr ( $charid, 0, 8 ) . $hyphen . substr ( $charid, 8, 4 ) . $hyphen . substr ( $charid, 12, 4 ) . $hyphen . substr ( $charid, 16, 4 ) . $hyphen . substr ( $charid, 20, 12 ) . chr ( 125 ); // "}"
			return $uuid;
		}
	}
	public static function truncate_utf8_string($string, $length, $etc = '...') {
		$result = '';
		$string = html_entity_decode ( trim ( strip_tags ( $string ) ), ENT_QUOTES, 'UTF-8' );
		$strlen = strlen ( $string );
		for($i = 0; (($i < $strlen) && ($length > 0)); $i ++) {
			if ($number = strpos ( str_pad ( decbin ( ord ( substr ( $string, $i, 1 ) ) ), 8, '0', STR_PAD_LEFT ), '0' )) {
				if ($length < 1.0) {
					break;
				}
				$result .= substr ( $string, $i, $number );
				$length -= 1.0;
				$i += $number - 1;
			} else {
				$result .= substr ( $string, $i, 1 );
				$length -= 0.5;
			}
		}
		$result = htmlspecialchars ( $result, ENT_QUOTES, 'UTF-8' );
		if ($i < $strlen) {
			$result .= $etc;
		}
		return $result;
	}
	public static function getStringFromRequest($key, $defaultValue = '') {
		return addslashes ( Yii::app ()->request->getParam ( $key, $defaultValue ) );
	}
	public static function getIntFromRequest($key, $defaultValue = 0) {
		return intval ( Yii::app ()->request->getParam ( $key, $defaultValue ) );
	}
	
	public static function getIntRequest($key) {
		return intval ( $key);
	}
	public static function getStringRequest($key) {
		return addslashes ( $key );
	}
	public static function getStringFromObject($obj, $key, $defalutValue = '') {
		if (empty ( $obj ) || empty ( $key ) || empty ( $obj->$key ))
			return $defalutValue;
		return $obj->$key;
	}
	public static function getArrayFromObject($obj, $key, $defalutValue = array()) {
		if (empty ( $obj ) || empty ( $key ) || empty ( $obj->$key ))
			return $defalutValue;
		return $obj->$key;
	}
	public static function getStringFromArray($array, $key, $defalutValue = '') {
		if (empty ( $array ) || empty ( $key ) || empty ( $array [$key] ))
			return $defalutValue;
		return $array [$key];
	}
	public static function getArrayFromArray($array, $key, $defalutValue = array()) {
		if (empty ( $array ) || empty ( $key ) || empty ( $array [$key] ))
			return $defalutValue;
		return $array [$key];
	}
	// 发邮件
	public static function sendMail($to, $topic, $message, &$error = '') {
		$validator = new CEmailValidator ();
		if (! $validator->validateValue ( $to )) {
			$error = '邮箱不合法';
			return false;
		}
		if (empty ( $topic ) || ! trim ( $topic )) {
			$error = '主题不能为空';
			return false;
		}
		if (empty ( $message ) || ! trim ( $message )) {
			$error = '邮件内容不能为空';
			return false;
		}
		$mailer = Yii::createComponent ( 'webroot.lib.mailer.EMailer' );
		$mailer->Host = 'smtp.163.com';
		$mailer->IsSMTP ();
		$mailer->SMTPAuth = true;
		$mailer->From = 'DataRenaissance@163.com'; // 设置发件地址
		                                           // $mailer->AddReplyTo('DataRenaissance@163.com');
		$mailer->AddAddress ( $to ); // 设置收件件地址
		$mailer->FromName = '数据复兴'; // 这里设置发件人姓名
		$mailer->Username = 'DataRenaissance'; // 这里输入发件地址的用户名
		$mailer->Password = 'drzaq12wsx'; // 这里输入发件地址的密码
		$mailer->SMTPDebug = false; // 设置SMTPDebug为true，就可以打开Debug功能，根据提示去修改配置
		$mailer->CharSet = 'UTF-8';
		$mailer->Subject = Yii::t ( 'DR', $topic ); // 设置邮件的主题
		$mailer->Body = $message;
		return $mailer->Send ();
	}

	public static function fcGET($url,$header = false,$cookieStr = null){
		if (function_exists(curl_init)){
			$curl = curl_init($url);
			if (!empty($cookieStr)){
				curl_setopt($curl,CURLOPT_COOKIE,$cookieStr);
			}
			if ($header) {
				curl_setopt($curl, CURLOPT_HEADER, 1 ); // 输出HTTP头
			}else{
				curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
			}
			curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
			$responseText = curl_exec($curl);
			//var_dump( curl_error($curl) );//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
			curl_close($curl);
			return $responseText;
		}else{
			echo "缺少CURL扩展";
			exit();
		}
	}

	public static function getrand($len=8,$flag=1){

		$str = "";
		$strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
		$max = strlen($strPol)-1;

		for($i=0;$i<2;$i++){
			$str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
		}

		$code0 = rand(1000,9999);
		$code = time();
		$code = substr($code,-4).$code0;
		return $flag ? ($str."_".$code):$code;
	}

}

