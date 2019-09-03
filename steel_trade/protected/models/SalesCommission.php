<?php

/**
 * This is the biz model class for table "sales_commission".
 *
 */
class SalesCommission extends SalesCommissionData
{
	
	public $total_weight;
	public $total_money;
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			"owned"=>array(self::BELONGS_TO, 'User', 'owned_by'),
			"created"=>array(self::BELONGS_TO, 'User', 'created_by'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'date' => 'Date',
			'owned_by' => 'Owned By',
			'weight' => 'Weight',
			'money' => 'Money',
			'created_at' => 'Created At',
			'created_by' => 'Created By',
			'is_deleted' => 'Is Deleted',
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
		$criteria->compare('date',$this->date,true);
		$criteria->compare('owned_by',$this->owned_by);
		$criteria->compare('weight',$this->weight,true);
		$criteria->compare('money',$this->money,true);
		$criteria->compare('created_at',$this->created_at);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('is_deleted',$this->is_deleted);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SalesCommission the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	//创建销售提成
	public static function createForm($post){
		$year = $post['commission']['year'];
		$month = $post['commission']['month'];
		if($month < 10){
			$month = "0".$month;
		}
		$date = $year."-".$month;
		$transaction=Yii::app()->db->beginTransaction();
		try {
			for($i=0;$i<count($post['userid']);$i++){
				if(intval($post["check"][$i]) != 1){continue;}
				$userId = $post['userid'][$i];
				$model = SalesCommission::model()->find("date like '%{$date}%' and status<>-1 and owned_by={$userId}");
				if(!$model){
					$model = new SalesCommission();
				}
				$old_money = $model->money;
				$model->date = $date;
				$model->owned_by = $userId;
				$model->weight = numChange($post['weight'][$i]);
				$model->money = numChange(trim($post['money'][$i]));
				$model->created_at = time();
				$model->created_by = currentUserId();
				
				if($model->save()){

				}else{
					throw new CException("保存失败");
				}
			}
			$transaction->commit();
		}catch (Exception $e)
		{
			$transaction->rollBack();//事务回滚
			return -1;
		}
		return 1;
	}
	
	//获取表单列表
	public static function getFormList($search){
		$tableData=array();
		$model = new SalesCommission();
		$criteria=New CDbCriteria();
		if(!empty($search))
		{
			if($search['year']!=0)
			{
				$criteria->addCondition('date like "%'.$search['year'].'%"');
			}
			if($search['month']!=0)
			{
				$month = $search['month'];
				if($month < 10){
					$month = "0".$month;
				}
				$criteria->addCondition('date like "%-'.$month.'%"');
			}
			if($search['owned']!=0){
				$criteria->addCondition('owned_by="'.$search['owned'].'"');
			}
		}else{
			$criteria->addCondition('date like "%'.date("Y").'%"');
		}
		if(!empty($search['status']) && $search['status']!=100){
			$criteria->addCondition('status="'.$search['status'].'"');
		}else{
			$criteria->addCondition('status<>-1');
		}
		
		$c = clone $criteria;
		$criteria->order="id DESC";
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize =intval($_COOKIE['commission_list']) ? intval($_COOKIE['commission_list']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);
		$details=$model->findAll($criteria);
		
		$c->select = "sum(weight) as total_weight,sum(money) as total_money";
		$alldetail = SalesCommission::model()->find($c);
		$totaldata = array();
		$totaldata["weight"] = $alldetail->total_weight;
		$totaldata["money"] = $alldetail->total_money;
		return array($details,$pages,$totaldata);
	}
	
	//编辑销售提成
	public static function updateForm($post,$id){
		$transaction=Yii::app()->db->beginTransaction();
		try {
			$model =SalesCommission::model()->findByPk($id);
			$old_money = $model->money;
			$money = numChange(trim($post["money"]));
			$model->money = $money;
			if($model->update()){
				
			}else{
				throw new CException("保存失败");
			}
			$transaction->commit();
		}catch (Exception $e)
		{
			$transaction->rollBack();//事务回滚
			return 0;
		}
		return 1;
	}
	
	//均摊销售单提成
	public static function subsidy($model,$status){
		$userId = $model->owned_by;
		if($status == 1){
			$price = $model->money/$model->weight;
		}else{
			$price = 0;
		}
		$date = $model->date;
		$sales = new FrmSales();
		$criteria=New CDbCriteria();
		$criteria->with = array("baseform");
		$criteria->addCondition("baseform.owned_by=$userId");
		$criteria->addCondition("baseform.form_time like '%".$date."%'");
		$criteria->addCondition('baseform.is_deleted=0');
		$sales = $sales->findAll($criteria);
		if($sales){
			foreach ($sales as $li){
				$li->subsidy = $price;
				$li->update();
			}
		}
	}
}