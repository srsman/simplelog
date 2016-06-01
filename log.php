<?php
require_once('include.php');


$username = isset( $_SERVER['PHP_AUTH_USER'] ) ? $_SERVER['PHP_AUTH_USER'] : '';
$passwd =  isset( $_SERVER['PHP_AUTH_PW'] ) ? $_SERVER['PHP_AUTH_PW'] : '';
if( $username != 'log' || $passwd != '123' ){
	header('WWW-Authenticate: Basic realm="jlog"');
	header('HTTP/1.0 401 Unauthorized');
	echo 'username or passwd invalid';
	exit;
}
 

define( "URL", get_site_url() );
$do = isset( $_GET['do'] ) ? $_GET['do'] : '';

if( $do == 'lookup' ){
	$smarty = get_smarty();
	$smarty->assign( 'action', URL.'log.php' );

	if( isset( $_GET['module'] ) ){
		$module = isset( $_GET['module'] ) ? $_GET['module'] : '';
		$level = isset( $_GET['level'] ) ? $_GET['level'] : '';
		$name = isset( $_GET['name'] ) ? $_GET['name'] : '';
		$time_start = isset( $_GET['time_start'] ) ? $_GET['time_start'] : '';
		$time_end = isset( $_GET['time_end'] ) ? $_GET['time_end'] : '';
		$con =  isset( $_GET['con'] ) ? $_GET['con'] : '';

		$filter = Condition::get_condition( $con );
//var_dump($filter);
		$logs = JLog::findlog( $module, $level , $name , strtotime( $time_start ), strtotime( $time_end ) , $filter );
		

		$smarty->assign('module', $module );
		$smarty->assign('level', $level );
		$smarty->assign('name', $name );
		$smarty->assign('time_start', $time_start );
		$smarty->assign('time_end', $time_end );
		$smarty->assign('con', $con );
		$smarty->display( 'jlog/log_lookup.html' );
		if( !$logs ){
			echo "没有找到记录";
		}
		if( count( $logs ) > 10000  ){
			echo "记录超过了一万条，缩小时间范围再查一次吧 ";
		}else{
			dump( $logs );
		}
		
	}else{
		
		$t = new TimePoint( time() );
		var_dump(date('Y-m-d H:i:s', time() ));
		$smarty->assign( 'time_start' , date('Y-m-d H:i:s', $t->day_head() ) );
		$smarty->assign( 'time_end' , date('Y-m-d H:i:s', $t->day_foot() ) );
		$smarty->display( 'jlog/log_lookup.html' );
	}
	
}else{
	header("Location:".URL.'log.php?do=lookup');
}		

		




 


