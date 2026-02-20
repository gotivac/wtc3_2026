<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ E_DEPRECATED);
ini_set('max_execution_time', 10800);
ini_set('memory_limit', '4G');
set_time_limit(10800);
date_default_timezone_set("Europe/Belgrade");
// change the following paths if necessary
$yiic=dirname(__FILE__).'/../yii/framework/yiic.php';
$config=dirname(__FILE__).'/config/console.php';

require_once($yiic);
