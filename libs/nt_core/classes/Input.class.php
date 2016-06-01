<?php
class Input{
	
	//对gpc中字符串去掉两端多余的空格
	//better 方法名应该说明这个是针对request的
	static public function trim_gpc( $request   ){
	 
		if(isset($request->get)){
			$request->get = self::_dtrim( $request->get );
		}
		if(isset($request->post)){
			$request->post = self::_dtrim( $request->post );
		}
		if(isset($request->cookie)){ 
			$request->cookie = self::_dtrim( $request->cookie );
		}
		return $request;
		 
	}	 
	/**
	*		 
	*	迭代trim
	*   filter::dtrim( $arr )
	*	@param mixed $string
	*	@return mixed
	*	 
	*/
	//better 参数名应该说明字符串和数组字符串都可以  改为 $string_mixed
	static protected function _dtrim( $string ){
		if(!$string) return $string;
		if(is_array($string)) {   
			foreach($string as $key => $val) {   
				$string[$key] = self::_dtrim($val);   
			}   
		}else{   
			$string = trim($string);   
		}   
		return $string;
	}
	/**
	*	迭代去掉html标签
	*	filter::dhtmlspecialchars($arr)
	*   @param mixed $string
	*	@return mixed
	*	 
	*	 
	*/
	// better 和上面一样
	static function dhtmlspecialchars( $string ){
		if(!$string) return $string;
		if(is_array($string)) {   
			foreach($string as $key => $val) {   
				$string[$key] = self::dhtmlspecialchars($val);   
			}   
		}else{   
			$string = htmlspecialchars($string);   
		}   
		return $string;
	}
}