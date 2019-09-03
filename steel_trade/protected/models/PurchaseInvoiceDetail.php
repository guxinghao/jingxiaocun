<?php

/**
 * This is the biz model class for table "purchase_invoice_detail".
 *
 */
class PurchaseInvoiceDetail extends PurchaseInvoiceDetailData
{
	public $checked_weight;
	public $checked_price;

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'purchaseInvoice' => array(self::BELONGS_TO, 'FrmPurchaseInvoice', 'purchase_invoice_id'),
			'detailForInvoice' => array(self::BELONGS_TO, 'DetailForInvoice', 'purchase_detail_id'),
			'purchase' => array(self::BELONGS_TO, 'FrmPurchase', 'frm_purchase_id'), 
			'purchaseDetail' => array(self::BELONGS_TO, 'PurchaseDetail', 'frm_purchase_detail_id'), 
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'weight' => 'Weight',
			'fee' => 'Fee',
			'purchase_invoice_id' => 'Purchase Invoice',
			'purchase_detail_id' => 'Purchase Detail',
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
		$criteria->compare('weight',$this->weight,true);
		$criteria->compare('fee',$this->fee,true);
		$criteria->compare('purchase_invoice_id',$this->purchase_invoice_id);
		$criteria->compare('purchase_detail_id',$this->purchase_detail_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PurchaseInvoiceDetail the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
	/*
	 * 销票统计
	 */
	public static function invoiceData($search)
	{
		$tableData=array();
		$tableHeader=array(
				array('name'=>'公司简称','class' =>"sort-disabled text-left",'width'=>"110px"),//修
				array('name'=>'结算单位','class' =>"sort-disabled text-left",'width'=>"110px"),//
				array('name'=>'应销票重量','class' =>"flex-col sort-disabled text-right",'width'=>"150px"),
				array('name'=>'应销票金额','class' =>"flex-col sort-disabled text-right",'width'=>"150px"),//
				array('name'=>'已销票重量','class' =>"flex-col sort-disabled text-right",'width'=>"150px"),
				array('name'=>'已销票金额','class' =>"flex-col sort-disabled text-right",'width'=>"150px"),
				array('name'=>'未销票重量','class' =>"flex-col sort-disabled text-right",'width'=>"150px"),
				array('name'=>'未销票金额','class' =>"flex-col sort-disabled text-right",'width'=>"150px"),
		);
		$model=new DetailForInvoice();
		$criteria=new CDbCriteria();
		$criteria->with=array('relation_form');
		if(!empty($search))
		{
			if($search['start_time']!='')
			{
				$criteria->addCondition('UNIX_TIMESTAMP(relation_form.form_time) >='.strtotime($search['start_time']));
			}
			if($search['end_time']!='')
			{
				$criteria->addCondition('UNIX_TIMESTAMP(relation_form.form_time) <='.(strtotime($search['end_time'])+86400));
			}
			if($search['title']!='')
			{
				$criteria->compare('t.title_id',$search['title']);
			}
			if($search['company'])
			{
				$criteria->compare('t.company_id',$search['company']);
			}			
			if($search['uncheck'])
			{
				if($search['uncheck']==2)
					$criteria->having='total_weight<>total_checked_weight';
				elseif($search['uncheck']==1)
				$criteria->having='total_weight=total_checked_weight';
			}
		}
		$criteria->compare('t.type','purchase');
		$criteria->group='t.title_id,t.company_id';
		$new_cri=clone $criteria;
		$criteria->select='t.title_id,dt.short_name as title_short_name,t.company_id,dc.name as supply_name,dc.short_name as  supply_short_name'
				.', sum(case sign(checked_money) when -1 then -checked_weight else  checked_weight end) as total_checked_weight,sum(checked_money) as total_checked_money
					,sum(case sign(money) when -1 then  -weight  else weight end) as total_weight,sum(money) as total_money';
		$criteria->join='LEFT JOIN dict_title dt on t.title_id=dt.id 
									  LEFT JOIN dict_company dc on t.company_id = dc.id';
// 		$criteria->having='total_weight>total_checked_weight';
		$details=$model::model()->findAll($criteria);
		if($details)
		{
			$da=array();
			$da['data']=array();
			$i=1;$tw=$tm=$tcw=$tcm=0;				
			foreach ($details as $each)
			{
				$da['data']=array(
						$each->title_short_name,
						'<span title="'.$each->supply_name.'">'.$each->supply_short_name.'</span>',
						number_format($each->total_weight,3),
						number_format($each->total_money,2),
						number_format($each->total_checked_weight,3),
						number_format($each->total_checked_money,2),
						number_format($each->total_weight-$each->total_checked_weight,3),
						number_format($each->total_money-$each->total_checked_money,2),
				);
				$da['group']=$i;
				array_push($tableData,$da);
				$i++;
				$tw+=$each->total_weight;
				$tm+=$each->total_money;
				$tcw+=$each->total_checked_weight;
				$tcm+=$each->total_checked_money;
			}
			$t=array('group'=>00,'data'=>array('总计','',number_format($tw,3),number_format($tm,2),
					number_format($tcw,3),number_format($tcm,2),number_format($tw-$tcw,3),number_format($tm-$tcm,2)));
			array_push($tableData,$t);
		}
		///////////////////////////////////////
// 		$model=PurchaseView::model();
// 		$criteria=new CDbCriteria();
// 		if(!empty($search))
// 		{
// 			if($search['start_time']!='')
// 			{
// 				$criteria->addCondition('t.created_at >='.strtotime($search['start_time']));
// 			}
// 			if($search['end_time']!='')
// 			{
// 				$criteria->addCondition('t.created_at <='.(strtotime($search['end_time'])+86400));
// 			}
// 			if($search['title']!='')
// 			{
// 				$criteria->compare('t.title_id',$search['title']);
// 			}
// 			if($search['company'])
// 			{
// 				$criteria->compare('t.supply_id',$search['company']);
// 			}			
// 		}
// 		$criteria->compare('t.form_type','CGD');
// 		$criteria->compare('t.is_deleted','0');
// 		$criteria->compare('t.is_yidan','0');
// 		$criteria->addCondition('t.form_status!="unsubmit"');
// 		$criteria->group='t.title_id,t.supply_id';
// 		$new_cri=clone $criteria;	
// 		$criteria->select='t.title_id,t.title_short_name,t.supply_id,t.supply_name,t.supply_short_name'
// 				.', sum(pid.weight) as total_checked_weight,sum(pid.fee) as total_checked_money';
// 		$criteria->join='LEFT JOIN purchase_invoice_detail as pid on pid.frm_purchase_detail_id=t.detail_id';
// 		$details=PurchaseView::model()->findAll($criteria);
// 		if($details)
// 		{
// 			$da=array();
// 			$da['data']=array();
// 			$i=1;
// 			$tw=0;
// 			$tm=0;
// 			$tcw=0;
// 			$tcm=0;
			
// 			foreach ($details as $each)
// 			{
// 				$temp=clone $new_cri;
// 				$temp->compare('t.title_id', $each->title_id);
// 				$temp->compare('t.supply_id',$each->supply_id);
// 				$temp0=clone $temp;
// 				$temp->compare('t.weight_confirm_status',1);
// 				$temp->select='sum(t.detail_fix_weight) as  total_weight,sum(t.detail_fix_weight*t.detail_fix_price) as total_money';
// 				$ed=PurchaseView::model()->find($temp);
// 				$temp0->compare('t.weight_confirm_status',0);
// 				$temp0->select='sum(t.detail_weight) as  total_weight,sum(t.detail_weight*t.detail_price) as total_money';
// 				$ed0=PurchaseView::model()->find($temp0);
// 				$da['data']=array(
// 						$each->title_short_name,
// 						'<span title="'.$each->supply_name.'">'.$each->supply_short_name.'</span>',
// 						number_format($ed->total_weight+$ed0->total_weight,3),
// 						number_format($ed->total_money+$ed0->total_money,2),
// 						number_format($each->total_checked_weight,3),
// 						number_format($each->total_checked_money,2),
// 						number_format($ed->total_weight+$ed0->total_weight-$each->total_checked_weight,3),
// 						number_format($ed->total_money+$ed0->total_money-$each->total_checked_money,2),
// 				);
// 				$da['group']=$i;
// 				array_push($tableData,$da);
// 				$i++;
// 				$tw+=$ed->total_weight+$ed0->total_weight;
// 				$tm+=$ed->total_money+$ed0->total_money;
// 				$tcw+=$each->total_checked_weight;
// 				$tcm+=$each->total_checked_money;
// 			}
// 			$t=array('group'=>00,'data'=>array('总计','',number_format($tw,3),number_format($tm,2),
// 					number_format($tcw,3),number_format($tcm,2),number_format($tw-$tcw,3),number_format($tm-$tcm,2)));
// 			array_push($tableData,$t);
// 		}
		return array($tableHeader,$tableData);
		
	}

}
