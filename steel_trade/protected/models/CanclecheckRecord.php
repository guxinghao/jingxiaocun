<?php

/**
 * This is the biz model class for table "canclecheck_record".
 *
 */
class CanclecheckRecord extends CanclecheckRecordData
{
	public $form_sn;
	public $form_status;
	public $confirm_status;
	public $nickname;
	public $owned;
	public $is_deleted;
	
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
				'user'=>array(self::BELONGS_TO,'User','created_by'),
				'common'=>array(self::BELONGS_TO,'CommonForms','common_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'common_id' => 'Common',
			'reason' => 'Reason',
			'created_time' => 'Created Time',
			'created_by' => 'Created By',
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
		$criteria->compare('common_id',$this->common_id);
		$criteria->compare('reason',$this->reason,true);
		$criteria->compare('created_time',$this->created_time);
		$criteria->compare('created_by',$this->created_by);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CanclecheckRecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/*
	 * 获取取消审核原因列表
	 */
	public static function getCancelList($search){
		$model = CanclecheckRecord::model();
		$criteria = new CDbCriteria();
		$criteria->with = array('user','common','common.sales','common.belong');
		$criteria->together=true;
		//搜索
		if(!empty($search))
		{
			if(trim($search['keywords']) != ''){
				$criteria->addCondition('common.form_sn like :contno');
				$criteria->params[':contno']= "%".trim($search['keywords'])."%";
			}
			if($search['time_L'] != '')
			{
				$criteria->addCondition('t.created_time >="'.strtotime($search['time_L'].' 00:00:00').'"');
			}
			if($search['time_H'] != '')
			{
				$criteria->addCondition('t.created_time <="'.strtotime($search['time_H'].' 23:59:59').'"');
			}
			if($search['owned'] != '0')
			{
				$criteria->compare('common.owned_by',$search['owned'],false);
			}
		}else{
			$criteria->addCondition('t.created_time >="'.strtotime(date('Y-m-d 00:00:00')).'"');
			$criteria->addCondition('t.created_time <="'.strtotime(date('Y-m-d 23:59:59')).'"');
		}

		$criteria->select = 't.id,t.common_id,t.reason,t.created_time,t.status,common.form_sn as form_sn,common.form_status as form_status,sales.confirm_status as confirm_status,user.nickname as nickname,belong.nickname as owned,common.is_deleted as is_deleted';
		
		// $criteria->addCondition('common.is_deleted=0');
		$criteria->order = 'created_time desc';
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize = intval($_COOKIE['sales_list']) ? intval($_COOKIE['sales_list']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);
		$data = $model->findAll($criteria);

		$tableData = array();
		if($data){
			$i = 0;
            $index = 1;
            foreach($data as $v){
                // 序号分组
                $b = -1;
                foreach($tableData as $k => $t){
                    if(in_array($v['form_sn'], $t['data'])){
                        $b = $k;
                        break;
                    }
                }
                if($b === -1){
                    $index1 = $index;
                    $index++;
                }else{
                    $index1 = '';
                }
                // 状态
                if($v['is_deleted'] == 1){
                	$status = '已废弃';
                }else if($v['confirm_status'] == 1){
                    $status = '已完成';
                }else if($v['form_status'] == 'unsubmit'){
                    $status = '未提交';
                }else if($v['form_status'] == 'submited'){
                    $status = '已提交';
                }else if($v['form_status'] == 'approve'){
                    $status = '已审核';
                }
                if($v['status'] == -1){
                	$checkStatus = '已取消';
                }else if($v['status'] == 0){
                	$checkStatus = '未处理';
                }else{
                	$checkStatus = '已处理';
                }
				$detail_url = Yii::app()->createUrl('FrmSales/detail', array('id'=>$v['common_id'], 'fpage'=>$_REQUEST['page'], 'backUrl'=>'cancelCheckRecord'));
                $tableData[$i]['data'] = array(
                    $index1,
                    '<a href="'.$detail_url.'" title="查看详情" class="a_view">'.$v['form_sn'].'</a>',
                    $status,
                    $v['reason'],
                    $checkStatus,
                    $v['owned'],
                    $v['nickname'],
                    date('Y-m-d H:i:s', $v['created_time'])
                );
				if($b === -1){
                    $tableData[$i]['group'] = $i+1;
                }else{
                    $tableData[$i]['group'] = $tableData[$b]['group'];
                }
                $i++;
            }
        }

		return array($tableData,$pages);
	}
}
