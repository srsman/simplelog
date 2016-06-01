<?php
require_once('include.php');


if(isset( $_GET['s'] ) ){
	$s = $_GET['s'];
}else{
	$s = '/';
}

list( $file, $controller, $action, $args ) = Route::get_func( $s );
Req::clear();
Req::set( 'php_env' , 'php-fpm' );
Req::set( 'request' , array() );
Req::set( 'response', array() );
Req::set( 'file', $file );
Req::set( 'controller', $controller );
Req::set( 'action' , $action );
Req::set( 'args', $args );
 
Req::set( 'url'  ,   get_site_url() );
Req::set( 'cookie_domain', '' );
Req::set( 'view_url'  ,  Req::get( 'url')  .'views/' );
Req::set( 'attachment_url' ,  Req::get( 'url')   .'data/attachment/'  );
 
Req::set( 'get' ,  $_GET );
 
Req::set( 'post'  ,  $_POST );
 

if(isset(  $_FILES  )){
	Req::set( 'files' ,  $_FILES );
}
try{
	//todo 一个简单的访问权限验证
	/*
	if( $_GET['token'] != 'haha' ){
		$content = Apimsg::json( Apimsg::err('1000') );
	}


	*/

	$content = Route::call( $file, $controller, $action, $args );
	//记录了请求的参数和返回值，方便历史查错
	$log_file_name =  basename( dirname( $file ) ).'/' . basename( strip_file_ext( $file ) ).'/'.$action;
	Log::write( $log_file_name , array( '_GET'=> isset( $_GET ) ? $_GET:array() , '_POST'=> isset( $_POST ) ? $_POST:array() , 'return' => isset( $content ) ? $content : ''   ) );
	


}catch( Exception $e ){
	MyExp::handle( $e );
}
if( isset( $content ) ){
	echo "$content" ;
}


 