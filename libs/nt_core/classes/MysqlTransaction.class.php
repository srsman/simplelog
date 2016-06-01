<?php

//mysql事务
/*

MySqlTransaction::start( $pdo )
MySqlTransaction::query( $pdo, sql, $params )
MySqlTransaction::rollback( $pdo )
MySqlTransaction::commit( $pdo )


*/
class MysqlTransaction{

	static public function start( $pdo ){
		return $pdo->beginTransaction();
	}
	static public function rollback( $pdo ){
		return $pdo->rollBack();
	}
	static public function commit( $pdo ){
		return $pdo->commit();

	}
      
	//通用的query语句
	static public function query( $pdo , $sql, $params = array() ){
		if(!is_array( $params )){
			$needles = func_get_args();
			array_shift( $needles );
			$params = $needles;
		}
		try{
			$result =  self::_mysql_query_and_parse_result( $pdo, $sql, $params );
		}catch( Exception $e){
			self::rollback( $pdo );//发生错误,事务回滚
			throw $e;
		} 
		
		return $result;
	
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
			throw new Exception("sql执行失败了 sql: ".Mysql::sql_format( $sql, $args )." 。errinfo : ".json_encode($stat->errorInfo()) );
		}else{
			return  $stat ;
		}
	}

	 

}



