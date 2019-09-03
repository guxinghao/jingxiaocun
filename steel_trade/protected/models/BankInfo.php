<?php

/**
 * This is the biz model class for table "bank_info".
 *
 */
class BankInfo extends BankInfoData
{
	

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'dictCompany' => array(self::BELONGS_TO, 'DictCompany', 'dict_company_id'),
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
			'bank_name' => 'Bank Name',
			'company_name' => 'Company Name',
			'bank_number' => 'Bank Number',
			'code' => 'Code',
			'money' => 'Money',
			'dict_company_id' => 'Dict Company',
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
		$criteria->compare('bank_name',$this->bank_name,true);
		$criteria->compare('company_name',$this->company_name,true);
		$criteria->compare('bank_number',$this->bank_number,true);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('money',$this->money,true);
		$criteria->compare('dict_company_id',$this->dict_company_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BankInfo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public static function getBankList($type, $id = "") 
	{
		$condition = "1 = 1";
		$params = array();
		if ($id) 
		{
			$condition .= " AND dict_company_id = :dict_company_id";
			$params[':dict_company_id'] = $id;
		}
		$banks = BankInfo::model()->findAll($condition, $params);
		if (!$banks) return ;
		
		$return = array();
		switch ($type) 
		{
			case 'array': 
				foreach ($banks as $bank)
				{
					$return[$bank->id] = $bank->company_name.'('.$bank->bank_number.')';
				}
				break;
			case 'json': 
				foreach ($banks as $bank) 
				{
					$temp = array();
					$temp['id'] = "$bank->id";
					$temp['bs'] = "$bank->code";
					$temp['name'] = "$bank->company_name($bank->bank_number)";
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
		$model = new BankInfo();
		$model->attributes = $post;
//		$bool = BankInfo::model()->exists("bank_name='{$model->bank_name}'");
////		$bool1 = DictTitle::model()->exists("short_name='{$model->short_name}'");
//		if($bool){
//			return -1;
//		}else{
			$model->created_at = time();
			$model->created_by = Yii::app()->user->userid;
			$model->last_update_at = $model->created_at;
			$model->last_update_by = $model->created_by;
			return $model->insert();
//		}
	}
	
	public function updateBankInfo($post){
		
		$this->attributes = $post;
		
//		$bool = BankInfo::model()->exists("id<>$this->id and bank_name='{$this->bank_name}'");
//		$bool1 = DictTitle::model()->exists("short_name='{$this->short_name}'");
//		if($bool){
//			return -1;
//		}else{
			$this->last_update_at = time();
			$this->last_update_by = Yii::app()->user->userid;
			return $this->save();
//		}
	}

	
	public static function getIndexList(){
		$model = new BankInfo();
		
		$cri = new CDbCriteria();
		$search =  new BankInfo();
		
		if($_POST['BankInfo']){
			
			$search->attributes = $_POST['BankInfo'];
			$cri->params = array(":bank_name"=>"%".$search->bank_name."%",":dict_company_id"=>$search->dict_company_id);
			if($search->bank_name){
				$cri->addCondition("bank_name like :bank_name or company_name like :bank_name or bank_number like :bank_name or code like :bank_name");
			}
			if($search->dict_company_id){
				$cri->addCondition("dict_company_id = $search->dict_company_id");
				$search->dict_company_name = DictCompany::getShortName($search->dict_company_id);
			}
			
		}
		$pages = new CPagination();
		$pages->itemCount = $model->count($cri);
		$pages->pageSize = $_COOKIE['jsyh']? intval($_COOKIE['jsyh']):10;
		$pages->applyLimit($cri);
		$items = $model->findAll($cri);
		return array($model,$search,$pages,$items);
	}
}
