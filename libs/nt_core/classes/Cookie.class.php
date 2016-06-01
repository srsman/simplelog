<?php  
/*

 

Cookie::set(...)
Cookie::get(...)
Cookie::delete(...)

*/
class Cookie {

	protected static $_salt = '9sefsddf763hfsd';
	protected static $_secure = FALSE;
	protected static $_httponly = TRUE;

	protected static $expiration = 86400;
	protected static $path = '/';
	protected static $_request = '';
	protected static $_response = '';


	static protected function _get_domain(){
		return Req::get('cookie_domain');
	}
 
	//获取useragent用于salt运算
	static protected function _get_useragent(){ //better 如果request是通过参数传过来的话，那么从调用者的角度看，会更容易理解.实际上这个方法做了两件事情，一个是取request。一个是从request取agent
		$request = Req::get('request');
		$server = $request->server;
		return isset( $server['http_user_agent'] ) ? strtolower( $server['http_user_agent'] ) : 'unknown_http_user_agent';
	}



    //better 用注释说明cookie中的内容做了hash的处理的方式是什么样子的
	static public function get( $key, $default = NULL){	
		 
		$request =  Req::get('request');
		$request_cookie = isset( $request->cookie ) ? $request->cookie: array();
		 
		
		
		if ( ! isset($request_cookie[$key])){
			// The cookie does not exist
			return $default;
		}

		// Get the cookie value
		$cookie = $request_cookie[$key];

		// Find the position of the split between salt and contents
		$split = strlen(self::_salt($key, NULL));

		if (isset($cookie[$split]) AND $cookie[$split] === '~')
		{
			// Separate the salt and the value
			list ($hash, $value) = explode('~', $cookie, 2);

			if (self::_salt($key, $value) === $hash)
			{
				// cookie signature is valid
				return $value;
			}

			// The cookie signature is invalid, delete it
			self::delete( $key );
		}

		return $default;
	}
 
	static public function set( $name, $value, $expiration = NULL){
		if ($expiration === NULL)
		{
			// Use the default expiration
			$expiration = self::$expiration;  //better 直接把默认值写在参数上。因为目前不清楚多少个地方用到self::$expiration。如果需要修改，要考虑影响多少个地方
		}

		if ($expiration !== 0)
		{
			// The expiration is expected to be a UNIX timestamp
			$expiration += time();
		}

		// Add the salt to the cookie value
		$value = self::_salt($name, $value).'~'.$value;
		$response = Req::get('response');
		return $response->cookie( $name, $value, $expiration, self::$path, self::_get_domain(), self::$_secure, self::$_httponly );
	}
	 
 
	static public function delete($name){
		// Remove the cookie
		$request =  Req::get('request');
		unset( $request->cookie[$name] );
		$response = Req::get('response');
		return $response->cookie( $name, NULL, -86400, self::$path, self::_get_domain(), self::$_secure, self::$_httponly );
		 
	}

	/**
	 * Generates a salt string for a cookie based on the name and value.
	 *
	 *     
	 *
	 * @param   string  $name   name of cookie
	 * @param   string  $value  value of cookie
	 * @return  string
	 */
	static protected function _salt( $name, $value, $http_user_agent = '' ){
		// Require a valid salt
		if ( ! self::$_salt ){

			self::$_salt = 'o19i28u76eyd65ch5ms64sh';//better 直接用salt变量不用self::$salt。表明只有这里用这个值，其他都不用
		}
		if( ! $http_user_agent ){
			$http_user_agent = self::_get_useragent();
		}
		return md5( $http_user_agent . $name . $value . self::$_salt );
	}

}
