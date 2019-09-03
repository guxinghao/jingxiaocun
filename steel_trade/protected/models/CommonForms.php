<?php

/**
 * This is the biz model class for table "common_forms".
 *
 */
class CommonForms extends CommonFormsData
{
	public static $formStatus = array(
			'unsubmit' => "未提交", 
			'submited' => "已提交", 
			'approved_1' => "审核中", //业务经理
			'approved_2' => "审核中", //财务经理
			'approved_3' => "审核中", //总经理
			'approve' => "已审核", //出纳
			'accounted' => "已入账", 
			'capias' => "已销票", 
			'invoice' => "已开票", 
			'delete' => "已作废"
	);

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
				'cag'=>array(self::HAS_ONE,'Turnover','common_forms_id','condition'=>'status<>"delete"'),//针对采购合同的往来
				'contract'=>array(self::BELONGS_TO,'FrmPurchaseContract','form_id'),
				'operator' => array(self::BELONGS_TO, 'User', 'created_by'), //操作员
				'belong'=>array(self::BELONGS_TO,'User','owned_by'), //业务员
				'approver'=>array(self::BELONGS_TO,'User','approved_by'),//审核人
				'lastupdate'=>array(self::BELONGS_TO,'User','last_updated_by'),
				'input'=>array(self::BELONGS_TO,'FrmInput','form_id'),//入库单
				'inputdx'=>array(self::BELONGS_TO,'FrmInputDx','form_id'),//入库单代销
				'inputplan'=>array(self::BELONGS_TO,'FrmInputPlan','form_id'),
				'output'=>array(self::BELONGS_TO,'FrmOutput','form_id'),//出库单
				'purchase'=>array(self::BELONGS_TO,'FrmPurchase','form_id'), //采购单
				'purchaseInvoice' => array(self::BELONGS_TO, 'FrmPurchaseInvoice', 'form_id'), //采购开票
				'purchaseReturn' => array(self::BELONGS_TO, 'FrmPurchaseReturn', 'form_id'), //采购退货
				'sales'=>array(self::BELONGS_TO,'FrmSales','form_id'), //销售单
				'salesInvoice' => array(self::BELONGS_TO, 'FrmSalesInvoice', 'form_id'), //销售开票
				'salesReturn' => array(self::BELONGS_TO, 'FrmSalesReturn', 'form_id'), //销售退货
				'send'=>array(self::BELONGS_TO,'FrmSend','form_id'),
				'rebate' => array(self::BELONGS_TO, 'FrmRebate', 'form_id'), //折让
				'billRecord'=>array(self::BELONGS_TO, 'BillRecord', 'form_id'), //费用登记
				'formBill' => array(self::BELONGS_TO, 'FrmFormBill', 'form_id'), //付款登记
				'pledgeRedeem' => array(self::BELONGS_TO, 'FrmPledgeRedeem', 'form_id'), //托盘
				'highopen'=>array(self::BELONGS_TO,'HighOpen','form_id'), //高开表
				'billRebate' => array(self::BELONGS_TO, 'BillRebate', 'form_id'), //钢厂让利
				'salesReturn'=>array(self::BELONGS_TO,'FrmSalesReturn','form_id'),//销售退货
				'billOther' => array(self::BELONGS_TO, 'FrmBillOther', 'form_id'), //其他收入|费用报支 
				'frmPledge'=>array(self::BELONGS_TO,'FrmPledgeRedeem','form_id'), //托盘赎回记录
				'Record'=>array(self::HAS_ONE,"BillRecord",'frm_common_id'),
				'FrmPypk'=>array(self::BELONGS_TO,'FrmPypk','form_id'), //盘盈盘亏
				'RebateRelation'=>array(self::HAS_ONE,'RebateRelation','sales_id'),
				'OwnerTransfer'=>array(self::BELONGS_TO,'OwnerTransfer','form_id'), //销售转库
				'transferAccounts' => array(self::BELONGS_TO, 'TransferAccounts', 'form_id'), //银行互转
				'shortLoan' => array(self::BELONGS_TO, 'ShortLoan', 'form_id'), //短期借贷
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'form_type' => 'Form Type',
			'form_sn' => 'Form Sn',
			'created_by' => 'Created By',
			'created_at' => 'Created At',
			'form_time' => 'Form Time',
			'form_status' => 'Form Status',
			'approved_at' => 'Approved At',
			'approved_by' => 'Approved By',
			'owned_by' => 'Owned By',
			'is_deleted' => 'Is Deleted',
			'comment' => 'Comment',
			'last_update' => 'Last Update',
			'last_updated_by' => 'Last Updated By',
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
		$criteria->compare('form_type',$this->form_type,true);
		$criteria->compare('form_sn',$this->form_sn,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('created_at',$this->created_at);
		$criteria->compare('form_time',$this->form_time,true);
		$criteria->compare('form_status',$this->form_status,true);
		$criteria->compare('approved_at',$this->approved_at);
		$criteria->compare('approved_by',$this->approved_by);
		$criteria->compare('owned_by',$this->owned_by);
		$criteria->compare('is_deleted',$this->is_deleted);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('last_update',$this->last_update);
		$criteria->compare('last_updated_by',$this->last_updated_by);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CommonForms the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/*
	 * 比较最后更新时间
	 */
	public static function CompareUpdateTime($id,$time)
	{
		$model=CommonForms::model()->findByPk($id);
		if($model)
		{
			$new=$model->last_update;
			if($time<$new)
			{
				return $new;
			}else{
				return 'pass';
			}
		}else{
			return 'error';
		}
	}
	
	/*
	 * 是否已审核
	 */
	public static function isAvailable($id,$type)
	{
		$result=true;
		switch ($type)
		{
			case 'purchase_normal':
				$model=CommonForms::model()->with('contract')->findByPk($id);
				if($model)
				{
					if($model->form_status=='approve')
					{
						if($model->contract->is_finish!=0)
						{
							$result=false;
						}
					}else{
						$result= false;
					}
				}
				break;
			case 'purcahse_xxhj':
				break;
			case 'pledge':
				$model=CommonForms::model()->findByPk($id);
				if($model)
				{
					if($model->form_status!='approve')
					{
						$result=false;
					}
				}
				break;
		}
		return $result;
	}
	
	
	
	/*
	 * 计算利润
	 */
	public function computeProfit($search)
	{
		
		return $data;		
	}
	
	
	/*
	 * 大数据脚本
	 */
	public static  function  bigData($type,$goods)
	{
		switch ($type)
		{
			case 'purchase':
				CommonForms::purchaseCreate($goods);
				break;
// 			case 'inputplan':
// 				CommonForms::inputPlanCreate();
// 				break;
			case 'input':
				CommonForms::inputCreate();
				break;			
			case 'skdj':
				CommonForms::skdjCreate();
				break;
			case 'fkdj':
				CommonForms::fkdjCreate();
				break;
		}
	}
	
	public static function purchaseCreate($goods_array)
	{
		$comm['form_type']='CGD';
		$comm['created_by']=43;
		$own=array(29,43);
		$comm['owned_by']=$own[array_rand($own)];
		$comm['comment']='批量产生';
		
		$main['purchase_type']='normal';
		$supply=array(2,3,4,5,6,7,8);
		$main['supply_id']=$supply[array_rand($supply)];
		$title=array(11,14);
		$main['title_id']=$title[array_rand($title)];
		$main['is_yidan']=mt_rand(0,1);
		$main['warehouse_id']=mt_rand(1,4);
		$main['invoice_cost']=100;
		$main['transfer_number']='沪A0001';
		$main['price_amount']=0;
		$main['amount']=0;
		$main['weight']=0;
			
		$details=array();
		$num=mt_rand(1,5);
		for($i=0;$i<$num;$i++)
		{
			$good=$goods_array[array_rand($goods_array)];
			$temp=array();
			$temp['price']=mt_rand(1800,2200);
			$temp['amount']=mt_rand(100,200);
			$temp['weight']=$temp['amount']*$good->unit_weight;
			$temp['product_id']=$good->product_id;
			$temp['brand_id']=$good->brand_id;
			$temp['texture_id']=$good->texture_id;
			$temp['rank_id']=$good->rank_id;
			$temp['length']=$good->length;
						
			$main['amount']+=$temp['amount'];
			$main['weight']+=$temp['weight'];
			$main['price_amount']+=$temp['weight']*$temp['price'];

			array_push($details, (Object)$temp);
		}
		
		$data['common']=(Object)$comm;
		$data['main']=(Object)$main;
		$data['detail']=$details;
		
		$form=new Purchase($id);
		$form->createSubmitForm($data);
		$form->approveForm();
			
		CommonForms::inputPlanCreate($form);
	}
	
	public static function inputPlanCreate($form)
	{
// 		$purchases=CommonForms::model()->with('purchase','purchase.purchaseDetails')->findAll('form_type="CGD" and is_deleted=0 and comment="批量产生"');
		
// 		foreach ($purchases as $each)
// 		{
			$each=$form->commonForm;
			$purchase=$form->mainInfo;
			$purDetails=$purchase->purchaseDetails;
			$comm['form_type']='RKP';
			$comm['created_by']=43;
			$own=array(29,43);
			$comm['owned_by']=$own[array_rand($own)];
			$comm['comment']='批量产生';
			
			$main['input_type']='ccrk';
			$main['purchase_id']=$each->id;
			$main['input_date']=time();
			$main['input_time']=0;
			$main['warehouse_id']=$purchase->warehouse_id;
			$main['input_status']=0;
			$main['input_company']=$purchase->title_id;
			$main['ship_no']='沪A00001';
			$main['form_sn']=$each->form_sn;
				
			$details=array();			
			foreach ($purDetails as $ea)
			{
				$temp=array();
				$temp['price']=$ea->price;
				$temp['input_amount']=$ea->amount;
				$temp['input_weight']=$ea->weight;
				$temp['product_id']=$ea->product_id;
				$temp['brand_id']=$ea->brand_id;
				$temp['texture_id']=$ea->texture_id;
				$temp['rank_id']=$ea->rank_id;
				$temp['length']=$ea->length;
				$temp['purchase_detail_id']=$ea->id;	
				array_push($details, (Object)$temp);
			}
			
			$data['common']=(Object)$comm;
			$data['main']=(Object)$main;
			$data['detail']=$details;

			$form=new InputPlan($id);
			$form->createForm($data);
// 		}
		
	}
	
	public static function inputCreate()
	{
		$inputs=FrmInput::model()->with('baseform','inputDetails')->find('input_type="ccrk" and baseform.is_deleted=0');		
		if($inputs)
		{
			$i=0;
			$baseform=$inputs->baseform;
			$details=$inputs->inputDetails;
			
			$form=new Input($baseform->id);
			$form->submitForm();
			$form->approveForm();
			
			$data=array();
			$data['input_date']=date('Y-m-d',time());
			$data['data']=array();
			foreach ( $details as $ea)
			{
				$temp=array();
				$temp['cost_price']=$ea->cost_price;
				$temp['input_amount']=$ea->input_amount;
				$temp['input_weight']=$ea->input_weight;
				$temp['purchase_detail_id']=$ea->purchase_detail_id;
				$temp['card_id']='SHIP'.time().$i;
				$temp['id']=$ea->id;
				array_push($data['data'], (Object)$temp);
				$i++;
			}
			$form->relStore($data);		
			$form=null;
			$inputs=null;		
			usleep(100);
		}
	}
	
	
	public static function skdjCreate()
	{
		$comm['form_type']='SKDJ';
		$comm['created_by']=43;
		$comm['owned_by']=mt_rand(15,25);
		$comm['comment']='批量产生';
		$main['bill_type']='XSSK';
		$main['is_yidan']=0;
		$main['pay_type']='transfer';
		$main['company_id']=mt_rand(10,17);
		$title=array(11,14);
		$p=mt_rand(0,1);
		$main['title_id']=$title[$p];
		$bank=array(22,23);
		$main['dict_bank_info_id']=$bank[$p];
		$main['fee']=mt_rand(1000,2000);
		$main['reach_at']=time();
		
		$data['common']=(Object)$comm;
		$data['main']=(Object)$main;
		
		$form = new FormBill('SKDJ', $id);
		$form->createForm($data);
		$form->submitForm();
	}
	
	public static function fkdjCreate()
	{
		$comm['form_type']='FKDJ';
		$comm['created_by']=43;
		$comm['owned_by']=mt_rand(15,25);
		$comm['comment']='批量产生';
		$main['bill_type']='CGFK';
		$main['is_yidan']=0;
		$main['pay_type']='transfer';
		$main['company_id']=mt_rand(2,8);
		$title=array(11,14);
		$p=mt_rand(0,1);
		$main['title_id']=$title[$p];
		$bank=array(22,23);
		$main['dict_bank_info_id']=$bank[$p];
		$main['fee']=mt_rand(1000,2000);
		$main['reach_at']=time();
	
		$data['common']=(Object)$comm;
		$data['main']=(Object)$main;
	
		$form = new FormBill('FKDJ', $id);
		$form->createForm($data);
		$form->submitForm();
	}
	
}
