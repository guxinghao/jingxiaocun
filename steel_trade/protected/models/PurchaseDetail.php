<?php

/**
 * This is the biz model class for table "purchase_detail".
 *
 */
class PurchaseDetail extends PurchaseDetailData
{
	public $sum_weight,$sum_fee,$title,$supply,$productName;
	public $ship, $total_rebate,$uninput_amount,$uninput_weight, $total_bill_weight,$total_bill_money, $checked_weight, $checked_money;
	public $total_money, $total_weight,$total_checked_money,$total_checked_weight;	
	public $bid,$form_sn,$created_at;
	public $sum_amount,$left_weight;
	public $form_no;//单号
	public $form_status;//采购单状态
	
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'inputDetails' => array(self::HAS_MANY, 'InputDetail', 'purchase_detail_id','condition'=>'inputDetails.from!="return"'),
			'purchaseInvoiceDetails' => array(self::HAS_MANY, 'PurchaseInvoiceDetail', 'purchase_detail_id'),
				
			"turnover"=>array(self::HAS_ONE,"Turnover","form_detail_id"),
			'frmPurchase'=>array(self::BELONGS_TO,'FrmPurchase','purchase_id'),
			'salesDetail'=>array(self::HAS_MANY,'SaledetailPurchase','purchase_detail_id'),
			'salesDetailXxhj'=>array(self::HAS_ONE,'SaledetailPurchase','purchase_detail_id'),
			'invoice'=>array(self::HAS_ONE,'DetailForInvoice','detail_id','condition'=>'invoice.type="purchase"'),//可以开票表
		    'brand'=>array(self::BELONGS_TO,'DictGoodsProperty','brand_id'),
		    'product'=>array(self::BELONGS_TO,'DictGoodsProperty','product_id'),
		    'texture'=>array(self::BELONGS_TO,'DictGoodsProperty','texture_id'),
		    'rank'=>array(self::BELONGS_TO,'DictGoodsProperty','rank_id'),
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
			'amount' => 'Amount',
			'weight' => 'Weight',
			'input_amount' => 'Input Amount',
			'input_weight' => 'Input Weight',
			'purchase_id' => 'Purchase',
			'fix_amount' => 'Fix Amount',
			'fix_weight' => 'Fix Weight',
			'fix_price' => 'Fix Price',
			'cost_price' => 'Cost Price',
			'invoice_price' => 'Invoice Price',
			'product_id' => 'Product',
			'brand_id' => 'Brand',
			'texture_id' => 'Texture',
			'rank_id' => 'Rank',
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
		$criteria->compare('price',$this->price,true);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('weight',$this->weight,true);
		$criteria->compare('input_amount',$this->input_amount);
		$criteria->compare('input_weight',$this->input_weight,true);
		$criteria->compare('purchase_id',$this->purchase_id);
		$criteria->compare('fix_amount',$this->fix_amount);
		$criteria->compare('fix_weight',$this->fix_weight,true);
		$criteria->compare('fix_price',$this->fix_price,true);
		$criteria->compare('cost_price',$this->cost_price,true);
		$criteria->compare('invoice_price',$this->invoice_price,true);
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('brand_id',$this->brand_id);
		$criteria->compare('texture_id',$this->texture_id);
		$criteria->compare('rank_id',$this->rank_id);
		$criteria->compare('length',$this->length);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PurchaseDetail the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/*
	 * 获取单条数据，根据id
	 */
	public static function getOne($id)
	{
		$model=PurchaseDetail::model()->findByPk($id);
		if($model)
		{
			return $model;
		}
		return false;
	}

	/**
	 * 采购明细列表
	 */
	public static function getIndexList($search)
	{
		$tableHeader = array(
				array('name'=>'','class' =>"sort-disabled",'width'=>"30px"),
				array('name'=>'采购公司','class' =>"sort-disabled",'width'=>"110px"),//
				array('name'=>'钢厂/供应商','class' =>"flex-col sort-disabled",'width'=>"110px"),//
				array('name'=>'托盘公司','class' =>"flex-col sort-disabled",'width'=>"110px"),//
				array('name'=>'单号','class' =>"flex-col sort-disabled",'width'=>"150px"),
				array('name'=>'开单日期','class' =>"flex-col sort-disabled",'width'=>"100px"),
				array('name'=>'状态','class' =>"flex-col sort-disabled",'width'=>"70px"),
				array('name'=>'乙单','class' =>"flex-col sort-disabled",'width'=>"50px"),
				array('name'=>'托盘','class' =>"flex-col sort-disabled",'width'=>"50px"),
				array('name'=>'产地','class' =>"flex-col sort-disabled",'width'=>"100px"),
				array('name'=>'品名','class' =>"flex-col",'width'=>"70px"),//
				array('name'=>'材质','class' =>"flex-col",'width'=>"85px"),//
				array('name'=>'规格','class' =>"flex-col",'width'=>"60px"),//
				array('name'=>'长度','class' =>"flex-col text-right",'width'=>"50px"),//
				array('name'=>'重量','class' =>"flex-col text-right",'width'=>"130px"),//
				array('name'=>'金额','class' =>"flex-col text-right",'width'=>"130px"),//
				array('name'=>'运费','class' =>"flex-col sort-disabled text-right",'width'=>"120px"),//
				array('name'=>'钢厂返利','class' =>"flex-col sort-disabled text-right",'width'=>"100px"),//
				array('name'=>'仓库返利','class' =>"flex-col sort-disabled text-right",'width'=>"100px"),//
				array('name'=>'仓储费用','class' =>"flex-col sort-disabled text-right",'width'=>"100px"),//
				array('name'=>'已入库件数','class' =>"flex-col sort-disabled text-right",'width'=>"100px"),
				array('name'=>'已入库重量','class' =>"flex-col sort-disabled text-right",'width'=>"130px"),
				array('name'=>'应销票重量','class' =>"flex-col sort-disabled text-right",'width'=>"130px"),
				array('name'=>'应销票金额','class' =>"flex-col sort-disabled text-right",'width'=>"130px"),
				array('name'=>'已销票重量','class' =>"flex-col sort-disabled text-right",'width'=>"130px"),
				array('name'=>'已销票金额','class' =>"flex-col sort-disabled text-right",'width'=>"130px"),
				array('name'=>'未销票重量','class' =>"flex-col sort-disabled text-right",'width'=>"130px"),
				array('name'=>'未销票金额','class' =>"flex-col sort-disabled text-right",'width'=>"130px"),
		);
		$tableData=array();
	   	$model=PurchaseView::model();
		$criteria=New CDbCriteria();
		//搜索
		if(!empty($search))
		{
			$criteria->together=true;
			$criteria->addCondition('form_sn like :contno');
			$criteria->params[':contno']= "%".$search['keywords']."%";
			if($search['time_L']!=''){
				$criteria->addCondition('UNIX_TIMESTAMP(form_time) >='.strtotime($search['time_L']));
			}
			if($search['time_H']!=''){
				$criteria->addCondition('UNIX_TIMESTAMP(form_time) <'.(strtotime($search['time_H'])+86400));
			}
			if($search['reach_time_L']){
				$criteria->addCondition('date_reach >='.strtotime($search['reach_time_L']));
			}
			if($search['reach_time_H']){
				$criteria->addCondition('date_reach <='.strtotime($search['reach_time_H']));
			}
			if($search['company']!='0'){
				$criteria->compare('t.title_id',$search['company']);
			}
			if($search['vendor']!='0'){
				$criteria->compare('supply_id',$search['vendor']);
			}
			if($search['form_status']!='0'){
				$criteria->compare('form_status',$search['form_status']);
			}else{
				$criteria->compare('is_deleted','0');
			}
			//产地,品名，规格,材质
			if($search['brand']!='0'){
				$criteria->compare('brand_id',$search['brand']);
			}
			if($search['product']!='0'){
				$criteria->compare('product_id',$search['product']);
			}
			if($search['rand']!='0'){
				$criteria->compare('rank_id',$search['rand']);
			}
			if($search['texture']!='0'){
				$criteria->compare('texture_id',$search['texture']);
			}
			
			//审单状态，采购单类型，乙单
			if($search['confirm_status']!=''){
				$criteria->compare('weight_confirm_status', $search['confirm_status']);				
			}
			if($search['purchase_type']!=''){
				$criteria->compare('purchase_type', $search['purchase_type']);
			}
			if($search['is_yidan']){
				$criteria->compare('t.is_yidan', $search['is_yidan']);
			}			
		}else{
			$criteria->compare('is_deleted','0');
		}
		$criteria->compare('form_type','CGD');	
		$criteria->addCondition('form_status!="unsubmit"');
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize =intval($_COOKIE['purchase_list']) ? intval($_COOKIE['purchase_list']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);
		$criteria->order="created_at DESC";
		$criteria->join='LEFT JOIN bill_record as b on t.common_id=b.frm_common_id LEFT JOIN purchase_invoice_detail as pid on pid.frm_purchase_detail_id=t.detail_id';
		$criteria->select='t.* ,b.price as bprice,pid.weight as pidweight,pid.fee as pidfee';
// 		$criteria->order="locate(form_status,'submited,unsubmit,approve,delete'),created_at DESC";		
		$details=PurchaseView::model()->findAll($criteria);
		if($details)
		{
			$da=array();
			$da['data']=array();
			$_status=array('unsubmit'=>'未提交','submited'=>'已提交','approve'=>'已审核','delete'=>'已作废');
			$baseform='';
			$i=1;	
			foreach ($details as $each)
			{
				$da['data']=array($i,
						$each->title_short_name,
						'<span title="'.$each->supply_name.'">'.$each->supply_short_name.'</span>',
						$each->purchase_type=="tpcg"?'<span title="'.$each->pledge_name.'">'.$each->pledge_short_name.'</span>':'',//托盘公司
						$each->form_sn,
						$each->form_time?$each->form_time:'',
						'<span class="'.($each->form_status!='approve'?'red':'').'">'.$_status[$each->form_status].'</span>',//审批状态
						$each->is_yidan==1?'是':'',
						$each->purchase_type=="tpcg"?'是':'',
						$each->brand_name,
						$each->product_name,
						str_replace('E', '<span class="red">E</span>',$each->texture_name),
						$each->rank_name,						
						$each->length,
						number_format($each->detail_weight,3),//总重量
						'<span class="'.($each->is_yidan?'red':'').'">'.number_format($each->detail_price*$each->detail_weight,2).'</span>',
						number_format($each->bprice*$each->detail_weight,2),
						number_format($each->rebate*$each->detail_weight,2),
						number_format($each->ware_rebate*$each->detail_weight,2),
						number_format($each->ware_cost*$each->detail_weight,2),
						$each->detail_input_amount,//仓库入库件数
						number_format($each->detail_input_weight,3),//仓库入库重量
						$each->is_yidan==1?'0.000':number_format($each->detail_weight,3),
						$each->is_yidan==1?'0.00':number_format($each->detail_weight*$each->detail_price,2),
						$each->is_yidan==1?'0.000':number_format($each->pidweight,3),
						$each->is_yidan==1?'0.00':number_format($each->pidfee,2),
						$each->is_yidan==1?'0.000':number_format($each->detail_weight-$each->pidweight,3),
						$each->is_yidan==1?'0.00':number_format($each->detail_weight*$each->detail_price-$each->pidfee,2),
				);
				$da['group']=$each->form_sn;
				array_push($tableData,$da);
				$i++;
			}
		}
		return array($tableHeader,$tableData,$pages);
	}
}
