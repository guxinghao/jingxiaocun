<?php
header("Content-type: text/html; charset=utf-8");
/**
 * This is the biz model class for table "dict_title".
 *
 */
class DictTitle extends DictTitleData
{
	

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'frmFormBills' => array(self::HAS_MANY, 'FrmFormBill', 'title_id'),
			'frmPurchases' => array(self::HAS_MANY, 'FrmPurchase', 'title_id'),
			'frmPurchaseContracts' => array(self::HAS_MANY, 'FrmPurchaseContract', 'dict_title_id'),
			'frmPurchaseInvoices' => array(self::HAS_MANY, 'FrmPurchaseInvoice', 'title_id'),
			'frmSalesInvoices' => array(self::HAS_MANY, 'FrmSalesInvoice', 'title_id'),
			'pledgeRedeemeds' => array(self::HAS_MANY, 'PledgeRedeemed', 'title_id'),
			'storages' => array(self::HAS_MANY, 'Storage', 'title_id'),
			'turnovers' => array(self::HAS_MANY, 'Turnover', 'title_id'),
		    'banks'=>array(self::HAS_MANY,'DictBankInfo','dict_title_id'),
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

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DictTitle the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/*
	 * 获取采购公司列表
	 */
	public static function getComs($type)
	{
		$return = array();
		$coms = DictTitle::model()->findAll("1 = 1 order by short_name not in ('乘翔实业', '瑞亮物资')");
		switch ($type) {
			case 'array': 
				if ($coms) {
					foreach ($coms as $com) {
						$return[$com->id] = $com->short_name;
					}
				}
				break;
			case 'json': 
				if ($coms) {
					foreach ($coms as $com) {
						$temp = array();
						$temp['id'] = "$com->id";
						$temp['bs'] = "$com->code";
						$temp['name'] = "$com->short_name";
						array_push($return, $temp);
					}
				}
				$return = json_encode($return);
				break;
			default: break;
		}
		return $return;
	}

	/*
	 * 获取名称
	 */
	public static function getName($id)
	{
		$model=DictTitle::model()->findByPk($id);
		if($model)
		{
			return $model->short_name;
		}
	}
	
	//保存公司抬头
	public function createTitle($post){
		$this->attributes = $post;
		$bool = DictTitle::model()->exists("name='{$model->name}'");
//		$bool1 = DictTitle::model()->exists("short_name='{$model->short_name}'");
		if ($bool) return -1;
		return $this->save();
	}
	
	public function updateTitle($post){
		$this->attributes = $post;
		$bool = DictTitle::model()->exists("id<>$this->id and name='{$this->name}'");
//		$bool1 = DictTitle::model()->exists("short_name='{$this->short_name}'");
		if($bool){
			return -1;
		}else{
			return $this->save();
		}
	}
	
	public static function getIndexList(){
		$model = new DictTitle();
		
		$cri = new CDbCriteria();
		$search =  new DictTitle();
		
		if($_POST['DictTitle']){
			
			$search->attributes = $_POST['DictTitle'];

			if($search->name){
				$cri->params[':name'] = "%".$search->name."%";
				$cri->addCondition("name like :name");
			}
			if($search->code){
				$cri->params[':code'] = "%".$search->code."%";
				$cri->addCondition("code like :code");
			}
		}
		$pages = new CPagination();
		$pages->itemCount = $model->count($cri);
		$pages->pageSize = $_COOKIE['gstt']? intval($_COOKIE['gstt']):Yii::app()->params['pageCount'];
		$pages->applyLimit($cri);
		$items = $model->findAll($cri);
		return array($model,$search,$pages,$items);
	}
	
	public static function synchronization($title, $type) 
	{
		$columns = array();
		$fields = array();
		
		$keys = array_keys($title->attributes);
		for ($i = 0; $i < count($title->attributes); $i++) 
		{
			if ($keys[$i] == 'name') //抬头名称
			{
				$columns[] = (Object)array('Text' => "抬头名称", 'Schema' => "title_name");
				$fields[] = (Object)array('Text' => $title->name, 'Value' => $title->id, 'Standard' => "");
			}
			if ($keys[$i] == 'code') //公司抬头-拼音
			{
				$columns[] = (Object)array('Text' => "助记码", 'Schema' => "title_code");
				$fields[] = (Object)array('Text' => $title->code, 'Value' => "", 'Standard' => "");
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
				'type' => "jxc_title",
				'unid' => Yii::app()->user->unid,
				'form_id' => $title->id,
				'operate' => $type,
				'content' => $json,
				'form_sn'=>$title->short_name,
		);
		$model = PushList::createNew($data);
		return $model;
		
// 		$api = new api_center();
// 		$json = $api->pushForm($json, 'jxc_title', $type, Yii::app()->user->unid, $push_id);
// 		$data["interface"] = $json;
// 		//接口中心
// 		$posturl = $api->api_center_host."/index.php/interface";
// 		$result = requestByCurl($posturl, $data);
// 		return $result; 
	}
	
	//导入公司抬头
	public static function importTitle($data){
		$has = DictTitle::model()->find("name='".$data["name"]."'");
		if($has){
			return 0;
		}
		$title = new DictTitle();
		$title->name = $data['name'];
		$title->short_name = $data['short_name'];
		$title->code = $data['code'];
		if($title->insert()){
			return $title->id;
		}else{
			return -1;
		}
	}
	
	//根据公司名称，获取公司id
	//$type为1为全称，默认为0根据简称查询
	public static function getTitleId($name,$type=0){
		if($type==1){
			$title = DictTitle::model()->find("name='".$name."'");
		}else{
			$title = DictTitle::model()->find("short_name='".$name."'");
		}
		return $title->id;
	}
}
