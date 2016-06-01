<?php  


/*

Log::write( $key, $info );

NT_LOGS_PATH
 

*/
class Log{

	static protected $_log_dir = NT_LOGS_PATH;
	static protected $_file_name_by_time = 'Y-m-d-H'; 
	static protected $_file_name_suffix = '.log.php';
	static protected $_log_str_prefix = '<?php ';
	static protected $_log_str_suffix = "\n";

	 
	 
	static public function write( $key, $info ){
 		$str = self::_arr2str( self::_pack( $info ) );//better 直接把逻辑写在这里。封装没有意义。因为只有这里用
		$file_name =  self::_log_file_name( $key );
		if( error_log( $str, 3,  $file_name  ) ){
			return $file_name;
		}else{
			return false;
		}
		

	}

	static protected function _pack( $value ){
		$info = array();
		$info['info'] = $value;
		$info['createtime'] =  date("Y-m-d H:i:s"); 
		return $info;
	} 
	static protected function _arr2str( $info ){
		$str = json_encode( $info );
		$str = str_replace("\\/", "/",  $str ); //不对json中的斜杠进行处理
		return self::$_log_str_prefix. $str . self::$_log_str_suffix ; //better 这两个变量完全不用self的就可以。因为只有这里用.这个依赖关系是完全没有意义的。
	}
 
	static protected function _log_file_name( $key ){
		if(!$key){
			throw new Exception(" Log: log key is null ");
		}
		$file = self::$_log_dir.$key.'-'.date( self::$_file_name_by_time ). self::$_file_name_suffix ; //better 这个依赖关系没有意义
		$dir = dirname( $file );
		if( ! file_exists( $dir ) ){
			if(false === mkdir( $dir, 0777, true)){
				throw new Exception(" Log:  mkdir: ".$dir." failed");
			}
		} 
		
		return $file;
		 
	}

	 

} 