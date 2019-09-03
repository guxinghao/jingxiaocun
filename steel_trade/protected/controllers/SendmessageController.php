<?php
class SendMessageController extends AdminBaseController
{
	//发送信息
	public function actionSmsSend()
	{
		$sendmodule = array(
				"1080933",
				"1080989"
		);
		$smslist = Sendmessage::model()->findAll("status=0");
		foreach ($smslist as $list) {
			//如果手机号码不正确，不发送短信
			if ($list->phone == "13000000000" || !is_numeric($list->phone) || strlen($list->phone)!=11 ||substr($list->phone, 0, 1) != 1) {
				Sendmessage::model()->updateByPk($list->id, array(
						"status" => 1,
						"send_at" => time()
				));
				continue;
			}
			$msg='';
			$jsoncontent = json_decode($list->content, true);
			foreach ($jsoncontent as $key => $val) {
				$msg .= "&#" . $key . "#=" . $val;
			}
			if ($msg) {
				$msg = substr($msg, 1);
			}
			$result = sendMsg($list->phone,$msg,$list->module_id);
			
			if($result){
				Sendmessage::model()->updateByPk($list->id, array(
						"status" => 1,
						"send_at" => time()
				));
			}			
		}
		@session_destroy();
	}
	
}