<?php

/*
	问题  1 当使用swoole的时候，一些例如本站根url等可能因为请求不同而不同的数据不能用常量表示
		  2 这些数据最好能够在所有的代码库中轻易引用

	解决  把这些数据不用常量表示，也不用全局变量表示，放在一个类的静态变量中 
	Req::set('url', 'www.artqiyi.com');
	$url = Req::get('url'); //获取
	$all = Req::get();//查看全部
	Req::clear(); //清空

	
	*/


class Req{

	static public $_data = array();

	static public function set( $key, $value ){
		if( strlen( $key ) == 0 ){
			throw new Exception('key can not be null ');
		}

		self::$_data[$key] = $value;
		return true;
	}

	static public function get( $key = null ){
		if( $key === null || strlen( $key ) == 0 ){
			return self::$_data;
		}
		if( isset( self::$_data[$key] ) ){
			return self::$_data[$key] ;
		}
		return null;
	}
	static public function clear(){
		self::$_data = array();
	}
}