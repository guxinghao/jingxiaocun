<?php

/**
 * This is the biz model class for table "team".
 *
 */
class Team extends TeamData
{
	

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
				'users'=>array(self::HAS_MANY,'User','team_id'),
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

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Team the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
	/*
	 * 获取组列表
	 */
	public static function getTeamList($type)
	{
		$teams=Team::model()->findAll();
		if($type=='array')
		{
			$return=array();
			if($teams)
			{
				foreach ($teams as $team)
				{
					$return[$team->id]=$team->name;
				}
			}
		}elseif($type=='json')
		{
			$temp=array();
			$return=array();
			if($teams)
			{
				foreach ($teams as $team)
				{
					$temp['id']="$team->id";
					$temp['bs']="$team->name";
					$temp['name']="$team->name";
					array_push($return, $temp);
				}
			}
			$return=json_encode($return);
		}			
		
		return $return;
	}
	
	
	/*
	 * 通过team_id获取组内成员
	 */
	public static  function getUsers($id)
	{
		$condition = "is_deleted = 0";
		$params = array();
		if ($id > 0) 
		{
			$condition .= " AND team_id = :team_id";
			$params[':team_id'] = $id;
		}
		$users = User::model()->findAll($condition, $params);
		if (!$users) return ;
		$return = '';
		foreach ($users as $each) 
		{
			$return .= '<option value="'.$each->id.'">'.$each->nickname.'</option>';
		}
		return $return;
	} 
	
	/*
	 * 获取名
	 */
	public static function getName($id)
	{
		$return='';
		$model=Team::model()->findByPk($id);
		if($model)
		{
			$return=$model->name;
		}
		return $return;
	
	}

	public function createTeam($post){
		$this->attributes = $post;
		$bool = Team::model()->exists("name='{$this->name}'");
		if($bool){
			return -1;
		}else{
			return $this->save();
		}
	}

	public function updateTeam($post){
		$this->attributes = $post;
		$bool = Team::model()->exists("id<>$this->id and name='{$this->name}'");
		if($bool){
			return -1;
		}else{
			return $this->save();
		}
	}
}
