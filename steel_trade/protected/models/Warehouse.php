<?php

/**
 * This is the biz model class for table "warehouse".
 *
 */
class Warehouse extends WarehouseData
{
	

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'creater' => array(self::BELONGS_TO, 'User', 'created_by'),
			'frmPurchases' => array(self::HAS_MANY, 'FrmPurchase', 'warehouse_id'),
			'frmPurchaseContracts' => array(self::HAS_MANY, 'FrmPurchaseContract', 'warehouse_id'),
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
			'code' => 'Code',
			'std' => 'Std',
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
		$criteria->compare('code',$this->code,true);
		$criteria->compare('std',$this->std,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Warehouse the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/*
	 *获取仓库列表
	 */
	public static function getWareList($type,$is_other=0)
	{
		$warehouses=Warehouse::model()->findAll("is_other<=".$is_other);
		if($type=='array')
		{
			$return=array();
			if($warehouses)
			{
				foreach ($warehouses as $warehouse)
				{
					$return[$warehouse->id]=$warehouse->name;
				}
			}
		}elseif($type=='json')
		{
			$temp=array();
			$return=array();
			if($warehouses)
			{
				foreach ($warehouses as $warehouse)
				{
					$temp['id']="$warehouse->id";
					$temp['bs']="$warehouse->code";
					$temp['name']="$warehouse->name";
					array_push($return, $temp);
				}				
			}
			$return=json_encode($return);
		}
		return $return;
	}

	public function createWarehouse($post){
		$this->attributes = $post;
		$this->is_other = 0;
		$this->created_by = Yii::app()->user->userid;
		$this->created_at = time();
		$bool = Warehouse::model()->exists("name='{$this->name}'");
		if($bool){
			return -1;
		}else if($bool1){
			return -6;
		}else{
			$this->insert();
			$this->std = "WH".date("Ymd").$model->id;
			return $this->save();
		}
	}
	
	public function updateWarehouse($post){
		$this->attributes = $post;
		$bool = Warehouse::model()->exists("id<>$this->id and name='{$this->name}'");
		if($bool){
			return -1;
		}else if($bool1){
			return -6;
		}else{
			return $this->save();
		}
	}
	
	/*
	 * 传入仓库名字，新建仓库 
	 */
	public static function SetWarehouse($name){
		$ware = new Warehouse();
		$ware->name = $name;
		$ware->is_other = 1;
		$ware->created_at = time();
		$ware->created_by = currentUserId();
		if($ware->insert()){
			$mainJson = $ware->datatoJson();
			$dataArray = array("tableName"=>"Warehouse","newValue"=>$mainJson,"oldValue"=>"");
			$baseform = new BaseForm();
			$baseform->dataLog($dataArray);
			return $ware;
		}else{
			return false;	
		}
	}
	
	
	public static function getName($id){
		$model = Warehouse::model()->findByPk($id);
		if(!$model) return "";
		return $model->name;
	}
	
	public static function getIndexList(){
		$model = new Warehouse();
		
		$cri = new CDbCriteria();
		$search =  new Warehouse();
		
		if($_POST['Warehouse']){
			$search->attributes = $_POST['Warehouse'];
			if($search->name){
				$cri->params[':name'] = $search->name;
				$cri->addCondition("name like :name or code like :name or std like :name or area like :name");
			}
		}
		$cri->order = "created_at desc";
		$pages = new CPagination();
		$pages->itemCount = $model->count($cri);
		$pages->pageSize = $_COOKIE['ck']? intval($_COOKIE['ck']):Yii::app()->params['pageCount'];
		$pages->applyLimit($cri);
		$items = $model->findAll($cri);
		return array($model,$search,$pages,$items);
	}
	
	public static function synchronization($warehouse, $type) 
	{
		$columns = array();
		$fields = array();
		
		$keys = array_keys($warehouse->attributes);
		for ($i = 0; $i < count($warehouse->attributes); $i++) 
		{
			if ($keys[$i] == 'name') //仓库名称
			{
				$columns[] = (Object)array('Text' => "仓库名称", 'Schema' => "warehouse_name");
				$fields[] = (Object)array('Text' => $warehouse->name, 'Value' => $warehouse->id, 'Standard' => "");
			}
			if ($keys[$i] == 'code') //拼音
			{
				$columns[] = (Object)array('Text' => "助记码", 'Schema' => "warehouse_code");
				$fields[] = (Object)array('Text' => $warehouse->code, 'Value' => "", 'Standard' => "");
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
				'type' => "jxc_warehouse",
				'unid' => Yii::app()->user->unid,
				'form_id' => $warehouse->id,
				'operate' => $type,
				'content' => $json,
				'form_sn'=>$warehouse->name,
		);
		$model = PushList::createNew($data);
		return $model;
	}
	
	//根据仓库名字获取仓库id
	public static function getWarehouseId($name)
	{
		$model = Warehouse::model()->find("short_name='".$name."'");
		return $model?$model->id:"";
	}
}
