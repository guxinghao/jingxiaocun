<?php

class QuotedController extends Controller
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
		//$openid = Yii::app()->request->cookies['steel_openId'];
		//if(!$openid)
		//{
			//$openid = $this->getOpenId($code);
		//}
		//$openid = "oJIm6t6DIbGQvTW9IFXc0svaR0qU";
		$quotedinfo['search']['productname'] = "品名";
		$quotedinfo['search']['texturename'] = "全部";
		$quotedinfo['search']['rankname'] = "全部";
		$quotedinfo['search']['length'] = "-1";
		$quotedinfo['search']['lengthname'] = "全部";
		$quotedinfo['search']['brandname'] = "产地";
		//$quotedinfo['search']['productall'] = "其他";
		$quotedinfo['search']['prefecturename'] = "品名";
		$product1 = addslashes($_GET['product']);
		$texture1 = addslashes($_GET['texture']);
		$rank1 = addslashes($_GET['rank']);
		$brandname1 = addslashes($_GET['brand']);
		$prefecture1 = addslashes($_GET['prefecture']);
		//$user = WxUser::model()->find("openid = '{$openid}'");
		//if(!$user)
		//{
			//$this->redirect(array("site/index?code={$code}"));
			//exit;
		//}
		$allbrand1 = WxQuoted::model()->findAll("id > 0 group by brand_std");
		foreach($allbrand1 as $va)
		{
			$allbrand[] = $va->brand_std;
		}
		$texture_all = array("HRB400","HRB400E","HRB300","HPB235","HPB195","Q235","Q195","HRB500E","HRB335");
		$property = DictGoodsProperty::model()->findAll();
		foreach($property as $va)
		{
			//品名
			if($va->property_type == "product")
			{
				$product[] = array("product_std"=>$va->std,"name"=>$va->name);
				if($va->std == $product1){
					$quotedinfo['search']['productname'] = $va->name;
				}
			}
			//材质
			if($va->property_type == "texture")
			{
				$texture[] = array("texture_std"=>$va->std,"name"=>$va->name);
				if($va->std == $texture1){
					$quotedinfo['search']['texturename'] = $va->name;
				}
			}
			//产地
			if($va->property_type == "brand")
			{
				if(in_array($va->std,$allbrand)){
					$brand[] = array("brand_std"=>$va->std,"name"=>$va->name);
				}
				if($va->std == $brandname1){
					$quotedinfo['search']['brandname'] = $va->name;
				}
			}
			//规格
			if($va->property_type == "rank")
			{
				$rank[] = array("rank_std"=>$va->std,"name"=>$va->name);
				if($va->std == $rank1){
					$quotedinfo['search']['rankname'] = $va->name;
				}
			}
		}
		//长度
		//$length = WxQuoted::model()->findAllBySql("select length from wx_quoted group by length");
		//专区
		$prefecture = Prefecture::model()->findAll();
		if($prefecture1){
			foreach($prefecture as $va){
				if($prefecture1 == $va->id){
					$quotedinfo['search']['prefecturename'] = $va->name;
				}
			}
		}
		//$length1 = isset($_GET['length'])?($_GET['length']>=0?$_GET['length']:"其他"):"其他";
		$length1 = isset($_GET['length'])?($_GET['length']>=0?$_GET['length']:""):"";
		//$quotedinfo['search']['productall'] = ($texture1?$quotedinfo['search']['texturename']:($rank1?$quotedinfo['search']['rankname']:$length1?$length1:"其他"));
		if($texture1){
			$quotedinfo['search']['productall'] .= $quotedinfo['search']['texturename'];
		}
		if($rank1){
			$quotedinfo['search']['productall'] .= ($texture1?"/":"").$quotedinfo['search']['rankname'];
		}
		if($length1){
			$quotedinfo['search']['productall'] .= (($texture1 || $rank1)?"/":"").$length1;
		}
        if(!$quotedinfo['search']['productall']){
			$quotedinfo['search']['productall'] = "其他";
		}
		foreach($texture_all as $va){
			foreach($texture as $v){
				if($v['name'] == $va){
					$texture_in[] = $v;
				}
			}
		}
		$quotedinfo['product'] = $product;
		$quotedinfo['texture'] = $texture_in;
		$quotedinfo['brand'] = $brand;
		$quotedinfo['rank'] = $rank;
		$quotedinfo['length'] = array(0,9,12);
		$quotedinfo['prefecture'] = $prefecture;
		//信息
		$date2 = date("Y-m-d",time());
		$model = WxQuoted::model();
		$cri = new CDbCriteria();
		$cri->select = "t.*,b.name prefecture_name ,c.area_id area,h.name areaname";
		$cri->join = "left join prefecture b on t.prefecture = b.id left join wx_quoted_relation c on t.id = c.quoted_id left join ware_area h on c.area_id = h.id
					left join dict_goods_property p on p.std=t.product_std
					left join dict_goods_property bra on bra.std=t.brand_std
					left join dict_goods_property tt on tt.std=t.texture_std
					left join dict_goods_property r on r.std=t.rank_std";
		$cri->order='convert(bra.name using gbk),convert(p.name using gbk),length ,CONVERT(substr(r.name,2,3),SIGNED),tt.name';
		//$cri->group = "t.id";
		$cri->addCondition("c.price_date = '{$date2}'");
		if($_GET)
		{
			if($_GET['brand'])
			{
				$cri->addCondition("t.brand_std ='{$_GET['brand']}'");
				$quotedinfo['search']['brand'] = $_GET['brand'];
			}
			if($_GET['product'])
			{
				$cri->addCondition("t.product_std ='{$_GET['product']}'");
				$quotedinfo['search']['product'] = $_GET['product'];
			}
			if($_GET['texture'])
			{
				$cri->addCondition("t.texture_std ='{$_GET['texture']}'");
				$quotedinfo['search']['texture'] = $_GET['texture'];
			}
			if($_GET['rank'])
			{
				$cri->addCondition("t.rank_std ='{$_GET['rank']}'");
				$quotedinfo['search']['rank'] = $_GET['rank'];
			}
			if(isset($_GET['length']))
			{
				if($_GET['length'] >= 0){
					$cri->addCondition("t.length ='{$_GET['length']}'");
					$quotedinfo['search']['lengthname'] = $_GET['length']?$_GET['length']:"其他";
				}
				$quotedinfo['search']['length'] = $_GET['length'];
			}
			if($_GET['prefecture'])
			{
				$cri->addCondition("t.prefecture ='{$_GET['prefecture']}'");
				$quotedinfo['search']['prefecture'] = $_GET['prefecture'];
			}
		}
		$items = $model->findAll($cri);//var_dump($items);
		$date1 = date("Y-m-d",strtotime("-1 day"));
		$date2 = date("Y-m-d",time());
		$where = "and price_date between '{$date1}' and '{$date2}'";
		if($items)
		{
			foreach($items as $va)
			{
				//价格
				$price = WxQuotedRelation::model()->find("quoted_id = {$va->id} and area_id = {$va->area} and price_date ='{$date2}'");
				if(!$price || ($price->price == '0.00')) continue;
				$price_info[$va->id][$va->area] = $price->price?$price->price:"0";

				foreach($property as $va1)
				{
					if($va1->std == $va->product_std){
						$proinfo['product'][$va->id] = $va1->name;
					}
					if($va1->std == $va->brand_std){
						$proinfo['brand'][$va->id] = $va1->name;
					}
					if($va1->std == $va->texture_std){
						$proinfo['texture'][$va->id] = $va1->name;
					}
					if($va1->std == $va->rank_std){
						$proinfo['rank'][$va->id] = $va1->name;
					}
				}
				$quoted_id[] = $va->id;
				$prefectureinfo[$va->prefecture][] = $va;

				$weight = DictGoods::model()->find("product_std = '{$va->product_std}' and brand_std = '{$va->brand_std}' and texture_std = '{$va->texture_std}' and rank_std = '{$va->rank_std}' and length = '{$va->length}'");
				$weight_info[$va->id] = $weight->unit_weight;

			}
			//价格变化
			if ($quoted_id) {
				$priceChange = WxQuotedRelation::model()->findAll("quoted_id in(".implode(",",$quoted_id).") {$where} order by price_date desc");
			}
			if($priceChange)
			{
				foreach($priceChange as $va)
				{
					$change[$va->quoted_id][$va->area_id][] = $va->price;
				}

				foreach($change as $key=>$va)
				{
					foreach($va as $k=>$v){
						$show = "";
						$up = intval($v[0]) - intval($v[1]);
						if($up > 0)
						{
							$pic = "/weixin/skin/images/zt_3.jpg";
							$class = "latest-lired";
						}
						else
						{
							$pic = "/weixin/skin/images/zt_4.jpg";
							$class = "latest-ligreen";
						}
						if(!intval($v[1])){
							$show = 'style="display:none"';
						}
						$changeinfo[$key][$k] = array("up"=>$up,"class"=>$class,"pic"=>$pic,"show"=>$show);
					}
				}
			}
		}
		$quotedinfo['search']['show'] = 1;
		if($quotedinfo['search']['length'] >= 0){
			$quotedinfo['search']['show'] = 3;
		}
		if($quotedinfo['search']['rank']){
			$quotedinfo['search']['show'] = 2;
		}
		if($quotedinfo['search']['texture']){
			$quotedinfo['search']['show'] = 1;
		}
//		if($quotedinfo['search']['product']){
//			$quotedinfo['search']['show'] = 1;
//		}
		$quotedinfo['changeinfo'] = $changeinfo;
		$quotedinfo['info'] = $prefectureinfo;
		$quotedinfo['weightinfo'] = $weight_info;
		$quotedinfo['priceinfo'] = $price_info;
		$quotedinfo['proinfo'] = $proinfo;
		$quotedinfo['is_spread'] = 1;//$user->is_spread;
		$quotedinfo['userphone'] = Yii::app()->params['phone'];//;PHONE;
		$openid = Yii::app()->request->cookies['steel_openId'];
		//$openid = "oJIm6t6DIbGQvTW9IFXc0svaR0qU";
		if($openid){
			$user = WxUser::model()->find("openid = '{$openid}'");
			if($user->user_id){
				$userphone = User::model()->findByPk($user->user_id);
				if($userphone->phone){
					$quotedinfo['userphone'] = $userphone->phone;
				}
				if($userphone->nickname){
					$quotedinfo['username'] = $userphone->nickname;
				}
			}
		}

		$this->render("index",array("quotedinfo"=>$quotedinfo));
	}

	/**
	 * 详情
	 */
	public function actionDetail()
	{
		
		$openid = Yii::app()->request->cookies['steel_openId'];
		//$openid = "oJIm6t6DIbGQvTW9IFXc0svaR0qU";
		//$user = WxUser::model()->find("openid = '{$openid}'");
		$quoted_id = Frame::getStringFromRequest ( "quoted_id" );
		$area_id = Frame::getStringFromRequest ( "area_id" );
		if(!$user)
		{
			//setcookie('quoted_id', $quoted_id, time()+3600*24*7,"/");
			//setcookie('area_id', $area_id, time()+3600*24*7,"/");
			//$this->redirect(array("site/getUser"));
			//exit;
		}
		//setcookie('quoted_id', '', time()-3600,"/");
		//setcookie('area_id', '', time()-3600,"/");
		$type = Frame::getStringFromRequest ( "type" );
		$area_id = $area_id?$area_id:0;

		$quoted = WxQuoted::model()->find("id = {$quoted_id}");
		$property = DictGoodsProperty::model()->findAll("std = '{$quoted->product_std}' or std = '{$quoted->brand_std}' or std = '{$quoted->texture_std}' or std = '{$quoted->rank_std}'");
		foreach($property as $va)
		{
			//品名
			if($va->property_type == "product")
			{
				//$product[] = array("product_std"=>$va->std,"name"=>$va->name);
				$quotedinfo['product'] = $va->name;
			}
			//材质
			if($va->property_type == "texture")
			{
				//$texture[] = array("texture_std"=>$va->std,"name"=>$va->name);
				$quotedinfo['texture'] = $va->name;
			}
			//产地
			if($va->property_type == "brand")
			{
				//$brand[] = array("brand_std"=>$va->std,"name"=>$va->name);
				$quotedinfo['brand'] = $va->name;
			}
			//规格
			if($va->property_type == "rank")
			{
				//$rank[] = array("rank_std"=>$va->std,"name"=>$va->name);
				$quotedinfo['rank'] = $va->name;
			}
		}

		$weight = DictGoods::model()->find("product_std = '{$quoted->product_std}' and brand_std = '{$quoted->brand_std}' and texture_std = '{$quoted->texture_std}' and rank_std = '{$quoted->rank_std}' and length = '{$quoted->length}'");
		$quotedinfo['weightinfo'] = $weight->unit_weight;
		//价格
		$type = $type?$type:1;
		$date1 = date("Y-m-d",time());
		if($type == 1){
			$date2 = date("Y-m-d",strtotime("-7 day"));
		}
		if($type == 2){
			$date2 = date("Y-m-d",strtotime("last month"));
		}
		$where = "and price_date between '{$date2}' and '{$date1}'";
		$pre_price = WxQuoted::model()->findAll("product_std = '{$quoted->product_std}' and brand_std = '{$quoted->brand_std}' and texture_std = '{$quoted->texture_std}' and rank_std = '{$quoted->rank_std}' and length = {$quoted->length}");// {$where}
		foreach($pre_price as $va){
			$allquoted[] = $va->id;
		}
		$price = WxQuotedRelation::model()->findAll("quoted_id in(".implode(",",$allquoted).") and area_id = {$area_id} {$where} order by price_date desc");

		foreach($price as $key=>$va)
		{
			$re = $price[$key+1];
			$nprice = $re->price?$re->price:$va->price;
			$up = $va->price - $nprice;
			if($up > 0)
			{
				$pic = "/weixin/skin/images/zt_3.jpg";
				$class = "latest-lired";
			}
			else
			{
				$pic = "/weixin/skin/images/zt_4.jpg";
				$class = "latest-ligreen";
			}
			$priceinfo[] = array("price"=>$va->price,"up"=>abs($up),"class"=>$class,"pic"=>$pic,"date"=>$va->price_date);
		}

		$price = array_reverse($price);
		foreach($price as $va){
			$content[] = $va->price;
			$date[] = "'".date("m-d",strtotime($va->price_date))."'";
		}
		//var_dump($date);exit;
		$quotedinfo['id'] = $quoted_id;
		$quotedinfo['type'] = $type;
		$quotedinfo['priceinfo'] = $priceinfo;
		$quotedinfo['length'] = $quoted->length;
		$quotedinfo['xtitle'] = $date?implode(",",$date):"null";
		$quotedinfo['ytitle'] = $content?implode(",",$content):"null";
		$quotedinfo['content'] = $content?implode(",",$content):"null";
		$this->render("detail",array("quotedinfo"=>$quotedinfo));
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