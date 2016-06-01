<?php
if( !defined('ROOT_PATH') ){
	throw new Exception('access error! no ROOT_PATH defined!' );
} 

return  array( 
				//会员写入数据库
				'id_secret'=> array(
					  'soma' => 'iwide30soma',
					  'active' => 'iwide30active',
					  'vip'=>'iwide30vip',
					),
		) ;