<?php

/**
 * This is the biz model class for table "sales_detail".
 *
 */
class SalesDetail extends SalesDetailData
{
	
	/**
	 * @return array relational rules.
	 */
	public $cost_price;
	public $can_surplus;
	public $surplus;
	public $product;
	public $rank;
	public $texture;
	public $brand;
	public $salesForm;
	public $one_weight;
	public $bonus_money;
	public $bonus_weight;
	public $total_amount;
	public $total_weight;
	public $total_rebate;
	public $total_bill;
	public $total_out_amount;
	public $total_out_weight;
	public $total_invoice_money;
	public $total_invoice_weight;
	public $has_invoice_money;
	public $has_invoice_weight;
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'frmOutputs' => array(self::HAS_MANY, 'FrmOutput', 'sales_detail_id'),
			'frmSendDetails' => array(self::HAS_MANY, 'FrmSendDetail', 'sales_detail_id'),
			'outputDetails' => array(self::HAS_MANY, 'OutputDetail', 'sales_detail_id'),
			'FrmSales' => array(self::BELONGS_TO, 'FrmSales', 'frm_sales_id'),
			'salesInvoiceDetails' => array(self::HAS_MANY, 'SalesInvoiceDetail', 'sales_detail_id'),
			'storage'=>array(self::BELONGS_TO,'Storage',"card_id"),
			'mergestorage'=>array(self::BELONGS_TO,'MergeStorage',"card_id"),
			'detailsInvoice'=>array(self::HAS_ONE,'DetailForInvoice',"detail_id",'condition'=>'detailsInvoice.type="sales"'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'price' => 'Price',
			'bonus_price' => 'Bonus Price',
			'frm_sales_id' => 'Frm Sales',
			'amount' => 'Amount',
			'weight' => 'Weight',
			'send_amount' => 'Send Amount',
			'send_weight' => 'Send Weight',
			'output_amount' => 'Output Amount',
			'output_weight' => 'Output Weight',
			'warehouse_output_amount' => 'Warehouse Output Amount',
			'warehouse_output_weight' => 'Warehouse Output Weight',
			'product_std' => 'Product Std',
			'brand_std' => 'Brand Std',
			'texture_std' => 'Texture Std',
			'rand_std' => 'Rand Std',
			'length' => 'Length',
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
		$criteria->compare('price',$this->price);
		$criteria->compare('bonus_price',$this->bonus_price);
		$criteria->compare('frm_sales_id',$this->frm_sales_id);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('weight',$this->weight);
		$criteria->compare('send_amount',$this->send_amount);
		$criteria->compare('send_weight',$this->send_weight);
		$criteria->compare('output_amount',$this->output_amount);
		$criteria->compare('output_weight',$this->output_weight);
		$criteria->compare('warehouse_output_amount',$this->warehouse_output_amount);
		$criteria->compare('warehouse_output_weight',$this->warehouse_output_weight);
		$criteria->compare('product_std',$this->product_std,true);
		$criteria->compare('brand_std',$this->brand_std,true);
		$criteria->compare('texture_std',$this->texture_std,true);
		$criteria->compare('rand_std',$this->rand_std,true);
		$criteria->compare('length',$this->length);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SalesDetail the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function getTotalmoney($id)
	{
		$money = 0;
		$result = SalesDetail::model()->findAll("frm_sales_id=".$id);
		if($result){
			foreach ($result as $each){
				$money +=(($each->price)*$each->weight);
			}
		}
		return $money;
	}
	
	/*
	 * 根据id获取单条数据
	 */
	public static function getOne($id)
	{
		$model=SalesDetail::model()->findByPk($id);
		if($model)
		{
			return $model;
		}
		return false;
	}
	
	/*
	 * 根据id和传的值改变相关性
	 */
	public static function setIsRelate($id,$value)
	{
		if($id=='all')
		{
			$sql='select * from steel_trade.sales_detail where is_related=-1';
			$models=SalesDetail::model()->findAllBySql($sql);
			if($models)
			{
				foreach ($models as $each)
				{
					$each->is_related=0;
					$each->update();
				}
				return true;
			}
		}else{
			$model=SalesDetail::getOne($id);
			if($model)
			{
				$model->is_related=$value;
				if($model->update())
				{
					return true;
				}
			}
		}
		
		return false;
	}
	
	/*
	 * 修改补过采购单的件数和重量
	 */
	public static function updatePurchased($sales,$type)
	{
		if(!empty($sales))
		{
			$arr=array();
			$frmsale='';
			$pur_type='';
			$total=0;
			foreach ($sales as $each)
			{
				$model=SalesDetail::getOne($each->sales_detail_id);
				$frmsale=$model->FrmSales;
				if($frmsale->sales_type=='xxhj')
				{
					$pur_type='xxhj';
				}
				$baseform=$frmsale->baseform;
				if($baseform->form_status!='approve'){
					return 'billchange';
				}
				$olddata=$model;
				$oldJson=$olddata->datatoJson();
				if($model)
				{
					if($type=="submit")
					{
						$model->purchased_amount+=$each->amount;
						$model->purchased_weight+=$each->weight;
						// $model->need_purchase_amount-=$each->amount;
						if($model->purchased_amount-$model->amount>0)
						{
							return 'morethanneed';
						}
						if($model->amount-$model->purchased_amount==0)
						{
							array_push($arr, 'yes');
						}else{
							array_push($arr,'no');
						}
						$total+=$each->amount;
					}else{
						$model->purchased_amount-=$each->amount;
						$model->purchased_weight-=$each->weight;
						// $model->need_purchase_amount+=$each->amount;
						if($model->amount-$model->purchased_amount<0)
						{
							return 'dataerror';
						}
					}					
					if($model->update()){						
						$mainJson = $model->datatoJson();
						$dataArray = array("tableName"=>"SalesDetail","newValue"=>$mainJson,"oldValue"=>$oldJson);
						$baseform = new BaseForm();
						$baseform->dataLog($dataArray);
					}
				}
			}
			//更新主体是否已关联
			if(!in_array('no', $arr)&&$frmsale!=''&&$pur_type=='xxhj')
			{
				if($type=='submit'&&$frmsale->amount==$total)
				{
					$frmsale->is_related=1;
				}else{
					$frmsale->is_related=0;
				}				
				$frmsale->update();
			}
			
			return true;
		}
		return 'dataerror';
		
	}
/*	
	//高开支付 列表
	public static function getFormBillList($search) 
	{
		$tableHeader = array(
				array('name' => "", 'class' => "sort-disabled", 'width' => "2%"),
				array('name' => "", 'class' => "sort-disabled", 'width' => "3%"),
				array('name' => "卡号", 'class' => "sort-disabled", 'width' => "16%"),
				array('name' => "销售单号", 'class' => "sort-disabled", 'width' => "16%"),
				array('name' => "重量", 'class' => "sort-disabled", 'width' => "12%"),
				array('name' => "金额", 'class' => "sort-disabled", 'width' => "12%"),
				array('name' => "高开价", '' => "sort-disabled", 'width' => "12%"),
				array('name' => "业务员", 'class' => "sort-disabled", 'width' => "15%"),
		);
		
		$tableData = array(); 
		$model = SalesDetail::model();
		$criteria = new CDbCriteria();
		$criteria->join = ", steel_trade.common_forms baseform, steel_trade.frm_sales sales";
// 		." LEFT JOIN steel_trade.frm_sales FrmSales ON FrmSales.id = t.frm_sales_id";
		$criteria->addCondition("baseform.form_id = t.frm_sales_id");
		$criteria->addCondition("sales.id = t.frm_sales_id");
		$criteria->addCondition("baseform.form_type = 'XSD'");
		$criteria->addCondition("baseform.form_status = 'approve'");
		//搜索
		if(!empty($search))
		{
			if ($search['company_id'])
			{
				$criteria->addCondition("sales.customer_id = :customer_id");
				$criteria->params[':customer_id'] = $search['company_id'];
			}
			if ($search['title_id'])
			{
				$criteria->addCondition("sales.title_id = :title_id");
				$criteria->params[':title_id'] = $search['title_id'];
			}
// 			if ($search['is_yidan']) 
// 			{
// 				$criteria->addCondition("sales.is_yidan = :is_yidan");
// 				$criteria->params[':is_yidan'] = $search['is_yidan'];
// 			}
			if ($search['keywords'])
			{
				$criteria->addCondition("baseform.form_sn like :keywords");
				$criteria->params[':keywords'] = "%".$search['keywords']."%";
			}
			if ($search['time_L'])
			{
				$criteria->addCondition("baseform.created_at >= :time_L");
				$criteria->params[':time_L'] = strtotime($search['time_L']);
			}
			if ($search['time_H'])
			{
				$criteria->addCondition("baseform.created_at <= :time_H");
				$criteria->params[':time_H'] = strtotime($search['time_H']);
			}
			if ($search['owned_by'])
			{
				$criteria->addCondition("baseform.owned_by = :owned_by");
				$criteria->params[':owned_by'] = $search['owned_by'];
			}
		}
		
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize = intval($_COOKIE['bill_list']) ? intval($_COOKIE['bill_list']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);
		$criteria->addCondition("t.bonus_price > 0");
		$criteria->order = "baseform.created_at DESC";
		
		$bill = $model->findAll($criteria);
		if ($bill) 
		{
			$i = 1;
			foreach ($bill as $item) 
			{
				$da = array('data' => array());
				$item->salesForm = CommonForms::model()->with('sales', 'belong')->find("form_type = 'XSD' AND form_status = 'approve' AND form_id = ".$item->frm_sales_id);
				if ($item->salesForm) 
				{
					$mark = $i;
					$operate = '<input type="checkbox" name="selected_bill[]" class="selected_bill" yidan="'.$item->salesForm->sales->is_yidan.'" value="'.$item->id.'" />';
					$i++;
				} 
				else
				{
					$mark = '';
					$operate = '';
				}
				
				$da['data'] = array($mark,
						$operate,
						$item->card_id, 
						$item->salesForm->form_sn,
						'<span class="weight">'.number_format($item->weight, 3).'</span>',
						'<span class="fee">'.number_format($item->weight * $item->bonus_price, 2).'</span>',
						number_format($item->bonus_price, 2),
						$item->salesForm->belong->nickname,
				);
				array_push($tableData, $da);
			}
		}
		return array($tableHeader, $tableData, $pages);
	}
	*/

}
