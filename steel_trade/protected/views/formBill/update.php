<?php 
echo $this->renderPartial("_form", array(
		'model' => $model,
		'baseform' => $baseform,
		'supply_array' => $supply_array, //供应商
		'logistics_array' => $logistics_array, //物流商
		'customer_array' => $customer_array, //客户
		'gk_array' => $gk_array, //高开结算单位
		'warehouse_array' => $warehouse_array, //仓库结算单位
		'pledge_array' => $pledge_array, //托盘公司
		'title_array' => $title_array, //公司抬头
		'team_array' => $team_array, //业务组
		'user_array' => $user_array, //业务员
		'bank_info_array' => $bank_info_array, //结算账户
		'dict_bank_info_array' => $dict_bank_info_array, //公司账户
		'pledge_bank_info_array' => $pledge_bank_info_array, //托盘账户
		'relations' => $relations,
		'back_url' => $back_url, //返回路径
		'msg' => $msg,
));
?>
