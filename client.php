<?php
// 
//使用示例  JLogClient::write('vip', 'info', 'getcard', array('xxxxxx'=>'xxxxxxx') );


class JLogClient{
	static $url =  '';//log服务器的地址 最好放在独立的配置文件中
	static $port = 9527;//log服务器的端口

	//module 自定义模块名称  level自定义等级  name自定义名称  content数组或者字符串或者数字，代表log内容，可以多维数组
	static public function write( $module , $level , $name , $content ){
		$data = array(
			'module' => $module,//模块名称  自定义
			'level' => $level, //log等级  自定义
			'name' =>  $name , //log名称 自定义
			'content' => $content,
		);
		$sock = socket_create( AF_INET, SOCK_DGRAM, SOL_UDP );
		$msg =  json_encode( $data );
		$len = strlen( $msg );
		socket_sendto( $sock, $msg, $len, 0, self::$url , self::$port ); 
		socket_close( $sock );
	}

	
}



// for ($i=0; $i < 10  ; $i++) { 
// 	usleep(1000);
	
// 	$num = rand(1,20);
// 	JLogClient::write( 'test','level'.$num,'name'.$num,  array('a'=>'a'.rand(1,10)  ,'b'=>'b'.rand(1,10) ) );
// }


 
 
//
//module  level  name  content  [array]
