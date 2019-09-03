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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'username' => '用户名',
			'loginname' => '微信名称',
			'phone' => '手机号',
			'yq_code' => '邀请码',
			'created_at' => '创建时间',
			'user_id' => '对应进销存用户id',
			'qq' => 'qq',
			'fax' => '传真',
			'openid' => 'Openid',
			'pic' => '头像',
			'is_deleted' => '是否删除',
			'is_spread' => '是有可以查看价格下浮',
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
