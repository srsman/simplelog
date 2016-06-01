<?php
if( !defined('ROOT_PATH') ){
	throw new Exception('access error! no ROOT_PATH defined!' );
} 
if(!defined('NT_VIEWS_PATH')){
	throw new Exception('找不到常量 : NT_VIEWS_PATH ');
}
if(!defined('NT_DATA_PATH')){
	throw new Exception('找不到常量 : NT_DATA_PATH ');
}
$simplefw_smarty_file_dir = NT_DATA_PATH.'temp/smarty/';
if( !is_writable( $simplefw_smarty_file_dir ) ){
	throw new Exception( 'smarty缓存目录不可写 : '.$simplefw_smarty_file_dir );
}

return array(
	//smarty模板所在绝对路径
	'html_dir' => NT_VIEWS_PATH,
	//smarty缓存放在这里  注意上面有检查是否可写  
	'smarty_file_dir' => $simplefw_smarty_file_dir,
	

);