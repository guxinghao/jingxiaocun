<?php

/** 
 * This is the biz model class for table "frm_bill_log". 
 * 
 */ 
class FrmBillLog extends FrmBillLogData
{ 
     public static $billType = array('1' => "销售收款", '2' => "采购付款", '3' => "其他收入", '4' => "费用报支", '5' => "银行互转", '6' => "短期借贷",
     		'7'=>"采购退货收款",'8'=>"销售退货付款",'9'=>"销售折让",'10'=>"采购折让",'11'=>"托盘赎回");
     public static $accountType = array('out' => "出账", 'in' => "入账");
     public $st; //开始时间
     public $et; //结束时间
     public $in_total_price; //入账总金额
     public $out_total_price; //出账总金额
     public $total_initial;

    /** 
     * @return array relational rules. 
     */ 
    public function relations() 
    { 
        // NOTE: you may need to adjust the relation name and the related 
        // class name for the relations automatically generated below. 
        return array(
            'company' => array(self::BELONGS_TO, 'DictCompany', 'company_id'),
            'company_bank' => array(self::BELONGS_TO, 'BankInfo', 'bank_id'),
            'title' => array(self::BELONGS_TO, 'DictTitle', 'title_id'),
            'title_bank' => array(self::BELONGS_TO, 'DictBankInfo', 'dict_bank_id'),
            'opposite_title' => array(self::BELONGS_TO, 'DictTitle', 'company_id'), //互转时，结算单位也为公司
            'opposite_title_bank' => array(self::BELONGS_TO, 'DictBankInfo', 'bank_id'), //互转公司账户
            'accounter' => array(self::BELONGS_TO, 'User', 'account_by'),
            'loanRecord' => array(self::BELONGS_TO, 'LoanRecord', 'form_id'), //短期借贷时form_id为明细id
            'baseform' => array(self::BELONGS_TO,'CommonForms','form_id'),
        ); 
    } 

    /** 
     * @return array customized attribute labels (name=>label) 
     */ 
    public function attributeLabels() 
    { 
        return array( 
            'id' => 'ID',
            'form_id' => 'Form',
            'form_sn' => 'Form Sn',
            'title_id' => 'Title',
            'dict_bank_id' => 'Dict Bank',
            'company_id' => 'Company',
            'bank_id' => 'Bank',
            'fee' => 'Fee',
            'account_type' => 'Account Type',
            'bill_type' => 'Bill Type',
            'pay_type' => 'Pay Type',
            'account_by' => 'Account By',
            'comment' => 'Comment',
            'created_at' => 'Created At',
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
        $criteria->compare('form_id',$this->form_id);
        $criteria->compare('form_sn',$this->form_sn,true);
        $criteria->compare('title_id',$this->title_id);
        $criteria->compare('dict_bank_id',$this->dict_bank_id);
        $criteria->compare('company_id',$this->company_id);
        $criteria->compare('bank_id',$this->bank_id);
        $criteria->compare('fee',$this->fee,true);
        $criteria->compare('account_type',$this->account_type,true);
        $criteria->compare('bill_type',$this->bill_type,true);
        $criteria->compare('pay_type',$this->pay_type,true);
        $criteria->compare('account_by',$this->account_by);
        $criteria->compare('comment',$this->comment,true);
        $criteria->compare('created_at',$this->created_at);

        return new CActiveDataProvider($this, array( 
            'criteria'=>$criteria, 
        )); 
    } 
     
        /** 
     * Returns the static model of the specified AR class. 
     * Please note that you should have this exact method in all your CActiveRecord descendants! 
     * @param string $className active record class name. 
     * @return FrmBillLog the static model class 
     */ 
    public static function model($className=__CLASS__) 
    { 
        return parent::model($className); 
    } 
    
    /**
     * 获取明细列表
     */
    public static function getIndexList($search) 
    {
        $model = new FrmBillLog();
        if ($_POST['FrmBillLog'] === null) {
            $model->dict_bank_id = $_GET['account_id'] ? intval($_GET['account_id']) : 0;
            $model->company_id = $_GET['company_id'] ? intval($_GET['company_id']) : 0;
            $model->st = $_GET['st'] ? $_GET['st'] : date('Y-m-d', time() - 86400 * 7);
            $model->et = $_GET['et'] ? $_GET['et'] : date('Y-m-d', time());
        }
        $criteria = new CDbCriteria();
        $criteria->with = array("baseform");
        if ($_POST['FrmBillLog']) 
        {
            $model->attributes = $_POST['FrmBillLog'];
            $model->st = $_POST['FrmBillLog']['st'] ? $_POST['FrmBillLog']['st'] : $model->st;
            $model->et = $_POST['FrmBillLog']['et'] ? $_POST['FrmBillLog']['et'] : $model->et;

            if ($model->title_id) {
                $criteria->addCondition("title_id = :title_id");
                $criteria->params[':title_id'] = $model->title_id;
            }
            //checkbox筛选
            if ($_POST['title_rl'] || $_POST['title_cx'] || $_POST['other']) {
                $title_rl = $_POST['title_rl'] ? $_POST['title_rl'] : '';
                $title_rl_val = DictTitle::getTitleId('瑞亮物资');
                $title_cx = $_POST['title_cx'] ? $_POST['title_cx'] : '';
                $title_cx_val = DictTitle::getTitleId('乘翔实业');
                $other = $_POST['other'];

                if (!$other) 
                    $criteria->addInCondition('title_id', array($title_rl_val, $title_cx_val));
                if (!$title_cx) 
                    $criteria->addNotInCondition('title_id', array($title_cx_val));
                if (!$title_rl) 
                    $criteria->addNotInCondition('title_id', array($title_rl_val));
            }
            if ($model->bill_type) {
                $criteria->addCondition("bill_type = :bill_type");
                $criteria->params[':bill_type'] = $model->bill_type;
            }

            if ($model->account_by) {
                $criteria->addCondition("account_by = :account_by");
                $criteria->params[':account_by'] = $model->account_by;
            }
            if ($search['owned_by']){
            	$criteria->addCondition("baseform.owned_by = :owned_by");
            	$criteria->params[':owned_by'] = $search['owned_by'];
            }
            if ($model->account_type) {
                $criteria->addCondition("account_type = :account_type");
                $criteria->params[':account_type'] = $model->account_type;
            }
        }
        //确认st
        if($model->st&&strtotime($model->st)>Yii::app()->params['turn_time'])
        {}else{
            $model->st=date('Y-m-d',Yii::app()->params['turn_time']);
        }
        if ($model->dict_bank_id) {
            $criteria->addCondition("dict_bank_id = :dict_bank_id");
            $criteria->params[':dict_bank_id'] = $model->dict_bank_id;
        }
        if ($model->company_id) {
            $criteria->addCondition("company_id = :company_id");
            $criteria->params[':company_id'] = $model->company_id;
        }
        if ($model->st) {
            // $criteria->addCondition("created_at >= :st");
            $criteria->addCondition("reach_at >= :st");
            $criteria->params[':st'] = strtotime($model->st.' 00:00:00');
        }
        if ($model->et) {
            // $criteria->addCondition("created_at <= :et");
            $criteria->addCondition("reach_at <= :et");
            $criteria->params[':et'] = strtotime($model->et.' 23:59:59');
        }
        $criteria->order = "reach_at asc,bill_type asc";

        //賬戶期初餘額，篩選由title_id,dict_bank_id,還有check_box篩選，
        //期初由最期初和所有出入帳的和，
        $criteria_inital=new CDbCriteria();
        if($model->title_id)
            $criteria_inital->compare('dict_title_id',$model->title_id);
        if($model->dict_bank_id)
            $criteria_inital->compare('id',$model->dict_bank_id);
        if ($_POST['title_rl'] || $_POST['title_cx'] || $_POST['other']) {
            if (!$other) 
                $criteria_inital->addInCondition('dict_title_id', array($title_rl_val, $title_cx_val));
            if (!$title_cx) 
                $criteria_inital->addNotInCondition('dict_title_id', array($title_cx_val));
            if (!$title_rl) 
                $criteria_inital->addNotInCondition('dict_title_id', array($title_rl_val));
        }
        $criteria_inital_clone=clone $criteria_inital;
        $criteria_inital->select='sum(initial_money) as total_initial';
        $money_inital=DictBankInfo::model()->find($criteria_inital);
        $banks=DictBankInfo::model()->findAll($criteria_inital_clone);
        $banks_id=array();
        if($banks)
        {
            foreach ($banks as $value) {
                array_push($banks_id ,$value->id);
            }
        }
        $qichu=0;
        // var_dump($money_inital->total_initial);
        // die;
        if($model->st)
        {
            $criii=new CDbCriteria();                   
            $criii->addInCondition('dict_bank_id',$banks_id);
            // var_dump($banks_id);
            $criii->addCondition("reach_at <= ".strtotime($model->st.' 00:00:00'));  
            // $criii->select='sum(fee) as in_total_price';
            // $criii_clone=clone $criii;
            // $criii->compare('account_type','in');
            $result_io= FrmBillLog::model()->findAll($criii);
            // $criii_clone->compare('account_type','out');
            // $result_out=FrmBillLog::model()->findAll($criii_clone);
            foreach($result_io as $r_i){
                if($r_i->account_type=='in')
                    $rein+=$r_i->fee;
                else
                    $reout+=$r_i->fee;

            }
            $qichu=$qichu+$rein-$reout;
            // var_dump($rein);
            // var_dump($reout);
        }
        if(intval($_REQUEST['page'])>1)
        {
            $more_cri=clone $criteria;
            // $more_cri->select='sum(fee) as total_initial';
            $more_cri->limit=($_COOKIE['zcmx']?intval($_COOKIE['zcmx']):Yii::app()->params['pageCount'])*(intval($_REQUEST['page'])-1);
            $more_cri->offset=0;                
            $more_res=FrmBillLog::model()->findAll($more_cri);           
            if($more_res)
            {
                foreach ($more_res as $ea) {
                    if($ea->account_type=='in'){
                      $in_money+=$ea->fee;  
                    }else{
                        $out_money+=$ea->fee;
                    }
                }  
            }              
            $qichu=$qichu+$in_money-$out_money;  
        }


    //总计
        //入账
        $c_in = clone $criteria;
        $c_in->select = "sum(fee) as in_total_price";
        $c_in->addCondition("account_type = 'in'");
        $total_in = FrmBillLog::model()->find($c_in);
        //出账
        $c_out = clone $criteria;
        $c_out->select = "sum(fee) as out_total_price";
        $c_out->addCondition("account_type = 'out'");
        $total_out = FrmBillLog::model()->find($c_out);

        $totaldata = array();
        $totaldata['in_price'] = $total_in->in_total_price;
        $totaldata['out_price'] = $total_out->out_total_price;
        $totaldata['final']=$qichu+$total_in->in_total_price-$total_out->out_total_price;


        $pages = new CPagination();
        $pages->itemCount = $model->count($criteria);
        $pages->pageSize = $_COOKIE['zcmx'] ? intval($_COOKIE['zcmx']) : Yii::app()->params['pageCount'];
        $pages->applyLimit($criteria);
        

        $items = $model->findAll($criteria);

        if(intval($_REQUEST['page'])<=1)
        {
            $item_new=new FrmBillLog();
            $item_new->unsetAttributes();
            $item_new->dict_bank_id='期初';    
            array_unshift($items,$item_new);        
        }

        return array($model, $items, $pages, $totaldata,$qichu);
    }

    /**
     * 资金汇总
     * @return array
     */
    public static function getTotalList() 
    {
        $model = new FrmBillLog();
        $model->st = date('Y-m-d', time() - 86400 * 7);
        $model->et = date('Y-m-d', time());

        $criteria = new CDbCriteria();
        $criteria_total = new CDbCriteria(); //总计
        $condition_total = "";

        $criteria_log = new CDbCriteria();
        if ($_POST['FrmBillLog']) {
            $model->attributes = $_POST['FrmBillLog'];
            $model->st = $_POST['FrmBillLog']['st'] ? $_POST['FrmBillLog']['st'] : $model->st; //开始时间
            $model->et = $_POST['FrmBillLog']['et'] ? $_POST['FrmBillLog']['et'] : $model->et; //结束时间

            if ($model->dict_bank_id) {
               $criteria->addCondition("t.id = :dict_bank_id");
               
               $criteria_total->addCondition("t.id = :dict_bank_id");
               $condition_total.=" AND dict_bank_id = :dict_bank_id";
               
               $criteria_log->params[':dict_bank_id'] = $model->dict_bank_id;
            }
            if ($model->company_id) {
               $criteria_log->addCondition("company_id = :company_id");
               $criteria_log->params[':company_id'] = $model->company_id;
            }
        }
         //确认st
        if($model->st&&strtotime($model->st)>Yii::app()->params['turn_time'])
        {}else{
            $model->st=date('Y-m-d',Yii::app()->params['turn_time']);
        }
        $st = strtotime($model->st.' 00:00:00');
        $et = strtotime($model->et.' 23:59:59');
        if ($st > $et) 
            return array($model, array(), array(), "起始时间不能大于结束时间！");
        
        //checkbox筛选
        if ($_POST['title_rl'] || $_POST['title_cx'] || $_POST['other']) {
            $title_rl = $_POST['title_rl'] ? $_POST['title_rl'] : '';
            $title_rl_val = DictTitle::getTitleId('瑞亮物资');
            $title_cx = $_POST['title_cx'] ? $_POST['title_cx'] : '';
            $title_cx_val = DictTitle::getTitleId('乘翔实业');
            $other = $_POST['other'];

            $criteria->join="left join dict_title_bank_relation relate on t.id=relate.bank_id";
            if (!$other){
                $criteria_log->addInCondition('title_id', array($title_rl_val, $title_cx_val));
                $criteria->addCondition(" relate.title_id in ($title_rl_val, $title_cx_val)");
            } 
                
            if (!$title_cx){
                $criteria_log->addNotInCondition('title_id', array($title_cx_val));
                $criteria->addCondition("relate.title_id not in ($title_cx_val)");
            } 
                
            if (!$title_rl) {
                $criteria_log->addNotInCondition('title_id', array($title_rl_val));
                $criteria->addCondition("relate.title_id not in ($title_rl_val)");
            }
        }

         $condition = $criteria_log->condition ? ' AND '.$criteria_log->condition : '';
         $condition_total .= $condition;
         $criteria_log->params[':st'] = $st;
         $criteria_log->params[':et'] = $et; 
//-----------------------------------------------------------------------------------------
         //期初入账 initial_in
         $criteria->join .= " left join (select dict_bank_id, ifnull(sum(fee), 0) as 'fee' from frm_bill_log where account_type = 'in' and reach_at <= :st".$condition." group by dict_bank_id) initial_in on initial_in.dict_bank_id = t.id";
         //期初入账 总计
         $criteria_total->join .= " left join (select ifnull(sum(fee), 0) as 'fee' from frm_bill_log where account_type = 'in' and reach_at <= :st".$condition_total.") initial_in on 1 = 1";

         //期初出账 initial_out
         $criteria->join .= " left join (select dict_bank_id, ifnull(sum(fee), 0) as 'fee' from frm_bill_log where account_type = 'out' and reach_at <= :st".$condition." group by dict_bank_id) initial_out on initial_out.dict_bank_id = t.id";
         //期初出账 总计
         $criteria_total->join .= " left join (select ifnull(sum(fee), 0) as 'fee' from frm_bill_log where account_type = 'out' and reach_at <= :st".$condition_total.") initial_out on 1 = 1";
//-----------------------------------------------------------------------------------------
         //期末入账 final_in
         $criteria->join .= " left join (select dict_bank_id, ifnull(sum(fee), 0) as 'fee' from frm_bill_log where account_type = 'in' and reach_at <= :et".$condition." group by dict_bank_id) final_in on final_in.dict_bank_id = t.id";
         //期末入账 总计
         $criteria_total->join .= " left join (select ifnull(sum(fee), 0) as 'fee' from frm_bill_log where account_type = 'in' and reach_at <= :et".$condition_total.") final_in on 1 = 1";

         //期末出账 final_out
         $criteria->join .= " left join (select dict_bank_id, ifnull(sum(fee), 0) as 'fee' from frm_bill_log where account_type = 'out' and reach_at <= :et".$condition." group by dict_bank_id) final_out on final_out.dict_bank_id = t.id";
         //期末出账 总计
         $criteria_total->join .= " left join (select ifnull(sum(fee), 0) as 'fee' from frm_bill_log where account_type = 'out' and reach_at <= :et".$condition_total.") final_out on 1 = 1";
//-----------------------------------------------------------------------------------------
         //本期入账 current_in
         $criteria->join .= " left join (select dict_bank_id, ifnull(sum(fee), 0) as 'fee' from frm_bill_log where account_type = 'in' and reach_at between :st and :et".$condition." group by dict_bank_id) current_in on current_in.dict_bank_id = t.id";
         //本期入账 总计
         $criteria_total->join .= " left join (select ifnull(sum(fee), 0) as 'fee' from frm_bill_log where account_type = 'in' and reach_at between :st and :et".$condition_total.") current_in on 1 = 1";
//-----------------------------------------------------------------------------------------
         //本期出账 current_out
         $criteria->join .= " left join (select dict_bank_id, ifnull(sum(fee), 0) as 'fee' from frm_bill_log where account_type = 'out' and reach_at between :st and :et".$condition." group by dict_bank_id) current_out on current_out.dict_bank_id = t.id";
         //本期出账 总计
         $criteria_total->join .= " left join (select ifnull(sum(fee), 0) as 'fee' from frm_bill_log where account_type = 'out' and reach_at between :st and :et".$condition_total.") current_out on 1 = 1";
//-----------------------------------------------------------------------------------------
         //总计
         $criteria_total->select = "
         (ifnull(initial_in.fee, 0) - ifnull(initial_out.fee, 0)) as 'initial_balance', 
         (ifnull(final_in.fee, 0) - ifnull(final_out.fee, 0)) as 'final_balance', 
         ifnull(current_in.fee, 0) as 'current_in', 
         ifnull(current_out.fee, 0) as 'current_out' ";
         $criteria_total->params = $criteria_log->params;
         $criteria_total->order = "t.priority ASC";
         $total = DictBankInfo::model()->find($criteria_total);

         $criteria->select = "t.*, 
         (ifnull(initial_in.fee, 0) - ifnull(initial_out.fee, 0)) as 'initial_balance', 
         (ifnull(final_in.fee, 0) - ifnull(final_out.fee, 0)) as 'final_balance', 
         ifnull(current_in.fee, 0) as 'current_in', 
         ifnull(current_out.fee, 0) as 'current_out' ";
        $criteria->params = $criteria_log->params;
        $criteria->group='t.id';
        $criteria->order = "t.priority ASC";
         $items = DictBankInfo::model()->findAll($criteria);
         return array($model, $items, $total, "");
    }


    public static function getAllList($search) 
    {
        $model = new FrmBillLog();
        $criteria = new CDbCriteria();
        if ($search) 
        {
            $model->attributes = $search;
            $model->st = $search['st'] ? $search['st'] : $model->st;
            $model->et = $search['et'] ? $search['et'] : $model->et;

            if ($model->title_id) {
                $criteria->addCondition("title_id = :title_id");
                $criteria->params[':title_id'] = $model->title_id;
            }
            //checkbox筛选
            if ($_POST['title_rl'] || $_POST['title_cx'] || $_POST['other']) {
                $title_rl = $_POST['title_rl'] ? $_POST['title_rl'] : '';
                $title_rl_val = DictTitle::getTitleId('瑞亮物资');
                $title_cx = $_POST['title_cx'] ? $_POST['title_cx'] : '';
                $title_cx_val = DictTitle::getTitleId('乘翔实业');
                $other = $_POST['other'];

                if (!$other) 
                    $criteria->addInCondition('title_id', array($title_rl_val, $title_cx_val));
                if (!$title_cx) 
                    $criteria->addNotInCondition('title_id', array($title_cx_val));
                if (!$title_rl) 
                    $criteria->addNotInCondition('title_id', array($title_rl_val));
            }
            if ($model->bill_type) {
                $criteria->addCondition("bill_type = :bill_type");
                $criteria->params[':bill_type'] = $model->bill_type;
            }

            if ($model->account_by) {
                $criteria->addCondition("account_by = :account_by");
                $criteria->params[':account_by'] = $model->account_by;
            }
            if ($model->account_type) {
                $criteria->addCondition("account_type = :account_type");
                $criteria->params[':account_type'] = $model->account_type;
            }
        }
        if ($model->dict_bank_id) {
            $criteria->addCondition("dict_bank_id = :dict_bank_id");
            $criteria->params[':dict_bank_id'] = $model->dict_bank_id;
        }
        if ($model->company_id) {
            $criteria->addCondition("company_id = :company_id");
            $criteria->params[':company_id'] = $model->company_id;
        }
        if ($model->st) {
            // $criteria->addCondition("created_at >= :st");
            $criteria->addCondition("reach_at >= :st");
            $criteria->params[':st'] = strtotime($model->st.' 00:00:00');
        }
        if ($model->et) {
            // $criteria->addCondition("created_at <= :et");
            $criteria->addCondition("reach_at <= :et");
            $criteria->params[':et'] = strtotime($model->et.' 23:59:59');
        }

    //总计
        //入账
        $c_in = clone $criteria;
        $c_in->select = "sum(fee) as in_total_price";
        $c_in->addCondition("account_type = 'in'");
        $total_in = FrmBillLog::model()->find($c_in);
        //出账
        $c_out = clone $criteria;
        $c_out->select = "sum(fee) as out_total_price";
        $c_out->addCondition("account_type = 'out'");
        $total_out = FrmBillLog::model()->find($c_out);

        $totaldata = array();
        $totaldata[4] = numChange(number_format($total_in->in_total_price, 2));
        $totaldata[5] = numChange(number_format($total_out->out_total_price, 2));

        // $pages = new CPagination();
        // $pages->itemCount = $model->count($criteria);
        // $pages->pageSize = $_COOKIE['zcmx'] ? intval($_COOKIE['zcmx']) : Yii::app()->params['pageCount'];
        // $pages->applyLimit($criteria);
        $criteria->order = "reach_at desc";

        $details = $model->findAll($criteria);
        $content = array();
        if (!$details) return $content;

        foreach ($details as $item) 
        {
            $company = $item->company;
            switch ($item->bill_type) {
                case '5': //银行互转
                    $company = $item->opposite_title;
                    break;
                default: 
                    continue;
                    break;
            }

            $temp = array(
                $item->reach_at > 0 ? date("Y-m-d", $item->reach_at) : '', 
                $item->form_sn, 
                $item->title_bank->dict_name, 
                $company->short_name, 
                $item->account_type == 'in' ? numChange(number_format($item->fee, 2)) : '', 
                $item->account_type == 'out' ? numChange(number_format($item->fee, 2)) : '', 
                FrmBillLog::$billType[$item->bill_type], 
                FrmFormBill::$payTypes[$item->pay_type], 
                $item->title->short_name, 
                $item->accounter->nickname, 
                str_replace('&nbsp;', ' ', $item->comment),
            );
            array_push($content, $temp);
        }
        array_push($content, $totaldata);

        return $content;
    }


    public static function getAllTotalList($search) 
    {
        $model = new FrmBillLog();
        $model->st = date('Y-m-d', time() - 86400 * 7);
        $model->et = date('Y-m-d', time());

        $criteria = new CDbCriteria();
        $criteria_total = new CDbCriteria(); //总计
        $condition_total = "";

        $criteria_log = new CDbCriteria();
        if ($search) {
            $model->attributes = $search;
            $model->st = $search['st'] ? $search['st'] : $model->st; //开始时间
            $model->et = $search['et'] ? $search['et'] : $model->et; //结束时间

            if ($model->dict_bank_id) {
               $criteria->addCondition("t.id = :dict_bank_id");

               $criteria_total->addCondition("t.id = :dict_bank_id");
               $condition_total .= " AND dict_bank_id = :dict_bank_id";

               $criteria_log->params[':dict_bank_id'] = $model->dict_bank_id;
            }
            if ($model->company_id) {
               $criteria_log->addCondition("company_id = :company_id");
               $criteria_log->params[':company_id'] = $model->company_id;
            }
        }
        $st = strtotime($model->st.' 00:00:00');
        $et = strtotime($model->et.' 23:59:59');
        if ($st > $et) 
            return array($model, array(), array(), "起始时间不能大于结束时间！");

        //checkbox筛选
        if ($_POST['title_rl'] || $_POST['title_cx'] || $_POST['other']) {
            $title_rl = $_POST['title_rl'] ? $_POST['title_rl'] : '';
            $title_rl_val = DictTitle::getTitleId('瑞亮物资');
            $title_cx = $_POST['title_cx'] ? $_POST['title_cx'] : '';
            $title_cx_val = DictTitle::getTitleId('乘翔实业');
            $other = $_POST['other'];

            if (!$other) 
                $criteria_log->addInCondition('title_id', array($title_rl_val, $title_cx_val));
            if (!$title_cx) 
                $criteria_log->addNotInCondition('title_id', array($title_cx_val));
            if (!$title_rl) 
                $criteria_log->addNotInCondition('title_id', array($title_rl_val));
        }

        $condition = $criteria_log->condition ? ' AND '.$criteria_log->condition : '';
        $condition_total .= $condition;
        $criteria_log->params[':st'] = $st;
        $criteria_log->params[':et'] = $et; 
//-----------------------------------------------------------------------------------------
        //期初入账 initial_in
        $criteria->join .= " left join (select dict_bank_id, ifnull(sum(fee), 0) as 'fee' from frm_bill_log where account_type = 'in' and created_at <= :st".$condition." group by dict_bank_id) initial_in on initial_in.dict_bank_id = t.id";
        //期初入账 总计
        $criteria_total->join .= " left join (select ifnull(sum(fee), 0) as 'fee' from frm_bill_log where account_type = 'in' and created_at <= :st".$condition_total.") initial_in on 1 = 1";

        //期初出账 initial_out
        $criteria->join .= " left join (select dict_bank_id, ifnull(sum(fee), 0) as 'fee' from frm_bill_log where account_type = 'out' and created_at <= :st".$condition." group by dict_bank_id) initial_out on initial_out.dict_bank_id = t.id";
        //期初出账 总计
        $criteria_total->join .= " left join (select ifnull(sum(fee), 0) as 'fee' from frm_bill_log where account_type = 'out' and created_at <= :st".$condition_total.") initial_out on 1 = 1";
//-----------------------------------------------------------------------------------------
        //期末入账 final_in
        $criteria->join .= " left join (select dict_bank_id, ifnull(sum(fee), 0) as 'fee' from frm_bill_log where account_type = 'in' and created_at <= :et".$condition." group by dict_bank_id) final_in on final_in.dict_bank_id = t.id";
        //期末入账 总计
        $criteria_total->join .= " left join (select ifnull(sum(fee), 0) as 'fee' from frm_bill_log where account_type = 'in' and created_at <= :et".$condition_total.") final_in on 1 = 1";

        //期末出账 final_out
        $criteria->join .= " left join (select dict_bank_id, ifnull(sum(fee), 0) as 'fee' from frm_bill_log where account_type = 'out' and created_at <= :et".$condition." group by dict_bank_id) final_out on final_out.dict_bank_id = t.id";
        //期末出账 总计
        $criteria_total->join .= " left join (select ifnull(sum(fee), 0) as 'fee' from frm_bill_log where account_type = 'out' and created_at <= :et".$condition_total.") final_out on 1 = 1";
//-----------------------------------------------------------------------------------------
        //本期入账 current_in
        $criteria->join .= " left join (select dict_bank_id, ifnull(sum(fee), 0) as 'fee' from frm_bill_log where account_type = 'in' and created_at between :st and :et".$condition." group by dict_bank_id) current_in on current_in.dict_bank_id = t.id";
        //本期入账 总计
        $criteria_total->join .= " left join (select ifnull(sum(fee), 0) as 'fee' from frm_bill_log where account_type = 'in' and created_at between :st and :et".$condition_total.") current_in on 1 = 1";
//-----------------------------------------------------------------------------------------
        //本期出账 current_out
        $criteria->join .= " left join (select dict_bank_id, ifnull(sum(fee), 0) as 'fee' from frm_bill_log where account_type = 'out' and created_at between :st and :et".$condition." group by dict_bank_id) current_out on current_out.dict_bank_id = t.id";
        //本期出账 总计
        $criteria_total->join .= " left join (select ifnull(sum(fee), 0) as 'fee' from frm_bill_log where account_type = 'out' and created_at between :st and :et".$condition_total.") current_out on 1 = 1";
//-----------------------------------------------------------------------------------------
        //总计
        $criteria_total->select = "
        (ifnull(initial_in.fee, 0) - ifnull(initial_out.fee, 0)) as 'initial_balance', 
        (ifnull(final_in.fee, 0) - ifnull(final_out.fee, 0)) as 'final_balance', 
        ifnull(current_in.fee, 0) as 'current_in', 
        ifnull(current_out.fee, 0) as 'current_out' ";
        $criteria_total->params = $criteria_log->params;
        $criteria_total->order = "t.priority ASC";
        $total = DictBankInfo::model()->find($criteria_total);
        $totaldata = array('', 
            numChange(number_format($total->final_balance, 2)), 
            numChange(number_format($total->current_in, 2)), 
            numChange(number_format($total->current_out, 2)), 
            numChange(number_format($total->initial_balance, 2))
        );

        $criteria->select = "t.*, 
        (ifnull(initial_in.fee, 0) - ifnull(initial_out.fee, 0)) as 'initial_balance', 
        (ifnull(final_in.fee, 0) - ifnull(final_out.fee, 0)) as 'final_balance', 
        ifnull(current_in.fee, 0) as 'current_in', 
        ifnull(current_out.fee, 0) as 'current_out' ";
        $criteria->params = $criteria_log->params;
        $criteria->order = "t.priority ASC";
        $details = DictBankInfo::model()->findAll($criteria);
        $content = array();
        if (!$details) return $content;

        foreach ($details as $item) {
            $temp = array(
                $item->dict_name, 
                numChange(number_format($item->final_balance, 2)), 
                numChange(number_format($item->current_in, 2)), 
                numChange(number_format($item->current_out, 2)), 
                numChange(number_format($item->initial_balance, 2)),
            );
            array_push($content, $temp);
        }
        array_push($content, $totaldata);

        return $content;
    }

} 