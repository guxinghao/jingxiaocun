<?php echo $this->renderPartial("_form", array(
		'model' => $model, 
		'baseform' => $baseform, 
		'title_array' => $title_array, //公司
		'supply_array' => $supply_array, //供应商
		'logistics_array' => $logistics_array, //物流商
		'customer_array' => $customer_array, //客户
		'warehouse_array' => $warehouse_array, //仓库结算单位
		'gk_array' => $gk_array, //高开结算单位
		'team_array' => $team_array, //业务组
		'user_array' => $user_array, //业务员
		'pledge_array' => $pledge_array, //托盘公司
		'relations' => $relations, //关联信息
		'back_url' => $back_url, 
));?>
