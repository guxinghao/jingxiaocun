<?php

/**
 * This is the biz model class for table "user".
 *
 */
class User extends UserData
{
	
	public $count;

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'team'=>array(self::BELONGS_TO, 'Team', 'team_id'), 
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'nickname' => 'Nickname',
			'loginname' => 'Loginname',
			'phone' => 'Phone',
			'password' => 'Password',
			'created_at' => 'Created At',
			'last_login_at' => 'Last Login At',
			'last_login_ip' => 'Last Login Ip',
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
		$criteria->compare('nickname',$this->nickname,true);
		$criteria->compare('loginname',$this->loginname,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('created_at',$this->created_at);
		$criteria->compare('last_login_at',$this->last_login_at);
		$criteria->compare('last_login_ip',$this->last_login_ip,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/*
	 * 获取用户姓名
	 */
	public static function getUserName($id)
	{
		$name='';
		if(intval($id))
		{
			$user=User::model()->findByPk($id);
			if($user)
			{
				$name=$user->nickname;
			}
		}
		return $name;
	}
	
	
	/*
	 * 获取用户列表
	 */
	public static function getUserList($type = "array", $salesman='yes',$id="")
	{
		if($id){
			$contact="select * from user where  is_deleted = 0 and team_id=".$id.' order by convert(nickname using gbk) ASC';
		}else{
			if($salesman=='yes')
			{
				$contact="select user.* from user left join authassignment a on user.id=a.userid where  a.itemname='业务员'  and  is_deleted = 0 order by  convert(nickname using gbk) ASC";
			}else {
				$contact="select * from user where is_deleted=0  order by convert(nickname using gbk) ASC";
			}			
		}
		$users = User::model()->findAllBySql($contact);
		if ($users)
		{
			$return = array();
			if ($type == "array")
			{
				foreach ($users as $user)
				{
					$return[$user->id] = $user->nickname;
				}
			}
			elseif ($type == "json")
			{				
				foreach ($users as $user)
				{
					$temp = array();
					$temp['id']="$user->id";
					$temp['bs']="$user->nickname";
					$temp['name']="$user->nickname";
					array_push($return, $temp);
				}
				$return = json_encode($return);
			}
		}
		return $return;
	}
	
	/*
	 * 获取财务系统用户列表
	 */
	public static function getCWUserList($type = "array")
	{
		$contact="is_deleted = 0 order by priority ASC,convert(nickname using gbk) ASC";
		$users = User::model()->findAll($contact);
		if ($users)
		{
			$return = array();
			if ($type == "array")
			{
				foreach ($users as $user)
				{
					$return[$user->id] = $user->nickname;
				}
			}
			elseif ($type == "json")
			{
				foreach ($users as $user)
				{
					$temp = array();
					$temp['id']="$user->id";
					$temp['bs']="$user->nickname";
					$temp['name']="$user->nickname";
					array_push($return, $temp);
				}
				$return = json_encode($return);
			}
		}
		return $return;
	}
	
	public static function getTeam($id) 
	{
		$user = User::model()->findByPk($id);
		return $user->team;
	}
	
	
	public static function getIndexList(){
		$model = User::model();
		$cri = new CDbCriteria();
		$cri->addCondition("is_deleted != 1");
		if($_REQUEST['name'])
		{
			$cri->addCondition("loginname like :name or nickname like :name");
			$cri->params[':name'] = "%".strtolower($_REQUEST['name'])."%";
		}
		$cri->order = "created_at desc";
		$cri->join='left join (select user_id,count(*) as count from wx_user wu group by user_id) temp on temp.user_id=t.id';
		$cri->select='t.*,temp.*';
		$pages = new CPagination();
		$pages->itemCount = $model->count($cri);
		$pages->pageSize = $_COOKIE['user']?intval($_COOKIE['user']):Yii::app()->params['pageCount'];
		$pages->applyLimit($cri);
		$items = $model->findAll($cri);
		return array($model,$pages,$items);
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
			if ($columns[$i]->Text == 'unid') $unid = $fields[$i]->Text;
			if ($columns[$i]->Text == '登录名') $loginname = $fields[$i]->Text;
			if ($columns[$i]->Text == '密码') $password = $fields[$i]->Text;
			if ($columns[$i]->Text == '昵称') $nickname = $fields[$i]->Text;
		}
		
		$transaction = Yii::app()->db->beginTransaction();
		try {
			switch ($body->Verb)
			{
				case 'add':
					$user_count = User::model()->count("unid = :unid", array(':unid' => $unid));
					if ($user_count > 0)
					{
						$res['result'] = "error";
						$res['message'] = urlencode("用户已存在，unid：".$unid);
						return urldecode(json_encode($res));
					}
					$model = new User();
					$model->unid = $unid;
					$model->loginname = $loginname;
					$model->password = $password;
					$model->nickname = $nickname;
					if (!$model->insert())
					{
						$res['result'] = "error";
						$res['message'] = urlencode("用户创建失败，unid：".$unid);
						return urldecode(json_encode($res));
					}else{
						$model->invit_code = str_pad($model->id,4,"0",STR_PAD_LEFT);
						$model->update();
					}
					break;
				case 'edit':
					$model = User::model()->find("unid = :unid", array(':unid' => $unid));
					if (!$model) 
					{
						$res['result'] = "error";
						$res['message'] = urlencode("用户不存在，unid：".$unid);
						return urldecode(json_encode($res));
					}
					$model->loginname = $loginname;
					$model->password = $password;
					$model->nickname = $nickname;
					if (!$model->update())
					{
						$res['result'] = "error";
						$res['message'] = urlencode("用户修改失败，unid：".$unid);
						return urldecode(json_encode($res));
					}
					break;
				case 'delete':
					$model = User::model()->find("unid = :unid", array(':unid' => $unid));
					if (!$model) break;
					if (!$model->delete())
					{
						$res['result'] = "error";
						$res['message'] = urlencode("用户删除失败，unid：".$unid);
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
			$res['message'] = urlencode("用户操作失败，unid：".$unid);
			return urldecode(json_encode($res));
		}
		return urldecode(json_encode($res));
	}
	
	/**
	 * 获取拥有权限用户列表
	 * $str 用户权限字符串
	 */
	function getOperationList($str){
		$user = User::model()->findAll();
		$auth = Yii::app()->authManager;
		$id = "";
		$user_id=isset(Yii::app()->user->userid)?Yii::app()->user->userid:0;
		foreach($user as $li){
			if($li->id ==$user_id ){continue;}
			$bool = $auth->checkAccess($str,$li->id);
			if($bool){
				$id.=$li->id.",";
			}
		}
		if(strlen($id)>1){
			$id = substr($id,0,strlen($id)-1);
		}
		return $id;
	}
	
	//根据用户名获取用户id
	public static function getUserId($name){
		$user = User::model()->find("nickname='".$name."'");
		if($user){
			return $user->id;
		}else{
			return 0;
		}
	}
}
