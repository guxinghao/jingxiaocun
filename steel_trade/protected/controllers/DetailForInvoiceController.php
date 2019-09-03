<?php

/**
 * 可开票明细
 * @author yy_prince
 *
 */
class DetailForInvoiceController extends AdminBaseController
{

    public $layout = 'admin';

    // public function actionIndex_old11()
    // {
    //     $this->pageTitle = "开票统计";
    //     $model = new DetailForInvoice();
        
    //     $st_date = $_REQUEST['start_time'] ? $_REQUEST['start_time'] : date("Y-m-d", time() - 24 * 7 * 3600);
    //     $et_date = $_REQUEST['end_time'] ? $_REQUEST['end_time'] : date("Y-m-d");
       
    //     if(!$st_date||strtotime($st_date)<Yii::app()->params['turn_time'])
    //     {
    //         $st_date=date('Y-m-d',Yii::app()->params['turn_time']);
    //     }        
    //     $cri = new CDbCriteria();
    //     $search = new DetailForInvoice();
    //     if ($_POST['DetailForInvoice']) {
    //         $search->attributes = $_POST['DetailForInvoice'];
    //         if ($search->title_id) {
    //             $cri->addCondition("t.title_id=" . intval($search->title_id));
    //         }
    //         if ($search->company_id) {
    //             $cri->addCondition("t.customer_id=" . intval($search->company_id));
    //         }
    //         if($_POST['DetailForInvoice']['owned_by'])
    //         {
    //             $search->owned_by=$_POST['DetailForInvoice']['owned_by'];
    //         }
    //     }
    //     $cri->join = "left join common_forms f on f.form_id=t.id and f.form_type='XSD'";
    //     if ($st_date) {
    //         $st = strtotime($st_date . " 00:00:00");
    //         $cri->addCondition("UNIX_TIMESTAMP(f.form_time)>=$st");
    //     }
    //     if ($et_date) {
    //         $et = strtotime($et_date . " 23:59:59");
    //         $cri->addCondition("UNIX_TIMESTAMP(f.form_time)<=$et");
    //     }
    //     if($search->owned_by)
    //     {
    //         $cri->addCondition("f.owned_by ={$search->owned_by}");
    //     }
    //     $cri->group = "t.title_id,t.customer_id";
    //     $cri->order = "t.title_id,t.customer_id";
    //     $cri->addCondition("f.form_type='XSD'");
    //     $cri->addCondition("f.form_status<>'delete'");
    //     // $cri->addCondition("t.is_import<>1");
    //     $items = FrmSales::model()->findAll($cri);
    //     $array_result = array();
    //     $total_data = array();
    //     foreach ($items as $i) {
    //         $temp = new stdClass();
    //         $temp->title_id = $i->title_id;
    //         $temp->title = $i->title->short_name;
    //         $temp->company_id = $i->customer_id;
    //         $temp->company = $i->company->short_name;
    //         $temp->company_full = $i->company->name;
            
    //         // 销项发票------------------------------------------------------
    //         /*
    //          * 已审单
    //          */
    //         $c = new CDbCriteria();
    //         $c->select = "sum(t.fix_fee) as total_price,sum(t.weight) as total_weight";
    //         $c->join = "left join common_forms f on f.form_id = t.id and f.form_type='XSD'";
    //         // $c->addCondition("f.form_type = 'XSD'");
    //         $c->addCondition("t.is_yidan<>1");
    //         // $c->addCondition("t.is_import<>1");
    //         $c->addCondition("t.title_id=$i->title_id and t.customer_id=$i->customer_id");
    //         $c->group='t.title_id,t.customer_id';
    //         if ($st_date) {
    //             $st = strtotime($st_date . " 00:00:00");
    //             $c->addCondition("UNIX_TIMESTAMP(f.form_time)>=$st");
    //         }
    //         if ($et_date) {
    //             $et = strtotime($et_date . " 23:59:59");
    //             $c->addCondition("UNIX_TIMESTAMP(f.form_time)<=$et");
    //         }
    //         $c->addCondition("t.confirm_status=1");
    //         $sales0 = FrmSales::model()->find($c);


    //           //销售退货
    //         $c1 = new CDbCriteria();
    //         $c1->select = "sum(case f.weight_confirm_status when 1 then t.fix_price*t.fix_weight else  t.return_price*t.return_weight end) as re_money,
    //                     sum(case f.weight_confirm_status when 1 then t.fix_weight else t.return_weight end) as re_weight";
    //         $c1->join = " left join frm_sales_return f on f.id=t.sales_return_id left join common_forms c on c.form_id = f.id and c.form_type='XSTH'";
    //         // $c1->addCondition("f.form_type = 'XSTH'");
    //         $c1->addCondition("f.is_yidan<>1 or isnull(f.is_yidan)");
    //         $c1->addCondition("f.title_id=$i->title_id and f.company_id=$i->customer_id");
    //         $c1->addCondition("c.form_status in ('submited','approve')");
    //         $c1->group='f.title_id,f.company_id';
    //         if ($st_date) {
    //             $st = strtotime($st_date . " 00:00:00");
    //             $c1->addCondition("UNIX_TIMESTAMP(c.form_time)>=$st");
    //         }
    //         if ($et_date) {
    //             $et = strtotime($et_date . " 23:59:59");
    //             $c1->addCondition("UNIX_TIMESTAMP(c.form_time)<=$et");
    //         }
    //         // $c1->addCondition("t.confirm_status=1");
    //         $return = SalesReturnDetail::model()->find($c1);
    //         // if($return )
            
    //         /*
    //          * 未审单
    //          */
    //         $c = new CDbCriteria();
    //         $c->select = "sum(t.fee) as total_price,sum(t.weight) as total_weight";
    //         $c->join = "left join common_forms f on f.form_id = t.id and f.form_type='XSD'";
    //         $c->addCondition("f.form_type = 'XSD'");
    //         $c->addCondition("t.is_yidan<>1");
    //         // $c->addCondition("t.is_import<>1");  
    //         $c->addCondition("f.form_status<>'delete'");
    //         $c->addCondition("t.title_id=$i->title_id and t.customer_id=$i->customer_id");
    //         $c->group='t.title_id,t.customer_id';
    //         if ($st_date) {
    //             $st = strtotime($st_date . " 00:00:00");
    //             $c->addCondition("UNIX_TIMESTAMP(f.form_time)>=$st");
    //         }
    //         if ($et_date) {
    //             $et = strtotime($et_date . " 23:59:59");
    //             $c->addCondition("UNIX_TIMESTAMP(f.form_time)<=$et");
    //         }
    //         $c->addCondition("t.confirm_status=0");
    //         $sales1 = FrmSales::model()->find($c);
            
    //         $temp->should_kp_weight = floatval($sales0->total_weight) + floatval($sales1->total_weight); // 应开
    //         // $temp->should_kp_weight = floatval($return->re_weight); // 应开
    //         $temp->should_kp_money = floatval($sales0->total_price) + floatval($sales1->total_price);
    //         // $temp->should_kp_money = floatval($return->re_money);
            
    //         $c = new CDbCriteria();
    //         $c->select = "sum(t.weight) as checked_weight,sum(t.fee) as checked_price";
    //         $c->join = "left join frm_sales s on t.frm_sales_id = s.id and s.is_import<>1
	   //                  left join common_forms f on f.form_id = s.id";
    //         $c->addCondition("f.form_type='XSD'");
    //         if ($st_date) {
    //             $st = strtotime($st_date . " 00:00:00");
    //             $c->addCondition("UNIX_TIMESTAMP(f.form_time)>=$st");
    //         }
    //         if ($et_date) {
    //             $et = strtotime($et_date . " 23:59:59");
    //             $c->addCondition("UNIX_TIMESTAMP(f.form_time)<=$et");
    //         }
    //         $c->addCondition("s.title_id=$i->title_id and s.customer_id=$i->customer_id");
    //         $sales_invoice = SalesInvoiceDetail::model()->find($c);
            
    //         $temp->already_kp_weight = floatval($sales_invoice->checked_weight);
    //         $temp->already_kp_money = floatval($sales_invoice->checked_price);
    //         $temp->not_kp_weight = $temp->should_kp_weight - floatval($sales_invoice->checked_weight);
    //         $temp->not_kp_money = $temp->should_kp_money - floatval($sales_invoice->checked_price);
    //         $total_data[0] += $temp->should_kp_weight;
    //         $total_data[1] += $temp->should_kp_money;
    //         $total_data[2] += $temp->already_kp_weight;
    //         $total_data[3] += $temp->already_kp_money;
    //         $total_data[4] += $temp->not_kp_weight;
    //         $total_data[5] += $temp->not_kp_money;
    //         $array_result[] = $temp;
    //     }
        
    //     $titles = DictTitle::getComs("json");
    //     $user_array=User::getUserList();
    //     $targets = DictCompany::getAllComs("json");
    //     $this->render('index', array(
    //         'search' => $search,
    //         // 'pages'=> $pages,
    //         // 'items'=>$items,
    //         'array_result' => $array_result,
    //         'model' => $model,
    //         'titles' => $titles,
    //         'targets' => $targets,
    //         'st_date' => $st_date,
    //         'et_date' => $et_date,
    //     	'totalData'=>$total_data,
    //         'user_array'=>$user_array
    //     ));
    // }



    /*
    *开票统计新方法--2016-05-12 by xing
    */
    public function actionIndex()
    {
        $this->pageTitle = "开票统计";
        $caiwu_view = checkOperation("财务开票统计");
        $yewu_view = checkOperation("业务开票统计");
        $view = $_REQUEST['view'];
        if($view == ''){$view = $_COOKIE['DetailForInvoiceView'];}
        if(!$caiwu_view && !$yewu_view){
        	echo "You do not have permission to access this page";
        	return ;
		}else if($caiwu_view){
			if($view == ''){$view = "caiwu";}
			if($view == "yewu" && !$yewu_view){$view = "caiwu";}
		}else{
			if($view == ''){$view = "yewu";}
			if($view == "caiwu" && !$caiwu_view){$view = "yewu";}
		}
		setcookie("DetailForInvoiceView",$view,time()+3600*24*30,"/");
		$str1 = "";
		$str2 = "";
		if($view == "yewu"){
			$str1 = ",client_id";
			$str2 = ",f.client_id";
		}
		$model = new DetailForInvoice();        
        $st_date = $_REQUEST['start_time'] ? $_REQUEST['start_time'] : date("Y-m-d", time() - 24 * 7 * 3600);
        $et_date = $_REQUEST['end_time'] ? $_REQUEST['end_time'] : date("Y-m-d");       
        if(!$st_date||strtotime($st_date)<Yii::app()->params['turn_time']){
            $st_date=date('Y-m-d',Yii::app()->params['turn_time']);
        }        
        $condition='';
        $condition1='';
        $search = new DetailForInvoice();
        if ($_POST['DetailForInvoice']) {
            $search->attributes = $_POST['DetailForInvoice'];
            $search->uncheck=$_POST['DetailForInvoice']['uncheck'];
            if ($search->title_id) {
                $condition.=' and  main_title_id='.$search->title_id;
                $condition1.=' and f.title_id='.$search->title_id;
            }
            if ($search->company_id) {
                $condition.=' and customer_id='.$search->company_id;
                $condition1.= ' and f.company_id='.$search->company_id;
            }
            if ($search->client_id) {
            	$condition.=' and client_id='.$search->client_id;
            	$condition1.= ' and f.client_id='.$search->client_id;
            }
            if($_POST['DetailForInvoice']['owned_by'])
            {
            	$search->owned_by = $_POST['DetailForInvoice']['owned_by'];
                $condition.= ' and  owned_by='.$_POST['DetailForInvoice']['owned_by'];
                $condition1.=' and c.owned_by='.$_POST['DetailForInvoice']['owned_by'];
            }
        }
        if ($st_date) {
            $st = strtotime($st_date . " 00:00:00");
            $condition.=" and UNIX_TIMESTAMP(form_time)>=$st";
            $condition1.=" and UNIX_TIMESTAMP(c.form_time)>=$st";
        }
        if ($et_date) {
            $et = strtotime($et_date . " 23:59:59");
            $condition.=" and UNIX_TIMESTAMP(form_time)<=$et";
             $condition1.=" and UNIX_TIMESTAMP(c.form_time)<=$et";
        }
        $condition.=' and form_status in ("submited","approve") and is_import<>1 and is_yidan<>1';
        $condition1.=' and (is_yidan<>1 or isnull(is_yidan)) and c.form_status in ("submited","approve")';
        $sql='select sum(fee) as fee,sum(weight) as weight,title_id,customer_id'.$str1.' from (
            (select sum(detail_fee) as fee,sum(weight) as weight, main_title_id as title_id, customer_id'.$str1.' from sales_view 
                where 1=1 '.$condition.' group by main_title_id,customer_id'.$str1.') 
            union 
            (select -sum(case f.weight_confirm_status when 1 then d.fix_price*d.fix_weight else  d.return_price*d.return_weight end) as fee,
                -sum(case f.weight_confirm_status when 1 then d.fix_weight else d.return_weight end) as weight,
                f.title_id ,f.company_id as customer_id'.($str1?",f.client_id as client_id":"").' from sales_return_detail d left join frm_sales_return f on f.id=d.sales_return_id 
                left join common_forms c on c.form_id=f.id and c.form_type="XSTH" where 1=1 '.$condition1.' group by f.title_id,f.company_id'.$str2.'))
                 union_table group by title_id,customer_id'.$str1.'';
        $connection = Yii::app()->db;  
        $command = $connection->createCommand($sql);    
        $results = $command->queryAll();
        $array_result = array();
        $total_data = array();
        $t_c=array();
        foreach ($results as $i) {
           
          
            
            $c = new CDbCriteria();
            $c->select = "sum(case t.type when 'sales' then t.weight else -t.weight end) as checked_weight,sum(t.fee) as checked_price";
            $c->join = "left join frm_sales_invoice f on t.sales_invoice_id = f.id 
                        left join common_forms c on c.form_id = f.id and c.form_type='XSKP'";
            // $c->addCondition("f.form_type='XSD'");
            $c->addCondition('c.form_status="invoice"');
            $c->group='f.title_id,f.company_id'.$str2;          
            if ($st_date) {
                $st = strtotime($st_date . " 00:00:00");
                $c->addCondition("UNIX_TIMESTAMP(c.form_time)>=$st");
            }
            if($_POST['DetailForInvoice']['owned_by']){
            	$c->addCondition('c.owned_by='.$_POST['DetailForInvoice']['owned_by']);
            }
            if ($et_date){
                $et = strtotime($et_date . " 23:59:59");
                // var_dump($et);die;
                $c->addCondition("UNIX_TIMESTAMP(c.form_time)<=$et");
            }
            if($i['client_id']){
            	$c->addCondition("f.title_id=".$i['title_id']." and f.company_id=".$i['customer_id']." and f.client_id=".$i['client_id']);
            }else{
            	$c->addCondition("f.title_id=".$i['title_id']." and f.company_id=".$i['customer_id']);
            }
            $sales_invoice = SalesInvoiceDetail::model()->find($c);
            $temp = new stdClass();
            $temp->should_kp_weight = $i['weight']; // 应开
            $temp->should_kp_money = $i['fee'];
            $temp->already_kp_weight = floatval($sales_invoice->checked_weight);
            $temp->already_kp_money = floatval($sales_invoice->checked_price);
            $temp->not_kp_weight = $temp->should_kp_weight - floatval($sales_invoice->checked_weight);
            if($str2){
            	array_push($t_c, array($i['title_id'],$i['customer_id'],$i['client_id']));
            }else{
            	array_push($t_c, array($i['title_id'],$i['customer_id']));
            }
            if($search->uncheck){
            	if($search->uncheck==2){
            		if($temp->not_kp_weight==0)continue;
            	}else{
            		if($temp->not_kp_weight!=0)continue;
            	}
            }
//             if($temp->not_kp_weight==0)continue;            
            $temp->not_kp_money = $temp->should_kp_money - floatval($sales_invoice->checked_price);            
            $temp->title_id = $i['title_id'];
            $temp->title = DictTitle::getName($i['title_id']);
            $temp->company_id = $i['customer_id'];
            $temp->company = DictCompany::getName($i['customer_id']);
            $temp->company_full = DictCompany::getLongName($i['customer_id']);
            $temp->client_id = $i['client_id'];
            $temp->client = DictCompany::getName($i['client_id']);
            $temp->client_full = DictCompany::getLongName($i['client_id']);
            
            $total_data[0] += $temp->should_kp_weight;
            $total_data[1] += $temp->should_kp_money;
            $total_data[2] += $temp->already_kp_weight;
            $total_data[3] += $temp->already_kp_money;
            $total_data[4] += $temp->not_kp_weight;
            $total_data[5] += $temp->not_kp_money;
            $array_result[] = $temp;
			
		}

        $c1 = new CDbCriteria();
        $c1->select = "sum(case t.type when 'sales' then  t.weight else -t.weight end) as checked_weight,sum(t.fee) as checked_price,f.title_id,f.company_id".$str2;
        $c1->join = "left join frm_sales_invoice f on t.sales_invoice_id = f.id 
                    left join common_forms c on c.form_id = f.id and c.form_type='XSKP'";
        $c1->addCondition('c.form_status="invoice"');
        $c1->group='f.title_id,f.company_id'.$str2;
		if ($search->title_id) {
        	$c1->addCondition('f.title_id='.$search->title_id);
        }
        if ($search->company_id) {
        	$c1->addCondition('f.company_id='.$search->company_id);
        }
        if ($search->client_id) {
        	$c1->addCondition('f.client_id='.$search->client_id);
        }
        if($_POST['DetailForInvoice']['owned_by']){
        	$c1->addCondition('c.owned_by='.$_POST['DetailForInvoice']['owned_by']);
        }
        if ($st_date) {
            $st = strtotime($st_date . " 00:00:00");
            $c1->addCondition("UNIX_TIMESTAMP(c.form_time)>=$st");
        }
        if ($et_date) {
            $et = strtotime($et_date . " 23:59:59");
            $c1->addCondition("UNIX_TIMESTAMP(c.form_time)<=$et");
        }
        $sales_invoice1 = SalesInvoiceDetail::model()->findAll($c1);
        
        if($sales_invoice1)
        {
            foreach ($sales_invoice1 as $each) {
            	if($search->uncheck==1&&floatval($each->checked_weight)!=0)continue;
            	if($str2){
            		if(!in_array(array($each->title_id,$each->company_id,$each->client_id),$t_c))
            		{
            			$temp = new stdClass();
            			$temp->title_id = $each->title_id;
            			$temp->title = DictTitle::getName($each->title_id);
            			$temp->company_id = $each->company_id;
            			$temp->company = DictCompany::getName($each->company_id);
            			$temp->company_full = DictCompany::getLongName($each->company_id);
            			$temp->client_id = $each->client_id;
            			$temp->client = DictCompany::getName($each->client_id);
            			$temp->client_full = DictCompany::getLongName($each->client_id);
            			$temp->should_kp_weight = 0; // 应开
            			$temp->should_kp_money = 0;
            			$temp->already_kp_weight = floatval($each->checked_weight);
            			$temp->already_kp_money = floatval($each->checked_price);
            			$temp->not_kp_weight = $temp->should_kp_weight - floatval($each->checked_weight);
            			$temp->not_kp_money = $temp->should_kp_money - floatval($each->checked_price);
            			$total_data[0] += $temp->should_kp_weight;
            			$total_data[1] += $temp->should_kp_money;
            			$total_data[2] += $temp->already_kp_weight;
            			$total_data[3] += $temp->already_kp_money;
            			$total_data[4] += $temp->not_kp_weight;
            			$total_data[5] += $temp->not_kp_money;
            			$array_result[] = $temp;
            		}
            	}else{
            		if(!in_array(array($each->title_id,$each->company_id),$t_c))
            		{
            			$temp = new stdClass();
            			$temp->title_id = $each->title_id;
            			$temp->title = DictTitle::getName($each->title_id);
            			$temp->company_id = $each->company_id;
            			$temp->company = DictCompany::getName($each->company_id);
            			$temp->company_full = DictCompany::getLongName($each->company_id);
            			$temp->should_kp_weight = 0; // 应开
            			$temp->should_kp_money = 0;
            			$temp->already_kp_weight = floatval($each->checked_weight);
            			$temp->already_kp_money = floatval($each->checked_price);
            			$temp->not_kp_weight = $temp->should_kp_weight - floatval($each->checked_weight);
            			$temp->not_kp_money = $temp->should_kp_money - floatval($each->checked_price);
            			$total_data[0] += $temp->should_kp_weight;
            			$total_data[1] += $temp->should_kp_money;
            			$total_data[2] += $temp->already_kp_weight;
            			$total_data[3] += $temp->already_kp_money;
            			$total_data[4] += $temp->not_kp_weight;
            			$total_data[5] += $temp->not_kp_money;
            			$array_result[] = $temp;
            		}
            	}
                
            }
        }
       
        $titles = DictTitle::getComs("json");
        $user_array=User::getUserList();
        $targets = DictCompany::getAllComs("json");
        $this->render('index', array(
            'search' => $search,
            // 'pages'=> $pages,
            'array_result' => $array_result,
            'model' => $model,
            'titles' => $titles,
            'targets' => $targets,
            'st_date' => $st_date,
            'et_date' => $et_date,
            'totalData'=>$total_data,
            'user_array'=>$user_array,
        	'caiwu_view'=>$caiwu_view,
        	'yewu_view'=>$yewu_view,
        	'view'=>$view,
        ));
    }


    /**
     * 明细导出
     */
    public function actionIndexExport()
    {
    	$name = "开票汇总".date("Y/m/d");
    	$title = array("公司抬头","结算单位","应开票重量","应开票金额","已开票重量","已开票金额","未开票重量","未开票金额");
    	$view = "caiwu";
    	$model = new DetailForInvoice();        
        $st_date = $_REQUEST['start_time'] ? $_REQUEST['start_time'] : date("Y-m-d", time() - 24 * 7 * 3600);
        $et_date = $_REQUEST['end_time'] ? $_REQUEST['end_time'] : date("Y-m-d");       
        if(!$st_date||strtotime($st_date)<Yii::app()->params['turn_time']){
            $st_date=date('Y-m-d',Yii::app()->params['turn_time']);
        }        
        $condition='';
        $condition1='';
        $search = new DetailForInvoice();
        if ($_POST['DetailForInvoice']) {
            $search->attributes = $_POST['DetailForInvoice'];
            if ($search->title_id) {
                $condition.=' and  main_title_id='.$search->title_id;
                $condition1.=' and f.title_id='.$search->title_id;
            }
            if ($search->company_id) {
                $condition.=' and customer_id='.$search->company_id;
                $condition1.= ' and f.company_id='.$search->company_id;
            }
            if ($search->client_id) {
            	$condition.=' and client_id='.$search->client_id;
            	$condition1.= ' and f.client_id='.$search->client_id;
            }
            if($_POST['DetailForInvoice']['owned_by'])
            {
                $condition.= ' and  owned_by='.$_POST['DetailForInvoice']['owned_by'];
                $condition1.=' and c.owned_by='.$_POST['DetailForInvoice']['owned_by'];
            }
        }
        if ($st_date) {
            $st = strtotime($st_date . " 00:00:00");
            $condition.=" and UNIX_TIMESTAMP(form_time)>=$st";
            $condition1.=" and UNIX_TIMESTAMP(c.form_time)>=$st";
        }
        if ($et_date) {
            $et = strtotime($et_date . " 23:59:59");
            $condition.=" and UNIX_TIMESTAMP(form_time)<=$et";
             $condition1.=" and UNIX_TIMESTAMP(c.form_time)<=$et";
        }
        $condition.=' and form_status in ("submited","approve") and is_import<>1 and is_yidan<>1';
        $condition1.=' and (is_yidan<>1 or isnull(is_yidan)) and c.form_status in ("submited","approve")';
        $sql='select sum(fee) as fee,sum(weight) as weight,title_id,customer_id'.$str1.' from (
            (select sum(detail_fee) as fee,sum(weight) as weight, main_title_id as title_id, customer_id'.$str1.' from sales_view 
                where 1=1 '.$condition.' group by main_title_id,customer_id'.$str1.') 
            union 
            (select -sum(case f.weight_confirm_status when 1 then d.fix_price*d.fix_weight else  d.return_price*d.return_weight end) as fee,
                -sum(case f.weight_confirm_status when 1 then d.fix_weight else d.return_weight end) as weight,
                f.title_id ,f.company_id as customer_id'.($str1?",f.client_id as client_id":"").' from sales_return_detail d left join frm_sales_return f on f.id=d.sales_return_id 
                left join common_forms c on c.form_id=f.id and c.form_type="XSTH" where 1=1 '.$condition1.' group by f.title_id,f.company_id'.$str2.'))
                 union_table group by title_id,customer_id'.$str1.'';
        $connection = Yii::app()->db;  
        $command = $connection->createCommand($sql);    
        $results = $command->queryAll();
        $array_result = array();
        $total_data = array();
        $t_c=array();
        foreach ($results as $i) {
            $temp = new stdClass();
            $temp->title_id = $i['title_id'];
            $temp->title = DictTitle::getName($i['title_id']);
            $temp->company_id = $i['customer_id'];
            $temp->company = DictCompany::getName($i['customer_id']);
            $temp->company_full = DictCompany::getLongName($i['customer_id']);
            $temp->client_id = $i['client_id'];
            $temp->client = DictCompany::getName($i['client_id']);
            $temp->client_full = DictCompany::getLongName($i['client_id']);
            
            $temp->should_kp_weight = $i['weight']; // 应开
            $temp->should_kp_money = $i['fee'];
            
            $c = new CDbCriteria();
            $c->select = "sum(t.weight) as checked_weight,sum(t.fee) as checked_price";
            $c->join = "left join frm_sales_invoice f on t.sales_invoice_id = f.id 
                        left join common_forms c on c.form_id = f.id and c.form_type='XSKP'";
            // $c->addCondition("f.form_type='XSD'");
            $c->addCondition('c.form_status="invoice"');
            $c->group='f.title_id,f.company_id'.$str2;          
            if ($st_date) {
                $st = strtotime($st_date . " 00:00:00");
                $c->addCondition("UNIX_TIMESTAMP(c.form_time)>=$st");
            }
            if($_POST['DetailForInvoice']['owned_by']){
            	$c->addCondition('c.owned_by='.$_POST['DetailForInvoice']['owned_by']);
            }
            if ($et_date){
                $et = strtotime($et_date . " 23:59:59");
                // var_dump($et);die;
                $c->addCondition("UNIX_TIMESTAMP(c.form_time)<=$et");
            }
            if($i['client_id']){
            	$c->addCondition("f.title_id=".$i['title_id']." and f.company_id=".$i['customer_id']." and f.client_id=".$i['client_id']);
            }else{
            	$c->addCondition("f.title_id=".$i['title_id']." and f.company_id=".$i['customer_id']);
            }
            $sales_invoice = SalesInvoiceDetail::model()->find($c);
            
            $temp->already_kp_weight = floatval($sales_invoice->checked_weight);
            $temp->already_kp_money = floatval($sales_invoice->checked_price);
            $temp->not_kp_weight = $temp->should_kp_weight - floatval($sales_invoice->checked_weight);
            $temp->not_kp_money = $temp->should_kp_money - floatval($sales_invoice->checked_price);
            $total_data[0] += $temp->should_kp_weight;
            $total_data[1] += $temp->should_kp_money;
            $total_data[2] += $temp->already_kp_weight;
            $total_data[3] += $temp->already_kp_money;
            $total_data[4] += $temp->not_kp_weight;
            $total_data[5] += $temp->not_kp_money;
            $array_result[] = $temp;
			if($str2){
				array_push($t_c, array($i['title_id'],$i['customer_id'],$i['client_id']));
			}else{
				array_push($t_c, array($i['title_id'],$i['customer_id']));
			}
		}

        $c1 = new CDbCriteria();
        $c1->select = "sum(t.weight) as checked_weight,sum(t.fee) as checked_price,f.title_id,f.company_id".$str2;
        $c1->join = "left join frm_sales_invoice f on t.sales_invoice_id = f.id 
                    left join common_forms c on c.form_id = f.id and c.form_type='XSKP'";
        $c1->addCondition('c.form_status="invoice"');
        $c1->group='f.title_id,f.company_id'.$str2;
		if ($search->title_id) {
        	$c1->addCondition('f.title_id='.$search->title_id);
        }
        if ($search->company_id) {
        	$c1->addCondition('f.company_id='.$search->company_id);
        }
        if ($search->client_id) {
        	$c1->addCondition('f.client_id='.$search->client_id);
        }
        if($_POST['DetailForInvoice']['owned_by']){
        	$c1->addCondition('c.owned_by='.$_POST['DetailForInvoice']['owned_by']);
        }
        if ($st_date) {
            $st = strtotime($st_date . " 00:00:00");
            $c1->addCondition("UNIX_TIMESTAMP(c.form_time)>=$st");
        }
        if ($et_date) {
            $et = strtotime($et_date . " 23:59:59");
            $c1->addCondition("UNIX_TIMESTAMP(c.form_time)<=$et");
        }
        $sales_invoice1 = SalesInvoiceDetail::model()->findAll($c1);
        
        if($sales_invoice1)
        {
            foreach ($sales_invoice1 as $each) {
            	if($str2){
            		if(!in_array(array($each->title_id,$each->company_id,$each->client_id),$t_c))
            		{
            			$temp = new stdClass();
            			$temp->title_id = $each->title_id;
            			$temp->title = DictTitle::getName($each->title_id);
            			$temp->company_id = $each->company_id;
            			$temp->company = DictCompany::getName($each->company_id);
            			$temp->company_full = DictCompany::getLongName($each->company_id);
            			$temp->client_id = $each->client_id;
            			$temp->client = DictCompany::getName($each->client_id);
            			$temp->client_full = DictCompany::getLongName($each->client_id);
            			$temp->should_kp_weight = 0; // 应开
            			$temp->should_kp_money = 0;
            			$temp->already_kp_weight = floatval($each->checked_weight);
            			$temp->already_kp_money = floatval($each->checked_price);
            			$temp->not_kp_weight = $temp->should_kp_weight - floatval($each->checked_weight);
            			$temp->not_kp_money = $temp->should_kp_money - floatval($each->checked_price);
            			$total_data[0] += $temp->should_kp_weight;
            			$total_data[1] += $temp->should_kp_money;
            			$total_data[2] += $temp->already_kp_weight;
            			$total_data[3] += $temp->already_kp_money;
            			$total_data[4] += $temp->not_kp_weight;
            			$total_data[5] += $temp->not_kp_money;
            			$array_result[] = $temp;
            		}
            	}else{
            		if(!in_array(array($each->title_id,$each->company_id),$t_c))
            		{
            			$temp = new stdClass();
            			$temp->title_id = $each->title_id;
            			$temp->title = DictTitle::getName($each->title_id);
            			$temp->company_id = $each->company_id;
            			$temp->company = DictCompany::getName($each->company_id);
            			$temp->company_full = DictCompany::getLongName($each->company_id);
            			$temp->should_kp_weight = 0; // 应开
            			$temp->should_kp_money = 0;
            			$temp->already_kp_weight = floatval($each->checked_weight);
            			$temp->already_kp_money = floatval($each->checked_price);
            			$temp->not_kp_weight = $temp->should_kp_weight - floatval($each->checked_weight);
            			$temp->not_kp_money = $temp->should_kp_money - floatval($each->checked_price);
            			$total_data[0] += $temp->should_kp_weight;
            			$total_data[1] += $temp->should_kp_money;
            			$total_data[2] += $temp->already_kp_weight;
            			$total_data[3] += $temp->already_kp_money;
            			$total_data[4] += $temp->not_kp_weight;
            			$total_data[5] += $temp->not_kp_money;
            			$array_result[] = $temp;
            		}
            	}
                
            }
        }
        $content = array();
        foreach ($array_result as $item){
        	$list = array(
        			$item->title,
        			$item->company_full,
        			numChange(number_format($item->should_kp_weight,3)),
        			numChange(number_format($item->should_kp_money,2)),
        			numChange(number_format($item->already_kp_weight,3)),
        			numChange(number_format($item->already_kp_money,2)),
        			numChange(number_format($item->not_kp_weight,3)),
        			numChange(number_format($item->not_kp_money,2))
        	);
        	array_push($content, $list);
        }
        $list = array(
        		"","",$total_data[0],$total_data[1],$total_data[2],$total_data[3],$total_data[4],$total_data[5]
        );
        array_push($content, $list);
    	PHPExcel::ExcelExport($name, $title, $content);
    }




    public function actionIndex1()
    {
        $this->pageTitle = "销票统计";
        if(isset($_REQUEST['search']))
        {
        	$search=$_REQUEST['search'];
        }else{
        	$search['start_time']=date("Y-m-d", time() - 24 * 7 * 3600);
        	$search['end_time']=date("Y-m-d");
        }
        list($tableHeader,$tableData)=PurchaseInvoiceDetail::invoiceData($search);
        $titles = DictTitle::getComs("json");
        $targets = DictCompany::getAllVendorList('json');//供应商
        $this->render('index1', array(
        		'search' => $search,
        		'tableHeader' => $tableHeader,
        		'tableData' => $tableData,
        		'titles' => $titles,
        		'targets' => $targets,
        ));
        
        /////////////////////////////////////////
        
        
//         $model = new DetailForInvoice();
        
//         $st_date = $_REQUEST['start_time'] ? $_REQUEST['start_time'] : date("Y-m-d", time() - 24 * 7 * 3600);
//         $et_date = $_REQUEST['end_time'] ? $_REQUEST['end_time'] : date("Y-m-d");
        
//         $cri = new CDbCriteria();
//         $search = new DetailForInvoice();
//         if ($_POST['DetailForInvoice']) {
//             $search->attributes = $_POST['DetailForInvoice'];
//             if ($search->title_id) {
//                 $cri->addCondition("t.title_id=" . intval($search->title_id));
//             }
//             if ($search->company_id) {
//                 $cri->addCondition("t.supply_id=" . intval($search->company_id));
//             }
//         }
//         $cri->join = "left join common_forms f on f.form_id = t.id";
//         if ($st_date) {
//             $st = strtotime($st_date . " 00:00:00");
//             $cri->addCondition("f.created_at>=$st");
//         }
//         if ($et_date) {
//             $et = strtotime($et_date . " 23:59:59");
//             $cri->addCondition("f.created_at<=$et");
//         }
//         $cri->group = "t.title_id,t.supply_id";
//         $cri->order = "t.title_id,t.supply_id";
//         $cri->addCondition("f.form_type='CGD'");
//         $items = FrmPurchase::model()->findAll($cri);
//         $array_result = array();
        
//         foreach ($items as $i) {
//             $temp = new stdClass();
//             $temp->title_id = $i->title_id;
//             $temp->title = $i->title->short_name;
//             $temp->company_id = $i->supply_id;
//             $temp->company = $i->company->short_name;
//             $temp->company_full = $i->company->name;
            
//             // 进项发票------------------------------------------------------
//             /*
//              * 第1种情况
//              */
//             $c = new CDbCriteria();
//             $c->select = "sum(t.weight) as total_weight,sum(t.price_amount) as total_price";
//             $c->join = "left join common_forms f on f.form_id = t.id";
//             $c->addCondition("f.form_type = 'CGD'");
//             $c->addCondition("t.is_yidan<>1");
//             $c->addCondition("t.title_id=$i->title_id and t.supply_id=$i->supply_id");
//             if ($st_date) {
//                 $st = strtotime($st_date . " 00:00:00");
//                 $c->addCondition("f.created_at>=$st");
//             }
//             if ($et_date) {
//                 $et = strtotime($et_date . " 23:59:59");
//                 $c->addCondition("f.created_at<=$et");
//             }
//             $c->addCondition("t.weight_confirm_status=0 and t.price_confirm_status=0");
//             $purchase00 = FrmPurchase::model()->find($c);
            
//             /*
//              * 第2种情况
//              */
            
//             $c = new CDbCriteria();
//             $c->select = "sum(t.confirm_weight) as total_weight,sum(t.price_amount) as total_price";
//             $c->join = "left join common_forms f on f.form_id = t.id";
//             $c->addCondition("f.form_type = 'CGD'");
//             $c->addCondition("t.is_yidan<>1");
//             $c->addCondition("t.title_id=$i->title_id and t.supply_id=$i->supply_id");
//             if ($st_date) {
//                 $st = strtotime($st_date . " 00:00:00");
//                 $c->addCondition("f.created_at>=$st");
//             }
//             if ($et_date) {
//                 $et = strtotime($et_date . " 23:59:59");
//                 $c->addCondition("f.created_at<=$et");
//             }
//             $c->addCondition("t.weight_confirm_status=1 and t.price_confirm_status=0");
//             $purchase10 = FrmPurchase::model()->find($c);
//             /*
//              * 第3种情况
//              */
//             $c = new CDbCriteria();
//             $c->select = "sum(t.weight) as total_weight,sum(t.confirm_cost) as total_price";
//             $c->join = "left join common_forms f on f.form_id = t.id";
//             $c->addCondition("f.form_type = 'CGD'");
//             $c->addCondition("t.is_yidan<>1");
//             $c->addCondition("t.title_id=$i->title_id and t.supply_id=$i->supply_id");
//             if ($st_date) {
//                 $st = strtotime($st_date . " 00:00:00");
//                 $c->addCondition("f.created_at>=$st");
//             }
//             if ($et_date) {
//                 $et = strtotime($et_date . " 23:59:59");
//                 $c->addCondition("f.created_at<=$et");
//             }
//             $c->addCondition("t.weight_confirm_status=0 and t.price_confirm_status=1");
//             $purchase01 = FrmPurchase::model()->find($c);
//             /*
//              * 第4种情况
//              */
//             $c = new CDbCriteria();
//             $c->select = "sum(t.confirm_weight) as total_weight,sum(t.confirm_cost) as total_price";
//             $c->join = "left join common_forms f on f.form_id = t.id";
//             $c->addCondition("f.form_type = 'CGD'");
//             $c->addCondition("t.is_yidan<>1");
//             $c->addCondition("t.title_id=$i->title_id and t.supply_id=$i->supply_id");
//             if ($st_date) {
//                 $st = strtotime($st_date . " 00:00:00");
//                 $c->addCondition("f.created_at>=$st");
//             }
//             if ($et_date) {
//                 $et = strtotime($et_date . " 23:59:59");
//                 $c->addCondition("f.created_at<=$et");
//             }
//             $c->addCondition("t.weight_confirm_status=1 and t.price_confirm_status=1");
//             $purchase11 = FrmPurchase::model()->find($c);
            
//             $temp->should_xp_weight = floatval($purchase00->total_weight) + floatval($purchase01->total_weight) + floatval($purchase10->total_weight) + floatval($purchase11->total_weight);
//             $temp->should_xp_money = floatval($purchase00->total_price) + floatval($purchase01->total_price) + floatval($purchase10->total_price) + floatval($purchase11->total_price);
            
//             $c = new CDbCriteria();
//             $c->select = "sum(t.weight) as checked_weight,sum(t.fee) as checked_price";
//             $c->join = "left join frm_purchase p on t.frm_purchase_id = p.id
// 	                    left join common_forms f on f.form_id = p.id";
//             $c->addCondition("f.form_type='CGD'");
//             if ($st_date) {
//                 $st = strtotime($st_date . " 00:00:00");
//                 $c->addCondition("f.created_at>=$st");
//             }
//             if ($et_date) {
//                 $et = strtotime($et_date . " 23:59:59");
//                 $c->addCondition("f.created_at<=$et");
//             }
//             $c->addCondition("p.title_id=$i->title_id and p.supply_id=$i->supply_id");
//             $purchase_invoice = PurchaseInvoiceDetail::model()->find($c);
//             $temp->already_xp_weight = floatval($purchase_invoice->checked_weight);
//             $temp->already_xp_money = floatval($purchase_invoice->checked_price);
//             $temp->not_xp_weight = $temp->should_xp_weight - floatval($purchase_invoice->checked_weight);
//             $temp->not_xp_money = $temp->should_xp_money - floatval($purchase_invoice->checked_price);
            
//             $array_result[] = $temp;
//         }
        
//         $titles = DictTitle::getComs("json");
//         $targets = DictCompany::getComs("json");
//         $this->render('index1', array(
//             'search' => $search,
//             // 'pages'=> $pages,
//             // 'items'=>$items,
//             'array_result' => $array_result,
//             'model' => $model,
//             'titles' => $titles,
//             'targets' => $targets,
//             'st_date' => $st_date,
//             'et_date' => $et_date
//         ));
    }

    public function actionIndex_old()
    {
        $this->pageTitle = "发票统计";
        $model = new DetailForInvoice();
        
        $st_date = $_REQUEST['start_time'] ? $_REQUEST['start_time'] : date("Y-m-d", time() - 24 * 7 * 3600);
        $et_date = $_REQUEST['end_time'] ? $_REQUEST['end_time'] : date("Y-m-d");
        
        $cri = new CDbCriteria();
        $search = new DetailForInvoice();
        if ($_POST['DetailForInvoice']) {
            $search->attributes = $_POST['DetailForInvoice'];
            if ($search->title_id) {
                $cri->addCondition("title_id=" . intval($search->title_id));
            }
            if ($search->company_id) {
                $cri->addCondition("company_id=" . intval($search->company_id));
            }
        }
        $cri->group = "title_id,company_id";
        $cri->order = "title_id,company_id";
        $items = $model->findAll($cri);
        $array_result = array();
        
        foreach ($items as $i) {
            $temp = new stdClass();
            $temp->title_id = $i->title_id;
            $temp->title = $i->title->short_name;
            $temp->company_id = $i->company_id;
            $temp->company = $i->company->short_name;
            $temp->company_full = $i->company->name;
            $temp->qmye = 0; // 余额。不知咋算
                             
            // 销项发票
            $c = new CDbCriteria();
            $c->select = "sum(t.money) as total_money,sum(t.weight) as total_weight,sum(t.checked_money) as total_checked_money,sum(t.checked_weight) as total_checked_weight";
            
            $c->join = "left join sales_invoice_detail s on s.sales_detail_id = t.id 
						left join frm_sales_invoice i on i.id = s.sales_invoice_id 
						left join common_forms f on i.id= f.form_id";
            $c->addCondition("f.form_type = 'XSKP'");
            if ($st_date) {
                $st = strtotime($st_date . " 00:00:00");
                $c->addCondition("f.created_at>=$st");
            }
            if ($et_date) {
                $et = strtotime($et_date . " 23:59:59");
                $c->addCondition("f.created_at<=$et");
            }
            
            $c->addCondition("t.type = 'sales'");
            $c->addCondition("t.title_id = $i->title_id and t.company_id = $i->company_id");
            $c->group = "t.title_id,t.company_id";
            $sale = DetailForInvoice::model()->find($c);
            
            $temp->should_kp_weight = $sale->total_weight;
            $temp->should_kp_money = $sale->total_money;
            $temp->not_kp_weight = $sale->total_weight - $sale->total_checked_weight;
            $temp->not_kp_money = $sale->total_money - $sale->total_checked_money;
            
            // 进项发票
            $c = new CDbCriteria();
            $c->select = "sum(t.money) as total_money,sum(t.weight) as total_weight,sum(t.checked_money) as total_checked_money,sum(t.checked_weight) as total_checked_weight";
            
            $c->join = "left join purchase_invoice_detail p on p.id = t.detail_id 
						left join frm_purchase_invoice i on i.id = p.purchase_invoice_id 
						left join common_forms f on i.id= f.form_id";
            $c->addCondition("f.form_type = 'CGKP'");
            if ($st_date) {
                $st = strtotime($st_date . " 00:00:00");
                $c->addCondition("f.created_at>=$st");
            }
            if ($et_date) {
                $et = strtotime($et_date . " 23:59:59");
                $c->addCondition("f.created_at<=$et");
            }
            
            $c->addCondition("t.type = 'purchase'");
            $c->addCondition("t.title_id = $i->title_id and t.company_id = $i->company_id");
            $c->group = "t.title_id,t.company_id";
            $purchase = DetailForInvoice::model()->find($c);
            
            $temp->should_xp_weight = $purchase->total_weight;
            $temp->should_xp_money = $purchase->total_money;
            $temp->already_xp_weight = $purchase->total_checked_weight;
            $temp->already_xp_money = $purchase->total_checked_money;
            $temp->not_xp_weight = $purchase->total_weight - $purchase->total_checked_weight;
            $temp->not_xp_money = $purchase->total_money - $purchase->total_checked_money;
            $array_result[] = $temp;
        }
        
        $titles = DictTitle::getComs("json");
        $targets = DictCompany::getComs("json");
        $this->render('index', array(
            'search' => $search,
            // 'pages'=> $pages,
            // 'items'=>$items,
            'array_result' => $array_result,
            'model' => $model,
            'titles' => $titles,
            'targets' => $targets,
            'st_date' => $st_date,
            'et_date' => $et_date
        ));
    }
    
    //随机生成可开票信息
    public function actionRandInvoice(){
    	$num = 0;
    	while(true){
    		$result = DetailForInvoice::RandInvoice();
    		if($result){
    			$num ++;
    			if($num>=1000){
    				break;
    			}
    		}
    	}
    }
    
    //初始化可开票信息金额
    public function actionSetData(){
    	$model = DetailForInvoice::model()->findAll("type='sales' or type='salesreturn'");
    	if($model){
    		$num = 0;
    		foreach ($model as $li){
    			if($li->type == "sales"){
	    			$detail = SalesDetail::model()->findByPk($li->detail_id);
	    			$sales = $detail->FrmSales;
	    			$baseform = $sales->baseform;
	    			$fee = floatval($detail->price*$li->weight);
	    			$money = floatval($li->money);
	    			if($li->weight != $detail->weight){
	    				echo "销售单{$baseform->form_sn}：明细id{$detail->id}重量不同<br/>";
	    			}else{
	    				if($money != $fee){
	    					$num ++;
	    					echo "销售单{$baseform->form_sn}：{$money}=>{$fee}<br/>";
	    					$li->money = $fee;
	    					//$li->checked_money = $fee;
	    					$li->update();
	    				}
	    				if($li->checked_weight > 0){
	    					$li->checked_money = $fee;
	    					$li->update();
	    					$invoice = SalesInvoiceDetail::model()->find("type='sales' and frm_sales_detail_id=".$li->detail_id);
	    					if($invoice->fee != $fee){
	    						$cha = $invoice->fee - $fee;
	    						$invoice->fee = $fee;
	    						$invoice->update();
	    						$in_main = $invoice->salesInvoice;
	    						$in_main->fee= $in_main->fee-$cha;
	    						$in_main->update();
	    					}
	    				}
	    			}
    			}else{
    				$detail = SalesReturnDetail::model()->findByPk($li->detail_id);
    				$sales = $detail->salesReturn;
    				$baseform = $sales->baseform;
    				$fee = -floatval($detail->return_price*$li->weight);
    				$money = floatval($li->money);
    				if($money != $fee){
    					$num ++;
    					echo "销售退货单{$baseform->form_sn}：{$money}=>{$fee}<br/>";
	    				$li->money = $fee;
	    				//$li->checked_money = $fee;
	    				$li->update();  
    				 }
    			}
    		}
    		echo $num;
    	}
    }
}