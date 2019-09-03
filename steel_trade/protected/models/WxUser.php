<?php

/**
 * This is the biz model class for table "wx_user".
 *
 */
class WxUser extends WxUserData
{
	

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
				'companys'=>array(self::HAS_MANY,'WxUserCompany','user_id','condition'=>'companys.is_deleted=0'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'username' => 'Username',
			'loginname' => 'Loginname',
			'phone' => 'Phone',
			'yq_code' => 'Yq Code',
			'created_at' => 'Created At',
			'user_id' => 'User',
			'qq' => 'Qq',
			'fax' => 'Fax',
			'openid' => 'Openid',
			'pic' => 'Pic',
			'is_deleted' => 'Is Deleted',
			'is_spread' => 'Is Spread',
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
		$criteria->compare('username',$this->username,true);
		$criteria->compare('loginname',$this->loginname,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('yq_code',$this->yq_code,true);
		$criteria->compare('created_at',$this->created_at);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('qq',$this->qq,true);
		$criteria->compare('fax',$this->fax,true);
		$criteria->compare('openid',$this->openid,true);
		$criteria->compare('pic',$this->pic,true);
		$criteria->compare('is_deleted',$this->is_deleted);
		$criteria->compare('is_spread',$this->is_spread);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return WxUser the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	public function getWxuserList($search)
	{
		
		$sql='SELECT wu.*,all_com FROM wx_user wu left join (select count(*) as all_com,user_id from wx_user_company where is_deleted=0 group by user_id) as wc on wc.user_id=wu.id';
		$conditions=' where wu.is_deleted=0';
		if($search['name'])
		{
			$conditions.=" and (username like '%".$search['name']."%' or loginname like '%".$search['name']."%' or all_com like '%".$search['name']."%')";
		}
		if($search['owned_by'])
		{
			$conditions.=' and wu.user_id='.$search['owned_by'];
		}
// 		if(Yii::app()->authManager->checkAccess('业务员',currentUserId()))
// 		{
// 			$own=currentUserId();
// 			$conditions.=' and wu.user_id='.$own;
// 		}
		if($search['linked'])
		{
			if($search['linked']=='yes')$conditions.=' and wu.user_id!=0';
			else $conditions.=' and wu.user_id=0';
		}
		$sql.=$conditions;
		$connection=Yii::app()->db;
		$pages=new CPagination();
		$pages->itemCount =count($connection->createCommand($sql)->queryAll());
		$pages->pageSize = intval($_COOKIE['choose_list']) ? intval($_COOKIE['choose_list']) : Yii::app()->params['pageCount'];
		$page=$_REQUEST['page']?$_REQUEST['page']:1;
		$sql.=' limit '.($page-1)*$pages->pageSize.','.$pages->pageSize;		
		$res=$connection->createCommand($sql)->queryAll();
		return array($res,$pages);
	}


	public static function getSelectList($name,$type,$id)
	{
		$wxUser=WxUser::model()->findByPk($id);
		$selected=$wxUser->user_id;
		$tableData=array();
		if($type=="user")
		{
			$tableHeader=array(
					array('name'=>'选择','class' =>"sort-disabled",'width'=>"20px"),
					array('name'=>'登录名','class' =>"sort-disabled",'width'=>"70px"),
					array('name'=>'昵称','class' =>"flex-col sort-disabled",'width'=>"70px"),//
					array('name'=>'邀请码','class' =>"flex-col sort-disabled",'width'=>"70px"),
					array('name'=>'手机','class' =>"flex-col sort-disabled",'width'=>"70px"),
				);
				
			$model=new User();
			$criteria=new CDbCriteria();
			$criteria->compare('is_deleted',0);
			if($name)
			{
				$criteria->addCondition(" loginname like '%{$name}%'  or nickname like '%{$name}%'");
			}
			$criteria->select="id,loginname,nickname,phone,invit_code";
			$criteria->join=' left join authassignment a on a.userid=t.id and a.itemname="业务员"';
			$criteria->addCondition('a.itemname is not null');
			$pages=new CPagination();
			$pages->itemCount = $model->count($criteria);
			$pages->pageSize = intval($_COOKIE['choose_list']) ? intval($_COOKIE['choose_list']) : Yii::app()->params['pageCount'];
			$pages->applyLimit($criteria);			
			$criteria->order='id!='.$selected.' ,convert(nickname using gbk) asc';
			$details=$model->findAll($criteria);
			if($details)
			{
				$i=1;
				foreach ($details as $each)
				{
					$input='<input class="user_select" name="user_select" type="radio"  '.($each->id==$selected?'checked="checked"':'').' value="'.$each->id.'">';
					$da['data']=array(
							$input,
							$each->loginname,
							$each->nickname,
							$each->invit_code,
							$each->phone,
					);
					$da['group']=$i;
					$i++;
					array_push($tableData, $da);
				}
			}			
		}elseif($type=="company")
		{
			$wx_user_companys=$wxUser->companys;
			$selected=array();
			if($wx_user_companys)
			{
				foreach ($wx_user_companys as $ea)
				{
					array_push($selected,$ea->company_id);
				}
			}
			$string=implode(',', $selected);
			if(strlen($string)==0)$string='0';
			$tableHeader=array(
					array('name'=>'选择','class' =>"sort-disabled",'width'=>"20px"),
					array('name'=>'名称','class' =>"sort-disabled",'width'=>"80px"),
					array('name'=>'短名称','class' =>"flex-col sort-disabled",'width'=>"80px"),//
			);
			$model=new DictCompany();
			$criteria=new CDbCriteria();
			if($name)
			{
				$criteria->addCondition(" name like '%{$name}%'  or short_name like '%{$name}%'");
			}
			$criteria->select="id,name,short_name";
			$pages=new CPagination();
			$pages->itemCount = $model->count($criteria);
			$pages->pageSize = intval($_COOKIE['choose_list']) ? intval($_COOKIE['choose_list']) : Yii::app()->params['pageCount'];
			$pages->applyLimit($criteria);
			$criteria->order='id not in ('.$string.'),convert(name using gbk) asc';
			$details=$model->findAll($criteria);
			if($details)
			{
				$i=1;
				foreach ($details as $each)
				{
					$input='<input class="com_select" name="com_select" type="checkbox" '.(in_array($each->id, $selected)?'checked="checked"':'').' value="'.$each->id.'">';
					$da['data']=array(
							$input,
							$each->name,
							$each->short_name,
					);
					$da['group']=$i;
					$i++;
					array_push($tableData, $da);
				}
			}
			$selected=implode(',', $selected);
			
		}	
		return array($tableHeader,$tableData,$pages,$selected);
	}
	
	
	/*
	 * save link data 
	 */
	public function saveLink($model,$type,$selected)
	{
		if($type=='user')
		{
			$model->user_id=$selected;
			if($model->update())return 1;
			else 
				return '数据库操作失败';
		}elseif($type=='company'){
			$companys=$model->companys;
			$selectedss=array();
			if($companys)
			{
				foreach ($companys as $ea)
				{
					array_push($selectedss,$ea->company_id);
				}
			}
			//new data
			$selected_array=explode(',', $selected);
			foreach ($selected_array as $each)
			{
				if($each=='')continue;
				if(!in_array($each, $selectedss))
				{
					$dict_c=DictCompany::model()->findByPk($each);
					if(!$dict_c)continue;
					$com=new WxUserCompany();
					$com->user_id=$model->id;
					$com->company=$dict_c->name;
					$com->created_at=time();
					$com->company_id=$each;
					$com->insert();
				}
			}
			
			//delete
			$diff=array_diff($selectedss,$selected_array);
			foreach ($diff as $ea_diff)
			{
				$comp=WxUserCompany::model()->find('user_id='.$model->id.' and company_id='.$ea_diff.' and is_deleted=0');
				if($comp)
				{
					$comp->is_deleted=1;
					$comp->update();
				}
			}
			$connection=Yii::app()->db;
			$sql='update wx_user_company set is_deleted=1 where user_id='.$model->id.' and company_id=0';
			$connection->createCommand($sql)->execute();

			return  1;
		}
	}
	
	//
	public function updateData($post){
		$this->attributes = $post;
		$bool = WxUser::model()->exists("id<>$this->id and loginname='{$this->loginname}'");
		if($bool){
			return -1;
		}else{
			return $this->save();
		}
	}

}
