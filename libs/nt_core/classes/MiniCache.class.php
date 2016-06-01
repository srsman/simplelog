<?php

/*
问题 1 redis缓存无法缓存数组
     2 redis缓存如果清空就全清空了。不想影响其他的数据.要把清空的范围控制在本对象存放的那些数据
     3 频繁清空的时候。不想因为数据太多而影响性能
解决 1 把数组变为字符串缓存，并且记录key的类型，等取出数据的时候好还原
     2 清空的时候，清空记录的key们的值。只影响这个对象添加的数据
     3 这里只适合缓存很量小的数据。例如网站的配置数据。例如货品很少的电商网站的goods数据
注意  这里的缓存不会自动过期，必须手动过期

*/




//小数据缓存   除非手动过期否则不过期 这个类不能直接使用，最好新出一个类继承这个类，类的名字是有意义的
//用于很少变动的数据 并且数据量很小的情况下。例如本系统中的一些配置数据或者商品属性数据或者sku，goods数据
/*
$c = new MiniCache('redis_session' , 'somename')
$c->set( $key, $str )
$c->set( $key, arr)

$c->get( $key )
$c->del( $key )
$c->all()  获取全部从此类放进去的内容
$c->flush() 清空全部从此类放进去的内容

class SomeCache extends CommonCache{
	public function __construct(){
		parent::__construct();
	}
}



*/
class MiniCache{
	static protected $_strkeyname = 'normalkey';
	static protected $_arrkeyname = 'unnormalkey';

	protected $_redis = null;
	protected $_name = 'default';
	 

	public function __construct(  $redis_config = '' , $name = 'default'){
		if( strlen( $redis_config ) == 0 ){
			throw new Exception('no redis config found:' );
		}
		$this->_name = md5( $name.'somerandomx34dsdf332' );
		$this->_redis  = MyRedis::connect( $redis_config );
	}
	
	protected function _transkey( $key ){
		return $this->_name.$key;
	}
	 
	protected function _normalgroupkey(){
		return get_class($this) . $this->_name . self::$_strkeyname ;
	}
	protected function _unnormalgroupkey(){
		return get_class($this) . $this->_name . self::$_arrkeyname ;
	}
	protected function _iskeynormal( $key ){
		$key = $this->_transkey( $key );
		$info =  $this->_redis->sContains( $this->_normalgroupkey(), $key );
		return $info;
	}
	protected function _iskeyunnormal( $key ){
		$key = $this->_transkey( $key );
		$info = $this->_redis->sContains( $this->_unnormalgroupkey(), $key );
		return $info;
	}

	public function set( $key, $value ){
		$key = $this->_transkey( $key );
		if( is_array($value) ){
			$value = serialize( $value );
			$this->_redis->sAdd( $this->_unnormalgroupkey(), $key  );
			return $this->_redis->set( $key, $value  );
		}else{
			$this->_redis->sAdd( $this->_normalgroupkey(), $key  );
			return $this->_redis->set( $key, $value  );
		}

		

	}

	public function get( $key ){
		if( $this->_iskeynormal( $key )){
		 	return $this->_redis->get(  $this->_transkey( $key )  );
		}elseif( $this->_iskeyunnormal( $key ) ){
			$info = $this->_redis->get(   $this->_transkey( $key )  );
			return unserialize( $info );
		}else{
			return '';
		}



	}
	public function del( $key ){
		$key =   $this->_transkey( $key )  ;
		$this->_redis->sRemove( $this->_normalgroupkey(), $key );
		$this->_redis->sRemove( $this->_unnormalgroupkey(), $key );

		return $this->_redis->delete( $key );

	}

	public function flush(){
		$keys = $this->_redis->sMembers( $this->_normalgroupkey() );
		foreach( $keys as $v ){
			$this->_redis->delete( $v );
		}

		$keys2 = $this->_redis->sMembers( $this->_unnormalgroupkey() );
		foreach( $keys2 as $v2 ){
			$this->_redis->delete( $v2 );
		}

		$this->_redis->delete( $this->_normalgroupkey() );
		$this->_redis->delete( $this->_unnormalgroupkey() );

		return true;
	}

	public function all(){
		$return = array();
		$keys = $this->_redis->sMembers( $this->_normalgroupkey() );
		foreach( $keys as $v ){
			$return[substr($v, strlen( $this->_name))] = $this->get( substr($v, strlen( $this->_name)) );
		}

		$keys2 = $this->_redis->sMembers( $this->_unnormalgroupkey() );
		foreach( $keys2 as $v2 ){
			$return[substr($v2, strlen( $this->_name))] = $this->get( substr($v2, strlen( $this->_name)) );
		}
		
		return $return ; 
	}


}