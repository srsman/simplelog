<?php
 
/*
 
//new

Session::get(...)
Session::set(...)
Session::del(...)
Session::data()


*/
class Session{
	static protected $_sess_name = 'php_sess';
 	 
	 
  
	static protected function _get_session_id(){
		if( ! Cookie::get( self::$_sess_name ) ){
 			if( ! Req::get('sess_id') ){
 				$sid = get_guid();
 				Req::set('sess_id', $sid );
 				Cookie::set( self::$_sess_name , $sid );
 			}else{
 				$sid = Req::get('sess_id');
 			}
 			
 		}else{
 			$sid = Cookie::get( self::$_sess_name  );
 		}

		 
		if( !$sid ){
			throw new Exception('no sessid found ,forget session::start?');
		}
		return $sid;
	}
	//获取session全部内容
	static public function data(){
		$sid = self::_get_session_id();
		return SwooleSession::data( $sid );
	}

	static public function set( $name, $value ){
		//目前value不能是数组只能是字符串
		if( is_array( $value ) ){
			throw new Exception("session目前不支持数组");
		}
		return SwooleSession::set( $name, $value, self::_get_session_id() );
	}

	static public function get( $name ){
		return SwooleSession::get( $name, self::_get_session_id() );
		 
	}
	static public function del( $name ){
		return SwooleSession::del( $name, self::_get_session_id() );
		$sid = $this->_get_session_id();
		 
	}

}

/*
 

//new
SwooleSession::data( $uid )
SwooleSession::get( $key, $uid )
SwooleSession::set( $key, $value, $uid )
SwooleSession::del( $key, $uid )

*/
class  SwooleSession{
	
	static protected $_redis_key_prefix = 'swoolesess';
	static protected $_redis_config = 'redis_session';
 
	static protected $_ttl = 3600;
	 
	
	static protected function _db(){
		return MyRedis::connect( self::$_redis_config  );
	}
	 

	//获取全部内容
	static public function data( $uid ){
		$uid = Nnull::str( $uid );
		$db = self::_db();
		$keys =  $db->keys(  self::$_redis_key_prefix . $uid ."*" );
		$data = array();
		if( $keys ){
			foreach ( $keys  as $v) {
				$data[ substr($v ,  strlen( self::$_redis_key_prefix .  $uid ) )] = $db->get( $v );
				
			}
		}
		return $data;
	}
 
	//读取session
	static public function get( $key, $uid ){
 		$key = Nnull::str( $key );
 		$uid = Nnull::str( $uid );
 		$db = self::_db();
		$redis_key = self::_get_key( $key , $uid );
		$sess = $db->get( $redis_key );
		$this->_db->expire( $redis_key, self::$_ttl );
		return $sess;
	}
	//更新session
	static public function set( $key, $data , $uid ){
		$uid = Nnull::str( $uid );
 		$db = self::_db();
		$redis_key = self::_get_key( $key , $uid );
		$db->set( $redis_key, $data );
		$db->expire( $redis_key, self::$_ttl );
		return true;
	}
	//session_destory
	static public function del( $key , $uid ){
		$uid = Nnull::str( $uid );
 		$db = self::_db();
		$redis_key = self::_get_key( $key , $uid  );
		$db->del( $redis_key );
		return true;
	}
	static protected function _get_key( $key , $uid  ){
		$uid = strval( $uid );
		$uid = Nnull::str( $uid );
		return self::$_redis_key_prefix . $uid . $key  ;
	}
	 






}