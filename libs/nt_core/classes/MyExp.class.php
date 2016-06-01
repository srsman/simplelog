<?php
class MyExp{

 
	static protected $_exit_type_exp_suffix = 'exit';
 
	//判断一个exp的类型
	static public function is_exp( $e ){
		
		$msg = $e->getMessage();
		if( !$msg ){
			return true;
		}
		if( strpos( $msg, self::$_exit_type_exp_suffix ) === 0 ){
			return false;
		}else{
			return true;
		}
	}
	 
	static protected function _log( $e  ){
		 
		if( ! self::is_exp( $e ) ){
			return null;
		}
		$data['message'] = $e->getMessage();
		 
		$data['url'] = Req::get('url');
		 
		
		$data['file'] = $e->getFile();
		$data['line'] = $e->getLine(); 
		Log::write( 'exception/exception',  $data );

	}
	//return true  non exp
	static public function handle( $e ){
		if( DEV_MODE && self::is_exp( $e ) ){
			var_dump( $e );
			self::_log( $e );
		}else{
			self::_log( $e );
		}
		
		 

	}


}