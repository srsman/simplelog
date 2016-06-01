<?php
//存储session的redis
if( !defined('ROOT_PATH') ){
	throw new Exception('access error! no ROOT_PATH defined!' );
} 

return array(
		//通用
		'redis' => array(
					'address' => '192.168.174.128',
					'port' => 6379,
					'db' => 6,

				),
		'api_token' => array(
					'address' => '192.168.174.128',
					'port' => 6379,
					'db' => 7,

				),
		'uucode' => array(
					'address' => '192.168.174.128',
					'port' => 6379,
					'db' => 8,

				),
		'api_spam' => array(
					'address' => '192.168.174.128',
					'port' => 6379,
					'db' => 9,

				),
	);

 