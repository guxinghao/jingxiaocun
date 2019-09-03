<?php

/**
 * This is the biz model class for table "quoted_detail".
 *
 */
class QuotedDetail extends QuotedDetailData
{
	
	public $product_name;
	public $brand_name;
	public $texture_name;
	public $rank_name;
	public $prefecture_name;
	public $product_id;
	public $brand_id;
	public $texture_id;
	public $rank_id;
	public $rprice_date;
	public $rprice;
	public $areaname;
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		'updater' => array(self::BELONGS_TO, 'User', 'last_update_by'),
		'warehouse' => array(self::BELONGS_TO, 'Warehouse', 'warehouse_id'),
		'relation'=>array(self::HAS_MANY,'QuotedWarehouseRelation','quoted_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'product_std' => 'Product Std',
			'texture_std' => 'Texture Std',
			'brand_std' => 'Brand Std',
			'rank_std' => 'Rank Std',
			'area' => 'Area',
			'price' => 'Price',
			'created_at' => 'Created At',
			'created_by' => 'Created By',
			'last_update' => 'Last Update',
			'type' => 'Type',
			'price_date' => 'Price Date',
			'prefecture' => 'Prefecture',
			'last_update_by' => 'Last Update By',
		
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
		$criteria->compare('product_std',$this->product_std,true);
		$criteria->compare('texture_std',$this->texture_std,true);
		$criteria->compare('brand_std',$this->brand_std,true);
		$criteria->compare('rank_std',$this->rank_std,true);
		$criteria->compare('area',$this->area,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('created_at',$this->created_at);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('last_update',$this->last_update);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('price_date',$this->price_date,true);
		$criteria->compare('prefecture',$this->prefecture,true);
		$criteria->compare('last_update_by',$this->last_update_by);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return QuotedDetail the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	
	/*
	 * new method to return price data
	 * for the change that I cannot recognize from the old one
	 */	
	public function getList1($ac='')
	{		
		$type=$_REQUEST['type'];
		$date_type=$_REQUEST['date_type'];
		$areas=array();
		$search=$_REQUEST['QuotedDetail'];
		
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
			if($search['rank_id']){
				$criteria->compare('rank_std', DictGoodsProperty::getStd($search['rank_id']));
				$search['rank_name']=DictGoodsProperty::getProName($search['rank_id']);
			}
			if($search['prefecture'])
				$criteria->compare('prefecture', $search['prefecture']);
			if($search['length']){
				if($search['length']==9||$search['length']==12)$criteria->compare('length',$search['length']);
				else $criteria->addCondition('length!=9 and length!=12');
			}				
		}
		$criteria->compare('type',$type);	
		$criteria->select='t.*,p.name as product_name,b.name as brand_name,tt.name as texture_name,r.name as rank_name ,prefecture.name as prefecture_name';
		$criteria->join='left join dict_goods_property p on p.std=t.product_std
					left join dict_goods_property b on b.std=t.brand_std
					left join dict_goods_property tt on tt.std=t.texture_std
					left join dict_goods_property r on r.std=t.rank_std
					left join prefecture on prefecture.id=t.prefecture';
// 		$criteria->addCondition("prefecture.name!='华宏专区' or isnull(prefecture.name)");
		if($type!='spread')
		{	//销售报价数据
			list($tableHeader,$tableData,$pages)=$this->salePriceData($ac,$date_type,$criteria);			
		}else{//基价数据
			list($tableHeader,$tableData,$pages)=$this->basePriceData($ac,$criteria);		
		}				
		$search=(Object)$search;
		return array($tableHeader,$tableData,$search,$pages,$date_type);
	} 	
	/*
	 * 销售报价数据
	 */
	public  function salePriceData($ac,$date_type,$criteria)
	{
		if($ac)
		{
			$checkbox='<input type="checkbox" class=" select_all" style="margin-left:10px;">';
			$tableHeader=array(
// 					array('name'=>$checkbox,'class' =>"sort-disabled",'width'=>"30px"),
					array('name'=>'产地<img src="/images/shang.png" class="order_img" value="bra">','class' =>"flex-col sort-disabled order_but",'width'=>"100px"),
					array('name'=>'品名<img src="/images/shang.png" class="order_img" value="pro">','class' =>"flex-col sort-disabled order_but",'width'=>"80px"),
					array('name'=>'材质<img src="/images/shang.png" class="order_img" value="tex">','class' =>"flex-col sort-disabled order_but",'width'=>"90px"),
					array('name'=>'规格<img src="/images/shang.png" class="order_img" value="rak">','class' =>"flex-col sort-disabled order_but",'width'=>"70px"),//
					array('name'=>'长度<img src="/images/shang.png" class="order_img" value="len">','class' =>"flex-col sort-disabled rightAlign order_but",'width'=>"50px"),
					array('name'=>'件重','class' =>"flex-col sort-disabled text-right",'width'=>"130px"),
			);
		}else{
			if($date_type=='yes')
			{
				$tableHeader=array(
						array('name'=>'产地<img src="/images/shang.png" class="order_img" value="bra">','class' =>"flex-col sort-disabled order_but",'width'=>"100px"),
						array('name'=>'品名<img src="/images/shang.png" class="order_img" value="pro">','class' =>"flex-col sort-disabled order_but",'width'=>"80px"),
						array('name'=>'材质<img src="/images/shang.png" class="order_img" value="tex">','class' =>"flex-col sort-disabled order_but",'width'=>"90px"),
						array('name'=>'规格<img src="/images/shang.png" class="order_img" value="rak">','class' =>"flex-col sort-disabled order_but",'width'=>"70px"),//
						array('name'=>'长度<img src="/images/shang.png" class="order_img" value="len">','class' =>"flex-col sort-disabled rightAlign order_but",'width'=>"50px"),
						array('name'=>'件重','class' =>"flex-col sort-disabled text-right",'width'=>"130px"),
				);
			}else{
				$tableHeader=array(
						array('name'=>'产地','class' =>"flex-col sort-disabled ",'width'=>"100px"),
						array('name'=>'品名','class' =>"flex-col sort-disabled ",'width'=>"80px"),
						array('name'=>'材质','class' =>"flex-col sort-disabled ",'width'=>"90px"),
						array('name'=>'规格','class' =>"flex-col sort-disabled ",'width'=>"70px"),//
						array('name'=>'长度','class' =>"flex-col sort-disabled rightAlign ",'width'=>"50px"),
						array('name'=>'件重','class' =>"flex-col sort-disabled text-right",'width'=>"130px"),
				);
			}
			
		}
		$areas=WareArea::getList();
		$areas=array_slice($areas,1,count($areas),true);
		$temp=array();
		foreach ($areas as $ea)
		{
			array_push($temp, array('name'=>$ea.'价格','class' =>"flex-col sort-disabled rightAlign",'width'=>"100px"));
		}
		$tableHeader=array_merge($tableHeader,$temp);				
		if($date_type!="yes"){
			array_push($tableHeader,array('name'=>'报价日期','class' =>"flex-col sort-disabled",'width'=>"100px"));
			$criteria->select.=',rel.price_date as rprice_date';
			$criteria->join.=' left join quoted_warehouse_relation rel on t.id=rel.quoted_id';
			if($_POST['start_time'])
				$criteria->addCondition("rel.price_date>='".$_POST['start_time']."'");
			if($_POST['end_time'])
				$criteria->addCondition("rel.price_date<='".$_POST['end_time']."'");
			$criteria->group='t.id,rel.price_date';
		}elseif(!$ac){
			$criteria->join.=' left join quoted_warehouse_relation rel on t.id=rel.quoted_id';
			$criteria->addCondition("rel.price_date='".date('Y-m-d')."'");
			$criteria->addCondition("rel.price!=0");
			$criteria->group='t.id';
		}
		if($date_type!='yes')
		{
			$pages = new CPagination();
			$pages->itemCount = $this->count($criteria);
			$pages->pageSize = $_COOKIE['qd']? intval($_COOKIE['qd']):50;
			if(!$_COOKIE['qd'])	$_COOKIE['qd'] = 50;
			$pages->applyLimit($criteria);
			$criteria->order='rel.price_date desc,t.prefecture asc,convert(brand_name using gbk),convert(product_name using gbk) ,texture_name,length,CONVERT(substr(rank_name,2,4),SIGNED)';
						
		}else{			
// 			$criteria->order='t.prefecture asc,convert(brand_name using gbk),convert(product_name using gbk) ,texture_name,length,CONVERT(substr(rank_name,2,4),SIGNED)';
			switch ($_COOKIE['saleprice_order']) {
				case 'bra_sha':
					$criteria->order='t.prefecture asc,convert(brand_name using gbk) asc,convert(product_name using gbk) ,texture_name,length,CONVERT(substr(rank_name,2,4),SIGNED)';
					break;
				case 'bra_xia':
					$criteria->order='t.prefecture asc,convert(brand_name using gbk) desc,convert(product_name using gbk) ,texture_name,length,CONVERT(substr(rank_name,2,4),SIGNED)';
					break;
				case 'pro_sha':
					$criteria->order='t.prefecture asc,convert(product_name using gbk) asc,convert(brand_name using gbk) ,texture_name,length,CONVERT(substr(rank_name,2,4),SIGNED)';
					break;
				case 'pro_xia':
					$criteria->order='t.prefecture asc,convert(product_name using gbk) desc,convert(brand_name using gbk) ,texture_name,length,CONVERT(substr(rank_name,2,4),SIGNED)';
					break;
				case 'tex_sha':
					$criteria->order='t.prefecture asc ,texture_name asc,convert(brand_name using gbk) ,convert(product_name using gbk),length,CONVERT(substr(rank_name,2,4),SIGNED)';
					break;
				case 'tex_xia':
					$criteria->order='t.prefecture asc,texture_name desc,convert(brand_name using gbk) ,convert(product_name using gbk) ,length,CONVERT(substr(rank_name,2,4),SIGNED)';
					break;
				case 'rak_sha':
					$criteria->order='t.prefecture asc,CONVERT(substr(rank_name,2,4),SIGNED) asc,convert(brand_name using gbk) ,convert(product_name using gbk) ,texture_name,length';
					break;
				case 'rak_xia':
					$criteria->order='t.prefecture asc,CONVERT(substr(rank_name,2,4),SIGNED) desc,convert(brand_name using gbk) ,convert(product_name using gbk) ,texture_name,length';
					break;
				case 'len_sha':
					$criteria->order='t.prefecture asc,length asc,convert(brand_name using gbk) ,convert(product_name using gbk) ,texture_name,CONVERT(substr(rank_name,2,4),SIGNED)';
					break;
				case 'len_xia':
					$criteria->order='t.prefecture asc,length desc,convert(brand_name using gbk) ,convert(product_name using gbk) ,texture_name,CONVERT(substr(rank_name,2,4),SIGNED)';
					break;
				default:
					break;
			}
		}		
// 		$criteria->order='t.prefecture asc,convert(brand_name using gbk),convert(product_name using gbk) ,length,texture_name,CONVERT(substr(rank_name,2,4),SIGNED)';
		$items = $this->findAll($criteria);
		$tableData=array();
		if($items)
		{//$tableData=QuotedDetail::backData($items, $type, $date_type, $ac,$areas);		
			foreach ($items as $each)
			{
				if($prefecture!=$each->prefecture)
				{
					$prefecture=$each->prefecture;
					$line='<span class="prefecture_line"></span>';
					if($ac)
					{
						$da['data']=array($each->prefecture_name,'','','','',$line);
					}else{
						$da['data']=array($each->prefecture_name,'','','','',$line);
					}
					foreach ($areas as $key=>$value)
					{
						$da['data'][]='';
					}
					if($date_type!="yes"){
						array_push($da['data'],'');
					}
					$da['group']='hhh';
					array_push($tableData,$da);
				}			
				$sql="select price,area_id from quoted_warehouse_relation  where  quoted_id=".$each->id;
				if($date_type=="yes")
					$sql.=' and price_date="'.date('Y-m-d').'"';
				else 
					$sql.=' and price_date="'.$each->rprice_date.'"';
				$data=QuotedWarehouseRelation::model()->findAllBySql($sql);
				$prices=array();
				if($data)
				{
					foreach ($data as $ss)
					{
						$prices[$ss->area_id]=$ss->price;
					}
				}				
				$unit_weight=DictGoods::getUnitWeightByStd($each->brand_std,$each->product_std,$each->texture_std,$each->rank_std,$each->length);
				if($ac)
				{
// 					$input='<input type="checkbox" value="'.$each->id.'" class="checkbox" style="margin-left:10px;" >';
					$da['data']=array(
// 							$input,
							$each->brand_name,
							$each->product_name,
							str_replace('E','<span class="red">E</span>',$each->texture_name),
							$each->rank_name,
							$each->length,
							$unit_weight,
					);
				}else{
					$da['data']=array(
							$each->brand_name,
							$each->product_name,
							str_replace('E','<span class="red">E</span>',$each->texture_name),
							$each->rank_name,
							$each->length,
							$unit_weight,
					);
				}				
				$i=1;
				foreach ($areas as $key=>$value)
				{
					$ins=$ac?'<input type="text" class="price " area_id="'.$key.'" name="'.$each->id.'"   onKeypress="return (/[\d.]/.test(String.fromCharCode(event.keyCode)))" value="'.($prices[$key]?$prices[$key]:0).'"/>':number_format($prices[$key],2);
					$da['data'][]=$ins;
				}
				if($date_type!="yes"){
					array_push($da['data'],$each->rprice_date);
				}
				$da['group']=$each->id;
				array_push($tableData,$da);
			}			
		}		
		return array($tableHeader,$tableData,$pages);
	}
	
	
	public function basePriceData($ac,$criteria)
	{
		$tableHeader=array(
				array('name'=>'产地','class' =>"flex-col sort-disabled",'width'=>"100px"),
				array('name'=>'品名','class' =>"flex-col sort-disabled",'width'=>"80px"),
				array('name'=>'材质','class' =>"flex-col sort-disabled",'width'=>"90px"),
				array('name'=>'规格','class' =>"flex-col sort-disabled",'width'=>"70px"),//
// 				array('name'=>'长度','class' =>"flex-col sort-disabled rightAlign",'width'=>"50px"),
// 				array('name'=>'专区','class' =>"flex-col sort-disabled",'width'=>"130px"),
				array('name'=>'价格','class' =>"flex-col sort-disabled rightAlign",'width'=>"100px"),//
// 				array('name'=>'最后报价人','class' =>"flex-col sort-disabled ",'width'=>"100px"),//
// 				array('name'=>'最后报价时间','class' =>"flex-col sort-disabled",'width'=>"110px"),//
		);	
		$pages = new CPagination();
		$pages->itemCount = $this->count($criteria);
		$pages->pageSize = $_COOKIE['qd']? intval($_COOKIE['qd']):50;
		if(!$_COOKIE['qd'])	$_COOKIE['qd'] = 50;
		$pages->applyLimit($criteria);
		$criteria->order='convert(brand_name using gbk),convert(product_name using gbk) ,texture_name,CONVERT(substr(rank_name,2,3),SIGNED)';
		$items = $this->findAll($criteria);
		$tableData=array();
		if($items)
		{			
			foreach ($items as $each)
			{
				$da['data']=array(
						$each->brand_name,
						$each->product_name,
						str_replace('E','<span class="red">E</span>',$each->texture_name),
						$each->rank_name,
// 						$each->length,
// 						$each->prefecture_name,
						$ac?'<input type="text" class="price spread" name="'.$each->id.'"  onKeypress="return (/[\-\d.]/.test(String.fromCharCode(event.keyCode)))" value="'.$each->price.'"/>':number_format($each->price,2),
// 						$each->updater->nickname,
// 						date('Y-m-d H:i:s',$each->last_update),
				);				
				$da['group']=$each->id;
				array_push($tableData,$da);
			}			
// 			$tableData=QuotedDetail::backData($items, $type, $date_type, $ac,$areas);
		}
		return array($tableHeader,$tableData,$pages);
	}
	
	
	
	
	
	public static function getList($ac=''){
		$type=$_REQUEST['type'];
		$date_type=$_REQUEST['date_type'];
		$areas=array();
		if($type!='spread')
		{
			if($type=='guidance')
			{
				if($ac)
				{
					$checkbox='<input type="checkbox" class=" select_all" style="margin-left:10px;">';
					$tableHeader=array(
						array('name'=>$checkbox,'class' =>"sort-disabled",'width'=>"30px"),
						array('name'=>'产地','class' =>"flex-col sort-disabled",'width'=>"100px"),
						array('name'=>'品名','class' =>"flex-col sort-disabled",'width'=>"80px"),
						array('name'=>'材质','class' =>"flex-col sort-disabled",'width'=>"90px"),
						array('name'=>'规格','class' =>"flex-col sort-disabled",'width'=>"70px"),//
						array('name'=>'长度','class' =>"flex-col sort-disabled rightAlign",'width'=>"50px"),
						array('name'=>'件重','class' =>"flex-col sort-disabled text-right",'width'=>"130px"),
					);
				}else{
					$tableHeader=array(
						array('name'=>'产地','class' =>"flex-col sort-disabled",'width'=>"100px"),
						array('name'=>'品名','class' =>"flex-col sort-disabled",'width'=>"80px"),
						array('name'=>'材质','class' =>"flex-col sort-disabled",'width'=>"90px"),
						array('name'=>'规格','class' =>"flex-col sort-disabled",'width'=>"70px"),//
						array('name'=>'长度','class' =>"flex-col sort-disabled rightAlign",'width'=>"50px"),
						array('name'=>'件重','class' =>"flex-col sort-disabled text-right",'width'=>"130px"),
					);
				}
				
			
			}else{
				$tableHeader=array(
					array('name'=>'产地','class' =>"flex-col sort-disabled",'width'=>"100px"),
					array('name'=>'品名','class' =>"flex-col sort-disabled",'width'=>"80px"),
					array('name'=>'材质','class' =>"flex-col sort-disabled",'width'=>"90px"),
					array('name'=>'规格','class' =>"flex-col sort-disabled",'width'=>"70px"),//
					array('name'=>'长度','class' =>"flex-col sort-disabled rightAlign",'width'=>"50px"),
					array('name'=>'专区','class' =>"flex-col sort-disabled",'width'=>"130px"),
				);
			}
			
			$areas=WareArea::getList();
			$areas=array_slice($areas,1,count($areas),true);
			$temp=array();
			foreach ($areas as $ea)
			{
				array_push($temp, array('name'=>$ea.'价格','class' =>"flex-col sort-disabled rightAlign",'width'=>"100px"));
			}
			$tableHeader=array_merge($tableHeader,$temp);
		}else{
			$tableHeader=array(
					array('name'=>'产地','class' =>"flex-col sort-disabled",'width'=>"100px"),
					array('name'=>'品名','class' =>"flex-col sort-disabled",'width'=>"80px"),
					array('name'=>'材质','class' =>"flex-col sort-disabled",'width'=>"90px"),
					array('name'=>'规格','class' =>"flex-col sort-disabled",'width'=>"70px"),//
					array('name'=>'长度','class' =>"flex-col sort-disabled rightAlign",'width'=>"50px"),
					array('name'=>'专区','class' =>"flex-col sort-disabled",'width'=>"130px"),
					array('name'=>'价格','class' =>"flex-col sort-disabled rightAlign",'width'=>"100px"),//
					array('name'=>'最后报价人','class' =>"flex-col sort-disabled ",'width'=>"100px"),//
					array('name'=>'最后报价时间','class' =>"flex-col sort-disabled",'width'=>"110px"),//
			);
		}
		
		if($date_type!="yes"){
			array_push($tableHeader,array('name'=>'报价日期','class' =>"flex-col sort-disabled",'width'=>"100px"));
		}
		$search=$_REQUEST['QuotedDetail'];		
		$model=new QuotedDetail();
		$criteria=new CDbCriteria();
		if(!empty($search))
		{
			if($search['brand_id']){
				$criteria->compare('brand_std', DictGoodsProperty::getStd($search['brand_id']));
				$search['brand_name']=DictGoodsProperty::getProName($search['brand_id']);
			}
			if($search['product_id']){
				$criteria->compare('product_std', DictGoodsProperty::getStd($search['product_id']));
				$search['poduct_name']=DictGoodsProperty::getProName($search['product_id']);
			}
			if($search['texture_id']){
				$criteria->compare('texture_std', DictGoodsProperty::getStd($search['texture_id']));
				$search['texture_name']=DictGoodsProperty::getProName($search['texture_id']);
			}
			if($search['rank_id']){
				$criteria->compare('rank_std', DictGoodsProperty::getStd($search['rank_id']));
				$search['rank_name']=DictGoodsProperty::getProName($search['rank_id']);
			}
			if($search['prefecture'])
				$criteria->compare('prefecture', $search['prefecture'],true);
		}
		$criteria->compare('type',$type);		
// 		$criteria->order = "t.price_date desc,t.texture_std,t.rank_std,t.brand_std,t.product_std";
// 		if($date_type=="yes"&&($type=="guidance"||$type=="net"))
// 			$criteria->addCondition("t.price_date='".date("Y-m-d")."'");
		if($date_type!="yes"){
			if($_POST['start_time'])
				$criteria->addCondition("t.price_date>='".$_POST['start_time']."'");
			if($_POST['end_time'])
				$criteria->addCondition("t.price_date<='".$_POST['end_time']."'");
		}
		$criteria->select='t.*,p.name as product_name,b.name as brand_name,tt.name as texture_name,r.name as rank_name ,prefecture.name as prefecture_name';
		$criteria->join='left join dict_goods_property p on p.std=t.product_std
					left join dict_goods_property b on b.std=t.brand_std
					left join dict_goods_property tt on tt.std=t.texture_std
					left join dict_goods_property r on r.std=t.rank_std 
					left join prefecture on prefecture.id=t.prefecture';
		if($date_type!="yes"){
			$pages = new CPagination();
			$pages->itemCount = $model->count($criteria);
			$pages->pageSize = $_COOKIE['qd']? intval($_COOKIE['qd']):50;
			if(!$_COOKIE['qd'])	$_COOKIE['qd'] = 50;
			
			$pages->applyLimit($criteria);
		}
		
		$criteria->order='t.prefecture asc,convert(product_name using gbk) ,length,texture_name,CONVERT(substr(rank_name,2,3),SIGNED)';
		$items = $model->findAll($criteria);
		$tableData=array();
		if($items)
		{
			$tableData=QuotedDetail::backData($items, $type, $date_type, $ac,$areas);
		}
		$search=(Object)$search;
		return array($tableHeader,$tableData,$search,$pages,$date_type);			
	}
	
public static  function backData($items,$type,$date_type,$ac,$areas)
{
	$tableData=array();
	if($type=='spread')
	{
		foreach ($items as $each)
		{
			$da['data']=array(
					$each->brand_name,
					$each->product_name,
					str_replace('E','<span class="red">E</span>',$each->texture_name),
					$each->rank_name,
					$each->length,
					$each->prefecture_name,
					$ac?'<input type="text" class="price spread" name="'.$each->id.'"  onKeypress="return (/[\d.]/.test(String.fromCharCode(event.keyCode)))" value="'.$each->price.'"/>':number_format($each->price,2),
					$each->updater->nickname,
					date('H:i:s',$each->last_update),
			);
			if($date_type!="yes"){
				array_push($da['data'],$each->price_date);
			}
			$da['group']=$each->id;
			array_push($tableData,$da);
		}
	}else{

		foreach ($items as $each)
		{
			if($prefecture!=$each->prefecture)
			{
				$prefecture=$each->prefecture;
				if($type=='guidance')
				{
					$line='<span class="prefecture_line"></span>';
					if($ac)
					{
						$da['data']=array('',$each->prefecture_name,'','','','',$line);						
					}else{
						$da['data']=array($each->prefecture_name,'','','','',$line);
					}
					foreach ($areas as $key=>$value)
					{						
						$da['data'][]='';
					}
					if($date_type!="yes"){
						array_push($da['data'],'');
					}
					$da['group']='hhh';
					array_push($tableData,$da);
				}

			}

			$sql="select price,area_id from quoted_warehouse_relation  where  quoted_id=".$each->id;
			if($date_type=="yes"&&($type=="guidance"||$type=="net"))
				$sql.=' and price_date="'.date('Y-m-d').'"';
			$data=QuotedWarehouseRelation::model()->findAllBySql($sql);			
			$prices=array();			
			if($data)
			{
				foreach ($data as $ss)
				{
					$prices[$ss->area_id]=$ss->price;					
				}
			}			
			if($type=='guidance')
			{
				$unit_weight=DictGoods::getUnitWeightByStd($each->brand_std,$each->product_std,$each->texture_std,$each->rank_std,$each->length);
				if($ac)
				{
					$input='<input type="checkbox" value="'.$each->id.'" class="checkbox" style="margin-left:10px;" >';
					$da['data']=array(
						$input,
						$each->brand_name,
						$each->product_name,
						str_replace('E','<span class="red">E</span>',$each->texture_name),
						$each->rank_name,
						$each->length,
						$unit_weight,
					);
				}else{
					$da['data']=array(
						$each->brand_name,
						$each->product_name,
						str_replace('E','<span class="red">E</span>',$each->texture_name),
						$each->rank_name,
						$each->length,
						$unit_weight,
					);
				}
				
				
			}else{
				$da['data']=array(
					$each->brand_name,
					$each->product_name,
					str_replace('E','<span class="red">E</span>',$each->texture_name),
					$each->rank_name,
					$each->length,
					$each->prefecture_name,
				);
			}
			
			$i=1;
			foreach ($areas as $key=>$value)
			{
				$ins=$ac?'<input type="text" class="price " area_id="'.$key.'" name="'.$each->id.'"   onKeypress="return (/[\d.]/.test(String.fromCharCode(event.keyCode)))" value="'.($prices[$key]?$prices[$key]:0).'"/>':number_format($prices[$key],2);
				$da['data'][]=$ins;
			}
			if($date_type!="yes"){
				array_push($da['data'],$each->price_date);
			}
			$da['group']=$each->id;
			array_push($tableData,$da);
		}
	}
	return $tableData;
}
	
	
	public static function getReportList(){
		$model = new QuotedDetail();
		$cri = new CDbCriteria();		
		$cri->select = "t.*,p.name as product_name,b.name as brand_name,tt.name as texture_name,r.name as rank_name,prefecture.name as prefecture_name,rel.price as rprice,group_concat(wa.name) as areaname";
		$cri->join = "left join dict_goods_property p on p.std=t.product_std
					left join dict_goods_property b on b.std=t.brand_std
					left join dict_goods_property tt on tt.std=t.texture_std
					left join dict_goods_property r on r.std=t.rank_std  
					left join quoted_warehouse_relation rel on rel.quoted_id=t.id 
				 	left join ware_area wa on rel.area_id=wa.id	 	
					left join prefecture on prefecture.id=t.prefecture";
		$cri->addCondition("t.type = 'guidance'");
		$cri->addCondition("rel.price_date='".date("Y-m-d")."'");
		$cri->addCondition("prefecture.name!='华宏专区'");
		$cri->addCondition("rel.price!=0");
		$cri->group='t.id,rel.price';
		$cri->order = "t.prefecture,convert(brand_name using gbk),CONVERT(product_name using gbk),texture_name,length asc,CONVERT(substr(rank_name,2,4),SIGNED)";
		$items = $model->findAll($cri);
		$time = 0;
		foreach ($items as $i){
			if($i->last_update>$time){
				$time = $i->last_update;
			}
		}
		
		return array($items,$time);
	} 
	
	/**
	 * 
	 * 根据历史数据查找今日数据
	 * @param 历史数据
	 */
	public function findByHistory($history){
		if(!$history)
		{
			return false;
		}
		$today = date("Y-m-d",time());
		$cri = new CDbCriteria();
		$cri->addCondition("product_std = '{$history->product_std}'");
		$cri->addCondition("texture_std = '{$history->texture_std}'");
		$cri->addCondition("brand_std = '{$history->brand_std}'");
		$cri->addCondition("rank_std = '{$history->rank_std}'");
		$cri->addCondition("length = '{$history->length}'");
		$cri->addCondition("area = '{$history->area}'");
		$cri->addCondition("prefecture = '{$history->prefecture}'");
		$cri->addCondition("warehouse_id = '{$history->warehouse_id}'");
		$cri->addCondition("price_date = '{$today}'");
		$cri->addCondition("type='{$history->type}'");
		
		return QuotedDetail::model()->find($cri);
		
	}

	/*
	*获取销售单价
	*/	
	public static function getSalePrice()
	{
		$model=QuotedDetail::model()->findByAttributes(array(
				'product_std'=>'',
				'brand_std'=>'',
				'texture_std'=>'',
				'rank_std'=>'',
				'length'=>'',
			));
	}



	/**
	 * 获取销售价
	 * 库存利润预估使用
	 */
	public static function getEstimatePrice($product,$rank,$texture,$brand,$length,$date,$warehouse)
	{
		if($length == ''){$length = 0;}
		$brand_trans=array('华宏'=>'华兴');
		$brand_name=DictGoodsProperty::getProName($brand);
		if(array_key_exists($brand_name,$brand_trans)){
			$new_brand_name=$brand_trans[$brand_name];
			$brand=DictGoodsProperty::model()->find('short_name="'.$new_brand_name.'"')->id;
		}		
		$model=QuotedDetail::model()->findByAttributes(array(
				'product_std'=>DictGoodsProperty::getStd($product),
				'texture_std'=>DictGoodsProperty::getStd($texture),
				'rank_std'=>DictGoodsProperty::getStd($rank),
				'brand_std'=>DictGoodsProperty::getStd($brand),
				'length'=>$length,
				'type'=>'guidance'
		));
		if($model){
			$sql="select price from quoted_warehouse_relation q left join warehouse w on w.area=q.area_id where w.id=".$warehouse.' and quoted_id='.$model->id.' and price!=0 and price_date="'.$date.'"';
			$price=QuotedWarehouseRelation::model()->findBySql($sql);
			if($price){
				$result = $price->price;
			}else{
				$sql="select price from quoted_warehouse_relation q left join warehouse w on w.area=q.area_id where w.id=".$warehouse.' and quoted_id='.$model->id.'  and price!=0  order by price_date desc';
				$price=QuotedWarehouseRelation::model()->findBySql($sql);
				$result = $price->price?$price->price:0;
			}			
			return $result;
		}else{
			return 0;
		}
	}

	
	/*
	 * 获取采购当日网价
	 */
	public function getPurchasePrice($brand,$product,$texture,$rank,$length,$date)
	{
		$brand_std=DictGoodsProperty::getStd($brand);
		$product_std=DictGoodsProperty::getStd($product);
		$texture_std=DictGoodsProperty::getStd($texture);
		$sql="select price from purchase_price p left join purprice_rank_rel r on r.price_id=p.id where brand_std='{$brand_std}' and product_std='{$product_std}'  and texture_std='{$texture_std}' and rank_id={$rank} and price_date='{$date}'";
		$connection=Yii::app()->db;
		$res=$connection->createCommand($sql)->queryRow();
		if($res)
		{
			return $res['price'];
		}else{
			return 0;
		}		
	}

	
	/*
	 * 获取基价
	 */
	public static  function getSpreadPrice($brand,$product,$texture,$rank)
	{
		$brand_std=DictGoodsProperty::getStd($brand);
		$product_std=DictGoodsProperty::getStd($product);
		$texture_std=DictGoodsProperty::getStd($texture);
		$rank_std=DictGoodsProperty::getStd($rank);
		$res=QuotedDetail::model()->findByAttributes(array('brand_std'=>$brand_std,'product_std'=>$product_std,'texture_std'=>$texture_std,'rank_std'=>$rank_std,'type'=>'spread'));
		if($res)
		{
			return $res->price;
		}else{
			return 0;
		}
	}
	

	
}
