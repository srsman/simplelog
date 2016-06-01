<?php

/**
*	常用的工具函数库
*
*/
//生成随机文件名 如果不给出参数，则生成的文件名没有后缀
function get_rand_file_name( $ext = '' ){
	$base = sha1(uniqid().rand(1,999999999).rand(1,99999999));
	if($ext){
		$ext = strval($ext);
		return $base.'.'.$ext;
	}else{
		return $base;
	}
}

//根据当前的日期生成文件夹的名
function get_date_dir_name(){
	return  date('Y-m-d', time());
}

//去掉一个文件名的后缀
function strip_file_ext( $basename ){
	if( strpos($basename, '.') === false ){
		return $basename;
	}
	$ext = get_file_ext( $basename );
	return substr($basename, 0, strlen( $basename ) - strlen( '.'.$ext ) );

}

//获取一个文件名的后缀 如果获取不了，返回default
function get_file_ext( $file_name , $default = '' ){

	if( strlen( $file_name ) == 0 ){
		throw new Exception('file_name为空');
	}
	if( strpos( $file_name, '.' ) === false ){
		return $default;
	}
	$temp = explode( '.' , $file_name ); 
	if(is_array($temp)){
		$temp = array_reverse($temp);
		return trim( $temp[0] );
	}
	return $default;
}
//检查一个目录是否存在并且可写
function is_dir_good( $dir , $create = false ){
	if(strlen($dir) == 0){
		throw new Exception('没有指定要检查的dir ');
	}
	if(!$create){
		if ( is_dir($dir) and is_writable(realpath($dir))){
			return true;
		}else{
			return false;
		}
	}else{
		if ( is_dir($dir) and is_writable(realpath($dir))){
			return true;
		}else{
			//创建文件夹
			if( false === mkdir($dir, 0777, true)){
				return false;
			}else{
				return true;
			}
		}
	}
 
}
// 同is_dir_good
function is_dir_writable( $dir, $create = false ){
	return is_dir_good( $dir, $create );
}
 

//类似python的map  第一个参数是函数，第二个是array或者字符串。返回值的形式取决于第二个参数的类型
/*
参见python的map函数
*/
function map( $func, $list ){
	if( !$list ) return $list;
	$result = array();
	if( is_array( $list ) ){
		foreach( $list as $v ){
			$result[] = $func( $v );
		}
		return $result;
	}elseif( is_string( $list )){
		$len = strlen( $list );
		for( $i=0; $i<$len; $i++ ){
			$result[] = $func( $list[$i] );
		}
		return implode('', $result  );
	}
}
 


//格式化输出
function dump($var, $echo=true, $label=null, $strict=true) {
	$label = ($label === null) ? '' : rtrim($label) . ' ';
	if (!$strict) {
		if (ini_get('html_errors')) {
			$output = print_r($var, true);
			$output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
		} else {
			$output = $label . print_r($var, true);
		}
	} else {
		ob_start();
		var_dump($var);
		$output = ob_get_clean();
		if (!extension_loaded('xdebug')) {
			$output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
			$output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
		}
	}
	if ($echo) {
		echo($output);
		return null;
	}else
		return $output;
}

 
 
//把一个sql和一个参数数组例如 select * from test where id = ?, array( 13 )。变成一个更可读的sql语句:select * from test where id = 13


		
//把第二个二维数组中的某字段的值拿出来组成一个数组
function fetch_array_field( $field, $arr ){
	Nnull::str( $field );
	if( !$arr ) return array();

	$new = array();
	foreach( $arr as $var ){
		if( isset( $var[$field] )){
			$new[] = $var[$field];
		}
		
	}
	return $new;
}	 


//合并常见的从数据库查询出来的二维数组，按照某个字段相等合并
function merge_array_field( $arr1, $arr2, $field ){
	 
	Nnull::str( $field );
	$new = array();
	foreach( $arr1 as $v ){
		$f_value = $v[$field];
		$good_arr = $v;
		foreach( $arr2 as $v2 ){
			if( $v2[$field] === $f_value ){
				$good_arr = array_merge( $v, $v2 );
			}
		}
		$new[] = $good_arr;
	}
	return $new;

}
	
//判断字符串str的结尾是不是tail
function str_tail( $str, $tail ){
	$str = strval( $str );
	$tail = strval( $tail );
	Assert::str_not_null( $str );
	if( strlen( $tail ) == 0 ){
		return true;
	}
	$len = strlen( $tail );
	$sub_str = substr( $str, -$len  );
	if( $sub_str === $tail ){
		return true;
	}else{
		return false;
	}
}

 
//规范化路径， 把\改为/
/*

例如 a\b\c 变成 a/b/c

*/
function unify_path($path){
	
	if(strlen($path)==0){
		throw new Exception("path eq ''");
	}
	return str_replace('\\', '/', $path );
}
//计算第一个路径相对于第二个路径的相对路径
/*

*/
function get_relative_path( $a, $b ){
	if(strlen($a) == 0){
		throw new Exception('第一个路径为空');
	}
	if(strlen($b)== 0){
		throw new Exception('第二个路径为空');
	}
	$a = shrink_path($a);
	$b = shrink_path($b);
	$a = trim($a, '/');
	$b = trim($b, '/');

	$arr_a = explode('/', $a);
	$arr_b = explode('/', $b); 
	$stack_a = new Stack();
	$stack_b = new Stack();
	foreach($arr_a as $var){
		$stack_a->push( $var );

	}
	foreach( $arr_b as $var2 ){
		$stack_b->push( $var2 );
	}
	//$data_a = $stack_a->get_data();
	//$data_b = $stack_b->get_data();
	$stack_a->reverse();
	$stack_b->reverse();
	while( true ){
		$temp_a = $stack_a->pop();
		$temp_b = $stack_b->pop();
		 
		if( $temp_a == $temp_b and $temp_a !== null and $temp_b !== null ){
			continue;
		}else{
			$stack_a->push($temp_a);
			$stack_b->push($temp_b);
			break;
		}
	}
	$data_a = $stack_a->get_data();
	$data_b = $stack_b->get_data();
 
	if( $data_a ){
		$end =  implode('/', $data_a);
		if(!$data_b){
			return  $end ;
		}else{
			$start = '';
			foreach($data_b as $v3){
				$start .= '../';
			}
			return rtrim( $start.$end , '/');
		}
	}else{
		if( $data_b ){
			$start = '';
			foreach($data_b as $v3){
				$start .= '../';
			}
			return rtrim( $start, '/');

		}else{
			return '.'; //如果是用两个一样的目录来计算会得到这个结果
		}
	}
}
	
//清理路径去掉. ..  只能用于绝对路径,否则去掉..或者.就不对了
function shrink_path( $path_r ){
	if( strlen($path_r) == 0 ){
		throw new Exception('路径为空');
	}
	$path = unify_path( $path_r );
	
	$path = preg_replace( '/(\/)+/', '/', $path );
	if(strpos($path,'/') === 0){
		$start = '/';
	}else{
		$start = '';
	}
	$path = trim($path, '/ ');
	$arr = explode( '/',$path); 
	$stack = new Stack();
	 
	foreach($arr as  $var){
		if($var == '.'){
			 
		}elseif($var == '..'){
			if($stack->pop() === null){
				throw new Exception('只能用于绝对路径:'.$path_r);
			}
		}else{
			$stack->push($var);
		}
	}
	$data = $stack->get_data();
	$data = array_reverse($data);
	return $start.implode('/', $data).'/';
}
//获取不重复的随机字符串guid
function get_guid(){
    if (function_exists('com_create_guid')){
        return trim( com_create_guid() ,'{}');
    }else{
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = chr(123)// "{"
            .substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12)
            .chr(125);// "}"
        return trim( $uuid , '{}' );
    }
}
 
function cover_phone_num( $phone ){
	if( !$phone ){
		return '';
	}
	$len = strlen( $phone );
	$prefix = substr( $phone, 0,3 );
	$suffix = substr( $phone, $len - 4 );
	return $prefix.'****'.$suffix;
}

function get_time_desc( $time ){
	$now = time();
	$cha = $now - $time;

	if( $cha < 60 ){
		return '刚刚'; 
	}elseif( $cha < 600 ){
		return floor( $cha/60 ).'分钟前';
	}elseif( date('Y-m-d' , $time ) == date( 'Y-m-d') ){
		return date( '今天H时m分', $time );
	}else{
		return '不久前';
	} 


}
//根据一个数组中的一个属性的值排序
function array_m_sort( $arr , $key , $asc = true ){
	$temp = array();
	foreach( $arr as $k => $v ){

		$temp[$k] = $v[$key];
	}
	if( $asc ){
		asort( $temp );
	}else{
		arsort( $temp );
	}
	$new = array();
	foreach( $temp as $kk => $vv ){
		$new[$kk] = $arr[$kk];
	}

	return $new;


}
