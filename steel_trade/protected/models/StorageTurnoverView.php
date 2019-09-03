<?php
/**
 * This is the biz model class for table "storage_turnover_view".
 *
 */
class StorageTurnoverView extends StorageTurnoverViewData
{
	public $st;//搜索条件开始时间，date型
	public $et;//搜索条件结束时间，date型

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'detail_id' => 'Detail',
			'type' => 'Type',
			'warehouse_id' => 'Warehouse',
			'title_id' => 'Title',
			'company_id' => 'Company',
			'card_no' => 'Card No',
			'brand_id' => 'Brand',
			'product_id' => 'Product',
			'texture_id' => 'Texture',
			'rank_id' => 'Rank',
			'length' => 'Length',
			'amount' => 'Amount',
			'weight' => 'Weight',
			'created_at' => 'Created At',
			'brand_name' => 'Brand Name',
			'product_name' => 'Product Name',
			'texture_name' => 'Texture Name',
			'rank_name' => 'Rank Name',
			'warehouse_name' => 'Warehouse Name',
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

		$criteria->compare('detail_id',$this->detail_id);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('warehouse_id',$this->warehouse_id);
		$criteria->compare('title_id',$this->title_id);
		$criteria->compare('company_id',$this->company_id);
		$criteria->compare('card_no',$this->card_no,true);
		$criteria->compare('brand_id',$this->brand_id);
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('texture_id',$this->texture_id);
		$criteria->compare('rank_id',$this->rank_id);
		$criteria->compare('length',$this->length);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('weight',$this->weight,true);
		$criteria->compare('created_at',$this->created_at);
		$criteria->compare('brand_name',$this->brand_name,true);
		$criteria->compare('product_name',$this->product_name,true);
		$criteria->compare('texture_name',$this->texture_name,true);
		$criteria->compare('rank_name',$this->rank_name,true);
		$criteria->compare('warehouse_name',$this->warehouse_name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return StorageTurnoverView the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public static function getIndexList(){
		$model = new StorageTurnoverView();
		$search = new StorageTurnoverView();
		$cri = new CDbCriteria();
		
		if($_REQUEST['is_ccrk']==1){
		    $cri->addCondition("input_type='ccrk'");
		}else{
		    $cri->addCondition("input_type<>'ccrk'");
		}
		
		if($_POST['StorageTurnoverView']){
			$search->attributes = $_POST['StorageTurnoverView'];
			$search->st = $_POST['StorageTurnoverView']['st'];
			$search->et = $_POST['StorageTurnoverView']['et'];
			if($search->brand_id){
				$cri->addCondition("brand_id = ".intval($search->brand_id));
			}
			if($search->product_id){
				$cri->addCondition("product_id = ".intval($search->product_id));
			}
			if($search->texture_id){
				$cri->addCondition("texture_id = ".intval($search->texture_id));
			}
			if($search->rank_id){
				$cri->addCondition("rank_id = ".intval($search->rank_id));
			}
			if($search->warehouse_id){
				$cri->addCondition("warehouse_id = ".intval($search->warehouse_id));
			}
			if($search->card_no){
				$cri->params[':card_no'] = "%".$search->card_no."%";
				$cri->addCondition("card_no like :card_no");
			}
			if($search->title_id){
				$cri->addCondition("title_id = ".intval($search->title_id));
			}
		}
		if($search->st){
			$st_time = strtotime($search->st." 00:00:00");
			$cri->addCondition("created_at>='{$st_time}'");
		}
		if($search->et){
			$et_time = strtotime($search->et." 23:59:59");
			$cri->addCondition("created_at<='{$et_time}'");
		}
		
		if(!$_POST&&$_GET['card_no']){
		    $cri->params[':card_no'] = $_GET['card_no'];
		    $cri->addCondition("card_no = :card_no");
		    $search->card_no = $_GET['card_no'];
		}
		
		if($search->card_no){
		    $cri->order = "created_at asc";
		}else{
		    $cri->order = "created_at desc";
		}
		$pages = new CPagination();
		$pages->itemCount = $model->count($cri);
		$pages->pageSize = $_COOKIE['kcls']?intval($_COOKIE['kcls']):Yii::app()->params['pageCount'];
		$pages->applyLimit($cri);
		$items = $model->findAll($cri);
		return array($model,$search,$pages,$items);
	}

}
