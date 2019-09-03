<?php

/**
 * This is the biz model class for table "frm_pypk".
 *
 */
class FrmPypk extends FrmPypkData
{
	public $total_amount;
	public $total_weight;
	public $product_id;
	public $warehouse_id;
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			"storage"=>array(self::BELONGS_TO, 'Storage', 'storage_id'),
			'baseform'=>array(self::HAS_ONE,'CommonForms','form_id','condition'=>'baseform.form_type="PYPK"'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'type' => 'Type',
			'storage_id' => 'Storage',
			'amount' => 'Amount',
			'weight' => 'Weight',
			'comment' => 'Comment',
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
		$criteria->compare('type',$this->type);
		$criteria->compare('storage_id',$this->storage_id);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('weight',$this->weight,true);
		$criteria->compare('comment',$this->comment,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FrmPypk the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * 获取盘盈盘亏列表
	 */
	public static function getFormList($search,$id)
	{
		$tableData=array();
		$model = new FrmPypk();
		$criteria=New CDbCriteria();
		$criteria->with = array("storage","storage.inputDetail","baseform");
	
		if(!empty($search)){
			if($search['time_L']!='')
			{
				$criteria->addCondition('baseform.form_time >="'.$search['time_L'].'"');
			}
			if($search['time_H']!='')
			{
				$criteria->addCondition('baseform.form_time <="'.$search['time_H'].'"');
			}
			
			if($search['card_no']!='')
			{
				$criteria->addCondition("storage.card_no like '%".$search['card_no']."%'");
			}
			if($search['warehouse_id']!='0')
			{
				$criteria->compare('storage.warehouse_id',$search['warehouse_id'],false);
			}
			if($search['title_id']!='0')
			{
				$criteria->compare('storage.title_id',$search['title_id'],false);
			}
			//产地,品名，规格,材质
			if($search['brand']!='0')
			{
				$criteria->compare('inputDetail.brand_id',$search['brand'],false);
			}
			if($search['product']!='0')
			{
				$criteria->compare('inputDetail.product_id',$search['product'],false);
			}
			if($search['rank']!='0')
			{
				$criteria->compare('inputDetail.rank_id',$search['rank'],false);
			}
			if($search['texture']!='0')
			{
				$criteria->compare('inputDetail.texture_id',$search['texture'],false);
			}
			if($search['type']!='0'){
				$criteria->compare('t.type',$search['type']-1,false);
			}
		}
		if($search['status']){
			$criteria->compare('baseform.is_deleted',$search['status']-1,false);
		}else{
			$criteria->compare('baseform.is_deleted',0,false);
		}
		if($id){
			$criteria->compare('t.storage_id',$id,false);
		}
		$c = clone $criteria;
		$criteria->order = "t.id DESC";
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize =intval($_COOKIE['pypk_list']) ? intval($_COOKIE['pypk_list']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);
		$details=$model->findAll($criteria);
		$c->select = "sum(t.amount) as total_amount,sum(t.weight) as total_weight";
		$alldetail = FrmPypk::model()->find($c);
		
		$totaldata = array();
		$totaldata["amount"] = $alldetail->total_amount;
		$totaldata["weight"] = $alldetail->total_weight;
		
		$da['data']=array();
		$_type = array("盘盈","盘亏");
		if($details){
			$i=1;
			foreach ($details as $each)
			{
				$baseform = $each->baseform;
				$storage = $each->storage;
				$inputDetails = $storage->inputDetail;
				if(checkOperation("盘盈盘亏:新增")){
					$operate = '<div class="cz_list_btn"><input type="hidden" class="form_sn" value="">'
								.'<span class="deleted" id="/index.php/FrmPypk/delete/'.$baseform->id.'" title="作废" onclick="deleteIt(this);" lastdate="'.$baseform->last_update.'"><img src="/images/zuofei.png"></span>'			
								.'</div>';
				}
				$card_url = Yii::app()->createUrl('storage/index',array('card_no'=>$storage->card_no));
				$da['data']=array(
						$i,
						$operate,
						$baseform->form_sn,
						$baseform->form_time,
						$storage->title->short_name,
						'<a href="'.$card_url.'" title="查看详情" class="a_view">'.$storage->card_no.'</a>',
						'<span class="warehouse_name">'.$storage->warehouse->name.'</span>',//仓库
						'<span class="brand">'.DictGoodsProperty::getProName($inputDetails->brand_id).'</span>',
						'<span class="product">'.DictGoodsProperty::getProName($inputDetails->product_id).'</span>',
						'<span class="texture">'.str_replace('E','<span class="red">E</span>',DictGoodsProperty::getProName($inputDetails->texture_id)).'</span>',
						'<span class="rank">'.DictGoodsProperty::getProName($inputDetails->rank_id).'</span>',
						'<span class="length">'.$inputDetails->length.'</span>',
						$_type[$each->type],
						//abs($each->amount),
						number_format(abs($each->weight),3),
 						$baseform->belong->nickname,
// 						date("Y-m-d",$each->created_at),
						'<span title="'.htmlspecialchars($each->comment).'">'.mb_substr($each->comment,0,15,"UTF-8").'</span>',
				);
				if($baseform->is_deleted == 1){
					array_push($da['data'],'<span title="'.htmlspecialchars($baseform->delete_reason).'">'.mb_substr($baseform->delete_reason,0,15,"UTF-8").'</span>');
				}
				$da['group']=$i;
				$i++;
				array_push($tableData,$da);
			}
		}
		return array($tableData,$pages,$totaldata);
	}
	
	/**
	 * 删除盘盈盘亏
	 */
	public static function deleted($id,$str)
	{
		$stock = FrmPypk::model()->findByPk($id);
		$oldJson = $stock->datatoJson();
		if($stock->is_deleted == 1){
			return false;
		}
		$stock->is_deleted = 1;
		$stock->deleted_reason = $str;
		if($stock->update())
		{
			$newJson = $stock->datatoJson();
			$dataArray = array("tableName"=>"StockTransfer","newValue"=>$newJson,"oldValue"=>$oldJson);
			$baseform = new BaseForm();
			$baseform->dataLog($dataArray);
			$storage = Storage::model()->findByPk($stock->storage_id);
			$oldJson = $storage->datatoJson();
			$storage->left_amount += $stock->amount;
			$storage->left_weight += $stock->weight;
			$storage->update();
			$newJson = $storage->datatoJson();
			$dataArray = array("tableName"=>"Storage","newValue"=>$newJson,"oldValue"=>$oldJson);
			$baseform = new BaseForm();
			$baseform->dataLog($dataArray);
			if($stock->amount > 0){
				$model = new MergeStorage();
				$criteria=New CDbCriteria();
				$criteria->addCondition('product_id ='.$storage->inputDetail->product_id);
				$criteria->addCondition('brand_id ='.$storage->inputDetail->brand_id);
				$criteria->addCondition('texture_id ='.$storage->inputDetail->texture_id);
				$criteria->addCondition('rank_id ='.$storage->inputDetail->rank_id);
				$criteria->addCondition('length ='.$storage->inputDetail->length);
				$criteria->addCondition('title_id ='.$storage->title_id);
				$criteria->addCondition('is_transit = 0');
				$criteria->addCondition('is_deleted = 0');
				$merge = $model->find($criteria);
				if($merge){
					$oldJson=$merge->datatoJson();
					$merge->left_amount += $stock->amount;
					$merge->left_weight += $stock->weight;
					if($merge->update()){
						$mainJson = $merge->datatoJson();
						$dataArray = array("tableName"=>"MergeStorage","newValue"=>$mainJson,"oldValue"=>$oldJson);
						$baseform = new BaseForm();
						$baseform->dataLog($dataArray);
						return true;
					}
				}
			}
			return true;
		}else{
			return false;
		}
	}
}
