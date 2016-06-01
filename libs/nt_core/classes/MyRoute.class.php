<?php
//路由用的
class MyRoute{
	protected static function _check_s( $s ){
		if( strlen( $s ) == 0 ){
			return false;
		}
		if( strpos($s, '/') !== 0 ){
			return false;
		}

		if( strlen( $s ) > 3000 ){
			return false;
		}
		if( strpos($s, '.') !== false ){
			return false;
		}
		if( $s === '/' ){
			return false;
		}
		return true;
	}

	protected static function _get_right_s( $s ){
		$s = trim( $s );
		if ( !self::_check_s( $s ) ){
			return 'index/index/index';
		}
		 
		return    trim( trim($s) , '/')   ;
	}

	protected static function _s_to_arr( $s ){
		if(strpos( $s, '/' ) !== false ){
			$str_arr = explode( '/', $s );
		}else{
			$str_arr = array( $s );
		}
		return $str_arr;
	}

	//get controller file  s=/index/a/b
	public static  function get_file( $s ){
		
		$str_arr =  self::_s_to_arr( self::_get_right_s( $s ) );
		if( isset( $str_arr[0] ) && strlen( $str_arr[0] ) != 0  && isset( $str_arr[1] ) && strlen( $str_arr[1] ) != 0   ){
			$var = $str_arr[0].'/'.$str_arr[1];
			return NT_CONTROLLER_PATH . $var.'.php';
		}
		if( isset( $str_arr[0] ) && strlen( $str_arr[0] ) != 0  ){
			$var = $str_arr[0].'/index' ;
			return NT_CONTROLLER_PATH . $var.'.php';
		}



		return NT_CONTROLLER_PATH .'index/index.php';

	}
	public static function get_class( $s ){
		$str_arr =  self::_s_to_arr( self::_get_right_s( $s ) );
		if( isset( $str_arr[1] ) && strlen( $str_arr[1] ) != 0 ){
			$var = $str_arr[1];
			return $var.'_controller';
		}
		return 'index_controller';
	}

	public static function get_action( $s ){
		$str_arr =  self::_s_to_arr( self::_get_right_s( $s ) );
		 
		if( isset( $str_arr[2] ) && strlen( $str_arr[2] ) != 0 ){
			$var = $str_arr[2];
			return $var ;
		} 
		return 'index';
	}
	public static function get_args( $s ){
		$str_arr =  self::_s_to_arr( self::_get_right_s( $s ) );
		if( isset( $str_arr[3] )  ){
			return array_slice( $str_arr, 3 );
		} 
		return array();
	}
}