<?php

//本文件的绝对路径
define('NT_CORE_PATH', realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR);
require_once( NT_CORE_PATH.'funcs.php' );
require_once( NT_CORE_PATH.'funcs_cgi_url.php' );

function nt_core_load_class( $class ){ 
	$file =   $class.'.class.php';
	$full_path = nt_core_find_file( NT_CORE_PATH.'classes'.DIRECTORY_SEPARATOR,  $file );  
	if($full_path){
		require $full_path ;
		return true;
	}
		
	return false;
}

function nt_core_find_file( $dir, $file ){
	$full_path =  $dir.DIRECTORY_SEPARATOR.$file; 
	if( file_exists($full_path) ){
		return $full_path;
	}
	return false;
}


spl_autoload_register( 'nt_core_load_class')  ;

 