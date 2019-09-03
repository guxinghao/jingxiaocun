<?php

/**
 * This is the biz model class for table "purchase_price".
 *
 */
class PurchasePrice extends PurchasePriceData
{
	
	public $price,$price_date,$brand_name,$product_name,$texture_name,$edit_at;
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
			'id' => 'ID',
			'brand_std' => 'Brand Std',
			'product_std' => 'Product Std',
			'texture_std' => 'Texture Std',
			'rank_range' => 'Rank Range',
			'length' => 'Length',
			'price' => 'Price',
			'price_date' => 'Price Date',
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
		$criteria->compare('brand_std',$this->brand_std,true);
		$criteria->compare('product_std',$this->product_std,true);
		$criteria->compare('texture_std',$this->texture_std,true);
		$criteria->compare('rank_range',$this->rank_range,true);
		$criteria->compare('length',$this->length);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('price_date',$this->price_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PurchasePrice the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/*
	 * 
	 */
	public  function getAllList($ac)
	{
		$date_type=$_REQUEST['date_type'];
		$tableHeader=array(
				array('name'=>'产地','class' =>"flex-col sort-disabled",'width'=>"100px"),
				array('name'=>'品名','class' =>"flex-col sort-disabled",'width'=>"80px"),
				array('name'=>'材质','class' =>"flex-col sort-disabled",'width'=>"90px"),
				array('name'=>'规格','class' =>"flex-col sort-disabled",'width'=>"70px"),//			
				array('name'=>'价格','class' =>"flex-col sort-disabled text-right",'width'=>"130px"),
		);		
		$search=$_REQUEST['PurchasePrice'];		
		$criteria=new CDbCriteria();
		if(!empty($search))
		{
			if($search['brand_id']){
				$criteria->compare('brand_std', DictGoodsProperty::getStd($search['brand_id']));
				$search['brand_name']=DictGoodsProperty::getProName($search['brand_id']);
			}
			if($search['product_id']){
				$criteria->compare('product_std', DictGoodsProperty::getStd($search['product_id']));
				$search['product_name']=DictGoodsProperty::getProName($search['product_id']);
			}
			if($search['texture_id']){
				$criteria->compare('texture_std', DictGoodsProperty::getStd($search['texture_id']));
				$search['texture_name']=DictGoodsProperty::getProName($search['texture_id']);
			}						
		}
		if(!$search['time'])$search['time']=date('Y-m-d');		
		$criteria->select='t.*,p.name as product_name,b.name as brand_name,tt.name as texture_name,pri.price as price,pri.price_date as price_date';
		$criteria->join='left join dict_goods_property p on p.std=t.product_std
					left join dict_goods_property b on b.std=t.brand_std
					left join dict_goods_property tt on tt.std=t.texture_std 
					left join purprice_date pri on t.id=pri.price_id	';
		if($date_type!='yes'){
			array_push($tableHeader,array('name'=>'报价日期','class' =>"flex-col sort-disabled",'width'=>"100px"));
			$criteria->order='pri.price_date desc,convert(brand_name using gbk),convert(product_name using gbk),texture_name';
			if($_POST['start_time'])
				$criteria->addCondition("pri.price_date>='".$_POST['start_time']."'");
			if($_POST['end_time']){
				$criteria->addCondition("pri.price_date<='".$_POST['end_time']."'");
			}else{
				$criteria->addCondition("pri.price_date is not null");
			}
			$pages = new CPagination();
			$pages->itemCount = $this->count($criteria);
			$pages->pageSize = $_COOKIE['qd']? intval($_COOKIE['qd']):Yii::app()->params['pageCount'];
			$pages->applyLimit($criteria);
		}else{
			// $criteria->compare('pri.price_date',$search['time']);
			$criteria->join .=' and pri.price_date="'.$search['time'].'"';
// 			$criteria->addCondition('pri.price_date="'.$search['time'].'" or isnull(price_date)');
			$criteria->order='convert(brand_name using gbk),convert(product_name using gbk),texture_name';
		}
		if($search['rank_id']){
			$criteria->compare('rel.rank_id', $search['rank_id']);
			$search['rank_name']=DictGoodsProperty::getProName($search['rank_id']);
			
			$criteria->join.=' left join purprice_rank_rel rel on rel.price_id=t.id';
			$criteria->group='t.id,pri.price_date';
		}
		
		$details=$this->findAll($criteria);
		$tableData=array();
		if($details)
		{
			foreach ($details as $each)
			{
				$da['data']=array(
						$each->brand_name,
						$each->product_name,
						str_replace('E','<span class="red">E</span>',$each->texture_name),
						$each->rank_range,
						$ac?'<input type="text" class="price" name="'.$each->id.'"   onKeypress="return (/[\d.]/.test(String.fromCharCode(event.keyCode)))" value="'.($each->price?$each->price:0).'"/>':number_format($each->price,2),				
				);
				if($date_type!="yes"){
					array_push($da['data'],$each->price_date);
				}
				$da['group']=$each->id;
				array_push($tableData,$da);
			}
		}
		return array($tableHeader,$tableData,(Object)$search,$date_type,$pages);		
	}
	
	
	public static function getReportList(){
		$model = new PurchasePrice();
		$cri = new CDbCriteria();
		$cri->select = "t.*,p.name as product_name,b.name as brand_name,tt.name as texture_name,pri.price as price,pri.edit_at as edit_at";
		$cri->join = "left join dict_goods_property p on p.std=t.product_std
					left join dict_goods_property b on b.std=t.brand_std
					left join dict_goods_property tt on tt.std=t.texture_std					
					left join purprice_date pri on pri.price_id=t.id	";
		
		$cri->addCondition("pri.price_date='".date("Y-m-d")."'");		
		$cri->order = "t.brand_std,t.product_std,t.texture_std";
		$items = $model->findAll($cri);
		$time = 0;
		foreach ($items as $i){
			if($i->edit_at>$time){
				$time = $i->edit_at;
			}
		}	
		return array($items,$time);
	}
	
	
	/*
	 * 获取网价
	 */
	public static function getNetPrice($time,$brand_name,$brand,$product,$texture,$rank,$length)
	{
		$comment='';
		$xml=readConfig();
		foreach ($xml->purchase->steelworks as $each)
		{
			if($each->name==$brand_name)
			{
				$days=$each->date;
				break;
			}
		}
		if(intval($days))
		{
			//获取采购几日均价
			$condition='';
// 			$d=ceil((time()-strtotime($time))/86400);				
			$brand_std=DictGoodsProperty::getStd($brand);
			$product_std=DictGoodsProperty::getStd($product);
			$texture_std=DictGoodsProperty::getStd($texture);
			$rank_std=DictGoodsProperty::getStd($rank);					
		
			$condition.='brand_std="'.$brand_std.'"';
			$condition.=' and product_std="'.$product_std.'" and texture_std="'.$texture_std.'"';
			$condition_hh=$condition;
			$condition.=' AND rank_std="'.$rank_std.'"';
			
			$condition_price=$condition_hh;
			$condition_price.=' and rank_id='.$rank;
			$sql_price="select avg(price) as price  from (select m.*,r.rank_id,pri.price,pri.price_date from purchase_price m
					left join purprice_rank_rel r on r.price_id=m.id
					left join purprice_date pri on pri.price_id=m.id
					where  {$condition_price} and price!=0  and pri.price_date<='{$time}'  order by pri.price_date desc  limit 0,{$days}) temp";
			
			$sql_hh="select avg(price) as price from (select m.*,r.rank_id,pri.price,pri.price_date from purchase_price m
					left join purprice_rank_rel r on r.price_id=m.id
					left join purprice_date pri on pri.price_id=m.id
					where {$condition_hh} and price!=0  and pri.price_date<='{$time}'	group by price_date order by pri.price_date desc  limit 0,{$days}) temp";
			$result=PurchasePrice::model()->findBySql($sql_price);
			if($result->price!=NULL)
			{				
				$return=$result->price;
				$comment="价格来自{$days}日网价均价";
			}else{
				$condition.=' and  type="spread"';					
				$spread=QuotedDetail::model()->find($condition);
				if($spread)
				{
					$result=PurchasePrice::model()->findBySql($sql_hh);
					if($result->price!=NULL)
					{
						$return=$result->price+$spread->price;
						$comment="价格来自{$days}日网价均价+基价差";
					}else{
						$return=0;
						$comment="未找到网价信息";
					}
				}else{
					$return=0;
					$comment="规格不在范围内且没有基价差数据";
				}
			}									
		}elseif($days=='n'){
			//获取最近报价
			$brand_std=DictGoodsProperty::getStd($brand);
			$product_std=DictGoodsProperty::getStd($product);
			$texture_std=DictGoodsProperty::getStd($texture);
			$rank_std=DictGoodsProperty::getStd($rank);
			$criteria=new CDbCriteria();
			$criteria->addCondition("brand_std='{$brand_std}'");
			$criteria->addCondition("product_std='{$product_std}'");
			$criteria->addCondition("texture_std='{$texture_std}'");
			$cri_price=clone $criteria;
			$cri=clone $criteria;
			$cri_hhprice=clone $cri_price;
			$criteria->addCondition("rel.rank_id='{$rank}'");
			
			$condition=$criteria->condition;
			$sql="select price,price_date from purchase_price t 
				left join purprice_date pri on pri.price_id=t.id	
				left join purprice_rank_rel rel on rel.price_id=t.id 
				where price_date=(select max(price_date) from purchase_price t left join purprice_date pri on pri.price_id=t.id
			left join purprice_rank_rel rel on rel.price_id=t.id where {$condition}) and {$condition}";	
			
			$cri->addCondition("rank_std='{$rank_std}'");		
			$result=PurchasePrice::model()->findBySql($sql);
			if($result)
			{
				$return=$result->price;
				$comment='价格来自'.$result->price_date.'网价';
			}else{
				$cri->addCondition('type="spread"');
				$spread=QuotedDetail::model()->find($cri);
				if($spread)
				{
					$condition=$cri_hhprice->condition;
					$sql="select price,price_date from purchase_price t
					left join purprice_date pri on pri.price_id=t.id
					left join purprice_rank_rel rel on rel.price_id=t.id
					where price_date=(select max(price_date) from purchase_price t left join purprice_date pri on pri.price_id=t.id
					left join purprice_rank_rel rel on rel.price_id=t.id where {$condition}) and {$condition}";
					$result=PurchasePrice::model()->findBySql($sql);
					if($result)
					{
						$return=$result->price+$spread->price;
						$comment='价格来自'.$result->price_date.'网价+基价差';
					}else{
						$return=0;
						$comment='未找到网价信息';
					}
				}else{
					$return=0;
					$comment='规格不在范围内且没有基价差数据';
				}
			}				
		}					
		return array($return,$comment);
	}
	
	

}
