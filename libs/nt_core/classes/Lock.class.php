<?php

//锁
 

/*
 
 
//new
if( Lock::getlock('tt') ){
	do some thing
}
Lock::unlock('tt')



*/

class Lock{
	static protected $_redis_config = 'redis_lock';

	static protected function  _db(){
		return MyRedis::connect( self::$_redis_config );
	}


	static public function getlock( $name ){
		Nnull::str( $name );
		$db = self::_db();

		return $db->setnx( $name , 'value' );
	}

	static public function unlock( $name ){
		Nnull::str( $name );
		$db = self::_db();
		return $db->delete( $name );
	}

	static public function clear(){
		return self::clear_locks();
	}

	static public function clear_locks(){
		$db = self::_db();
		return $db->flushDB();
	}
}
