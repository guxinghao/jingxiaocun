<?php

/**
 * This is the biz model class for table "stock_transfer".
 *
 */
class StockTransfer extends StockTransferData
{
	

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			"storage"=>array(self::BELONGS_TO, 'Storage', 'storage_id'),
			'created' => array(self::BELONGS_TO, 'User', 'created_by'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'storage_id' => 'Storage',
			'amount' => 'Amount',
			'weight' => 'Weight',
			'comment' => 'Comment',
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
		$criteria->compare('storage_id',$this->storage_id);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('weight',$this->weight,true);
		$criteria->compare('comment',$this->comment,true);
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
	 * @return StockTransfer the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * 新建调拨记录
	 */
	public static function createStock($post)
	{
		$model = new StockTransfer();
		$model->storage_id = $post["id"];
		$model->amount = $post["amount"];
		$model->weight = $post["weight"];
		$model->comment = $post["comment"];
		$model->created_at = time();
		$model->created_by = currentUserId();
		if($model->insert()){
			$mainJson = $model->datatoJson();
			$dataArray = array("tableName"=>"StockTransfer","newValue"=>$mainJson,"oldValue"=>"");
			$baseform = new BaseForm();
			$baseform->dataLog($dataArray);
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * 获取调拨列表
	 */
	public static function getFormList($search)
	{
		$tableData=array();
		$model = new StockTransfer();
		$criteria=New CDbCriteria();
		$criteria->with = array("storage","storage.inputDetailDx");
		
		if(!empty($search)){
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
				$criteria->compare('inputDetailDx.brand_id',$search['brand'],false);
			}
			if($search['product']!='0')
			{
				$criteria->compare('inputDetailDx.product_id',$search['product'],false);
			}
			if($search['rank']!='0')
			{
				$criteria->compare('inputDetailDx.rank_id',$search['rank'],false);
			}
			if($search['texture']!='0')
			{
				$criteria->compare('inputDetailDx.texture_id',$search['texture'],false);
			}
		}
		if($search['status']){
			$criteria->compare('t.is_deleted',$search['status']-1,false);
		}else{
			$criteria->compare('t.is_deleted',0,false);
		}
		
		$criteria->order = "t.created_at DESC";
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize =intval($_COOKIE['stock_list']) ? intval($_COOKIE['stock_list']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);
		$details=$model->findAll($criteria);
		$da['data']=array();
		if($details){
			$i=1;
			foreach ($details as $each)
			{
				$storage = $each->storage;
				$inputDetails = $storage->inputDetailDx;
				if(checkOperation("代销调拨:新增")){
				$operate = '<div class="cz_list_btn"><input type="hidden" class="form_sn" value="">'
							.'<span class="deleted" id="/index.php/stockTransfer/delete/'.$each->id.'" title="作废" onclick="deleteIt(this);"><img src="/images/zuofei.png"></span>'
							.'</div>';
				}
				$da['data']=array(
					$i,
					$operate,
					$storage->title->short_name,
					'<span class="supply_name" title="'.$inputDetails->input->supply->name.'">'.$inputDetails->input->supply->short_name.'</span>',
					$storage->card_no,
					'<span class="warehouse_name">'.$storage->warehouse->name.'</span>',//仓库
					'<span class="brand">'.DictGoodsProperty::getProName($inputDetails->brand_id).'</span>',
					'<span class="product">'.DictGoodsProperty::getProName($inputDetails->product_id).'</span>',
					'<span class="texture">'.str_replace('E','<span class="red">E</span>',DictGoodsProperty::getProName($inputDetails->texture_id)).'</span>',
					'<span class="rank">'.DictGoodsProperty::getProName($inputDetails->rank_id).'</span>',
					'<span class="length">'.$inputDetails->length.'</span>',
					$each->amount,
					number_format($each->weight,3),
					$each->created->nickname,
					date("Y-m-d",$each->created_at),
					'<span title="'.htmlspecialchars($each->comment).'">'.mb_substr($each->comment,0,15,"UTF-8").'</span>',
				);
				if($each->is_deleted == 1){
					array_push($da['data'],'<span title="'.htmlspecialchars($each->deleted_reason).'">'.mb_substr($each->deleted_reason,0,15,"UTF-8").'</span>');
				}
				$da['group']=$i;
				$i++;
				array_push($tableData,$da);
			}
		}
		return array($tableData,$pages,$totaldata);
	}
	
	/**
	 * 删除调拨
	 */
	public static function deleted($id,$str)
	{
		$stock = StockTransfer::model()->findByPk($id);
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
			$storage->update();
			$newJson = $storage->datatoJson();
			$dataArray = array("tableName"=>"Storage","newValue"=>$newJson,"oldValue"=>$oldJson);
			$baseform = new BaseForm();
			$baseform->dataLog($dataArray);
			return true;
		}else{
			return false;
		}
	}
}

