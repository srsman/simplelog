<?php
/**
*  任何使用本框架的工程只要载入这个文件就载入了框架
*/
//在项目根目录的文件必须定义ROOT_PATH
if( !defined('ROOT_PATH') ){
	throw new Exception('need ROOT_PATH defined!' );
}

define('NT_BOOT_PATH',  ROOT_PATH .'boot/');
define('NT_CONTROLLER_PATH', ROOT_PATH .'controller/' );  
define('NT_MODEL_PATH', ROOT_PATH .'model/' );  
define('NT_ROOT_CONFIG_PATH',  ROOT_PATH. 'config/' ); 
define('NT_SYSLOGS_PATH',  ROOT_PATH .'data/syslogs/' );
define('NT_LOGS_PATH',  ROOT_PATH .'data/logs/' );
define('NT_TEMP_PATH',  ROOT_PATH .'data/temp/' );
define('NT_LIBS_PATH',  ROOT_PATH .'libs/' );
define('NT_DATA_PATH',  ROOT_PATH .'data/' );
define('NT_ATTACHMENT_PATH',  ROOT_PATH .'data/attachment/' ); 
define('NT_VIEWS_PATH',  ROOT_PATH .'views/' );
 


//载入框架各个类库
require_once( NT_LIBS_PATH .'nt_core/auto_load.php'); 
require_once( NT_LIBS_PATH.'smarty/auto_load.php' );
require_once( NT_LIBS_PATH.'upload/auto_load.php' );
require_once( NT_LIBS_PATH.'xcrypt/auto_load.php' );

//载入主要固定配置文件
require_once(   NT_ROOT_CONFIG_PATH .'config.php');
define('NT_CURRENT_CONFIG_PATH',  NT_ROOT_CONFIG_PATH . CURRENT_CONFIG_GROUP  .'/' );
require_once(  NT_CURRENT_CONFIG_PATH  .'globalconfig.php' );


if(  DEV_MODE  == true ){
	ini_set('display_errors',1);
	error_reporting(E_ALL);
}else{
	ini_set('display_errors',0);
	error_reporting(0);
}
ini_set('log_errors',1); 
ini_set('error_log',NT_LOGS_PATH.'php_error.txt');



//自动载入model下文件夹内的文件
spl_autoload_register( array( 'NT','load_models' ) );


