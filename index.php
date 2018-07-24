<?php
ob_start();
define('THINK_PATH', './ThinkPHP/');
define('APP_NAME', 'Ab');
define('APP_PATH', './Ab/');
define('APP_DEBUG',true);
//define('RUNTIME_PATH', '/var/log/tpcache'.$_SERVER['SERVER_ADMIN'].'/');
define('OUTPUT_ENCODE',false);
define("WEB_ROOT", dirname(__FILE__) . "/");

$website = '70887';


	//DB
	define('DB_HOST_P','localhost');
	define('DB_USER_P','A7test');
	define('DB_PASS_P','A7test@123456!');
	define('DB_NAME_P','crown70887');

	//模版路径
	// define('TMPL_PATH','Ab/70887/');



if(!function_exists("spl_qq")){
	function spl_qq(&$obj){
		foreach($obj as $k=>$v){
			if(is_array($v)){
				spl_qq($v);
			}
			else{
				$_t=trim($v);
				$obj[$k]=mysql_escape_string($_t);
			}
		}
	}
}
spl_qq($_REQUEST);
spl_qq($_GET);
spl_qq($_POST);

require(THINK_PATH . "ThinkPHP.php");
?>
