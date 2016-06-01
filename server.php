<?php

$serv = new swoole_server( '0.0.0.0', 9527 , SWOOLE_PROCESS , SWOOLE_SOCK_UDP );
$serv->set( array('daemonize'=>false  ,'worker_num' => 1  ) );

//$a = 1;
// $serv->on('receive', function ($serv, $fd, $from_id, $data) {
//     var_dump( long2ip( $fd  ) );
//     var_dump(  $from_id   );
//     var_dump(json_decode( $data , true ) );
//     //global $a;
//     //$a++;
//     //var_dump($xx);
//     //var_dump($a);
//     //Log::write('testudp', $data );
//     $data = json_decode( $data, true );
//     JLog::save(  $data  );
     
// });

$serv->on( 'Packet' , function( $serv, $data, $clientinfo ){

	
	 
	$address = $clientinfo['address'];
	$port = $clientinfo['port'];
	//var_dump($address);
	//var_dump($port);

	$data = json_decode($data, true);
	var_dump($data);
	if( $data ){
		$data['address'] = $address;
		$data['port'] = $port;
		$data['createtime'] = time();
		JLog::save(  $data  );
	}
	

} );
  

$serv->on('WorkerStart', function( $serv, $worker_id){

	try{

		require_once('include.php');
		//初始化数据库和redis连接池		 
 		echo "worker start success!id: $worker_id \n";
	}catch( Exception $e){
		//如果这里有错误，一定要修复环境之后，重新启动
		MyExp::handle( $e );
		echo "worker start failed \n";		
	}
	

});
swoole_set_process_name('swoole_log');
$serv->start();
