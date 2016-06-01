<?php
/*
 

//new
Mysql::get_pdo( $conf )
Mysql::query( $conf, $sql, $params ) 
Mysql::filter_data( $conf, $table, $data ); 


*/

class Mysql{
	static protected $_recon_exp_msg = 'mysql_need_reconnect';
	protected static $_pdo_insts = array();  
	 

 	//返回pdo连接
	static public function get_pdo( $conf_name ){
		return self::_get_mysql_con( $conf_name, true );
	}
	

	//参数是一个数组，通常是要插入或者更新到数据库的数据，用一个表的字段列表过滤一下，去掉表中没有字段的数据
	static public function filter_data( $conf, $table, $data ){
		Nnull::str( $conf );
		Nnull::str( $table );

		$fields = self::_fields( $conf, $table );
		if( is_array( $fields ) && $fields ){
			$new = array();
			foreach( $data as $key => $var){
				if(in_array( $key, $fields, true ) ){
					$new[$key] = $var;
				}
			}
			$data = $new;
		}
		 
		$new2 = array();
		foreach( $data as $key2 => $var2 ){
			if( isset($var2) ){
				$new2[$key2] = $var2;
			}
			
		}
		$data = $new2;
		return $data;
	} 
	      
	//通用的query语句
	static public function query( $conf , $sql, $params = array() ){
		if(!is_array( $params )){
			$needles = func_get_args();
			array_shift( $needles );
			$params = $needles;
		}
		 
		$pdo = self::_get_mysql_con( $conf );
		

		//dump( self::sql_format( $sql, $params ));
		try{
			$start = microtime( true );
			$result =  self::_mysql_query_and_parse_result( $pdo, $sql, $params );
			$end = microtime( true );
			if( $end - $start > 0.001 ){
				Log::write('slow_sql/slow_sql' , array( 'sql'=>self::sql_format( $sql, $params ) , 'time' => $end  -  $start ));
			}
		}catch( Exception $e){
			$exp_code = $e->getMessage();
			if( $exp_code == self::$_recon_exp_msg ){
				//重连 因为一般来说都是因为长时间没有活动mysql服务器主动关闭了连接
				// 这里应该不会出现无限重连的情况，因为如果真的连不上服务器_get_mysql_con中会抛出异常
				Log::write('mysql_recon', time() );
				self::_get_mysql_con( $conf , true );//第二个参数代表强制重新获取pdo
				return self::query( $conf, $sql, $params );
			}else{
				//别的异常类型的异常不处理
				throw $e;
			}
		} 
		
		return $result;
	
	}
	//获取某个表的字段列表
	protected static function _fields( $conf, $table_name ){
		Nnull::str( $conf );
		Nnull::str( $table_name );

		static $return = array();
		$key = $conf.'_'.$table_name ; 
		if( isset( $return[$key] ) ){

		}else{
			$sql = 'show full columns from '.$table_name  ;  
			$info = self::query( $conf, $sql, array() );
			foreach( $info as $var ){
				$result[] = $var['Field'];
			}
			$return[$key] = $result;
		}
		return $return[$key];
		 
	}

	/**
	*	获取pdo连接   
	*	@params conf_name 数据库配置名
	*	@force_new 强制重新获取连接而不是使用旧的
	*/
	static protected function _get_mysql_con( $conf_name , $force_new = false   ){
		$conf_name = Nnull::str( $conf_name );
		$key = md5( $conf_name );
		if( $force_new ||  isset( self::$_pdo_insts[$key] ) == false     ){
			self::$_pdo_insts[$key] = self::_get_pdo( self::_get_and_check_config( $conf_name ) );
		}
		return self::$_pdo_insts[$key];
 
	}
	//获取一个pdo
	static protected function _get_pdo( $conf ){
		try{
			//Log::write( "mysql_connection", time() );
			$dbh = new PDO( $conf['dsn'], $conf['user'], $conf['password'] ,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8';" ));
			return $dbh;
		} catch ( PDOException $e ) {
			throw new Exception('数据库连接失败了，错误信息是 : '.json_encode( $e->getMessage()) );
		}
	}


	//检查一个db_config是不是合法的
	static protected function _get_and_check_config( $conf_name ){
		$conf_name = strval( $conf_name );

		if(strlen($conf_name) == 0 ){
			throw new Exception('数据库配置名为空');
		}
		 
		$conf= NT::load_db_config( $conf_name );
		if( !isset($conf['dsn']) || !$conf['dsn']  ){
			throw new Exception( $conf_name.': 数据库配置缺少字段: dsn ');
		}
		if( !isset($conf['user']) || !$conf['user']  ){
			throw new Exception( $conf_name.':  数据库配置缺少字段: user ');
		}
		if( !isset($conf['password']) ){
			throw new Exception( $conf_name.': 数据库配置缺少字段: password ');
		}
		return $conf;
	}
	/**
	*	执行sql语句
	*
	*/
	static protected function _mysql_query_and_parse_result(  $pdo,  $sql, $args = array() ){ 
		$result = self::_mysql_exe_sql( $pdo, $sql, $args );
		
		if( self::_sql_start($sql, 'update','delete')){
			
			return  $result->rowCount();
			  
		}elseif( self::_sql_start($sql, 'insert')){  
			 
			return  $pdo->lastInsertId();
			 
		}elseif( self::_sql_start($sql, 'select','show')){
			$data = $result->fetchAll(PDO::FETCH_ASSOC);
			if($data){
				return $data ;
			}else{
				return array() ;
			}
		}else{
			throw new Exception("sql语句只能是 select, show, insert ,update, delete 开头的: $sql ");
				 
		}
	}

	/**
	*	检查sql的开头 _sql_start($sql, 'select');
	*
	*/
	static protected function _sql_start( $sql ){
		if(!$sql){
			throw new Exception('sql语句是空的');
		}
		$str = trim($sql,'()');
		$needles = func_get_args();
		array_shift( $needles );
		foreach( $needles as $var ){
			if(strpos(trim(strtolower($str)), strtolower($var)) === 0 ){
				return true;
			}
		}
		return false;
	}
	/**
	*	执行sql语句 返回pdostatement
	*/
	static protected function _mysql_exe_sql( $pdo, $sql, $args = array()) {
		if(!$args) $args = array();
		//检查args里面不能再有数组对象
		if( $args ){
			foreach( $args as $v ){
				if(is_array($v)){
					throw new Exception('sql执行时参数中不能有数组');
				}
				if( is_object($v) ){
					throw new Exception('sql执行时参数中不能有对象');
				}
			}
		}
		if( !$sql ){
			throw new Exception('sql语句是空的');
		}
		 
		$stat = $pdo->prepare ( $sql );
		$result = $stat->execute( $args );
		if( $result === false){
			//判断是否需要重连就能解决问题
			$err_code = $stat->errorInfo();
			$err_code = $err_code[1];
			if( $err_code == '2006' ){
				throw new Exception( self::$_recon_exp_msg );
			}else{
				throw new Exception("sql执行失败了 sql: ".self::sql_format( $sql, $args )." 。errinfo : ".json_encode($stat->errorInfo()) );
			}	
			
		}else{
			return  $stat ;
		}
	}

	static public function sql_format( $sql, $args = array() ){
		if( is_array( $sql ) && isset( $sql['sql'] ) && $sql['sql'] && isset($sql['args']) ){
			$args = $sql['args'];
			$sql = $sql['sql'];
		}
		if(!$args ) return $sql;
		if(strpos( $sql, '?' ) === false ){
			return $sql;
		}
		while( strpos($sql, '?') !== false ){
			$element = array_shift( $args );
			if( is_string($element) ){
				$sql = preg_replace('/\?/',  "'$element'", $sql, 1 );
			}else{
				$sql = preg_replace('/\?/',   $element , $sql, 1 );
			}
		}
		return $sql;

	}

}