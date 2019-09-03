<?php

/**
 * This is the biz model class for table "dict_goods".
 *
 */
class DictGoods extends DictGoodsData
{
	public $product_name;
	public $brand_name;
	public $texture_name;
	public $rank_name;
	public $product_id;
	public $brand_id;
	public $texture_id;
	public $rank_id;

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
				'product'=>array(self::BELONGS_TO,'DictGoodsProperty','product_id'),
				'rand'=>array(self::BELONGS_TO,'DictGoodsProperty','rand_id'),
				'texture'=>array(self::BELONGS_TO,'DictGoodsProperty','texture_id'),
				'brand'=>array(self::HAS_ONE,'DictGoodsProperty','std','order'=>'brand.priority asc'),
				'rank' => array(self::BELONGS_TO, 'DictGoodsProperty', 'rank_id'),
				'brand1'=>array(self::BELONGS_TO,'DictGoodsProperty','brand_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'shor_name' => 'Shor Name',
			'product_id' => 'Product',
			'brand_id' => 'Brand',
			'texture_id' => 'Texture',
			'rand_id' => 'Rand',
			'length' => 'Length',
			'last_update' => 'Last Update',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('shor_name',$this->shor_name,true);
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('brand_id',$this->brand_id);
		$criteria->compare('texture_id',$this->texture_id);
		$criteria->compare('rand_id',$this->rand_id);
		$criteria->compare('length',$this->length);
		$criteria->compare('last_update',$this->last_update);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DictGoods the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public static function getGood($detail)
	{
		
		if($detail['length']=='')$detail['length']=0; 	
		$model=DictGoods::model()->findByAttributes(array(
				'product_std'=>DictGoodsProperty::getStd($detail['product_id']),
				'texture_std'=>DictGoodsProperty::getStd($detail['texture_id']),
				'rank_std'=>DictGoodsProperty::getStd($detail['rank_id']),
				'brand_std'=>DictGoodsProperty::getStd($detail['brand_id']),
				'length'=>$detail['length'],
		));
		if($model){return $model->id;}
		return false;
	}
	
	
	/*
	 * 获取当日价
	 */
	public static function getGoodPrice($arr)
	{
		$return='';
		$brand_std=DictGoodsProperty::getStd($arr['brand']);
		$product_std=DictGoodsProperty::getStd($arr['product']);
		$texture_std=DictGoodsProperty::getStd($arr['texture']);
		$rank_std=DictGoodsProperty::getStd($arr['rank']);
		$length=$arr['length'];
// 		$ware=$arr['ware'];
		$type=$arr['type'];
		if($brand_std==''||$product_std==''||$texture_std==''||$rank_std==''||$length=='')
		{
			return false;
		}
// 		$warehouse=Warehouse::model()->findByPk($ware);
// 		if(!$warehouse)return false;
// 		$area_id=$warehouse->area;
		$criteria=new CDbCriteria();
		$criteria->compare('brand_std', $brand_std);
		$criteria->compare('product_std', $product_std);
		$criteria->compare('texture_std', $texture_std);
		$cri_price=clone $criteria;
		$criteria->compare('rank_std', $rank_std);
		$cri=clone $criteria;
		$criteria->addCondition('length ="'.$length.'"');
		$good=DictGoods::model()->find($criteria);
		if(!$good)return false;		
		$cri_price->select='pri.price as price';
		$cri_price->join='left join purprice_date pri on pri.price_id=t.id 
					left join purprice_rank_rel rel on rel.price_id=t.id';		
		$cri_price->compare('pri.price_date',date('Y-m-d'));
		$cri_hhprice=clone $cri_price;
		$cri_price->compare('rel.rank_id', $arr['rank']);
		$result=PurchasePrice::model()->find($cri_price);
		if($result)
		{
			$return=$result->price;
		}else{
			$cri->addCondition('type="spread"');
			$spread=QuotedDetail::model()->find($cri);
			if($spread)
			{
				$result=PurchasePrice::model()->find($cri_hhprice);
				if($result)
				{
					$return=$result->price+$spread->price;
				}else{
					$return=false;
				}				
			}else{
				$return=false;
			}
		}
		return $return;
	}
	
	public static function getUnitWeight($detail)
	{
		if ($detail['length'] == '') $detail['length'] = 0;
		$model = DictGoods::model()->findByAttributes(array(
			'product_std' => DictGoodsProperty::getStd($detail['product']), 
			'texture_std' => DictGoodsProperty::getStd($detail['texture']), 
			'rank_std' => DictGoodsProperty::getStd($detail['rank']), 
			'brand_std'=> DictGoodsProperty::getStd($detail['brand']), 
			'length' => $detail['length'],
		));
		return $model ? $model->unit_weight : false;
	}
	//获取件重，和上面的区别，仅仅是传入的数组键值不同
	public static function getUnitWeightID($detail)
	{
		if ($detail['length'] == '') $detail['length'] = 0;
		$model = DictGoods::model()->findByAttributes(array(
				'product_std' => DictGoodsProperty::getStd($detail['product_id']),
				'texture_std' => DictGoodsProperty::getStd($detail['texture_id']),
				'rank_std' => DictGoodsProperty::getStd($detail['rank_id']),
				'brand_std'=> DictGoodsProperty::getStd($detail['brand_id']),
				'length' => $detail['length'],
		));
		return $model ? $model->unit_weight : false;
	}
	
	public static function getUnitWeightByStd($brand_std,$product_std,$texture_std,$rank_std,$length)
	{
		if($length=='')$length=0;
		$model=DictGoods::model()->findByAttributes(array(
				'product_std'=>$product_std,
				'texture_std'=>$texture_std,
				'rank_std'=>$rank_std,
				'brand_std'=>$brand_std,
				'length'=>$length,
		));
		if($model)
		{
			$return=$model->unit_weight;
			return number_format($return,3);
		}
		return "0.000";
	}

	public static function getWeightByStorage($storage)
	{
		if(!$storage->length)$storage->length=0; 
		
		$model=DictGoods::model()->findByAttributes(array(
				'product_std'=>DictGoodsProperty::getStd($storage->product_id),
				'texture_std'=>DictGoodsProperty::getStd($storage->texture_id),
				'rank_std'=>DictGoodsProperty::getStd($storage->rank_id),
				'brand_std'=>DictGoodsProperty::getStd($storage->brand_id),
				'length'=>$storage->length,
		));	
		if($model){return number_format($model->unit_weight,3);}
		return 0;
	}
	
	public static function getIndexList(){
		$model = new DictGoods();
		$cri = new CDbCriteria();
		$cri->select = "t.*, 
		p.short_name as product_name, 
		b.short_name as brand_name, 
		tt.name as texture_name, 
		r.name as rank_name";
		$cri->join = "LEFT JOIN dict_goods_property p ON p.std = t.product_std AND p.is_available = 1 
		LEFT JOIN dict_goods_property b ON b.std = t.brand_std AND b.is_available = 1 
		LEFT JOIN dict_goods_property tt ON tt.std = t.texture_std AND tt.is_available = 1 
		LEFT JOIN dict_goods_property r ON r.std = t.rank_std AND r.is_available = 1";
		
		if (isset($_POST['DictGoods'])) 
		{
			$model->attributes = $_POST['DictGoods'];
			if ($_POST['DictGoods']["name"]) 
			{
				$cri->addCondition("t.name LIKE :name OR t.short_name LIKE :name");
				$cri->params[':name'] = '%'.$_POST['DictGoods']["name"].'%';
			}
			if($_POST['DictGoods']["product_id"]) 
			{
				$model->product_id = $_POST['DictGoods']["product_id"];
				$model->product_std = DictGoodsProperty::getStd($_POST['DictGoods']["product_id"]);
				$cri->addCondition('t.product_std = :product_std');
				$cri->params[':product_std'] = $model->product_std;
			}
			if($_POST['DictGoods']["texture_id"]) 
			{
				$model->texture_id = $_POST['DictGoods']["texture_id"];
				$model->texture_std = DictGoodsProperty::getStd($_POST['DictGoods']["texture_id"]);
				$cri->addCondition('t.texture_std = :texture_std');
				$cri->params[':texture_std'] = $model->texture_std;
			}
			if ($_POST['DictGoods']["rank_id"])
			{
				$model->rank_id = $_POST['DictGoods']["rank_id"];
				$model->rank_std = DictGoodsProperty::getStd($_POST['DictGoods']["rank_id"]);
				$cri->addCondition('t.rank_std = :rank_std');
				$cri->params[':rank_std'] = $model->rank_std;
			}
			if ($_POST['DictGoods']["brand_id"])
			{
				$model->brand_id = $_POST['DictGoods']["brand_id"];
				$model->brand_std = DictGoodsProperty::getStd($_POST['DictGoods']["brand_id"]);
				$cri->addCondition('t.brand_std = :brand_std');
				$cri->params[':brand_std'] = $model->brand_std;
			}
		}
// 		$cri->order = "t.id DESC";
		
		$pages = new CPagination();
		$pages->itemCount = $model->count($cri);
		$pages->pageSize = $_COOKIE['d_goods']? intval($_COOKIE['d_goods']):Yii::app()->params['pageCount'];
		$pages->applyLimit($cri);
		
		$items = $model->findAll($cri);
		return array($model, $pages, $items);
	}
	
	public static function synchronization($data) 
	{
		$res = array('result' => "success", 'message' => urlencode("成功"));
		if (!$data)
		{
			$res['result'] = "error";
			$res['message'] = urldecode("数据不存在");
			return urldecode(json_encode($res));
		}
		$body = $data->Body;
		$columns = $body->Content->Tables[0]->Columns;
		$records = $body->Content->Tables[0]->Records;
		$fields = $records[0]->Fields;
		
		for ($i = 0; $i < count($columns); $i++) 
		{
			if ($columns[$i]->Text == '名称') $name = $fields[$i]->Text;
			if ($columns[$i]->Text == '简称') $short_name = $fields[$i]->Text;
			if ($columns[$i]->Text == '长度') $length = $fields[$i]->Text;
			if ($columns[$i]->Text == '件重') $unit_weight = $fields[$i]->Text;
			if ($columns[$i]->Text == '品名') $product_std = $fields[$i]->Text;
			if ($columns[$i]->Text == '产地') $brand_std = $fields[$i]->Text;
			if ($columns[$i]->Text == '材质') $texture_std = $fields[$i]->Text;
			if ($columns[$i]->Text == '规格') $rank_std = $fields[$i]->Text;
		}
		
		$transaction = Yii::app()->db->beginTransaction();
		try {
			switch ($body->Verb) 
			{
				case 'add': 
					$model = new DictGoods();
					$model->name = $name;
					$model->short_name = $short_name;
					$model->length = $length;
					$model->unit_weight = $unit_weight;
					$model->product_std = $product_std;
					$model->brand_std = $brand_std;
					$model->texture_std = $texture_std;
					$model->rank_std = $rank_std;
					if (!$model->insert())
					{
						$res['result'] = "error";
						$res['message'] = urlencode("件重创建失败，product_std：".$product_std."，brand_std：".$brand_std."，texture_std：".$texture_std."，rank_std：".$rank_std);
						return urldecode(json_encode($res));
					}
					break;
				case 'edit': 
					$model = DictGoods::model()->find("product_std = :product_std AND brand_std = :brand_std AND texture_std = :texture_std AND rank_std = :rank_std AND length = :length", array(':product_std' => $product_std, ':brand_std' => $brand_std, ':texture_std' => $texture_std, ':rank_std' => $rank_std, ':length' => $length));
					if (!$model)
					{
						$res['result'] = "error";
						$res['message'] = urlencode("件重数据不存在，product_std：".$product_std."，brand_std：".$brand_std."，texture_std：".$texture_std."，rank_std：".$rank_std);
						return urldecode(json_encode($res));
					}
					$model->name = $name;
					$model->short_name = $short_name;
					$model->unit_weight = $unit_weight;
					if (!$model->update())
					{
						$res['result'] = "error";
						$res['message'] = urlencode("件重修改失败，product_std：".$product_std."，brand_std：".$brand_std."，texture_std：".$texture_std."，rank_std：".$rank_std);
						return urldecode(json_encode($res));
					}
					break;
				case 'delete': 
					$model = DictGoods::model()->find("product_std = :product_std AND brand_std = :brand_std AND texture_std = :texture_std AND rank_std = :rank_std AND length = :length", array(':product_std' => $product_std, ':brand_std' => $brand_std, ':texture_std' => $texture_std, ':rank_std' => $rank_std, ':length' => $length));
					if (!$model) break;
					if (!$model->delete())
					{
						$res['result'] = "error";
						$res['message'] = urlencode("件重删除失败，product_std：".$product_std."，brand_std：".$brand_std."，texture_std：".$texture_std."，rank_std：".$rank_std);
						return urldecode(json_encode($res));
					}
					break;
				default: break;
			}
			$transaction->commit();
		} 
		catch (Exception $e)
		{
			$transaction->rollBack();//事务回滚
			$res['result'] = "error";
			$res['message'] = urlencode("件重操作失败，product_std：".$product_std."，brand_std：".$brand_std."，texture_std：".$texture_std."，rank_std：".$rank_std);
			return urldecode(json_encode($res));
		}
		return urldecode(json_encode($res));
	}
	
	
	/*
	 * simple list of price content 
	 */
	public static function getSimList($search,$ids,$type){		
		$tableHeader = array(
				array('name'=>'','class' =>"sort-disabled text-center",'width'=>"20px"),
				array('name'=>'操作','class' =>"sort-disabled",'width'=>"80px"),
				array('name'=>'名称','class' =>"flex-col  sort-disabled",'width'=>"80px"),
				array('name'=>'短名称','class' =>"flex-col   sort-disabled",'width'=>"80px"),
				array('name'=>'产地','class' =>"flex-col  sort-disabled",'width'=>"80px"),
				array('name'=>'品名','class' =>"flex-col sort-disabled",'width'=>"80px"),
				array('name'=>'材质','class' =>"flex-col sort-disabled ",'width'=>"80px"),//
				array('name'=>'规格','class' =>"flex-col sort-disabled",'width'=>"80px"),//
				array('name'=>'长度','class' =>"flex-col sort-disabled",'width'=>"80px"),//
				);
		$tableData=array();
		$model=new DictGoods();		
		$cri = new CDbCriteria();
		if (!empty($search))
		{
			
			if($search["product_id"])
			{
				$product_std = DictGoodsProperty::getStd($search["product_id"]);
				$cri->addCondition('t.product_std = :product_std');
				$cri->params[':product_std'] = $product_std;
			}
			if($search["texture_id"])
			{
				$texture_std = DictGoodsProperty::getStd($search["texture_id"]);
				$cri->addCondition('t.texture_std = :texture_std');
				$cri->params[':texture_std'] = $texture_std;
			}
			if ($search["rank_id"])
			{
				$rank_std = DictGoodsProperty::getStd($search["rank_id"]);
				$cri->addCondition('t.rank_std = :rank_std');
				$cri->params[':rank_std'] = $rank_std;
			}
			if ($search["brand_id"])
			{
				$brand_std = DictGoodsProperty::getStd($search["brand_id"]);
				$cri->addCondition('t.brand_std = :brand_std');
				$cri->params[':brand_std'] = $brand_std;
			}
			if($search['choosed'])
			{
				if($search['choosed']=='choosed')
				{
					$cri->addInCondition('t.id', $ids);
				}else{
					$cri->addNotInCondition('t.id', $ids);
				}
			}
		}
		
		$cri->select = "t.*,
		p.short_name as product_name,
		b.short_name as brand_name,
		tt.name as texture_name,
		r.name as rank_name";
		$cri->join = "LEFT JOIN dict_goods_property p ON p.std = t.product_std AND p.is_available = 1
		LEFT JOIN dict_goods_property b ON b.std = t.brand_std AND b.is_available = 1
		LEFT JOIN dict_goods_property tt ON tt.std = t.texture_std AND tt.is_available = 1
		LEFT JOIN dict_goods_property r ON r.std = t.rank_std AND r.is_available = 1";
		
		$pages = new CPagination();
		$pages->itemCount = $model->count($cri);
		$pages->pageSize = $_COOKIE['d_goods']? intval($_COOKIE['d_goods']):Yii::app()->params['pageCount'];
		$pages->applyLimit($cri);
		$str=implode(',', $ids);
		if(strlen($str)==0)$str='0';
		$cri->order='t.id not in ('.$str.'),convert(product_name using gbk) asc,texture_name';
		$details = $model->findAll($cri);
		if($details)
		{
			$i=1;
			if($type)
			{
				foreach ($details as $each)
				{
					$operate='<input class="clickme" value="'.$each->id.'" type="checkbox" '.(in_array($each->id, $ids)?'checked="checked"':'').'>';
					$da['data']=array(
							$i,
							$operate,
							$each->name,
							$each->short_name,
							$each->brand_name,
							$each->product_name,
							$each->texture_name,
							$each->rank_name,
							$each->length
					);
					$da['group']=$i;
					array_push($tableData,$da);
					$i++;
				}
			}else{
				foreach ($details as $each)
				{
					$operate='<a class="update_b del_but" title="删除"  name="'.$each->id.'" style="margin-left:5px"><img  src="/images/zuofei.png"></a>';
					$da['data']=array(
							$i,
							$operate,
							$each->name,
							$each->short_name,
							$each->brand_name,
							$each->product_name,
							$each->texture_name,
							$each->rank_name,
							$each->length
					);
					$da['group']=$i;
					array_push($tableData,$da);
					$i++;
				}
			}
		
		}
		
		return array($tableHeader,$tableData, $pages);
	}
	
	
	public  static function getPrefectureGoods($id)
	{
		$sql="select d.id from quoted_detail  q left join dict_goods d on q.brand_std=d.brand_std and q.product_std=d.product_std and q.texture_std=d.texture_std 
		  and q.rank_std=d.rank_std and q.length=d.length where prefecture=$id and type='guidance'  group by d.id";
		$connection=Yii::app()->db;
		$res=$connection->createCommand($sql)->queryAll();
		$ids=array();
		foreach ($res as $each)
		{
			array_push($ids, $each['id']);
		}
		return array_filter($ids);
	}
	
}
