<?php

/**
 * This is the model class for table "wx_user".
 *
 * The followings are the available columns in table 'wx_user':
 * @property integer $id
 * @property string $username
 * @property string $loginname
 * @property string $phone
 * @property string $yq_code
 * @property integer $created_at
 * @property integer $user_id
 * @property string $qq
 * @property string $fax
 * @property string $openid
 * @property string $pic
 * @property integer $is_deleted
 * @property integer $is_spread
 */
class WxUserData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'wx_user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username', 'required'),
			array('created_at, user_id, is_deleted, is_spread', 'numerical', 'integerOnly'=>true),
			array('username, loginname, phone, openid, pic', 'length', 'max'=>255),
			array('yq_code, qq, fax', 'length', 'max'=>45),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, username, loginname, phone, yq_code, created_at, user_id, qq, fax, openid, pic, is_deleted, is_spread', 'safe', 'on'=>'search'),
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
}
