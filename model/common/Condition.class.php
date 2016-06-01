<?php

class Condition{

	 
	//清理str
	static protected function _clean( $str ){
		$str = trim( $str , ' ()');
		$str = str_replace( '(', ' ( ', $str );
		$str = str_replace( ')', ' ) ', $str );
		$str = str_replace( ',', ' , ', $str );
		$str = str_replace( '=', ' = ', $str );
		$str = str_replace( '>=', ' >= ', $str );
		$str = str_replace( '<=', ' <= ', $str );
		$str = str_replace( '>', ' > ', $str );
		$str = str_replace( '<', ' < ', $str );

		$str = preg_replace( "/[\s]+/is"," ", $str );
		$str = str_replace( '< =', '<=', $str );
		$str = str_replace( '> =', '>=', $str );

		return $str;
	}
	//转化为数组
	static protected function _arr( $str ){
		return explode(" ",  $str );
	}

	//是不是单个条件
	static protected function _is_atom( $arr ){
		 
		if( count( $arr ) == 3 ){
			return true;
		}
		return false;
	}

	/*
	 只支持and操作 ， 运算符 = > < <= >=
	*/
	static public function get_condition( $str ){
		$str = trim( $str );

		if( strlen( $str ) == 0 ){
			return array();
		}
		$str = self::_clean( $str );
		$arr = self::_arr( $str );
		return self::_get_condition( $arr );
	}

	// =
	static public function _get_condition( $arr ){
		 
		 
		$filter = array();

		foreach( $arr as $k => $v ){
			if( $v == '=' || $v == '<' || $v == '>' || $v == '<=' || $v == '>=' ){
				$left = $arr[$k-1];
				$left = 'content.'.$left;
				$right = $arr[$k+1];
				if( $v == '=' ){
					$filter[$left] = $right;
				}elseif( $v == '<' ){
					$filter[$left] = array("\$lt" => $right );
 				}elseif( $v == '>' ){
 					$filter[$left] = array("\$gt" => $right );
 				}elseif( $v == '<=' ){
 					$filter[$left] = array("\$lte" => $right );
 				}elseif( $v == '>=' ){
 					$filter[$left] = array("\$gte" => $right );
 				}
				 
			}
		}

		return $filter;
	}
	 
}