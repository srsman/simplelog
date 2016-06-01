<?php

class Nnull{

	static public function str( $str ){
		if( is_array( $str ) ){
			throw new Exception('Nnull: supposed to be str ');
		}
		 
		if( strlen( $str ) == 0 ){
			throw new Exception('Nnull: str null');
		}
		return $str;
		 
	}

	static public function obj( $var ){
		if( is_object( $var ) ){
			return $var;
		}
		throw new Exception("Nnull:: not obj ");
		 
	}

 

	//数字不能是0
	static public function num( $num  ){
		if( is_array( $num )){
			throw new Exception('Nnull:  supposed to be num ');
		}
		if( ! is_numeric($num)){
			throw new Exception('Nnull: not num');
		}
		if( !$num ){
			throw new Exception('Nnull: num null');
		}

		return $num;
		 
	}
	 
	 
	//非空数组
	static public function arr( $arr ){
		 
		if( is_array( $arr ) && $arr ){
			return $arr;
		}else{
			throw new Exception( 'Nnull: arr empty' );
		}
	}


	//no empty   注意0是可以通过检测的
	static public function mixed( $value   ){
		if( !$msg  ){
			$msg = '参数不能是empty';
		}
		if( ! in_array($value, array(NULL, FALSE, '', array()), TRUE) ){
			return $value ;
		}else{
			throw new Exception( $msg );
		}
	}
	 
	 

}