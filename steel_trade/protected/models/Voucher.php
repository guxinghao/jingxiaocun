<?php

/**
 * This is the biz model class for table "voucher".
 *
 */
class Voucher extends VoucherData
{
	

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'createdby'=>array(self::BELONGS_TO,'User','created_by'),//创建人
			'voucherDetails' => array(self::HAS_MANY, 'VoucherDetail', 'voucher_id','order'=>'voucherDetails.sort1 asc,voucherDetails.sort2 asc'),
			'gkDetails' => array(self::HAS_MANY, 'VoucherGk', 'voucher_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'voucher_name' => 'Voucher Name',
			'voucher_number' => 'Voucher Number',
			'type' => 'Type',
			'attachment' => 'Attachment',
			'created_at' => 'Created At',
			'form_at' => 'Form At',
			'is_deleted' => 'Is Deleted',
			'created_by' => 'Created By',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('voucher_name',$this->voucher_name,true);
		$criteria->compare('voucher_number',$this->voucher_number);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('attachment',$this->attachment);
		$criteria->compare('created_at',$this->created_at);
		$criteria->compare('form_at',$this->form_at);
		$criteria->compare('is_deleted',$this->is_deleted);
		$criteria->compare('created_by',$this->created_by);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Voucher the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	//获取财务凭证列表
	public static function getFormList($search){
		$tableData=array();
		$model = new VoucherDetail();
		$criteria=New CDbCriteria();
		$withArray = array();
		$criteria->with = array("voucher");
		
		//搜索
		if(!empty($search))
		{
			$criteria->together=true;
			if(trim($search['keywords'])){
				$criteria->addCondition('voucher.voucher_name like :contno or t.comment like :contno');
				$criteria->params[':contno']= "%".$search['keywords']."%";
			}
			if($search['time_L']!='')
			{
				$start = strtotime($search['time_L']);
				$criteria->addCondition('voucher.form_at >="'.$start.'"');
			}
			if($search['time_H']!='')
			{
				$end = strtotime($search['time_H']." 23:59:59");
				$criteria->addCondition('voucher.form_at <="'.$end.'"');
			}
		}
		$criteria->addCondition('voucher.is_deleted=0');
		$criteria->order = "voucher.form_at DESC,locate(voucher.voucher_name,'现付,现收,银付,银收,转帐'),voucher.voucher_number,t.sort1 ASC,t.sort2 ASC";
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize =intval($_COOKIE['voucher_list']) ? intval($_COOKIE['voucher_list']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);
		$details=$model->findAll($criteria);
		if($details){
			$da=array();
			$da['data']=array();
			$i=0;
			$mark='';
			foreach($details as $each){
				$voucher = $each->voucher;
				$i++;
				if($mark != $voucher->id){
					$mark = $voucher->id;
					$edit_url= Yii::app()->createUrl('voucher/update',array("id"=>$voucher->id));
					if($voucher->is_export == 1){
						$change_url = Yii::app()->createUrl('voucher/change',array("id"=>$voucher->id,"type"=>"no"));
						$title="取消导出";
						$class = "icon-remove-sign";
					}else{
						$change_url = Yii::app()->createUrl('voucher/change',array("id"=>$voucher->id,"type"=>"is"));
						$title = "设置已导出";
						$class = "icon-plus-sign";
					}
					$operate='<div class="cz_list_btn"><input type="hidden" class="form_sn" value="'.$voucher->voucher_name.'">';
					if($voucher->is_export != 1){
						$operate.='<a class="update_b" href="'.$edit_url.'" title="编辑"><span><img src="/images/bianji.png"></span></a>';
						$operate.='<span class="delete_form" url="/index.php/voucher/deleteform/'.$voucher->id.'"  title="作废" is_export="'.$voucher->is_export.'"><img src="/images/zuofei.png"></span>';
					}
					$operate.='<span class="submit_form" url="'.$change_url.'"  title="'.$title.'"><i class="icon '.$class.'"></i></span>';
					$operate.='</div>';
				}else{
					$operate='';
				}
				$da['data']=array(
						$i,
						$operate,
						$voucher->voucher_name."-".$voucher->voucher_number,
						$each->comment,
						$each->account_code,
						$each->account_name,
						$each->debit!=0?number_format($each->debit,2):"",
						$each->credit!=0?number_format($each->credit,2):"",
						date("Y-m-d",$voucher->form_at),
						$voucher->createdby->nickname,
						date("Y-m-d",$voucher->created_at),
						'<span title="'.$each->company->name.'">'.$each->company->short_name.'</span>',
				);
				$da['group']=$voucher->id;
				array_push($tableData,$da);
			}
		}
		return array($tableData,$pages);
	}
	
	//获取导出财务凭证列表
	public static function getAllList($search){
		$tableData=array();
		$model = new VoucherDetail();
		$criteria=New CDbCriteria();
		$withArray = array();
		$criteria->with = array("voucher");
		
		//搜索
		if(!empty($search))
		{
			$criteria->together=true;
			if(trim($search['keywords'])){
				$criteria->addCondition('voucher.voucher_name like :contno or t.comment like :contno');
				$criteria->params[':contno']= "%".$search['keywords']."%";
			}
			if($search['time_L']!='')
			{
				$start = strtotime($search['time_L']);
				$criteria->addCondition('voucher.form_at >="'.$start.'"');
			}
			if($search['time_H']!='')
			{
				$end = strtotime($search['time_H']." 23:59:59");
				$criteria->addCondition('voucher.form_at <="'.$end.'"');
			}
		}
		$criteria->addCondition('voucher.is_export=0');
		$criteria->addCondition('voucher.is_deleted=0');
		$criteria->order = "voucher.form_at DESC,locate(voucher.voucher_name,'现付,现收,银付,银收,转帐'),voucher.voucher_number,t.sort1 ASC,t.sort2 ASC";
		$details=$model->findAll($criteria);
		$content = array();
		if($details){
			$mark = 0;
			$num = 0;
			$i = 0;
			$_type = array(0=>"客户",1=>"供应商_KIS75自定义",2=>"客户");
			foreach ($details as $each){
				$voucher = $each->voucher;
				//设置已导出为1
				$voucher->is_export = 1;
				$voucher->update();
				if($mark == $voucher->id){
					$i++;
				}else{
					$i = 0;
					//$i++;
					$num ++;
				}
				$mark = $voucher->id;
				$temp = array();
				$temp[0] = $voucher->created_at > 0 ? date("Y-m-d",$voucher->created_at) : '';
				$temp[1] = $voucher->created_at > 0 ? date("Y",$voucher->created_at) : '';
				$temp[2] = $voucher->created_at > 0 ? date("m",$voucher->created_at) : '';
				$temp[3] = $voucher->voucher_name;
				$temp[4] = $voucher->voucher_number;
				$temp[5] = $each->account_code;
				$temp[6] = $each->account_name;
				$temp[7] = "RMB";
				$temp[8] = "人民币";
				$temp[9] = $each->debit>0?numChange(number_format($each->debit,2)):numChange(number_format($each->credit,2));
				$temp[10] = numChange(number_format($each->debit,2));
				$temp[11] = numChange(number_format($each->credit,2));
				$temp[12] = $voucher->createdby->nickname;
				$temp[13] = "NONE";
				$temp[14] = "NONE";
				$temp[15] = "NONE";
				$temp[16] = "";
				$temp[17] = "*";
				$temp[18] = "";
				$temp[19] = $each->comment;
				$temp[20] = $each->amount;
				$temp[21] =  $each->billother_id?"*":$each->unit;
				$temp[22] = 0;//单价
				$temp[23] = "";
				$temp[24] = $voucher->created_at > 0 ? date("Y-m-d",$voucher->created_at) : '';
				$temp[25] = "";
				$temp[26] = $voucher->attachment;
				$temp[27] = $voucher->id;//序号
				$temp[28] = "";
				$temp[29] = "";
				$temp[30] = 1;
				$temp[31] = $i;//分录序号
				if($each->company_id > 0&&!$each->billother_id){
					if($each->type==0){
						$temp[32] = $_type[$each->type]."---".$each->company->cus_number."---".$each->company->name;//核算项目
					}else if($each->type==1){
						$temp[32] = $_type[$each->type]."---".$each->company->sup_number."---".$each->company->name;//核算项目
					}else if($each->type==2){
						//$temp[32] = $_type[$each->type]."---".$each->company->dj_number."---".$each->company->name;//核算项目
					}
				}else{
					$temp[32] = "";
				}
				$temp[33] = 0;
				$temp[34] = "";
				$temp[35] = "";
				array_push($content,$temp);
			}
		}
		return $content;
	}
	
	//获取业务凭证列表
	public static function getBusinessList($search){
		$tableData=array();
		$model=new BusinessView();
		$criteria=New CDbCriteria();
		//搜索
		if(!empty($search))
		{
			$criteria->together=true;
			if (trim($search['keywords'])){
				$criteria->addCondition('form_sn like :contno');
				$criteria->params[':contno']= "%".$search['keywords']."%";
			}
			if($search['time_L']!='')
			{
				$criteria->addCondition('form_time >="'.$search['time_L'].'"');
			}
			if($search['time_H']!='')
			{
				$criteria->addCondition('form_time <="'.$search['time_H'].'"');
			}
			if($search['title_id']!='0')
			{
				$criteria->compare('title_id',$search['title_id'],false);
			}
			if($search['is_yidan'] >= 0){
				$criteria->compare('is_yidan',$search['is_yidan'],false);
			}
			if($search['money'] != 0){
				if($search['money'] == 1){
					$criteria->addCondition('fee >0 or fix_fee>0');
				}
				if($search['money'] == 2){
					$criteria->addCondition('fee=0 and fix_fee=0');
				}
				if($search['money'] == 3){
					$criteria->addCondition('fee>5 or fix_fee>5');
				}
			}
			if($search['customer_id']!='0')
			{
				$criteria->compare('customer_id',$search['customer_id'],false);
			}
			if($search['type']){
				$criteria->addCondition('form_type="'.$search['type'].'"');
			}
		}
		$criteria->order = "form_time ASC";
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize =intval($_COOKIE['business_list']) ? intval($_COOKIE['business_list']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);
		$details=$model->findAll($criteria);
		$_type = array("XSD"=>"销售","XSTH"=>"销售退货","CGD"=>"采购","CGTH"=>"采购退货","XSZR"=>"销售折让","CGZR"=>"采购折让");
		if($details){
			$da=array();
			$da['data']=array();
			$i =0;
			foreach ($details as $each){
				$i++;
				$str = '<input type="checkbox" class="checkone" value="'.$each->common_id.'">';
				$operate='<div class="cz_list_btn" ><input type="hidden" class="form_sn" value="'.$each->form_sn.'">'
						.'<span class="ignore_form" title="忽略" url="/index.php/voucher/ignoreform/'.$each->common_id.'" salesid="'.$each->main_id.'"><img src="/images/zuofei.png"></span>'
						.'</div>';
				$da['data']=array(
						$i,
						$str,
						$operate,
						$each->form_sn,
						$_type[$each->form_type],
						$each->form_time,
						$each->owned_by_nickname,
						$each->title_name,
						'<span title="'.$each->customer_name.'">'.$each->customer_short_name.'</span>',
						number_format($each->confirm_status==1?$each->fix_amount:$each->amount),
						number_format($each->confirm_status==1?$each->fix_weight:$each->weight,3),
						number_format($each->confirm_status==1?$each->fix_fee:$each->fee,2),
						$each->is_yidan == 1?"是":"",
						$each->created_at>0?date("Y-m-d",$each->created_at):"",
						'<span title="'.htmlspecialchars($each->comment).'">'.mb_substr($each->comment,0,15,"UTF-8").'</span>',
				);
				$da['group']=$each->form_sn;
				array_push($tableData,$da);
			}
		}
		return array($tableData,$pages);
	}
	
	//处理业务凭证数据
	public static function processBusiData(){
		$str = $_COOKIE["check_voucher_list"];
		$list = explode(",",$str);
		//数组去重
		$list = array_unique($list);
		$num = 0;
		$str_type1 = $str_type2 = $str = "";
		$data = array();
		$_type = array("XSD"=>1,"XSTH"=>2,"CGD"=>2,"CGTH"=>1);
		$_formtype = array("XSD"=>"销售单","XSTH"=>"销售退货","CGD"=>"采购单","CGTH"=>"采购退货","XSZR"=>"销售折让","CGZR"=>"采购折让");
		foreach ($list as $li){
			if($li==0){continue;}
			$num ++;
			$business = BusinessView::model()->find("common_id=$li");
			$temp = array();
			if($business){
				$form_type = $business->form_type;
				$title_id = $business->title_id;
				$customer_id = $business->customer_id;
				$user_id = $business->owned_by;
				$title = DictTitle::model()->findByPk($title_id);
				$customer = DictCompany::model()->findByPk($customer_id);
				$user = User::model()->findByPk($user_id);
				if($form_type !="XSZR" || $form_type !="CGZR"){
					if($title->out_number == "" or $title->out_number == "default"){
						$str.="单号：{$business->form_sn}的公司抬头：“{$business->title_name}”没有财务编码<br/>";
					}
				}
				if($user->number == "" or $user->number == "default"){
					$str.="单号：{$business->form_sn}的业务员：“{$business->owned_by_nickname}”没有财务编码<br/>";
				}
				if($form_type=="XSD" || $form_type=="XSTH"||$form_type=="XSZR"){
					if($customer->cus_number == "" or $customer->cus_number == "default"){
						$str.="单号：{$business->form_sn}的采购商：“{$business->customer_name}”没有财务编码<br/>";
					}
				}else if($form_type=="CGD" || $form_type=="CGTH"||$form_type=="CGZR"){
					if($customer->sup_number == "" or $customer->sup_number == "default"){
						$str.="单号：{$business->form_sn}的供应商：“{$business->customer_name}”没有财务编码<br/>";
					}
				}
				switch ($form_type){
					case "XSD":
						$str_type1 .= "单号：{$business->form_sn}的类型为：{$_formtype[$form_type]},分录项与其他类型不同<br/>";
						$temp["account_code"] = $user->number;
						$temp["account_name"] = $user->nickname;
						if($business->confirm_status == 1){
							$temp["debit"] = round($business->fix_fee,2);
							$temp["amount"] = $business->fix_weight;
						}else{
							$temp["debit"] = round($business->fee,2);
							$temp["amount"] = $business->weight;
						}
						break;
					case "XSTH":
						$str_type2 .= "单号：{$business->form_sn}的类型为：{$_formtype[$form_type]},分录项与其他类型不同<br/>";
						$temp["account_code"] = $user->number;
						$temp["account_name"] = $user->nickname;
						if($business->confirm_status == 1){
							$temp["debit"] = round($business->fix_fee,2);
							$temp["amount"] = $business->fix_weight;
						}else{
							$temp["debit"] = round($business->fee,2);
							$temp["amount"] = $business->weight;
						}
						break;
					case "CGD":
						$str_type3 .= "单号：{$business->form_sn}的类型为：{$_formtype[$form_type]},分录项与其他类型不同<br/>";
						if($business->is_yidan){
							$temp["account_code"] = "1123.0005";
						}else{
							$temp["account_code"] = $title->out_number;
						}
						$temp["account_name"] = $title->short_name;
						if($business->confirm_status == 1){
							$temp["credit"] = round($business->fix_fee,2);
							$temp["amount"] = $business->fix_weight;
						}else{
							$temp["credit"] = round($business->fee,2);
							$temp["amount"] = $business->weight;
						}
						break;
					case "CGTH":
						$str_type4 .= "单号：{$business->form_sn}的类型为：{$_formtype[$form_type]},分录项与其他类型不同<br/>";
						if($business->is_yidan){
							$temp["account_code"] = "1123.0005";
						}else{
							$temp["account_code"] = $title->out_number;
						}
						$temp["account_name"] = $title->short_name;
						if($business->confirm_status == 1){
							$temp["credit"] = round($business->fix_fee,2);
							$temp["amount"] = $business->fix_weight;
						}else{
							$temp["credit"] = round($business->fee,2);
							$temp["amount"] = $business->weight;
						}
						break;
					case "XSZR":
						$str_type5 .= "单号：{$business->form_sn}的类型为：{$_formtype[$form_type]},分录项与其他类型不同<br/>";
						$temp["account_code"] = $user->number;
						$temp["account_name"] = $user->nickname;
						$temp["credit"] = round($business->fix_fee,2);
						$temp["amount"] = $business->fix_weight;
						break;
					case "CGZR":
						$str_type5 .= "单号：{$business->form_sn}的类型为：{$_formtype[$form_type]},分录项与其他类型不同<br/>";
						$temp["account_code"] = "1123.0005";
						$temp["account_name"] = $title->short_name;
						$temp["credit"] = round($business->fix_fee,2);
						$temp["amount"] = $business->fix_weight;
						break;
					default:
						return "数据类型不正确";
				}
				$temp["company_id"] = $customer_id;
				$temp["id"] = $business->common_id;
				$temp["form_type"] = $form_type;
				$temp["is_yidan"] = $business->is_yidan;
				array_push($data,$temp);
			}else{
				return "获取数据失败";
			}
		}
		//判断字符串长度，以多的为准，提示少的数据错误
		$error_str = Voucher::compareLongStr($str_type1,$str_type2,$str_type3,$str_type4,$str_type5);
		$str .= $error_str;
		
		if($str){
			return $str;
		}
		
		if($data){
			$result = Voucher::createData($data);
			if($result){
				return "success";
			}else{
				return "保存数据失败";
			}
		}
	}
	
	//生成凭证
	public static function createData($data){
		$transaction=Yii::app()->db->beginTransaction();
		try {
			$main = new Voucher();
			$main->voucher_name = "转帐";
			$voucher_number = Voucher::getVoucherNum(time(),"转帐");
			$main->voucher_number = $voucher_number;
			//$main->type = $v_type;
			$main->attachment = 0;
			$main->created_at = strtotime(date("Ymd"));
			$main->form_at = strtotime(date("Ymd"));
			$main->created_by = currentUserId();
			$_mer_arr = array(
					"XSD0"=>array("6001.0001","正品","销售出库"),"XSD1"=>array("6001.0002","次品","销售出库"),
					"XSTH0"=>array("6001.0001","正品","退货入库"),"XSTH1"=>array("6001.0002","次品","退货入库"),
					"CGD0"=>array("1405.0001","正品","采购入库"),"CGD1"=>array("1405.0002","次品","采购入库"),
					"CGTH0"=>array("1405.0001","正品","退货出库"),"CGTH1"=>array("1405.0002","次品","退货出库"),
					"XSZR0"=>array("6601.0005","尾款转入","销售尾款"),"XSZR1"=>array("6601.0005","尾款转入","销售尾款"),
					"CGZR0"=>array("6601.0005","尾款转入","采购尾款"),"CGZR1"=>array("6601.0005","尾款转入","采购尾款"),
			);
			if($main->insert()){
				$total = array();
				foreach ($data as $li){
					$type = $li["form_type"];
					switch($type){
						case "XSD":
							$v_type = 0;
							$comment = "销售出库";
							$is_tui = 0;
							break;
						case "XSTH":
							$v_type = 0;
							$comment = "退货入库";
							$is_tui = 1;
							break;
						case "CGD":
							$v_type = 1;
							$comment = "采购入库";
							$is_tui = 0;
							break;
						case "CGTH":
							$v_type = 1;
							$comment = "退货出库";
							$is_tui = 1;
							break;
						case "XSZR":
							$v_type = 0;
							$comment = "尾款折让";
							$is_tui = 0;
							break;
						case "CGZR":
							$v_type = 1;
							$comment = "尾款折让";
							$is_tui = 1;
							break;
						default:
							return false;
					}
					$detail = new VoucherDetail();
					$detail->voucher_id = $main->id;
					$detail->comment = $comment;
					$detail->account_code = $li["account_code"];
					$detail->account_name = $li["account_name"];
					if($is_tui ){
						$detail->debit = -$li["debit"];
						$detail->credit = -$li["credit"];
					}else{
						$detail->debit = $li["debit"];
						$detail->credit = $li["credit"];
					}
					if($type=="CGD" ||$type=="CGTH" || $type=="XSZR" ||$type=="CGZR" ){
						$detail->sort2=1;
					}else{
						$detail->sort2=0;
					}
					$detail->amount = $li["amount"];
					$detail->unit = "吨";
					$detail->company_id = $li["company_id"];
					$detail->common_id = $li["id"];
					$detail->type = $v_type;
					$key = $li["form_type"].intval($li["is_yidan"]);
					if(count($total[$key]) == 0){
						$total[$key]["sort"] = count($total);
					}
					$total[$key]["debit"] += $detail->credit;
					$total[$key]["credit"] += $detail->debit;
					$total[$key]["amount"] += $detail->amount;
					$total[$key]["comment"] = $comment;
					$total[$key]["is_tui"] = $is_tui;
					$total[$key]["type"] = $type;
					$detail->sort1 = $total[$key]["sort"];
					$detail->insert();
					$baseform = CommonForms::model()->findByPk($li["id"]);
					$baseform->is_voucher = 1;
					$baseform->update();
				}
				foreach ($total as $k=>$v){
					$detail = new VoucherDetail();
					$detail->voucher_id = $main->id;
					$detail->comment = $_mer_arr[$k][2];
					$yidan = intval($yidan);
					$detail->account_code = $_mer_arr[$k][0];
					$detail->account_name = $_mer_arr[$k][1];
					//$detail->comment = $v['comment'];
					$detail->debit = $v['debit'];
					$detail->credit = $v['credit'];
					$type=$v["type"];
					if($type=="CGD" ||$type=="CGTH" || $type=="XSZR" ||$type=="CGZR" ){
						$detail->sort2=0;
					}else{
						$detail->sort2=1;
					}
					$detail->sort1 = $v['sort'];
					$detail->amount = $v["amount"];
					$detail->unit = "吨";
					$detail->insert();
				}
			}
			$transaction->commit();
		}catch (Exception $e)
		{
			$transaction->rollBack();//事务回滚
			return false;
		}
		return true;
	}
	
	//获取收付款凭证列表
	public static function getPaymentList($search){
		$tableData=array();
		$model=new PaymentView();
		$criteria=New CDbCriteria();
		//搜索
		if(!empty($search))
		{
			$criteria->together=true;
			if (trim($search['keywords'])){
				$criteria->addCondition('form_sn like :contno');
				$criteria->params[':contno']= "%".$search['keywords']."%";
			}
			if($search['time_L']!='')
			{
				$criteria->addCondition('form_time >="'.$search['time_L'].'"');
			}
			if($search['time_H']!='')
			{
				$criteria->addCondition('form_time <="'.$search['time_H'].'"');
			}
			if($search['title_id']!='0')
			{
				$criteria->compare('title_id',$search['title_id'],false);
			}
			if($search['is_yidan'] >= 0){
				$criteria->compare('is_yidan',$search['is_yidan'],false);
			}
			if($search['customer_id']!='0')
			{
				$criteria->compare('customer_id',$search['customer_id'],false);
			}
			if($search['type']){
				if($search['type'] == "DQJK"){
					$criteria->addCondition('form_type="DQJK" or form_type="DQDK"');
				}else if($search['type'] == "FKDJ"){
					$criteria->addCondition('form_type="'.$search['type'].'" and bill_type <> "GKFK"');
				}else if($search['type'] == "GKFK"){
					$criteria->addCondition('form_type="FKDJ" and bill_type = "GKFK"');
				}else{
					$criteria->addCondition('form_type="'.$search['type'].'"');
				}
			}
		}
		$criteria->order = "form_time ASC";
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize =intval($_COOKIE['payment_list']) ? intval($_COOKIE['payment_list']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);
		$details=$model->findAll($criteria);
		//$_type = array("FKDJ"=>"付款","SKDJ"=>"收款","YHHZ"=>"银行互转","DQJK"=>"短期借款","DQDK"=>"短期贷款");
		$_type = array("XSSK"=>"销售收款","XSTH"=>"销售退款","CGFK"=>"采购付款","CGTH"=>"采购退款","GKFK"=>"高开付款","transfer"=>"银行互转",
				"lend"=>"借出","borrow"=>"借入","TPYF"=>"托盘预付","TPSH"=>"托盘赎回","FYBZ"=>"费用报支");
		$_way = array("cyber"=>"网银","money"=>"现金");
		if($details){
			$da=array();
			$da['data']=array();
			$i=0;
			foreach ($details as $each){
				if($each->form_type == "YHHZ"){
					$bank1 = DictBankInfo::model()->findByPk($each->bank_id);
					$bank2 = DictBankInfo::model()->findByPk($each->out_bank_id);
				}
				$i++;
// 				$str = '<input type="checkbox" class="checkone" value="'.$each->common_id.'">';
				if($each->bill_type=="FYBZ")
				{
					$str = '<input type="checkbox" class="checkone" value="'.$each->common_id.'" bz="'.$each->main_id.'">';
				}else{
					$str = '<input type="checkbox" class="checkone" value="'.$each->common_id.'">';
				}
				$operate='<div class="cz_list_btn" ><input type="hidden" class="form_sn" value="'.$each->form_sn.'">'
						.'<span class="ignore_form" title="忽略" url="/index.php/voucher/ignoreform/'.$each->common_id.'" salesid="'.$each->main_id.'"><img src="/images/zuofei.png"></span>'
						.'</div>';
						$da['data']=array(
								$i,
								$str,
								$operate,
								$each->form_sn,
								$_type[$each->bill_type],
								//$_way[$each->pay_type],
								$each->form_time,
								$each->owned_by_nickname,
								$each->form_type=="YHHZ"?$bank1->dict_name:$each->title_name,
								$each->form_type=="YHHZ"?$bank2->dict_name:'<span title="'.$each->customer_name.'">'.$each->customer_short_name.'</span>',
								number_format($each->amount),
								number_format($each->weight,3),
								number_format($each->fee,2),
								$each->is_yidan == 1?"是":"",
								$each->created_at>0?date("Y-m-d",$each->created_at):"",
								'<span title="'.htmlspecialchars($each->comment).'">'.mb_substr($each->comment,0,15,"UTF-8").'</span>',
						);
						$da['group']=$each->form_sn;
						array_push($tableData,$da);
			}
		}
		return array($tableData,$pages);
	}
	
	//处理收付款凭证数据
	public static function processPayData(){
		$str = $_COOKIE["check_payment_list"];
		$str_bz=$_COOKIE['check_payment_list_fybz'];
		$list = explode(",",$str);
		$list_bz=explode(",",$str_bz);
		//数组去重
		$list_bz=array_unique($list_bz);
		$list = array_unique($list);
		$num = 0;
		$str_type1 = $str_yidan1 = $str = "";
		$str_type2 = $str_yidan2 = "";
		$data = array();
		$_type = array("FKDJ"=>"付款","SKDJ"=>"收款","YHHZ"=>"银行互转");
		$_yidan = array("甲单","乙单");
		$gaokai = array();
		$gk_id = array();
		$fybz =array();		
		foreach ($list as $li){
			if($li==0){continue;}
			$business = PaymentView::model()->find("common_id=$li");
			if($business->bill_type=="FYBZ")
			{
				array_push($fybz, $li);
				continue;
			}
			$num ++;
			$business = PaymentView::model()->find("common_id=$li");
			$temp = array();
			if($business){
				$type = $business->form_type;
 				$yidan = $business->is_yidan;
				$title_id = $business->title_id;
				$customer_id = $business->customer_id;
				$user_id = $business->owned_by;
				$bank_id = $business->bank_id;
				$title = DictTitle::model()->findByPk($title_id);
				$customer = DictCompany::model()->findByPk($customer_id);
				$user = User::model()->findByPk($user_id);
				$bank = DictBankInfo::model()->findByPk($bank_id);
				$outbank = DictBankInfo::model()->findByPk($business->out_bank_id);
				//银付
				if(($type == "FKDJ" && $yidan == 0) || ($type == "DQDK" &&  $bank->voucher_type==0)){
					$str1 .= "单号：{$business->form_sn}的类型为：银付,与其余数据不符<br/>";
					$voucher_type = "银付";
				}
				//银收
				if(($type == "SKDJ" && $yidan == 0) || ($type == "YHHZ" && $bank->voucher_type==0) || ($type == "DQJK" &&  $bank->voucher_type==0)){
					$str2 .= "单号：{$business->form_sn}的类型为：银收,与其余数据不符<br/>";
					$voucher_type = "银收";
				}
				//现付
				if(($type == "FKDJ" && $yidan == 1) || ($type == "DQDK" &&  $bank->voucher_type==1)){
					$str3 .= "单号：{$business->form_sn}的类型为：现付,与其余数据不符<br/>";
					$voucher_type = "现付";
				}
				//现收
				if(($type == "SKDJ" && $yidan == 1) || ($type == "YHHZ" && $bank->voucher_type==1) || ($type == "DQJK" &&  $bank->voucher_type==1)){
					$str4 .= "单号：{$business->form_sn}的类型为：现收,与其余数据不符<br/>";
					$voucher_type = "现收";
				}
				//目前只有收付款才需要抬头和业务员的财务编码
				if($type == "SKDJ" || $type == "FKDJ"){
					if($title->out_number == "" || $title->out_number == "default"){
						$str.="单号：{$business->form_sn}的公司抬头：“{$business->title_name}”没有财务编码<br/>";
					}
					if($user->number == "" || $user->number == "default"){
						$str.="单号：{$business->form_sn}的业务员：”{$business->owned_by_nickname}“没有财务编码<br/>";
					}
					$bill_type = $business->bill_type;
					if($bill_type == "XSSK" || $bill_type == "XSTH"){
						if($customer->cus_number == "" || $customer->cus_number == "default"){
							$str.="单号：{$business->form_sn}的结算单位：“{$customer->name}”没有财务编码<br/>";
						}
					}
					if($bill_type == "CGFK" || $bill_type == "CGTH"||$bill_type=="TPYF"||$bill_type=="TPSH"){
						if($customer->sup_number == "" || $customer->sup_number == "default"){
							$str.="单号：{$business->form_sn}的供应商：“{$customer->name}”没有财务编码<br/>";
						}
					}
				}
				if($bank->number == "" || $bank->number == "default"){
					$str.="单号：{$business->form_sn}的银行账户：“{$bank->dict_name}”没有财务编码<br/>";
				}
				//如果是银行互转，判断转出账户是否存在财务编码
				if($type == "YHHZ"){
					if($outbank->number == "" or $outbank->number == "default"){
						$str.="单号：{$business->form_sn}的银行账户：“{$outbank->dict_name}”没有财务编码<br/>";
					}
				}
				//如果时短期借贷，判断结算单位是否有编码
				if($type == "DQJK" || $type == "DQDK"){
					if($customer->dj_number == "" || $customer->dj_number == "default"){
							$str.="单号：{$business->form_sn}的结算单位：“{$customer->name}”没有财务编码<br/>";
					}
				}
				if($type == "SKDJ" || $type == "FKDJ"){
					$form_type = $business->bill_type;
					switch ($form_type){
						case "XSSK":
							$temp["account_code"] = $user->number;
							$temp["account_name"] = $user->nickname;
							break;
						case "XSTH":
							$temp["account_code"] = $user->number;
							$temp["account_name"] = $user->nickname;
							break;
						case "CGFK":
							if($business->is_yidan){
								$temp["account_code"] = "1123.0005";
							}else{
								$temp["account_code"] = $title->out_number;
							}
							$temp["account_name"] = $title->short_name;
							break;
						case "CGTH":
							if($business->is_yidan){
								$temp["account_code"] = "1123.0005";
							}else{
								$temp["account_code"] = $title->out_number;
							}
							$temp["account_name"] = $title->short_name;
							break;
						case "TPYF":
							$temp["account_code"] = "1123.0004";
							$temp["account_name"] = $title->short_name;
							break;
						case "TPSH":
							$temp["account_code"] = "1123.0004";
							$temp["account_name"] = $title->short_name;
							break;
						case "GKFK":
							$bank_key = $business->bank_id;
							$gaokai[$bank_key]["account_code"] = "6601.0002";
							$gaokai[$bank_key]["account_name"] = "返利支出";
							$gaokai[$bank_key]["debit"] += $business->fee;
							$gaokai[$bank_key]["sort2"] = 0;
							$gaokai[$bank_key]["credit"] = 0;
							$gaokai[$bank_key]["amount"] += $business->amount;
							$gaokai[$bank_key]["company_id"] = 0;
							$gaokai[$bank_key]["id"] = $business->common_id;
							$gaokai[$bank_key]["bank_id"] = $business->bank_id;
							$gaokai[$bank_key]["form_type"] = $form_type;
							$gaokai[$bank_key]["type"] = $type;
							$gaokai[$bank_key]["is_yidan"] = $yidan;
							$gaokai[$bank_key]["sort1"] = $num;
							$gaokai[$bank_key]["bankname"] = $bank->dict_name;
							array_push($gk_id,$business->common_id);
							break;
						default:
							return "数据类型不正确";
					}
					if($form_type == "GKFK"){continue;}
					if($type == "FKDJ"){
						$temp["debit"] = $business->fee;
						$temp["sort2"] = 0;
						$temp["credit"] = 0;
					}else{
						$temp["debit"] = 0;
						$temp["sort2"] = 1;
						$temp["credit"] = $business->fee;
					}
					$temp["amount"] = $business->amount;
					$temp["company_id"] = $customer_id;
					$temp["id"] = $business->common_id;
					$temp["bank_id"] = $business->bank_id;
					$temp["form_type"] = $form_type;
					$temp["type"] = $type;
					$temp["is_yidan"] = $yidan;
					array_push($data,$temp);
				}
				if($type == "YHHZ"){
					//银行互转拼接成两条明细，入账为主，出款在前
					$temp["account_code"] = $outbank->number;
					$temp["account_name"] = $outbank->dict_name;
					$temp["debit"] = 0;
					$temp["credit"] = $business->fee;
					$temp["sort2"] = 1;
					$temp["amount"] = $business->amount;
					$temp["company_id"] = 0;
					$temp["id"] = $business->common_id;
					$temp["bank_id"] = $business->out_bank_id;
					$temp["form_type"] = "YHHZ";
					$temp["type"] = $type;
					$temp["is_yidan"] = $yidan;
					$temp["sort1"] = $num;
					array_push($data,$temp);
					$temp["account_code"] = $bank->number;
					$temp["account_name"] = $bank->dict_name;
					$temp["debit"] = $business->fee;
					$temp["sort2"] = 0;
					$temp["credit"] = 0;
					$temp["amount"] = $business->amount;
					$temp["company_id"] = 0;
					$temp["id"] = $business->common_id;
					$temp["bank_id"] = $business->bank_id;
					$temp["form_type"] = "YHHZ";
					$temp["type"] = $type;
					$temp["is_yidan"] = $yidan;
					$temp["sort1"] = $num;
					array_push($data,$temp);
				}
				//短期借款，同银行互转一样，需要生成两条明细。借方在上
				if($type == "DQJK"){
					$temp["account_code"] = $bank->number;
					$temp["account_name"] = $bank->dict_name;
					$temp["debit"] = $business->fee;
					$temp["sort2"] = 0;
					$temp["credit"] = 0;
					$temp["amount"] = $business->amount;
					$temp["company_id"] = $customer_id;
					$temp["id"] = $business->common_id;
					$temp["bank_id"] = $business->out_bank_id;
					$temp["form_type"] = "DQJK";
					$temp["type"] = $type;
					$temp["is_yidan"] = $yidan;
					$temp["sort1"] = $num;
					array_push($data,$temp);
					$temp["account_code"] = $customer->dj_number;
					$temp["account_name"] = $customer->name;
					$temp["debit"] = 0;
					$temp["sort2"] = 1;
					$temp["credit"] = $business->fee;
					$temp["amount"] = $business->amount;
					$temp["company_id"] = $customer_id;
					$temp["id"] = $business->common_id;
					$temp["bank_id"] = $business->bank_id;
					$temp["form_type"] = "DQJK";
					$temp["type"] = $type;
					$temp["is_yidan"] = $yidan;
					$temp["sort1"] = $num;
					array_push($data,$temp);
				}
				if($type == "DQDK"){
					$temp["account_code"] = $customer->dj_number;
					$temp["account_name"] = $customer->name;
					$temp["debit"] = $business->fee;
					$temp["sort2"] = 0;
					$temp["credit"] = 0;
					$temp["amount"] = $business->amount;
					$temp["company_id"] = $customer_id;
					$temp["id"] = $business->common_id;
					$temp["bank_id"] = $business->bank_id;
					$temp["form_type"] = "DQDK";
					$temp["type"] = $type;
					$temp["is_yidan"] = $yidan;
					$temp["sort1"] = $num;
					array_push($data,$temp);
					$temp["account_code"] = $bank->number;
					$temp["account_name"] = $bank->dict_name;
					$temp["debit"] = 0;
					$temp["sort2"] = 1;
					$temp["credit"] = $business->fee;
					$temp["amount"] = $business->amount;
					$temp["company_id"] = $customer_id;
					$temp["id"] = $business->common_id;
					$temp["bank_id"] = $business->out_bank_id;
					$temp["form_type"] = "DQDK";
					$temp["type"] = $type;
					$temp["is_yidan"] = $yidan;
					$temp["sort1"] = $num;
					array_push($data,$temp);
				}
				
			}else{
				return "获取数据失败";
			}
		}		
		if($gaokai){
			foreach ($gaokai as $k=>$v){
				array_push($data,$v);
				$temp = array();
				$temp["account_code"] = "1001.0001";
				$temp["account_name"] = $v['bankname'];
				$temp["debit"] = $v['credit'];
				$temp["sort2"] = 1;
				$temp["credit"] = $v['debit'];
				$temp["amount"] = $v['amount'];
				$temp["company_id"] = 0;
				$temp["id"] = $v['id'];
				$temp["bank_id"] = $key;
				$temp["form_type"] = "GKFK";
				$temp["type"] = $v['type'];
				$temp["is_yidan"] = $v['is_yidan'];
				$temp["sort1"] = $v['sort1'];
				array_push($data,$temp);
			}
		}
			
		//费用报支		
		if($fybz)
		{				
			$code_array=array();
			$criteria=new CDbCriteria();
			$criteria->addInCondition('main_id', $list_bz);
			$criteria->addInCondition('common_id', $fybz);
			$business_s=PaymentView::model()->findAll($criteria);
			if($business_s)
			{
				foreach ($business_s as $business)
				{
					$num ++;
					$temp = array();
					$type = $business->form_type;
					$yidan = $business->is_yidan;
					$title_id = $business->title_id;
					$customer_id = $business->customer_id;
					$user_id = $business->owned_by;
					$bank_id = $business->bank_id;
					$title = DictTitle::model()->findByPk($title_id);
					$customer = DictCompany::model()->findByPk($customer_id);
					$user = User::model()->findByPk($user_id);
					$bank = DictBankInfo::model()->findByPk($bank_id);
					$outbank = DictBankInfo::model()->findByPk($business->out_bank_id);
					$billother_detail=BillOtherDetail::model()->with('recordType2')->findByPk($business->main_id);
					if($bank->number == "" || $bank->number == "default"){
						$str.="单号：{$business->form_sn}的银行账户：“{$bank->dict_name}”没有财务编码<br/>";
					}
					if($bank->number)array_push($code_array, substr($bank->number, 0,4));
					$recordType=$billother_detail->recordType2->name;
					switch ($recordType)
					{
						case'银行手续费':
							if($bank->voucher_type==0)$voucher_type = "银付";
							if($bank->voucher_type==1)	$voucher_type = "现付";
							$temp['account_code']="6603.0001";
							$temp['account_name']="银行手续费";
							$temp['mark']='BZSX';
							break;
						case '社会保险费':
							$voucher_type="现付";
							$temp['account_code']="6602.0004";
							$temp['account_name']="社会保险费";
							$temp['mark']='BZBX';
							break;
						case '工资费用':
							$voucher_type="现付";
							$temp['account_code']="2211";
							$temp['account_name']="应付职工薪酬";
							$temp['mark']='BZGZ';
							break;
						case '税金':
							$voucher_type="银付";
							$temp['account_code']="6602.0005";
							$temp['account_name']="税金";
							$temp['mark']='BZSJ';
							break;
						default:
							return "数据类型不正确";
							break;
					}
					$temp["debit"] = $business->fee;
					$temp["sort1"] = 0;
					$temp["credit"] = 0;
					$temp["amount"] = $business->amount;
					$temp["company_id"] = $customer_id;
					$temp["id"] = $business->common_id;
					$temp["bank_id"] = $business->bank_id;
					$temp["form_type"] = "FYBZ";
					$temp["type"] = $type;
					$temp["is_yidan"] = $yidan;
					$temp['main_id']=$business->main_id;
					$temp['comment']=$recordType;
					$temp['sort2']=$num;
					array_push($data,$temp);				
				}
			}
			$code_array=array_unique($code_array);
			if(count($code_array)>1)
			{
				$str.='生成的凭证中既有银付又有现付，不能生成';
			}			
		}
		
			
		//判断字符串长度，以多的为准，提示少的数据错误
		$error_str = Voucher::compareLongStr($str1,$str2,$str3,$str4);
		$str .= $error_str;
		if($str){
			return $str;
		}
		if($data){
			$result = Voucher::createPayData($data,$voucher_type,$gk_id);
			if($result){
				return "success";
			}else{
				return "保存数据失败";
			}
		}else{
			return "没有数据";
		}
	}
	
	//生成凭证
	public static function createPayData($data,$voucher_type,$gk_id){
		$voucher_name = $voucher_type;
		$transaction=Yii::app()->db->beginTransaction();
		try {
			$voucher_number = Voucher::getVoucherNum(time(),$voucher_name);
			$main = new Voucher();
			$main->voucher_name = $voucher_name;
			$main->voucher_number = $voucher_number;
			//$main->type = $v_type;
			$main->attachment = 0;
			$main->created_at = strtotime(date("Ymd"));
			$main->form_at = strtotime(date("Ymd"));
			$main->created_by = currentUserId();
			if($main->insert()){				
				$bankarr = array();
				$bzarr=array();
				$sort=array();
				foreach ($data as $li){
					$form_type = $li["form_type"];
					if(!in_array($form_type, $sort)){
						array_push($sort, $form_type);
					}
					switch ($form_type){
						case "XSSK":
							$v_type = 0;
							$comment = "销售收款";
							break;
						case "XSTH":
							$v_type = 0;
							$comment = "退还对方货款";
							break;
						case "CGFK":
							$v_type = 1;
							$comment = "付货款";
							break;
						case "GKFK":
							$v_type = 1;
							$comment = "高开付款";
							break;
						case "CGTH":
							$v_type = 1;
							$comment = "采购退款";
							break;
						case "YHHZ":
							$v_type = 0;
							$comment = "内部往来";
							break;
						case "TPYF":
							$v_type =1;
							$comment='托盘预付款';
							break;
						case "TPSH":
							$v_type=1;
							$comment='托盘赎回';
							break;
						case "FYBZ":
							$v_type=1;
							$comment=$li['comment'];
							break;
						case "DQJK":
						case "DQDK":
							$v_type = 2;
							$comment = "短借";
							break;
						default:
							return false;
					}
					
					if($form_type!='FYBZ')
					{
						$detail = new VoucherDetail();
						$detail->voucher_id = $main->id;
						$detail->comment = $comment;
						$detail->account_code = $li["account_code"];
						$detail->account_name = $li["account_name"];
						$detail->debit = $li["debit"];
						$detail->sort2 = $li["sort2"];
						$detail->credit = $li["credit"];
						$detail->amount = $li["amount"];
						$detail->unit = "吨";
						$detail->company_id = $li["company_id"];
						$detail->common_id = $li["id"];
						$detail->type = $v_type;
						$detail->billother_id=$li['main_id'];
						$type=$li["type"];
						if(($type == "SKDJ" || $type == "FKDJ") && $form_type != "GKFK"){
							$key = $form_type.$li["bank_id"];
							if(count($bankarr[$key]) == 0){
								$bankarr[$key]["sort"] = count($bankarr);
							}
							$bankarr[$key]["amount"] +=  $detail->amount;
							$bankarr[$key]["debit"] +=  $li["credit"];
							$bankarr[$key]["credit"] +=  $li["debit"];
							$bankarr[$key]["is_yidan"] = $li["is_yidan"];
							$bankarr[$key]["comment"] = $comment;
							$detail->sort1 = $bankarr[$key]["sort"];
						}else{
							$detail->sort1 = $li["sort1"];
						}
						$detail->insert();
						if($form_type != "GKFK"&&$form_type!="FYBZ"){
							$baseform = CommonForms::model()->findByPk($li["id"]);
							$baseform->is_voucher = 1;
							$baseform->update();
						}					
					}else{
						//费用报支单独						
						$bankinfo = DictBankInfo::model()->findByPk($li['bank_id']);
						$detail = new VoucherDetail();
						$detail->voucher_id = $main->id;
						$detail->comment = $comment;
						$detail->account_code = $bankinfo->number;
						$detail->account_name = $bankinfo->dict_name;
						$detail->company_id=$li['company_id'];
						$detail->debit = $li["credit"];
						$detail->credit =$li["debit"];				
						$detail->sort2=$li['sort2'];					
						$detail->amount = $li["amount"];
						$detail->unit = "*";
						$detail->billother_id=$li['main_id'];									
						
						$key_a=$li['mark'];												
						$bzarr[$key_a]['voucher_id']=$main->id;
						$bzarr[$key_a]['comment']=$comment;
						$bzarr[$key_a]['account_code']=$li['account_code'];
						$bzarr[$key_a]['account_name']=$li['account_name'];
						$bzarr[$key_a]['debit']+=$li['debit'];
						$bzarr[$key_a]['credit']+=$li['credit'];
						$bzarr[$key_a]['amount']+=$li['amount'];
						$bzarr[$key_a]['unit']="*";
						$bzarr[$key_a]['type']=$v_type;
						$detail->sort1 = count($sort)+array_search($key_a, array_keys($bzarr))+1;
						$detail->insert();
						$bzarr[$key_a]['sort1']=$detail->sort1;
			
						$billother_detail = BillOtherDetail::model()->findByPk($li["main_id"]);
						$billother_detail->is_voucher = 1;
						$billother_detail->update();			
					}				
				}
				if($gk_id){
					foreach($gk_id as $k=>$v){
						$vou_gk = new VoucherGk();
						$vou_gk->voucher_id = $main->id;
						$vou_gk->common_id = $v;
						$vou_gk->save();
						$baseform = CommonForms::model()->findByPk($v);
						$baseform->is_voucher = 1;
						$baseform->update();
					}
				}				
				foreach ($bzarr as $k=>$v){
					$detail = new VoucherDetail();
					$detail->voucher_id = $main->id;
					$detail->comment = $v["comment"];					
					$detail->account_code = $v['account_code'];
					$detail->account_name = $v['account_name'];
					$detail->credit = $v["credit"];
					$detail->debit = $v["debit"];				
					$detail->sort2=0;								
					$detail->amount = $v["amount"];
					$detail->unit = "*";
					$detail->sort1 = $v["sort1"];
					$detail->insert();
				}
				
				foreach ($bankarr as $k=>$v){
					$bank_id = substr($k,4);
					$bankinfo = DictBankInfo::model()->findByPk($bank_id);
					$detail = new VoucherDetail();
					$detail->voucher_id = $main->id;
					$detail->comment = $v["comment"];
					$yidan = intval($v["is_yidan"]);
					$detail->account_code = $bankinfo->number;
					$detail->account_name = $bankinfo->dict_name;
					$detail->debit = $v["debit"];
					$detail->credit = $v["credit"];
					if($detail->debit != 0){
						$detail->sort2=0;
					}
					if($detail->credit != 0){
						$detail->sort2=1;
					}
					$detail->amount = $v["amount"];
					$detail->unit = "吨";
					$detail->sort1 = $v["sort"];
					//$detail->company_id = 0;
					$detail->insert();
				}
			}
			$transaction->commit();
		}catch (Exception $e)
		{
			$transaction->rollBack();//事务回滚
			return false;
		}
		return true;
	}
	
	//更新
	public static function updateData($post,$id){
		$time = strtotime($post['form_at']);
		$voucherNum = $post['voucher_number'];
		$year = date("Y",$time);
		$month = date("m",$time);
		$allday = date("t",$time);
		$strat_time = strtotime($year."-".$month."-1"." 00:00:00");
		$end_time = strtotime($year."-".$month."-".$allday."23:59:59");
		$voucher = Voucher::model()->findByPk($id);
		$month1 = date("m",$voucher->form_at);
		if($month1 != $month){
			$voucherNum = Voucher::getVoucherNum($time,$post['voucher_name']);
		}
		if($voucher){
			$criteria=new CDbCriteria;
			$criteria->select='voucher_number'; // only select the 'title' column
			$criteria->condition="is_deleted=0 and voucher_name='{$post['voucher_name']}' and voucher_number='{$post['voucher_number']}' and id<>{$id} and form_at>={$strat_time} and form_at<={$end_time}";
			$voucher_num = Voucher::model()->find($criteria);
			if($voucher_num){return -1;}
			$voucher->voucher_name = $post['voucher_name'];
			$voucher->voucher_number = $voucherNum;
			$voucher->attachment = $post['attachment'];
			$voucher->created_at = strtotime($post['created_at']);
			$voucher->form_at = $time;
			if($voucher->update()){
				return 1;
			}else{
				return 0;
			}
		}else{
			return -1;
		}
	}
	
	//获取忽略凭证列表
	public static function getIgnoreList($search){
		$tableData=array();
		$model = new CommonForms();
		$criteria=New CDbCriteria();
		//搜索
		if(!empty($search))
		{
			$criteria->together=true;
			if(trim($search['keywords'])){
				$criteria->addCondition('form_sn like :contno');
				$criteria->params[':contno']= "%".$search['keywords']."%";
			}
			if($search['time_L']!='')
			{
				$criteria->addCondition('form_time >="'.$search['time_L'].'"');
			}
			if($search['time_H']!='')
			{
				$criteria->addCondition('form_time <="'.$search['time_H'].'"');
			}
			if($search['form_type']){
				if($search['form_type'] == "DQJK"){
					$criteria->addCondition('form_type="DQJK" or form_type="DQDK"');
				}else{
					$criteria->addCondition('form_type="'.$search['form_type'].'"');
				}
			}
		}
		$criteria->addCondition('is_deleted=0');
		$criteria->addCondition('is_voucher=2');
		$criteria->order = "form_time DESC";
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize =intval($_COOKIE['ignore_list']) ? intval($_COOKIE['ignore_list']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);
		$details=$model->findAll($criteria);
		if($details){
			$da=array();
			$da['data']=array();
			$i=0;
			$mark='';
			$_type=array("XSD"=>"销售单","XSTH"=>"销售退货","CGD"=>"采购单","CGTH"=>"采购退货","SKDJ"=>"收款","FKDJ"=>"付款",
					"YHHZ"=>"银行互转","DQJK"=>"短期借款","DQDK"=>"短期贷款");
			foreach($details as $each){
				$i++;
				$operate='<div class="cz_list_btn"><input type="hidden" class="form_sn" value="'.$each->form_sn.'">';
				$operate.='<span class="cancel_form" url="/index.php/voucher/cancleIgonre/'.$each->id.'"  title="取消忽略"><img src="/images/zuofei.png"></span>';
				$operate.='</div>';
				$da['data']=array(
						$i,
						$operate,
						$each->form_sn,
						$_type[$each->form_type],
						$each->form_time,
						$each->belong->nickname,
				);
				$da['group']=$each->id;
				array_push($tableData,$da);
			}
		}
		return array($tableData,$pages);
	}
	
	//比较提示字符串的长度，返回除最长的以外其余字符串拼接的结果
	public static function compareLongStr($str1,$str2,$str3="",$str4="",$str5="",$str6=""){
		list($str_l,$str_r) = compareStr($str1,$str2);
		$str.=$str_r;
		list($str_l,$str_r) = compareStr($str_l,$str3);
		$str.=$str_r;
		list($str_l,$str_r) = compareStr($str_l,$str4);
		$str.=$str_r;
		list($str_l,$str_r) = compareStr($str_l,$str5);
		$str.=$str_r;
		list($str_l,$str_r) = compareStr($str_l,$str6);
		$str.=$str_r;
		return $str;
	}
	
	//根据月份和年份获取凭证号
	public static function getVoucherNum($time,$type){
		$year = date("Y",$time);
		$month = date("m",$time);
		$allday = date("t",$time);
		$strat_time = strtotime($year."-".$month."-1"." 00:00:00");
		$end_time = strtotime($year."-".$month."-".$allday."23:59:59");
		
		$criteria=new CDbCriteria;
		$criteria->select='voucher_number'; // only select the 'title' column
		$criteria->condition="is_deleted=0 and voucher_name='{$type}' and form_at>={$strat_time} and form_at<={$end_time}";
		$criteria->order = "voucher_number DESC";
		$criteria->limit = "1";
		$voucher = Voucher::model()->find($criteria);
		if($voucher){
			return $voucher->voucher_number + 1;
		}else{
			return 1;
		}
	}
}