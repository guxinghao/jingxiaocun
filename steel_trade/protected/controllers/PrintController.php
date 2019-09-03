<?php
/**
 * 打印
 * @author leitao
 *
 */
class PrintController extends AdminBaseController 
{
	public $layout = 'admin';
	
	public function actionPrint($id) 
	{
		$baseform = CommonForms::model()->findByPK($id);
		if (!$baseform) return false;
		
		switch ($baseform->form_type) 
		{
			case 'CGD': //采购单
				$print_url = Yii::app()->createUrl('purchase/print', array('id' => $id));
				break;
			case 'XSD': //结算单
				$print_url = Yii::app()->createUrl('frmSales/print', array('id' => $id));
				break;
			case 'CGTH': //采购退货
				$print_url = Yii::app()->createUrl('frmPurchaseReturn/print', array('id' => $id));
				break;
			case 'XSTH': //销售退货
				$print_url = Yii::app()->createUrl('salesReturn/print', array('id' => $id));
				break;
			case 'FYBZ': //费用报支
				$print_url = Yii::app()->createUrl('billOther/print', array('id' => $id));
				break;
			case 'CGHT'://采购合同
				$print_url = Yii::app()->createUrl('contract/print', array('id' => $id));
				break;
			case 'DQDK'://短期借贷
				$print_url = Yii::app()->createUrl('shortLoan/print', array('id' => $id));
				break;
			case 'DQJK'://短期借贷
				$print_url = Yii::app()->createUrl('shortLoan/print', array('id' => $id));
				break;
			default: break;
		}
		
		$this->renderPartial('index', array(
				'print_url' => $print_url,
		));
	}

	//A4纸打印
	public function actionPrintA4($id)
	{
		$baseform = CommonForms::model()->findByPK($id);
		if (!$baseform) return false;
	
		switch ($baseform->form_type)
		{
			case 'CGHT'://采购合同
				$print_url = Yii::app()->createUrl('contract/print', array('id' => $id));
				break;
			default: break;
		}
	
		$this->renderPartial('indexA4', array(
				'print_url' => $print_url,
		));
	}
	
	/**
	 * 报价单打印
	 */
	public function actionQuotedPrint() 
	{
		$print_url = Yii::app()->createUrl('quotedDetail/print');

		$this->renderPartial('indexA4', array(
				'print_url' => $print_url,
		));
	}
}