<?php

// change the following paths if necessary
$yii=dirname(__FILE__).'/../framework/yii.php';
$config=dirname(__FILE__).'/weixin/config/main.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);
include_once dirname(__FILE__).'/protected/pubfun.php';
include_once dirname(__FILE__).'/protected/APISDK/api_center.class.php';
Yii::createWebApplication($config)->run();