<?php
//mongo操作类本身就有自动重连的功能，所以不需要自己写这个逻辑

class MyMongo{

	static protected $_inst = array();

	static public function connect( $conf ){
		$config = NT::load_mongo_config( $conf );
		$link = $config['link'];

		$key = md5( $conf );
		if( !isset( self::$_inst[$key] ) ){
			self::$_inst[$key] = new MongoDB\Driver\Manager( $link );
		}
		return self::$_inst[$key];

	}

	static public function write( $conf, $namespace, $bulk ){
		try{
			$manager = self::connect( $conf );
			$writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000 );
	    	return $manager->executeBulkWrite( $namespace, $bulk, $writeConcern);
		}catch (MongoDB\Driver\Exception\BulkWriteException $e) {
		    $result = $e->getWriteResult();

		    // Check if the write concern could not be fulfilled
		    if ($writeConcernError = $result->getWriteConcernError()) {
		    	$str = $writeConcernError->getMessage().$writeConcernError->getCode().var_export($writeConcernError->getInfo(), true);
		        throw new Exception( $str );
		    }
		    foreach ($result->getWriteErrors() as $writeError) {
		    	$str =  "Operation#%d: %s (%d)\n". $writeError->getIndex(). $writeError->getMessage(). $writeError->getCode();
		         
		        throw new Exception($str );
		    }

		    
		} catch (MongoDB\Driver\Exception\Exception $e) {
		    throw new Exception("Other error: %s\n", $e->getMessage());
		}
		
	}

	static public function read( $conf, $namespace, $filter, $options  ){
		$manager = self::connect( $conf );
		$query = new MongoDB\Driver\Query($filter, $options);
		$cursor = $manager->executeQuery( $namespace , $query);
		$arr = array();
		foreach ($cursor as $document) {
		    $arr[] = $document;
		}

		return $arr;
	}


}