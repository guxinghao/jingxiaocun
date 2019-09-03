<?php

/**
 * This is the biz model class for table "company_contact".
 *
 */
class CompanyContact extends CompanyContactData
{
	

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'frmPurchaseReturns' => array(self::HAS_MANY, 'FrmPurchaseReturn', 'company_contact_id'),
			'frmSalesReturns' => array(self::HAS_MANY, 'FrmSalesReturn', 'contact_id'),
			'company'=>array(self::BELONGS_TO, 'DictCompany', 'dict_company_id'), 
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
			'name' => 'Name',
			'mobile' => 'Mobile',
			'is_default' => 'Is Default',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('is_default',$this->is_default);
		$criteria->compare('dict_company_id',$this->dict_company_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CompanyContact the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/*
	 * 获取公司联系人列表
	 */
	public static function getContactList($id="")
	{
		if($id){
			$condition = "dict_company_id=".$id;
		}else{
			$condition = "";
		}
		$contacts=CompanyContact::model()->findAll($condition);
		$contacts_array=array();
		if($contacts)
		{
			foreach ($contacts as $contact)
			{
				$contacts_array[$contact->id]=$contact->name;
			}
		}
		return $contacts_array;
	}
	
	/*
	 * 获取某公司联系人列表
	 */
	public static  function getConts($id)
	{
		$return='';
		$phone='';
		if($id==0)
		{
			$users=CompanyContact::model()->findAll();		
		}else{
			$users=CompanyContact::model()->findAllByAttributes(array('dict_company_id'=>$id));
		}	
		if($users)
		{
			$temp='';			
			$flag=0;
			foreach ($users as $each)
			{
				if($each->is_default==1)
				{
					$temp='<option value="'.$each->id.'">'.$each->name.'</option>';
					$phone=$each->mobile;
					$flag=1;
					continue;
				}
				$return.='<option value="'.$each->id.'">'.$each->name.'</option>';
				if($flag==0)$phone=$each->mobile;
			}
			$return=$temp.$return.'o1o'.$phone;
		}
		
		return $return;
	}

	public static function createContact($post){
		$model = new CompanyContact();
		$model->attributes = $post;
		$model->created_at = time();
		$model->created_by = Yii::app()->user->userid;
		$model->last_update_at = $model->created_at;
		$model->last_update_by = $model->created_by;
		if($model->is_default==1){
			$old = CompanyContact::model()->find("dict_company_id=$model->dict_company_id and is_default=1");
			if($old){
				$old->is_default = 0;
				$old->last_update_at = time();
				$old->update();
			}
		}
		return $model->insert();
	}
	
	public function updateContact($post){
		$this->attributes = $post;
		$this->last_update_at = time();
		$this->last_update_by = Yii::app()->user->userid;
		if($this->is_default==1){
			$old = CompanyContact::model()->find("dict_company_id={$this->dict_company_id} and is_default=1 and id<>{$this->id}");
			if($old){
				$old->is_default = 0;
				$old->last_update_at = time();
				$old->update();
			}
		}
		return $this->save();
	}

	
	public static function getIndexList(){
		$model = new CompanyContact();
		
		$cri = new CDbCriteria();
		$search =  new CompanyContact();
		if($_GET['dict_company_id']){
		    $search->dict_company_id = $_GET['dict_company_id'];
		}
		if($_POST['CompanyContact']){
			
			$search->attributes = $_POST['CompanyContact'];
			if(!$_POST['CompanyContact']['dict_company_id']&&$_GET['dict_company_id']){
			    $search->dict_company_id = $_GET['dict_company_id'];
			}
			if($search->name){
			    $cri->params[':name'] = "%".$search->name."%";
				$cri->addCondition("name like :name");
			}
		}
		if($search->dict_company_id){
		    $cri->addCondition("dict_company_id = $search->dict_company_id");
		}
		$pages = new CPagination();
		$pages->itemCount = $model->count($cri);
		$pages->pageSize = $_COOKIE['cc']? intval($_COOKIE['cc']):10;
		$pages->applyLimit($cri);
		$items = $model->findAll($cri);
		return array($model,$search,$pages,$items);
	}
	
}
