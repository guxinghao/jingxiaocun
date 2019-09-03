<?php
/**
 * This is the biz model class for table "dict_company".
 *
 */
class DictCompany extends DictCompanyData
{
	

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'dictCompanyContacks' => array(self::HAS_MANY, 'DictCompanyContack', 'dict_company_id'),
			'frmFormBills' => array(self::HAS_MANY, 'FrmFormBill', 'company_id'),
			'frmPurchases' => array(self::HAS_MANY, 'FrmPurchase', 'supply_id'),
			'frmPurchaseContracts' => array(self::HAS_MANY, 'FrmPurchaseContract', 'dict_company_id'),
			'frmPurchaseInvoices' => array(self::HAS_MANY, 'FrmPurchaseInvoice', 'company_id'),
			'frmSalesInvoices' => array(self::HAS_MANY, 'FrmSalesInvoice', 'company_id'),
			'pledgeRedeemeds' => array(self::HAS_MANY, 'PledgeRedeemed', 'company_id'),
			'storages' => array(self::HAS_MANY, 'Storage', 'redeem_company_id'),
			'turnovers' => array(self::HAS_MANY, 'Turnover', 'target_id'),
			'turnovers1' => array(self::HAS_MANY, 'Turnover', 'proxy_company_id'),
		    'creater' => array(self::BELONGS_TO, 'User', 'created_by'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'short_name' => 'Short Name',
			'is_supply' => 'Is Supply',
			'is_pledge' => 'Is Pledge',
			'is_customer' => 'Is Customer',
			'is_logistics' => 'Is Logistics',
			'code' => 'Code',
			'created_at' => 'Created At',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('short_name',$this->short_name,true);
		$criteria->compare('is_supply',$this->is_supply);
		$criteria->compare('is_pledge',$this->is_pledge);
		$criteria->compare('is_customer',$this->is_customer);
		$criteria->compare('is_logistics',$this->is_logistics);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('created_at',$this->created_at);
		$criteria->compare('created_by',$this->created_by);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DictCompany the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * 获取某类公司列表
	 * @param $name 类型
	 */
	public static function getVendorList($type,$name="")
	{
		$change = $name ? $name : "is_supply";
		if($change == "is_supply"){
			$vendors = DictCompany::model()->findAllByAttributes(array($change => '1'),array("order"=>"su_priority ASC"));
		}else{
			$vendors = DictCompany::model()->findAllByAttributes(array($change => '1'),array("order"=>"priority ASC"));
		}
		
		if (!$vendors) return false;
		$return = array();
		if ($type == 'array') 
		{
			foreach ($vendors as $vendor) 
			{
				$return[$vendor->id] = $vendor->name;
			}
		} 
		elseif ($type == 'json') 
		{
			$temp = array();
			foreach ($vendors as $vendor) 
			{
				$temp['id'] = "$vendor->id";
				$temp['bs'] = "$vendor->code";
				$temp['name'] = "$vendor->name";
				array_push($return, $temp);
			}
			$return = json_encode($return);
		}
		return $return;
	}
	
	/**
	 * 获取某类公司列表,全称
	 * @param $name 类型
	 */
	public static function getAllVendorList($type,$name="")
	{
		$change = $name ? $name : "is_supply";
		if($change == "is_supply"){
			$vendors = DictCompany::model()->findAllByAttributes(array($change => '1'),array("order"=>"su_priority ASC"));
		}else{
			$vendors = DictCompany::model()->findAllByAttributes(array($change => '1'),array("order"=>"priority ASC"));
		}
	
		if (!$vendors) return false;
		$return = array();
		if ($type == 'array')
		{
			foreach ($vendors as $vendor)
			{
				$return[$vendor->id] =$vendor->name;
			}
		}
		elseif ($type == 'json')
		{
			$temp = array();
			foreach ($vendors as $vendor)
			{
				$temp['id'] = "$vendor->id";
				$temp['bs'] = "$vendor->code";
				$temp['name'] = $vendor->name;
				array_push($return, $temp);
			}
			$return = json_encode($return);
		}
		return $return;
	}
	
	public static function getLongName($id)
	{
		$model=DictCompany::model()->findByPk($id);
		if($model)
		{
			return $model->name;
		}
	}
	
	public static function getName($id)
	{
		$model=DictCompany::model()->findByPk($id);
		if($model)
		{
			return $model->short_name;
		}
	}
	public static function getShortName($id)
	{
		$model=DictCompany::model()->findByPk($id);
		if($model)
		{
			return $model->short_name;
		}
	}
	
	public function post_search($cri,$search){
		if($search->name){
		    $cri->params[':name'] = "%".$search->name."%";
			$cri->addCondition("name like :name or short_name like :name");		
		}
		if($search->code){
		    $cri->params[':code'] = "%".$search->code."%";
			$cri->addCondition("code like :code");
		}
		return $cri;
	}
	
	public function createCompany($post){
		$this->attributes = $post;
		$bool = DictCompany::model()->exists("name='{$this->name}'");
		$bool1 = DictCompany::model()->exists("short_name='{$this->short_name}'");
		
		if ($bool) return -1;
		if ($bool1) return -5;
		if ($this->is_pledge == 0) $this->level = 0;
		if ($this->is_supply == 0) {
			$this->fee = 0;
			$this->pledge_length = 0;
			$this->pledge_rate = 0;
		}
		$this->created_at = time();
		$this->created_by = Yii::app()->user->userid;
		return $this->save();
	}
	
	public function updateCompany($post){
		$this->attributes = $post;
		$bool = DictCompany::model()->exists("id<>$this->id and name='{$this->name}'");
		$bool1 = DictCompany::model()->exists("id<>$this->id and short_name='{$this->short_name}'");
		
//		var_dump($post['is_pledge']);
//		die;
		if($bool){
			return -1;
		}elseif($bool1){
			return -5;
		}else{
		    if($this->is_pledge==0){
		        $this->level=0;
		    }
		    if($this->is_supply==0){
		        $this->fee = 0;
		        $this->pledge_length = 0;
		        $this->pledge_rate = 0;
		    }
			return $this->save();
		}
	}
	
	/**
	 * 如果是供应商，根据供应商id获取运费
	 * @param int $id
	 */
	public static function getPrice($id)
	{
		$model=DictCompany::model()->findByPk(intval($id));
		if (!$model || !$model->is_supply) return 0;
		
		return $model->fee?$model->fee : 0;
	}
	
	
	/*
	 * 获取公司列表
	 */
	public static function getComs($type)
	{
		$coms=DictCompany::model()->findAll();
		$return=array();
		if($coms)
		{
			if($type=='array')
			{
				foreach ($coms as $com)
				{
					$return[$com->id]=$com->short_name;
				}
			}elseif($type=='json')
			{
				$temp=array();
				foreach ($coms as $com)
				{
					$temp['id']="$com->id";
					$temp['bs']="$com->code";
					$temp['name']="$com->short_name";
					array_push($return, $temp);
				}
				$return=json_encode($return);
			}
			return $return;
		}
	}
	
	/*
	 * 获取公司列表，使用全称
	 */
	public static function getAllComs($type)
	{
		$coms=DictCompany::model()->findAll();
		$return=array();
		if($coms)
		{
			if($type=='array')
			{
				foreach ($coms as $com)
				{
					$return[$com->id]=$com->name;
				}
			}elseif($type=='json')
			{
				$temp=array();
				foreach ($coms as $com)
				{
					$temp['id']="$com->id";
					$temp['bs']="$com->code";
					$temp['name']=$com->name;
					array_push($return, $temp);
				}
				$return=json_encode($return);
			}
			return $return;
		}
	}


	/*
	*短期借贷获取top10
	*	
	*/
	public static function getAllComsForDJ($type)
	{
		$coms=DictCompany::model()->findAll(array('order'=>' loan_priority asc'));
		$return=array();
		if($coms)
		{
			if($type=='array')
			{
				foreach ($coms as $com)
				{
					$return[$com->id]=$com->name;
				}
			}elseif($type=='json')
			{
				$temp=array();
				foreach ($coms as $com)
				{
					$temp['id']="$com->id";
					$temp['bs']="$com->code";
					$temp['name']=$com->name;
					array_push($return, $temp);
				}
				$return=json_encode($return);
			}
			return $return;
		}
	}


	
	/*
	 * 获取短期借贷公司列表（主要方便不懂拼音的人筛选查找）
	 */
	public static function getComsShort($type)
	{
		$coms=DictCompany::model()->findAll("1 = 1 order by short_name not in ('上海杨浦览坤小额贷款股份有限公司', '林永峰', '林国轮', '郑秀兰', '陈香端', '陈兴', '陈忠坚', '林锦燕', '蔡美錞', '陈翔')");
		$return=array();
		if($coms)
		{
			if($type=='array')
			{
				foreach ($coms as $com)
				{
					$return[$com->id]=$com->short_name;
				}
			}elseif($type=='json')
			{
				$temp=array();
				foreach ($coms as $com)
				{
					$temp['id']="$com->id";
					$temp['bs']="$com->code";
					$temp['name']="$com->short_name";
					array_push($return, $temp);
				}
				$return=json_encode($return);
			}
			return $return;
		}
	}
	public static function getIndexList(){
		$model = new DictCompany();
		
		$cri = new CDbCriteria();
		$search =  new DictCompany();
		
		if($_POST['DictCompany']){
			$search->attributes = $_POST['DictCompany'];
			$cri = DictCompany::model()->post_search($cri, $search);
		}
// 		$auth=Yii::app()->authManager;
// 		$bool=$auth->checkAccess('管理员',Yii::app()->user->userid);
		if (!checkOperation("结算单位管理"))
        {
            $cri->addCondition("created_by = ".Yii::app()->user->userid);
        }
		$cri->order = "created_at desc";
		
		$pages = new CPagination();
		$pages->itemCount = $model->count($cri);
		$pages->pageSize = $_COOKIE['jsdw']? intval($_COOKIE['jsdw']):Yii::app()->params['pageCount'];
		$pages->applyLimit($cri);
		$items = $model->findAll($cri);
		return array($model,$search,$pages,$items);
	}
	
	public static function synchronization($company, $type) 
	{
		$columns = array();
		$fields = array();
		
		$keys = array_keys($company->attributes);
		for ($i = 0; $i < count($company->attributes); $i++) 
		{
			if ($keys[$i] == 'name') //公司名称
			{
				$columns[] = (Object)array('Text' => "公司名称", 'Schema' => "name");
				$fields[] = (Object)array('Text' => $company->name, 'Value' => $company->id, 'Standard' => "");
			}
			if ($keys[$i] == 'code') //拼音
			{
				$columns[] = (Object)array('Text' => "助记码", 'Schema' => "code");
				$fields[] = (Object)array('Text' => $company->code, 'Value' => "", 'Standard' => "");
			}
			if ($keys[$i] == 'short_name') //简称
			{
				$columns[] = (Object)array('Text' => "公司简称", 'Schema' => "short_name");
				$fields[] = (Object)array('Text' => $company->short_name, 'Value' => "", 'Standard' => "");
			}
			if ($keys[$i] == 'is_supply') //是否是供应商
			{
				$columns[] = (Object)array('Text' => "是否是供应商", 'Schema' => "is_supply");
				$fields[] = (Object)array('Text' => $company->is_supply, 'Value' => "", 'Standard' => "");
			}
			if ($keys[$i] == 'is_pledge') //是否托盘公司
			{
				$columns[] = (Object)array('Text' => "是否托盘公司", 'Schema' => "is_pledge");
				$fields[] = (Object)array('Text' => $company->is_pledge, 'Value' => "", 'Standard' => "");
			}
			if ($keys[$i] == 'is_customer') //是否采购商（客户）
			{
				$columns[] = (Object)array('Text' => "是否采购商", 'Schema' => "is_customer");
				$fields[] = (Object)array('Text' => $company->is_customer, 'Value' => "", 'Standard' => "");
			}
			if ($keys[$i] == 'is_logistics') //是否物流供应商
			{
				$columns[] = (Object)array('Text' => "是否物流供应商", 'Schema' => "is_logistics");
				$fields[] = (Object)array('Text' => $company->is_logistics, 'Value' => "", 'Standard' => "");
			}
			if ($keys[$i] == 'is_dx') //是否代销
			{
				$columns[] = (Object)array('Text' => "是否代销", 'Schema' => "is_dx");
				$fields[] = (Object)array('Text' => $company->is_dx, 'Value' => "", 'Standard' => "");
			}
		}
		
		$record = new Record();
		$record->Fields = $fields;
		
		$records = array();
		$records[] = $record;
		
		$table = new Table();
		$table->Columns = $columns;
		$table->Records = $records;
		$json = json_encode($table);
		
		$data = array(
				'type' => "customer_company",
				'unid' => Yii::app()->user->unid,
				'form_id' => $company->id,
				'operate' => $type,
				'content' => $json,
				'form_sn'=>$company->short_name,
		);
		$model = PushList::createNew($data);
		return $model;
		
// 		$api = new api_center();
// 		$json = $api->pushForm($json, 'customer_company', $type, Yii::app()->user->unid, $push_id);
// 		$data["interface"] = $json;
// 		//接口中心
// 		$posturl = $api->api_center_host."/index.php/interface";
// 		$result = requestByCurl($posturl, $data);
// 		return $result; 
	}
	
	//根据公司名称，获取公司id
	//$type为1为全称，默认为0根据简称查询
	public static function getCompanyId($name,$type=0){
		if($type==1){
			$title = DictCompany::model()->find("name='".$name."'");
		}else{
			$title = DictCompany::model()->find("short_name='".$name."'");
		}
		return $title->id;
	}
	
	//导入结算单位
	public static function importCompany($data){
		//保存结算单位
		$model=new DictCompany();
		$model->name=$data['name'];
		$model->short_name=$data['short_name'];
		$model->code=$data['code'];
		$model->is_logistics=$data['is_logistics']?1:0;
		$model->is_customer=$data['is_customer']?1:0;
		$model->is_supply=$data['is_supply']?1:0;
		$model->created_at=time();
		$model->created_by=1;
		if($model->insert()){
			//从货主平台查询联系人
			$sql="select c.id as cid,c.name as cname,t.name,t.phone,t.is_default from customer_company_contact as t left join customer_company as c on t.customer_company_id=c.id where t.status=1 and c.name='".$model->name."'";
			$cmd = Yii::app()->db->createCommand($sql);
			$company = $cmd->queryAll($cmd);
			if($company){
				//新建联系人
				foreach ($company as $li){
					$contact=new CompanyContact();
					$contact->created_at=time();
					$contact->created_by=1;
					$contact->name=$li["name"];
					$contact->mobile=$li["phone"];
					$contact->is_default=$li["is_default"];
					$contact->dict_company_id=$model->id;
					if(!$contact->insert()){
						return -2;
					}
				}
				
			}else{
				//货主平台里没有找到对应结算但闻
				return -3;
			}
			
			
		}else{
			return -1;	
		}
	}
}
