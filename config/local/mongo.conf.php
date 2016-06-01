<?php

if( !defined('ROOT_PATH') ){
	throw new Exception('access error! no ROOT_PATH defined!' );
} 

return  array( 

				'mongo'=> array( 
					'link' => 'mongodb://127.0.0.1:27017/jlog',
					 
				),
				'mongo2'=> array( 
					'link' => 'mongodb://money:123123123@127.0.0.1:27017/xx',
					 
				),

		

		) ;