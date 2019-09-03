<?php

/**
 * This is the biz model class for table "variance_report".
 *
 */
class VarianceReport extends VarianceReportData
{
	public $start_time; 
	public $end_time;

	public static $type = array('sales' => "销售明细");

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'salesDetail' => array(self::BELONGS_TO, 'SalesDetail', 'detail_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'type' => 'Type',
			'detail_id' => 'Detail',
			'variance_amount' => 'Variance Amount',
			'variance_weight' => 'Variance Weight',
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
		$criteria->compare('type',$this->type,true);
		$criteria->compare('detail_id',$this->detail_id);
		$criteria->compare('variance_amount',$this->variance_amount);
		$criteria->compare('variance_weight',$this->variance_weight,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return VarianceReport the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function getIndexList($search) 
	{
		$model = new SalesView();
		$tableData = array();

		$criteria = new CDbCriteria();
		$criteria->join = "right join variance_report vr on vr.detail_id = t.detail_id";
		$criteria->select = "t.*, vr.variance_amount, vr.variance_weight";
		
		if (!empty($search)) 
		{
			if ($search['keywords']) {
				$criteria->addCondition("t.form_sn like :keywords");
				$criteria->addCondition("t.comment like :keywords", 'OR');
				$criteria->params[':keywords'] = '%'.$search['keywords'].'%';
			}
			if ($search['start_time']) {
				$criteria->addCondition("t.created_at >= :start_time");
				$criteria->params[':start_time'] = strtotime($search['start_time'].' 00:00:00');
			}
			if ($search['end_time']) {
				$criteria->addCondition("t.created_at <= :end_time");
				$criteria->params[':end_time'] = strtotime($search['end_time'].' 23:59:59');
			}
			if ($search['title_id']) {
				$criteria->addCondition("t.main_title_id = :title_id");
				$criteria->params[':title_id'] = intval($search['title_id']);
			}
			if ($search['customer_id']) {
				$criteria->addCondition("t.customer_id = :customer_id");
				$criteria->params[':customer_id'] = intval($search['customer_id']);
			}
			if ($search['owned_by']) {
				$criteria->addCondition("t.owned_by = :owned_by");
				$criteria->params[':owned_by'] = intval($search['owned_by']);
			}
			if ($search['sales_type']) {
				$criteria->addCondition("t.main_type = :sales_type");
				$criteria->params[':sales_type'] = $search['sales_type'];
			}
			if ($search['brand_id']) { //产地
				$criteria->addCondition("t.brand_id = :brand_id");
				$criteria->params[':brand_id'] = intval($search['brand_id']);
			}
			if ($search['product_id']) { //品名
				$criteria->addCondition("t.product_id = :product_id");
				$criteria->params[':product_id'] = intval($search['product_id']);
			}
			if ($search['texture_id']) { //规格
				$criteria->addCondition("t.texture_id = :texture_id");
				$criteria->params[':texture_id'] = intval($search['texture_id']);
			}
			if ($search['rank_id']) { //材质
				$criteria->addCondition("t.rank_id = :rank_id");
				$criteria->params[':rank_id'] = intval($search['rank_id']);
			}
		}
		$criteria->addCondition("t.confirm_status = 1");
		
		$pages = new CPagination();
		$pages->itemCount = $model->count($criteria);
		$pages->pageSize = intval($_COOKIE['variance_list']) ? intval($_COOKIE['variance_list']) : Yii::app()->params['pageCount'];
		$pages->applyLimit($criteria);

		$details = $model->findAll($criteria);
		if (!$details) return array($tableData, $pages);

		$mark = 1;
		foreach ($details as $detail) {
			$da = array();
			$detail_view = Yii::app()->createUrl('frmSales/detail', array('id' => $detail->common_id, 'fpage' => $_REQUEST['page'], 'backUrl' => "varianceReport"));
			$product_info = $detail->brand_name.'/'.$detail->product_name.'/'.$detail->texture_name.'/'.$detail->rank_name.'/'.$detail->detail_length;

			$da['data'] = array($mark, 
				'<a title="查看详细" href="'.$detail_view.'">'.$detail->form_sn.'</a>',
				$detail->created_at > 0 ? date('Y-m-d', $detail->created_at) : '',
				$detail->title_name,
				'<span title="'.$detail->customer_name.'">'.$detail->customer_short_name.'</span>',
				$product_info, 
				number_format($detail->pre_amount), 
				number_format($detail->pre_weight, 3),
				number_format($detail->detail_output_amount), 
				number_format($detail->detail_output_weight, 3),
				$detail->variance_amount != 0 ? number_format($detail->variance_amount) : '', 
				$detail->variance_weight != 0 ? number_format($detail->variance_weight, 3) : '',
				FrmSales::$sales_type[$detail->main_type],
				$detail->owned_by_nickname, 
				$detail->team_name,
				'<span title="'.htmlspecialchars($detail->comment).'">'.mb_substr($detail->comment, 0, 15, 'UTF-8').'</span>',
			);
			$da['group'] = $detail->detail_id; 
			array_push($tableData, $da);
			$mark++;
		}
		return array($tableData, $pages);
	}

}
