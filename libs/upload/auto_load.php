<?php

//本文件的绝对路径
define('DOWNLOAD_UPLOAD_M_PATH', realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR);
 
 

function download_upload_load_class( $class ){  
	$file = $class.'.class.php';
	$full_path = download_upload_find_file( DOWNLOAD_UPLOAD_M_PATH.'classes'.DIRECTORY_SEPARATOR,  $file );  
	if($full_path){
		require $full_path ;
		return true;
	} 
		
	return false;
}


function download_upload_find_file( $dir, $file ){
	$full_path =  $dir.DIRECTORY_SEPARATOR.$file; 
	if( file_exists($full_path) ){
		return $full_path;
	}
	return false;
}


spl_autoload_register( 'download_upload_load_class')  ;