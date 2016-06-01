<?php
 


//获取当前的host 例如 获取到http://www.artqiyi.com
function nt_url_origin($s, $use_forwarded_host=false){
    $ssl = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on') ? true:false;
    $sp = strtolower($s['SERVER_PROTOCOL']);
    $protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
    $port = $s['SERVER_PORT'];
    $port = ((!$ssl && $port=='80') || ($ssl && $port=='443')) ? '' : ':'.$port;
    $host = ($use_forwarded_host && isset($s['HTTP_X_FORWARDED_HOST'])) ? $s['HTTP_X_FORWARDED_HOST'] : (isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : null);
    $host = isset($host) ? $host : $s['SERVER_NAME'] . $port;
    return $protocol . '://' . $host;
}
//获取当前的host  
/*
例如获取到 http://www.artqiyi.com

*/
function get_current_host($s='',  $use_forwarded_host=false ){
	if(!$s) $s = $_SERVER;
	return nt_url_origin( $s,  $use_forwarded_host);
}
//获取domain不包括www
/*
例如获取到 artqiyi.com

*/
function get_site_domain(){
	$tmp = nt_url_origin( $_SERVER );
	$tmp = explode('://', $tmp );
	$domain  =  $tmp[1];
	if( strpos( $domain, 'www.') !== false ){
		return substr( $domain, strlen('www.') );
	}
	return $domain;
	
}
//根据目前的域名获取cookie domain   
/**
	www.artqiyi.com   => .artqiyi.com
	artqiyi.com => .artqiyi.com
	test.artqiyi.com => .test.artqiyi.com
	12.12.12.12 => 12.12.12.12
	localhost => null
*/
function get_nt_cookie_domain(){
	$domain = get_site_domain(); 
	if( strpos( $domain, ":") !== 0 ){
		$tmp = explode(':',  $domain );

		$name = $tmp[0];

		if( $name == long2ip(ip2long( $name )) ){

			return null;
		}else{
			$domain = $name;
		}
	}
	
	if( strpos( $domain , 'localhost') === 0 ){
		//把域名设置为localhost是设置不上cookie的所以改为没有
		return null;
	}
	
	return '.'.$domain;
}


/**
获取 cookie path  
一般的情况下为 /
但是某些情况下例如项目根目录是 artqiyi  那么它的值就是/artqiyi/

*/
function get_nt_cookie_path(){
	$temp = get_site_dir();
	if( $temp == '.' ){
		return '/';
	}
	return '/'.$temp.'/';
}

//获取当前的host
/**
获取host name
例如 获取到 www.artqiyi.com

*/
function get_host_name($s='',  $use_forwarded_host=false ){
	if(!$s) $s = $_SERVER;
	$info = nt_url_origin( $s,  $use_forwarded_host);
	$arr = explode('://', $info );
	return $arr[1];
}
 
//$absolute_url = get_current_url($_SERVER);		
//echo $absolute_url;
//获取当前的url
/**
例如 获取到http://www.artqiyi.com/index/test/test
*/
function get_current_url($s='', $use_forwarded_host = false){
	if(!$s){
		$s = $_SERVER;
	}
    return nt_url_origin($s, $use_forwarded_host).$s['REQUEST_URI'];
}
 
 
/**

获取当前的项目所在web服务器的document root


*/
function get_document_root(){
	$root = isset( $_SERVER['DOCUMENT_ROOT'] ) && $_SERVER['DOCUMENT_ROOT'] ?  $_SERVER['DOCUMENT_ROOT'] : '';
	if( !$root ){
		throw new Exception('need $_SERVER[\'DOCUMENT_ROOT\']');
	}
	return shrink_path( $root );
}
//获取网站根url
/**
获取网站跟url
例如获取到http://www.artqiyi.com
或者在没有把项目放在了根目录而放在了比如test目录
那么获取到的就是http://www.artqiyi.com/test/
*/
function get_site_url(){
	$host = get_current_host();
	$root_dir = get_site_dir();
	if( $root_dir == '.' ){
		return $host.'/';
	}else{
		return $host.'/'.$root_dir.'/';
	}
}

//获取本项目与document_root目录的相对目录
/**
获取当前的项目对应web服务器的document root的相对目录
例如，比如项目放在test目录下，获取到test/

*/
function get_site_dir(){
	$document_root = get_document_root();
	//index.php所在的目录
	$site_root = ROOT_PATH;
	return get_relative_path( $site_root, $document_root );
}

  
