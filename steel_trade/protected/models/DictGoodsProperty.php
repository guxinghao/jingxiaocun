<?php
/**
 * This is the biz model class for table "dict_goods_property".
 *
 */
class DictGoodsProperty extends DictGoodsPropertyData
{
	

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
			'property_type' => 'Property Type',
			'name' => 'Name',
			'short_name' => 'Short Name',
			'code' => 'Code',
			'std' => 'Std',
			'last_update' => 'Last Update',
			'reserve' => 'Reserve',
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
		$criteria->compare('property_type',$this->property_type,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('short_name',$this->short_name,true);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('std',$this->std,true);
		$criteria->compare('last_update',$this->last_update);
		$criteria->compare('reserve',$this->reserve,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DictGoodsProperty the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public static function getProList($type,$str="array",$id_array="")
	{
		if($id_array=="all")
		{
			$products=DictGoodsProperty::model()->findAllByAttributes(array('property_type'=>$type),array('order'=>'priority asc'));
		}elseif (empty($id_array)){
			$products=DictGoodsProperty::model()->findAllByAttributes(array('property_type'=>$type,'is_available'=>'1'),array('order'=>'priority asc'));
		}elseif(is_array($id_array)){
			$id_str = implode(",",$id_array);
			$products=DictGoodsProperty::model()->findAll("(is_available=1 or id in (".$id_str.")) and property_type='".$type."'".' order by priority asc');
		}else{
			return false;
		}
		$products_array = array();
			if($str=="array"){
				foreach ($products as $product){
					$products_array[$product->id]=$product->short_name;
				}
			}else{
				$temp=array();
				foreach ($products as $product)
				{
					$temp['id']="$product->id";
					$temp['bs']="$product->code";
					$temp['name']="$product->short_name";
					array_push($products_array, $temp);
				}
				$products_array=json_encode($products_array);
			}	
		return $products_array;
	}
	
	/**
	 * 获取属性名简称
	 * @param integer $id
	 * @return string
	 */
	public static function getProName($id)
	{
		$model = DictGoodsProperty::model()->findByPk($id);
		return $model ? $model->short_name : '';		
	}
	
	/**
	 * 获取属性名全名
	 * @param integer $id
	 * @return string
	 */
	public static function getFullProName($id)
	{
		$model = DictGoodsProperty::model()->findByPk($id);
		return $model ? $model->name : '';
	}
	
	/**
	 * 获取std
	 * @param integer $id
	 * @return string
	 */
	public static function getStd($id)
	{
		$model = DictGoodsProperty::model()->findByPk($id);
		return $model ? $model->std : '';
	}
	
	/**
	 * 获取id
	 * @param string $std
	 * @return string
	 */
	public static function getId($std)
	{
		$model = DictGoodsProperty::model()->find("std = :std AND is_available = 1", array(':std' => $std));
		return $model ? $model->id : '';
	}
	
	
	public static function getIdByName($name)
	{
		$model = DictGoodsProperty::model()->find("short_name = :name AND is_available = 1", array(':name' => $name));
		return $model ? $model->id : '';
	}
	
	public static function getIndexList(){
		$model = new DictGoodsProperty();
		
		$cri = new CDbCriteria();
		$search =  new DictGoodsProperty();
		if($_REQUEST['property_type']){
			$cri->params = array(":property_type"=>$_REQUEST['property_type']);
		}
		if($_POST['DictGoodsProperty']){
			$search->attributes = $_POST['DictGoodsProperty'];
			if($search->name){
				$cri->params = array_merge($cri->params,array(":name"=>"%".$search->name."%"));
				$cri->addCondition("name like :name or short_name like :name or code like :name or std like :name");
			}
		}
		if($_REQUEST['property_type']){
			$cri->addCondition("property_type = :property_type");
		}
		$cri->order = "priority";
		$pages = new CPagination();
		$pages->itemCount = $model->count($cri);
		$pages->pageSize = $_COOKIE['d_goodsd']? intval($_COOKIE['d_goodsd']):Yii::app()->params['pageCount'];
		$pages->applyLimit($cri);
		$items = $model->findAll($cri);
		return array($model,$search,$pages,$items);
	}
	
	
	
	/*
	 * 根据产地获取级联信息
	 */
	public static function brandSet($post)
	{
		$id=$post['id'];
		$brand=$post['brand'];
		$product=$post['product'];
		$texture=$post['texture'];
		$rank=$post['rank'];
		$oldBrand=$post['brandOld'];
		$oldProduct=$post['productOld'];
		$oldTexture=$post['textureOld'];
		$oldRank=$post['rankOld'];
		$model=DictGoodsProperty::model()->findByPk($id);
		$std=$model->std;		
		$condition=array();		
		if($std)
		{
			$condition['brand_std']=$std;			
		}		
		if($product!='')
		{
			$model_pro=DictGoodsProperty::model()->findByPk($product);
			$condition['product_std']=$model_pro->std;
		}
		if($texture!='')
		{
			$model_tex=DictGoodsProperty::model()->findByPk($texture);
			$condition['texture_std']=$model_tex->std;
		}
		if($rank!='')
		{
			$model_ra=DictGoodsProperty::model()->findByPk($rank);
			$condition['rank_std']=$model_ra->std;
		}
		
		$product_con=$condition;
		$texture_con=$condition;
		$rank_con=$condition;		
		$pro_stds=array();
		$tex_stds=array();
		$ra_stds=array();
		$lengs=array();
		
		$pros=array();
		$texs=array();
		$ras=array();
		unset($product_con['product_std']);
		$products=DictGoods::model()->findAllByAttributes($product_con);
		foreach ($products as $each)
		{
			if(!in_array($each->product_std, $pro_stds))
			{
				if($oldProduct&&$oldProduct!='undefined')
				{
					$sql='select id,short_name from (select id,short_name,is_available from dict_goods_property where std="'.$each->product_std.'") as temp_t where is_available=1 or id='.$oldProduct;
					$pro=DictGoodsProperty::model()->findAllBySql($sql);
					if($pro)
					{
						foreach ($pro as $ea)
						{
							$pros[$ea->id]=$ea->short_name;
						}
					}					
				}else{
					$pro=DictGoodsProperty::model()->find('std="'.$each->product_std.'" and is_available=1');
					if($pro)
					{
						$pros[$pro->id]=$pro->short_name;
					}					
				}				
				array_push($pro_stds,$each->product_std);
			}
		}		
		unset($texture_con['texture_std']);
		$textures=DictGoods::model()->findAllByAttributes($texture_con);
		foreach ($textures as $each)
		{
			if(!in_array($each->texture_std, $tex_stds)){
				if($oldTexture&&$oldTexture!='undefined')
				{
					$sql='select id,short_name from (select id,short_name,is_available from dict_goods_property where std="'.$each->texture_std.'") as temp_t where is_available=1 or id='.$oldTexture;
					$tex=DictGoodsProperty::model()->findAllBySql($sql);
					if($tex)
					{
						foreach ($tex as $ea)
						{
							$texs[$ea->id]=$ea->short_name;
						}
					}					
				}else{
					$tex=DictGoodsProperty::model()->find('std="'.$each->texture_std.'" and is_available=1');
					if($tex)
					{
						$texs[$tex->id]=$tex->short_name;
					}					
				}				
				array_push($tex_stds,$each->texture_std);
			}
		}		
		unset($rank_con['rank_std']);
		$ranks=DictGoods::model()->findAllByAttributes($rank_con);
		foreach ($ranks as $each)
		{
			if(!in_array($each->rank_std, $ra_stds)){
				if($oldRank&&$oldRank!='undefined')
				{
					$sql='select id,short_name from (select id,short_name,is_available from dict_goods_property where std="'.$each->rank_std.'") as temp_t where is_available=1 or id='.$oldRank;
					$ra=DictGoodsProperty::model()->findAllBySql($sql);
					if($ra)
					{
						foreach ($ra as $ea)
						{
							$ras[$ea->id]=$ea->short_name;
						}
					}					
				}else{
					$ra=DictGoodsProperty::model()->find('std="'.$each->rank_std.'" and is_available=1');
					if($ra)
					{
						$ras[$ra->id]=$ra->short_name;
					}					
				}				
				array_push($ra_stds,$each->rank_std);
			}
		}		
		$goods=DictGoods::model()->findAllByAttributes($condition);		
		if($goods)
		{
			$lengs=array();
			foreach ($goods as $each)
			{
				if(!in_array($each->length, $lengs)){
					$lengs[]=$each->length;
				}
			}		
		}
		$data='<option></option>';
		foreach ($pros as $k=>$v)
		{
			$data.='<option value="'.$k.'">'.$v.'</option>';
		}
		$data.='o1@o<option></option>';
		asort($texs);
		foreach ($texs as $k=>$v)
		{
			$data.='<option value="'.$k.'">'.$v.'</option>';
		}
		$data.='o2@o<option></option>';
		$ran_arr=sortRank($ras);
		foreach ( $ran_arr  as $k=>$v)
		{
			$data.='<option value="'.$k.'">'.$v.'</option>';
		}
		$data.='o3@o';
		if(count($lengs)==1)
		{
			$data.=$lengs[0];
		}
		return $data;
	}
	
	/*
	 * 品名变换改变级联
	 */
	public static function productSet($post)
	{
		$id=$post['id'];
		$brand=$post['brand'];
		$product=$post['product'];
		$texture=$post['texture'];
		$rank=$post['rank'];
		$oldBrand=$post['brandOld'];
		$oldProduct=$post['productOld'];
		$oldTexture=$post['textureOld'];
		$oldRank=$post['rankOld'];
		$model=DictGoodsProperty::model()->findByPk($id);
		$std=$model->std;
		$condition=array();
		if($std)
		{
			$condition['product_std']=$std;
		}
		if($brand!='')
		{
			$model_pro=DictGoodsProperty::model()->findByPk($brand);
			$condition['brand_std']=$model_pro->std;
		}
		if($texture!='')
		{
			$model_tex=DictGoodsProperty::model()->findByPk($texture);
			$condition['texture_std']=$model_tex->std;
		}
		if($rank!='')
		{
			$model_ra=DictGoodsProperty::model()->findByPk($rank);
			$condition['rank_std']=$model_ra->std;
		}
		
		$brand_con=$condition;
		$texture_con=$condition;
		$rank_con=$condition;
		$bra_stds=array();
		$tex_stds=array();
		$ra_stds=array();
		$lengs=array();
		
		$bras=array();
		$texs=array();
		$ras=array();
		unset($brand_con['brand_std']);
		$brands=DictGoods::model()->findAllByAttributes($brand_con);
		foreach ($brands as $each)
		{
			if(!in_array($each->brand_std, $bra_stds))
			{
				if($oldBrand&&$oldBrand!='undefined')
				{
					$sql='select id,code,short_name from (select id,code,short_name,is_available from dict_goods_property where std="'.$each->brand_std.'") as temp_t where is_available=1 or id='.$oldBrand;
					$bra=DictGoodsProperty::model()->findAllBySql($sql);
					if($bra)
					{
						foreach ($bra as $ea)
						{
							$temp=array();
							$temp['id']="$ea->id";
							$temp['bs']="$ea->code";
							$temp['name']="$ea->short_name";
							array_push($bras, $temp);
						}
					}
				}else{
					$bra=DictGoodsProperty::model()->find('std="'.$each->brand_std.'" and is_available=1');
					if($bra)
					{
						$temp=array();
						$temp['id']="$bra->id";
						$temp['bs']="$bra->code";
						$temp['name']="$bra->short_name";
						array_push($bras, $temp);
					}					
				}				
				array_push($bra_stds,$each->brand_std);
			}
		}
		unset($texture_con['texture_std']);
		$textures=DictGoods::model()->findAllByAttributes($texture_con);
		foreach ($textures as $each)
		{
			if(!in_array($each->texture_std, $tex_stds)){
				
				if($oldTexture&&$oldTexture!='undefined')
				{
					$sql='select id,short_name from (select id,short_name,is_available from dict_goods_property where std="'.$each->texture_std.'") as temp_t where is_available=1 or id='.$oldTexture;
					$tex=DictGoodsProperty::model()->findAllBySql($sql);
					if($tex)
					{
						foreach ($tex as $ea)
						{
							$texs[$ea->id]=$ea->short_name;
						}
					}
				}else{
					$tex=DictGoodsProperty::model()->find('std="'.$each->texture_std.'" and is_available=1');
					if($tex)
					{
						$texs[$tex->id]=$tex->short_name;
					}
				}
				array_push($tex_stds,$each->texture_std);
			}
		}
		unset($rank_con['rank_std']);
		$ranks=DictGoods::model()->findAllByAttributes($rank_con);
		foreach ($ranks as $each)
		{
			if(!in_array($each->rank_std, $ra_stds)){
				if($oldRank&&$oldRank!='undefined')
				{
					$sql='select id,short_name from (select id,short_name,is_available from dict_goods_property where std="'.$each->rank_std.'") as temp_t where is_available=1 or id='.$oldRank;
					$ra=DictGoodsProperty::model()->findAllBySql($sql);
					if($ra)
					{
						foreach ($ra as $ea)
						{
							$ras[$ea->id]=$ea->short_name;
						}
					}					
				}else{
					$ra=DictGoodsProperty::model()->find('std="'.$each->rank_std.'" and is_available=1');
					if($ra)
					{
						$ras[$ra->id]=$ra->short_name;
					}					
				}				
				array_push($ra_stds,$each->rank_std);
			}
		}
		$goods=DictGoods::model()->findAllByAttributes($condition);
		if($goods)
		{
			$lengs=array();
			foreach ($goods as $each)
			{
				if(!in_array($each->length, $lengs)){
					$lengs[]=$each->length;
				}
			}
		}
		$data='';
		$data.=json_encode($bras);
		$data.='o1@o<option></option>';
		asort($texs);
		foreach ($texs as $k=>$v)
		{
			$data.='<option value="'.$k.'">'.$v.'</option>';
		}
		$data.='o2@o<option></option>';		
		$ran_arr=sortRank($ras);
		foreach ( $ran_arr  as $k=>$v)
		{
			$data.='<option value="'.$k.'">'.$v.'</option>';
		}
		$data.='o3@o';
		if(count($lengs)==1)
		{
			$data.=$lengs[0];
		}		
		return $data;
	}
	
	
	
	/*
	 * 材质变换改变级联
	 */
	public static function textureSet($post)
	{
		$id=$post['id'];
		$brand=$post['brand'];
		$product=$post['product'];
		$texture=$post['texture'];
		$rank=$post['rank'];
		$oldBrand=$post['brandOld'];
		$oldProduct=$post['productOld'];
		$oldTexture=$post['textureOld'];
		$oldRank=$post['rankOld'];
		$model=DictGoodsProperty::model()->findByPk($id);
		$std=$model->std;
		$condition=array();
		if($std)
		{
			$condition['texture_std']=$std;
		}
		if($product!='')
		{
			$model_pro=DictGoodsProperty::model()->findByPk($product);
			$condition['product_std']=$model_pro->std;
		}
		if($brand!='')
		{
			$model_tex=DictGoodsProperty::model()->findByPk($brand);
			$condition['brand_std']=$model_tex->std;
		}
		if($rank!='')
		{
			$model_ra=DictGoodsProperty::model()->findByPk($rank);
			$condition['rank_std']=$model_ra->std;
		}
		
		$brand_con=$condition;
		$product_con=$condition;
		$rank_con=$condition;
		$bra_stds=array();
		$pro_stds=array();
		$ra_stds=array();
		$lengs=array();
		
		$pros=array();
		$bras=array();
		$ras=array();
		unset($brand_con['brand_std']);
		$brands=DictGoods::model()->findAllByAttributes($brand_con);
		foreach ($brands as $each)
		{
			if(!in_array($each->brand_std, $bra_stds))
			{
				if($oldBrand&&$oldBrand!='undefined')
				{
					$sql='select id,code,short_name from (select id,code,short_name,is_available from dict_goods_property where std="'.$each->brand_std.'") as temp_t where is_available=1 or id='.$oldBrand;
					$bra=DictGoodsProperty::model()->findAllBySql($sql);
					if($bra)
					{
						foreach ($bra as $ea)
						{
							$temp=array();
							$temp['id']="$ea->id";
							$temp['bs']="$ea->code";
							$temp['name']="$ea->short_name";
							array_push($bras, $temp);
						}
					}
				}else{
					$bra=DictGoodsProperty::model()->find('std="'.$each->brand_std.'" and is_available=1');
					if($bra)
					{
						$temp=array();
						$temp['id']="$bra->id";
						$temp['bs']="$bra->code";
						$temp['name']="$bra->short_name";
						array_push($bras, $temp);
					}					
				}				
				array_push($bra_stds,$each->brand_std);
			}
		}
		unset($product_con['product_std']);
		$products=DictGoods::model()->findAllByAttributes($product_con);
		foreach ($products as $each)
		{
			if(!in_array($each->product_std, $pro_stds))
			{
				if($oldProduct&&$oldProduct!='undefined')
				{
					$sql='select id,short_name from (select id,short_name,is_available from dict_goods_property where std="'.$each->product_std.'") as temp_t where is_available=1 or id='.$oldProduct;
					$pro=DictGoodsProperty::model()->findAllBySql($sql);
					if($pro)
					{
						foreach ($pro as $ea)
						{
							$pros[$ea->id]=$ea->short_name;
						}
					}
				}else{
					$pro=DictGoodsProperty::model()->find('std="'.$each->product_std.'" and is_available=1');
					if($pro)
					{
						$pros[$pro->id]=$pro->short_name;
					}
				}
				array_push($pro_stds,$each->product_std);
			}
		}
		unset($rank_con['rank_std']);
		$ranks=DictGoods::model()->findAllByAttributes($rank_con);
		foreach ($ranks as $each)
		{
			if(!in_array($each->rank_std, $ra_stds)){
				if($oldRank&&$oldRank!='undefined')
				{
					$sql='select id,short_name from (select id,short_name,is_available from dict_goods_property where std="'.$each->rank_std.'") as temp_t where is_available=1 or id='.$oldRank;
					$ra=DictGoodsProperty::model()->findAllBySql($sql);
					if($ra)
					{
						foreach ($ra as $ea)
						{
							$ras[$ea->id]=$ea->short_name;
						}
					}					
				}else{
					$ra=DictGoodsProperty::model()->find('std="'.$each->rank_std.'" and is_available=1');
					if($ra)
					{
						$ras[$ra->id]=$ra->short_name;
					}					
				}				
				array_push($ra_stds,$each->rank_std);
			}
		}
		$goods=DictGoods::model()->findAllByAttributes($condition);
		if($goods)
		{
			$lengs=array();
			foreach ($goods as $each)
			{
				if(!in_array($each->length, $lengs)){
					$lengs[]=$each->length;
				}
			}
		}
		$data='';
		$data.=json_encode($bras);
		$data.='o1@o<option></option>';
		foreach ($pros as $k=>$v)
		{
			$data.='<option value="'.$k.'">'.$v.'</option>';
		}
		$data.='o2@o<option></option>';
		$ran_arr=sortRank($ras);
		foreach ( $ran_arr  as $k=>$v)
		{
			$data.='<option value="'.$k.'">'.$v.'</option>';
		}
		$data.='o3@o';
		if(count($lengs)==1)
		{
			$data.=$lengs[0];
		}
	return $data;
	
	}
	
	/*
	 * 规格变换改变级联
	 */
	public static function rankSet($post)
	{	
		$id=$post['id'];
		$brand=$post['brand'];
		$product=$post['product'];
		$texture=$post['texture'];
		$rank=$post['rank'];
		$oldBrand=$post['brandOld'];
		$oldProduct=$post['productOld'];
		$oldTexture=$post['textureOld'];
		$oldRank=$post['rankOld'];
		$model=DictGoodsProperty::model()->findByPk($id);
		$std=$model->std;
		$condition=array();
		if($std)
		{
			$condition['rank_std']=$std;
		}
		if($product!='')
		{
			$model_pro=DictGoodsProperty::model()->findByPk($product);
			$condition['product_std']=$model_pro->std;
		}
		if($texture!='')
		{
			$model_tex=DictGoodsProperty::model()->findByPk($texture);
			$condition['texture_std']=$model_tex->std;
		}
		if($brand!='')
		{
			$model_ra=DictGoodsProperty::model()->findByPk($brand);
			$condition['brand_std']=$model_ra->std;
		}
		
		$brand_con=$condition;
		$product_con=$condition;
		$texture_con=$condition;
		$bra_stds=array();
		$pro_stds=array();
		$tex_stds=array();
		$lengs=array();
		
		$pros=array();
		$bras=array();
		$texs=array();
		unset($brand_con['brand_std']);
		$brands=DictGoods::model()->findAllByAttributes($brand_con);
		foreach ($brands as $each)
		{
			if(!in_array($each->brand_std, $bra_stds))
			{
				if($oldBrand&&$oldBrand!='undefined')
				{
					$sql='select id,code,short_name from (select id,code,short_name,is_available from dict_goods_property where std="'.$each->brand_std.'") as temp_t where is_available=1 or id='.$oldBrand;
					$bra=DictGoodsProperty::model()->findAllBySql($sql);
					if($bra)
					{
						foreach ($bra as $ea)
						{
							$temp=array();
							$temp['id']="$ea->id";
							$temp['bs']="$ea->code";
							$temp['name']="$ea->short_name";
							array_push($bras, $temp);
						}
					}
				}else{
					$bra=DictGoodsProperty::model()->find('std="'.$each->brand_std.'" and is_available=1');
					if($bra)
					{
						$temp=array();
						$temp['id']="$bra->id";
						$temp['bs']="$bra->code";
						$temp['name']="$bra->short_name";
						array_push($bras, $temp);
					}					
				}				
				array_push($bra_stds,$each->brand_std);
			}
		}
		unset($product_con['product_std']);
		$products=DictGoods::model()->findAllByAttributes($product_con);
		foreach ($products as $each)
		{
			if(!in_array($each->product_std, $pro_stds))
			{
				if($oldProduct&&$oldProduct!='undefined')
				{
					$sql='select id,short_name from (select id,short_name,is_available from dict_goods_property where std="'.$each->product_std.'") as temp_t where is_available=1 or id='.$oldProduct;
					$pro=DictGoodsProperty::model()->findAllBySql($sql);
					if($pro)
					{
						foreach ($pro as $ea)
						{
							$pros[$ea->id]=$ea->short_name;
						}
					}					
				}else{
					$pro=DictGoodsProperty::model()->find('std="'.$each->product_std.'" and is_available=1');
					if($pro)
					{
						$pros[$pro->id]=$pro->short_name;
					}					
				}	
				array_push($pro_stds,$each->product_std);
			}
		}
		unset($texture_con['texture_std']);
		$textures=DictGoods::model()->findAllByAttributes($texture_con);
		foreach ($textures as $each)
		{
			if(!in_array($each->texture_std, $tex_stds)){
				if($oldTexture&&$oldTexture!='undefined')
				{
					$sql='select id,short_name from (select id,short_name,is_available from dict_goods_property where std="'.$each->texture_std.'") as temp_t where is_available=1 or id='.$oldTexture;
					$tex=DictGoodsProperty::model()->findAllBySql($sql);
					if($tex)
					{
						foreach ($tex as $ea)
						{
							$texs[$ea->id]=$ea->short_name;
						}
					}
				}else{
					$tex=DictGoodsProperty::model()->find('std="'.$each->texture_std.'" and is_available=1');
					if($tex)
					{
						$texs[$tex->id]=$tex->short_name;
					}
				}
				array_push($tex_stds,$each->texture_std);
			}
		}
		$goods=DictGoods::model()->findAllByAttributes($condition);
		if($goods)
		{
			$lengs=array();
			foreach ($goods as $each)
			{
				if(!in_array($each->length, $lengs)){
					$lengs[]=$each->length;
				}
			}
		}
		$data='';
		$data.=json_encode($bras);
		$data.='o1@o<option></option>';
		foreach ($pros as $k=>$v)
		{
			$data.='<option value="'.$k.'">'.$v.'</option>';
		}
		$data.='o2@o<option></option>';
		asort($texs);
		foreach ( $texs  as $k=>$v)
		{
			$data.='<option value="'.$k.'">'.$v.'</option>';
		}
		$data.='o3@o';
		if(count($lengs)==1)
		{
			$data.=$lengs[0];
		}
	return $data;
	
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
			if ($columns[$i]->Text == '名称') { $name = $fields[$i]->Text; $api_property_id = $fields[$i]->Value; }
			if ($columns[$i]->Text == '简称') $short_name = $fields[$i]->Text;
			if ($columns[$i]->Text == '类型') $property_type = $fields[$i]->Text;
			if ($columns[$i]->Text == '助记码') $code = $fields[$i]->Text;
			if ($columns[$i]->Text == 'STD') $std = $fields[$i]->Text;
			if ($columns[$i]->Text == '是否启用') $is_available = $fields[$i]->Text;
		}
		
		$transaction = Yii::app()->db->beginTransaction();
		try {
			switch ($body->Verb) 
			{
				case 'add': 
					$model = new DictGoodsProperty();
					$model->name = $name;
					$model->short_name = $short_name;
					$model->property_type = $property_type;
					$model->code = $code;
					$model->std = $std;
					$model->last_update = time();
					$model->is_available = $is_available;
					if (!$model->insert())
					{
						$res['result'] = "error";
						$res['message'] = urlencode("创建失败，std：".$std);
						return urldecode(json_encode($res));
					}
					
					//映射
					$relation = new DictGoodPropertyRelation();
					$relation->attributes = array('jxc_property_id' => $model->id, 'api_property_id' => $api_property_id, 'property_type' => $property_type);
					if (!$relation->insert())
					{
						$res['result'] = "error";
						$res['message'] = urlencode("映射失败，std：".$std);
						return urldecode(json_encode($res));
					}
					break;
				case 'edit': 
					$model = DictGoodsProperty::model()->find("std = :std AND is_available = 1", array(':std' => $std));
					if (!$model) 
					{
						$res['result'] = "error";
						$res['message'] = urlencode("数据不存在，std：".$std);
						return urldecode(json_encode($res));
					}
					$model->name = $name;
					$model->short_name = $short_name;
					$model->property_type = $property_type;
					$model->code = $code;
					$model->std = $std;
					$model->last_update = time();
					$model->is_available = $is_available;
					if (!$model->update()) 
					{
						$res['result'] = "error";
						$res['message'] = urlencode("修改失败，std：".$std);
						return urldecode(json_encode($res));
					}
					break;
				case 'delete': 
					$model = DictGoodsProperty::model()->find("std = :std", array(':std' => $std));
					if (!$model) break;
					$relation = DictGoodPropertyRelation::model()->find("property_type = :property_type AND jxc_property_id = :jxc_property_id", array(':property_type' => $model->property_type, ':jxc_property_id' => $model->id));
					if (!$model->delete())
					{
						$res['result'] = "error";
						$res['message'] = urlencode("删除失败，std：".$std);
						return urldecode(json_encode($res));
					}
					$relation->delete();
					break;
				default: break;
			}
			$transaction->commit();
		} 
		catch (Exception $e)
		{
			$transaction->rollBack();//事务回滚
			$res['result'] = "error";
			$res['message'] = urlencode("操作失败，std：".$std);
			return urldecode(json_encode($res));
		}
		return urldecode(json_encode($res));
	}

	/*
	 * 更新品名，规格，材质，产地等属性表
	 */
	public static function setGoods($data)
	{
		$has = DictGoodsProperty::model()->find("std='".$data["std"]."' and is_available=".$data["is_available"]);
		if($has){
			return 0;
		}
		$model = new DictGoodsProperty();
		$model->name = $data["name"];
		$model->short_name = $data["short_name"];
		$model->code = $data["code"];
		$model->std = $data["std"];
		$model->is_available = $data["is_available"];
		$model->property_type = $data["property_type"];
		$model->last_update = time();
		if($model->insert()){
			return $model->id;
		}else{
			return -1;
		}
	}
	
	
	/**
	 * 根据名称获取std
	 */
	public static function getStdByName($name)
	{
		$name = trim($name);
		$std = DictGoodsProperty::model()->find("name = '".$name."'")->std;
		return $std;
	}
}
