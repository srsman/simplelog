<?php
//简单读取文件
/*
MyDir::list_dir_file( $dir );
 


*/ 
class  Dir{
	
	//返回文件名，只找一层 //better 参数名没有说明参数是一个绝对路径，还是相对路径，还是随便一个文件名  改为 absolute_dir
	//better 没有说明返回值的格式
	static public function list_dir_file( $file_name  ){
		if( ! is_dir( $file_name ) ){
			return array();
		}
		$return = array();
		if ($handle = opendir( $file_name  )) {
		  
		    /* 这是正确地遍历目录方法 */
		    while (false !== ($file = readdir($handle))) {
		    	if( $file != '.' and $file != '..'){
		    		$return[] = $file;
		    	}
		    }
		    closedir($handle);
	 
		}
		return $return;
	}
	//返回结果为绝对路径    //better 和上面一样
	//better 没有说明返回值的格式
	static public function list_dir_file2( $file_name  ){
		if( ! is_dir( $file_name ) ){
			return array();
		}
		$return = array();
		if ($handle = opendir( $file_name  )) {
		  
		    /* 这是正确地遍历目录方法 */
		    while (false !== ($file = readdir($handle))) {
		    	if( $file != '.' and $file != '..'){
		    		$return[] =  rtrim( $file_name, '/ ' ).'/'.$file;
		    	}
		    }
		    closedir($handle);
	 
		}
		return $return;
	}

	 
}



