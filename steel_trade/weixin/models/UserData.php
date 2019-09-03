<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $id
 * @property string $loginname
 * @property string $nickname
 * @property string $password
 * @property string $phone
 * @property integer $created_at
 * @property integer $last_login_at
 * @property string $last_login_ip
 * @property integer $is_deleted
 * @property string $unid
 * @property integer $team_id
 * @property integer $priority
 * @property string $invit_code
 */
class UserData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('created_at, last_login_at, is_deleted, team_id, priority', 'numerical', 'integerOnly'=>true),
			array('loginname, nickname, phone, last_login_ip, unid, invit_code', 'length', 'max'=>45),
			array('password', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, loginname, nickname, password, phone, created_at, last_login_at, last_login_ip, is_deleted, unid, team_id, priority, invit_code', 'safe', 'on'=>'search'),
		);
	}

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
			'loginname' => '登录名',
			'nickname' => '昵称',
			'password' => '密码',
			'phone' => '手机号',
			'created_at' => '创建时间',
			'last_login_at' => '上次登录时间',
			'last_login_ip' => '上次登录ip',
			'is_deleted' => '是否删除',
			'unid' => '上次登录ip',
			'team_id' => '业务组',
			'priority' => '优先级',
			'invit_code' => '用户邀请码',
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
		$criteria->compare('loginname',$this->loginname,true);
		$criteria->compare('nickname',$this->nickname,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('created_at',$this->created_at);
		$criteria->compare('last_login_at',$this->last_login_at);
		$criteria->compare('last_login_ip',$this->last_login_ip,true);
		$criteria->compare('is_deleted',$this->is_deleted);
		$criteria->compare('unid',$this->unid,true);
		$criteria->compare('team_id',$this->team_id);
		$criteria->compare('priority',$this->priority);
		$criteria->compare('invit_code',$this->invit_code,true);

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
}
