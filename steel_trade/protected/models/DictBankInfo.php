<?php

/**
 * This is the biz model class for table "dict_bank_info".
 *
 */
class DictBankInfo extends DictBankInfoData
{
	public $initial_balance; //期初余额
	public $final_balance; //期末余额

	public $current_in; //本期入账
	public $current_out; //本期出账
	public $total_initial;
	

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'dictTitle' => array(self::MANY_MANY, 'DictTitle','dict_title_bank_relation(bank_id,title_id)'),
			'creater' => array(self::BELONGS_TO, 'User', 'created_by'),
			'updater' => array(self::BELONGS_TO, 'User', 'last_update_by'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'created_at' => 'Created At',
			'created_by' => 'Created By',
			'last_update_at' => 'Last Update At',
			'last_update_by' => 'Last Update By',
			'dict_title_id' => 'Dict Title',
			'bank_name' => 'Bank Name',
			'dict_name' => 'Dict Name',
			'bank_number' => 'Bank Number',
			'code' => 'Code',
			'money' => 'Money',
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
		$criteria->compare('created_at',$this->created_at);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('last_update_at',$this->last_update_at);
		$criteria->compare('last_update_by',$this->last_update_by);
		$criteria->compare('dict_title_id',$this->dict_title_id);
		$criteria->compare('bank_name',$this->bank_name,true);
		$criteria->compare('dict_name',$this->dict_name,true);
		$criteria->compare('bank_number',$this->bank_number,true);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('money',$this->money,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DictBankInfo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function getComs($type, $id = "") 
	{
		$condition = "1 = 1";
		$params = array();
		if ($id) 
		{
			$condition .= " AND title_id = :title_id";
			$params[':title_id'] = $id;
		}
		$condition .= " GROUP BY bank_id";

		$relations = DictTitleBankRelation::model()->findAll($condition, $params);
		if (!$relations) return ;
		
		$return = array();
		switch ($type) 
		{
			case 'array': 
				foreach ($relations as $each) 
				{
					$bank = $each->bank;
					$return[$bank->id] = $bank->dict_name;
				}
				break;
			case 'json': 
				foreach ($relations as $each) 
				{
					$bank = $each->bank;
					$temp = array();
					$temp['id'] = "$bank->id";
					$temp['bs'] = "$bank->code";
					$temp['name'] = "$bank->dict_name";
					array_push($return, $temp);
				}
				$return = json_encode($return);
				break;
			default: 
				break;
		}
		return $return;
	}

	public static function getBankList($type, $id = "",$is_yidan=100) 
	{
		// $condition = "1 = 1";
		// $params = array();
		// if ($id) 
		// {
		// 	$condition .= " AND title_id = :title_id";
		// 	$params[':title_id'] = $id;
		// }		
		// $condition .= " GROUP BY t.bank_id";
		// $relations = DictTitleBankRelation::model()->findAll($condition, $params);
		$criteria=new CDbCriteria();
		if($id){
			$criteria->compare('title_id',$id);
		}
		if($is_yidan!=100)
		{
			if($is_yidan)
			{
				$criteria->addCondition("bank.bank_level= -1  or bank.bank_level=0");
			}else{
				$criteria->addCondition("bank.bank_level= 1  or bank.bank_level=0");
			}
		}
		
		$criteria->with=array('bank');
		$criteria->order="priority ASC";
		$criteria->group='t.bank_id';		
		$relations = DictTitleBankRelation::model()->findAll($criteria);
		if (!$relations) return ;		
		$return = array();
		switch ($type) 
		{
			case 'array': 
				foreach ($relations as $each) 
				{
					$bank = $each->bank;
					$return[$bank->id] = $bank->dict_name.'('.$bank->bank_number.')';
				}
				break;
			case 'json': 
				foreach ($relations as $each) 
				{
					$bank = $each->bank;
					$temp = array();
					$temp['id'] = "$bank->id";
					$temp['bs'] = "$bank->code";
					$temp['name'] = "$bank->dict_name($bank->bank_number)";
					array_push($return, $temp);
				}
				$return = json_encode($return);
				break;
			default: 
				break;
		}
		return $return;
	}
	
	public static function getAccountList($type, $id = "")
	{
	    $condition = "1 = 1";
		$params = array();
		if ($id) 
		{
			$condition .= " AND title_id = :title_id";
			$params[':title_id'] = $id;
		}
		$condition .= " GROUP BY bank_id";
		$relations = DictTitleBankRelation::model()->findAll($condition, $params);
		if (!$relations) return ;
//		$banks = DictBankInfo::model()->findAll($condition, $params);
//		if (!$banks) return ;
		
		$return = array();
		switch ($type) 
		{
			case 'array': 
				foreach ($relations as $each) 
				{
					$bank = $each->bank;
					$return[$bank->id] = $bank->dict_name.'('.$bank->bank_number.')';
				}
				break;
			case 'json': 
				foreach ($relations as $each) 
				{
					$bank = $each->bank;
					$temp = array();
					$temp['id'] = "$bank->id";
					$temp['bs'] = "$bank->code";
					$temp['name'] = "$bank->dict_name($bank->bank_number)";
					array_push($return, $temp);
				}
				$return = json_encode($return);
				break;
			default: 
				break;
		}
		return $return;
	}
	
	public static function createBankInfo($post){
		$bank_no = DictBankInfo::model()->find("bank_number='".$post['bank_number']."'");
		if($bank_no){
			return -1;
		}
		$model = new DictBankInfo();
		$model->attributes = $post;
		$model->money = $model->initial_money;
		$model->created_at = time();
		$model->created_by = Yii::app()->user->userid;
		$model->last_update_at = $model->created_at;
		$model->last_update_by = $model->created_by;
		
		if($model->insert()){
			foreach($post['dict_title_id'] as $k=>$v){
				$relation = new DictTitleBankRelation();
				$relation->bank_id = $model->id;
				$relation->title_id = $v;
				$relation->insert();
			}
		}else{
			return 0;
		}
		return 1;
	}
	
	public function updateBankInfo($post){
		$id = $post["id"];
		$list = $post["dict_title_id"];
		$arr = array();
		$model = DictBankInfo::model()->findByPk($id);
		$oldJson = $model->datatoJson();
		$model->bank_name = $post["bank_name"];
		$model->dict_name = $post["dict_name"];
		$model->bank_number = $post["bank_number"];
		$model->code = $post["code"];
		$model->initial_money = $post["initial_money"];
		$model->last_update_at = time();
		$model->last_update_by = Yii::app()->user->userid;
		$model->bank_level=$post['bank_level'];
		$model->number = $post["number"];
		$model->voucher_type = $post["voucher_type"];
		$model->update();
	
		//日志
		$base = new BaseForm();
		$mainJson = $model->datatoJson();
		$dataArray = array("tableName"=>'dict_bank_info',"newValue"=>$mainJson,"oldValue"=>$oldJson);
		$base->dataLog($dataArray);
		$relation = DictTitleBankRelation::model()->findAll("bank_id=".$id);
		if($relation){
			foreach ($relation as $li){
				if(!in_array($li->id,$list)){
					$oldJson = $li->datatoJson();
					$li->delete();
					$base = new BaseForm();
					$dataArray = array("tableName"=>'dict_title_bank_relation',"newValue"=>'',"oldValue"=>$oldJson);
					$base->dataLog($dataArray);
				}else{
					array_push($arr,$li->id);
				}
			}
			foreach($list as $k=>$v){
				if(!in_array($v,$arr)){
					$relation = new DictTitleBankRelation();
					$relation->bank_id = $id;
					$relation->title_id = $v;
					$relation->insert();
					$mainJson = $relation->datatoJson();
					$dataArray = array("tableName"=>'dict_title_bank_relation',"newValue"=>$mainJson,"oldValue"=>'');
					$base->dataLog($dataArray);
				}
			}
		}
		return true;
// 		$old_initial_money = $this->initial_money; 
// 		$this->attributes = $post;
// 		$this->money  =  $this->initial_money - $old_initial_money + $this->money; 
// 		$this->last_update_at = time();
// 		$this->last_update_by = Yii::app()->user->userid;
// 		return $this->save();
	}
	
	public static function getIndexList(){
		$model = new DictBankInfo();
		$cri = new CDbCriteria();
// 		$cri->with=array("dictTitle"=>array("condition"=>'dictTitle.id=11'));
		if ($_POST['DictBankInfo']) 
		{
			$model->attributes = $_POST['DictBankInfo'];
			if ($model->bank_name) {
				$cri->addCondition("t.bank_name LIKE :bank_name OR t.dict_name LIKE :bank_name OR t.bank_number LIKE :bank_name OR t.code LIKE :bank_name");
				$cri->params[':bank_name'] = '%'.$model->bank_name.'%';
			}
		} 
		elseif ($_GET['title_id']) 
		{ 
			$model->dict_title_id = intval($_GET['title_id']);
		}
//		$cri->addCondition("dictTitle.id=11");
		if ($model->dict_title_id) {
			$cri->with=array("dictTitle"=>array("condition"=>'dictTitle.id='.$model->dict_title_id));
			$model->dict_title_name = DictTitle::getName($model->dict_title_id);
// 			$cri->addCondition("t.dict_title_id = :dict_title_id");
// 			$cri->params[':dict_title_id'] = $model->dict_title_id;
		}
		$cri->order="t.id DESC";
		$cri->together=true;
		$pages = new CPagination();
		$pages->itemCount = $model->count($cri);
		//$pages->pageSize = $_COOKIE['gsyh'] ? intval($_COOKIE['gsyh']) : Yii::app()->params['pageCount'];
		$pages->pageSize = 100;
		$pages->applyLimit($cri);
// 		$items = $model->findAll($cri);
		$items = DictBankInfo::model()->findAll($cri);
// 		var_dump(count($items),$pages->itemCount,$model->dict_title_id,$cri);
		return array($model, $items, $pages);
	}
	
	//导入银行账户信息
	public static function importBank($data){
		$title_id = intval(DictTitle::getTitleId($data['title_name']));
		if($title_id == 0){return 0;}
		// 查找银行账户是否存在
		$bank = DictBankInfo::model()->find('bank_number="'.$data['bank_number'].'"');
		if($bank){
			$relation = new DictTitleBankRelation();
			$relation->title_id = $title_id;
			$relation->bank_id = $bank->id;
			if(!$relation->insert()){
				return -2;
			}else{
				return 1;
			}
		}else{
			$model = new DictBankInfo();
			$model->created_at = time();
			$model->created_by = 1;
			$model->dict_title_id = 0;
			$model->bank_name = $data['bank_name'];
			$model->dict_name = $data['dict_name'];
			$model->code = $data['code'];
			$model->bank_number = $data['bank_number'];
			if($model->insert()){
				$relation = new DictTitleBankRelation();
				$relation->title_id = $title_id;
				$relation->bank_id = $model->id;
				if(!$relation->insert()){
					return -2;
				}else{
					return 1;
				}
			}else{
				return -1;
			}
		}
		
	}
}
