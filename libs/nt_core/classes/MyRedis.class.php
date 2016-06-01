<?php
//reids
/*
相当于在redis外面包了一层，所有的接口都没有变化，用操作redis对象的方式操作本对象，
本代码唯一的作用就是实现断线重连
$redis =   MyRedis::connect('redis'); 
$redis->get($Key)
*/
class MyRedis{
	static protected $_obj_insts = array();
	protected $_inst = NULL;
	protected $_conf = '';
	protected $_db = '';
	static public function connect( $conf ){
		$key = md5( $conf );
		if( !isset( self::$_obj_insts[$key]) ){
			 self::$_obj_insts[$key] = new MyRedis( $conf );
		}
		return self::$_obj_insts[$key] ;
	}
	protected function __construct( $conf ){
		$this->_conf = $conf;
		//一开始就连接redis的有助于检查环境中的redis是否能正常使用
		
	}
	// 
	public function __call( $name, $args ){
		$this->_connect( $this->_conf );
		$function = trim( $name );
		try{
			if( ! $this->_inst->select( $this->_db ) ){
				//这里是因为如果redis其实已经关闭了，对redis操作却不会抛出异常所以主动引发异常
				throw new Exception('redis need reconnect!');
			} 
			$result = call_user_func_array( array($this->_inst, $function ),  $args );
		}catch( Exception $e ){
			 
			$this->_connect( $this->_conf, true );
			$result = call_user_func_array( array($this->_inst, $function ),  $args );
			
		}
		


		return $result;
		 
	}

	
	protected function _connect( $config , $force_new = false  ){
		$config = Nnull::str( $config );
		 
		if( $force_new  ){
			$this->_inst = $this->_get_redis( $config );
		}else{
			if( ! $this->_inst ){
				$this->_inst = $this->_get_redis( $config );
			}
		}
		
			 
		 
	}
	protected function _get_redis( $config ){
		$config = Nnull::str( $config );
		$conf = NT::load_redis_config( $config );
		$address = $conf['address'];
		$port = $conf['port'];
		$db = $conf['db'];
		$this->_db = $db;
		try{
			//Log::write('redis_connection', time() );
			$redis = new Redis();
			$redis->connect( $address, $port );
			$redis->select( $db );
			 
			return $redis;
		}catch( Exception $e ){

			throw $e;
				
		}
		
	}
}



