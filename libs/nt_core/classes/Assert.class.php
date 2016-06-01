<?php
class Assert{

 
	//是一个email
	static public function  email( $email , $msg = '' ){
		if( !$msg  ){
			$msg = '参数必须是符合规则的email';
		}
		$result =   Valid::email( $email ); //better 把函数调用的逻辑直接放在这里。其实valid里面的这个方法除了这里用别的也没有用处
		if( $result ){
			return $email ;
		}else{
			throw new Exception( $msg );
		}

	}
	 
	 

	//检查email的域名是否真的可用
	static public function email_domain( $domain , $msg = '' ){

		if( !$msg  ){
			$msg = '电子邮件不可达';
		}
		if( Valid::email_domain( $domain ) ){ //better 把逻辑直接写在这里
			return $domain;
		}else{
			throw new Exception( $msg );
		}
	}

	//合法的url
	static public function url( $url , $msg = '' ){
		if( !$msg  ){
			$msg = 'URL格式错误';
		}
		if( Valid::url( $url ) ){  //better 把逻辑直接写在这里
			return $url;
		}else{
			throw new Exception( $msg );
		}
	}

	//合法的ip地址
	static public function ip( $ip, $msg = ''  ){
		if( !$msg  ){
			$msg = 'IP地址格式错误';
		}
		if( Valid::ip( $ip ) ){  //better 把逻辑直接写在这里
			return $ip;
		}else{
			throw new Exception( $msg );
		}
	}
	//合法的电话号码包括手机
	static public function phone( $number , $msg = '' ){
		if( !$msg  ){
			$msg = '手机号码格式错误';
		}
		if( Valid::phone( $number ) ){  //better 把逻辑直接写在这里
			return $number ;
		}else{
			throw new Exception( $msg );
		}
	}
	 






}