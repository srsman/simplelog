<?php
if( !defined('ROOT_PATH') ){
	throw new Exception('access error! no ROOT_PATH defined!' );
} 

return  array( 
				//会员写入数据库
				'vip'=> array(
					'dsn' => 'mysql:dbname=xx;host=127.0.0.1',
					'user' => 'root',
					'password' => 'xx',
					'comment' => '会员写入数据库'

					),
		) ;