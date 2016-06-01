<?php

/*
问题 让controller通俗易懂

解决
	$s = $_GET['s'];
	list( $file, $controller, $action, $args ) = Route::get_func( $s );
	$content = Route::call( $file, $controller, $action, $args );
	echo $content;

*/
class Route{

	static public function get_func( $s ){
		$file = MyRoute::get_file( $s );
		$class = MyRoute::get_class( $s );

		$action = MyRoute::get_action( $s );
		$args = MyRoute::get_args( $s );

		return array( $file, $class, $action, $args );


	}

	static public function call( $file, $class, $action, $args ){
		if( !file_exists($file)){
			throw new Exception("exit : can not find file :".$file );
		}
		require_once( $file );
		if( !class_exists( $class ) ){
			throw new Exception("exit : can not find controller class :".$class );
		}
		$obj = new $class();  
		if( !method_exists( $obj, $action ) ){
			throw new Exception("exit : can not find method :".$action );
		} 
		$str = call_user_func_array( array( $obj, $action ), $args );
		 
		return $str;
	}

}