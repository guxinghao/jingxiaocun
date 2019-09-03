<?php

/**
 * 资金
 * @category I_Don't_Know.
 * @package  I_Don't_Know.
 * @author   yy_prince   <gengzicong@xun-ao.com>
 * @license  http://www.xun-ao.com YY_prince_license
 * @link     http://www.xun-ao.com
 */
class FrmBillLogController extends AdminBaseController
{
    /**
     * 资金明细
     */
    public function actionIndex() 
    {
        $this->pageTitle = "资金账户明细";
        $search = $_POST['search'];
        if(!isset($_REQUEST['FrmBillLog']))
        {
            $_POST['FrmBillLog']=array();
            $_POST['title_rl']=updateSingleCookie('cc','search_detailbill_titlerl');
            $_POST['title_cx']=updateSingleCookie('cc','search_detailbill_titlecx');
            $_POST['other']=updateSingleCookie('cc','search_detailbill_other');
        }else{
            $_POST['title_rl']=updateSingleCookie($_POST['title_rl'],'search_detailbill_titlerl');
            $_POST['title_cx']=updateSingleCookie($_POST['title_cx'],'search_detailbill_titlecx');
            $_POST['other']=updateSingleCookie($_POST['other'],'search_detailbill_other');
        }       
        $_POST['FrmBillLog']=updateSearch($_POST['FrmBillLog'],'search_detailbill');
        list($model, $items, $pages, $totaldata,$qichu) = FrmBillLog::getIndexList($search);

        $title_array = DictTitle::getComs("json");
        $company_array = DictCompany::getAllComs("json");
        $dict_bank_array = DictBankInfo::getBankList('json');
        $user_array = User::getUserList("array");

        $this->render('index', array(
            'model' => $model, 
            'items' => $items, 
            'pages' => $pages, 
            'totaldata' => $totaldata, 
            'qichu'=>$qichu,
            'title_array' => $title_array, 
            'company_array' => $company_array, 
            'dict_bank_array' => $dict_bank_array, 
            'users' => $user_array,
        	'search'=>$search,
        ));
    }

    /**
     * 资金汇总
     */
    public function actionTotal() 
    {
        $this->pageTitle = "资金账户汇总";
        if(!isset($_REQUEST['FrmBillLog']))
        {
            $_POST['FrmBillLog']=array();
            $_POST['title_rl']=updateSingleCookie('cc','search_totalbill_titlerl');
            $_POST['title_cx']=updateSingleCookie('cc','search_totalbill_titlecx');
            $_POST['other']=updateSingleCookie('cc','search_totalbill_other');
        }else{
            $_POST['title_rl']=updateSingleCookie($_POST['title_rl'],'search_totalbill_titlerl');
            $_POST['title_cx']=updateSingleCookie($_POST['title_cx'],'search_totalbill_titlecx');
            $_POST['other']=updateSingleCookie($_POST['other'],'search_totalbill_other');
        }       
        $_POST['FrmBillLog']=updateSearch($_POST['FrmBillLog'],'search_totalbill');
        
        list($model, $items, $totaldata, $msg) = FrmBillLog::getTotalList();

        $company_array = DictCompany::getAllComs('json');
        $dict_bank_array = DictBankInfo::getBankList('array');

        $this->render('total', array(
            'model' => $model, 
            'items' => $items, 
            'totaldata' => $totaldata, 
            'company_array' => $company_array, 
            'dict_bank_array' => $dict_bank_array, 
            'msg' => $msg,
        ));
    }



    public function actionIndexExport() 
    {
        $search = $_REQUEST['FrmBillLog'];
        $name = "资金账户明细".date("Y/m/d");
        $title = array("日期", "单据号", "公司资金账户", "结算单位", "入帐金额", "出帐金额", "收付类型", "收付款方式", "公司简称", "入账人", "备注");
        
        $content = FrmBillLog::getAllList($search);
        PHPExcel::ExcelExport($name, $title, $content);
    }

    public function actionTotalExport() 
    {
        $search = $_REQUEST['FrmBillLog'];
        $name = "资金账户汇总".date("Y/m/d");
        $title = array("资金账户", "期末余额", "本期入账", "本期出账", "期初余额");

        $content = FrmBillLog::getAllTotalList($search);
        PHPExcel::ExcelExport($name, $title, $content);
    }

    //更新销售退货对应老数据类型
    public function actionChangeType(){
    		$model = FrmFormBill::model()->findAll('bill_type="XSTH"');
    		$num = 0;
    		if($model){
    			foreach ($model as $li){
    				$base_id = $li->baseform_fkdj->id;
    				$log = FrmBillLog::model()->find("form_id=$base_id");
    				if($log){
    					$log->bill_type = 8;
    					$log->update();
    					$num ++;
    				}
    			}
    		}
    		echo "本次共处理数据{$num}条";
    }
}