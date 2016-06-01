<?php  

/**
*	此框架的主干类	 
*	@package hp
*   @category system
*	@author Jersey Mars
*	 
*	 
*/
class NT{

	static protected $_config_dir = NT_CURRENT_CONFIG_PATH ;
	static protected $_models_dir = NT_MODEL_PATH ;
	static protected $_config_mysql_name = 'db';
	static protected $_config_redis_name = 'redis';
	static protected $_config_mongodb_name = 'mongo';


 	static public function load_db_config( $name ){
 		$config = self::load_config( self::$_config_mysql_name );
 		return $config[$name];
 	}

 	static public function load_mongo_config( $name ){
 		$config = self::load_config( self::$_config_mongodb_name );
 		return $config[$name];
 	}

 	static public function load_redis_config( $name ){
 		$config = self::load_config( self::$_config_redis_name );
 		return $config[$name];
 	}

	static public function load_config(  $conf_name ){
		static $loaded_config = array();
		if( isset($loaded_config[$conf_name]) &&  $loaded_config[$conf_name] ){
			return  $loaded_config[$conf_name]  ;
		}
		 
		$conf_name = trim( $conf_name );
		if( strlen($conf_name) == 0  ){
			throw new Exception(  '配置名为空' );
		}
		
		$file =  self::$_config_dir  .$conf_name.'.conf.php';
		if(! file_exists( $file ) ){
			throw new Exception( '找不到配置文件 :  '.  $file );
		}
		$result = require  $file  ;
		$loaded_config[$conf_name] = $result;
		return $result ;

		 
	}
	
	//自动载入model的方法
	static public function load_models(  $class ){
		$filename = $class.'.class.php';
		 
		$file = self::_find_model_file(  $filename );  
		if( $file ){
			require_once ( $file );
			return true;
		}
		return false;
			
		 
	}
	//寻找需要载入的model类文件
	static protected function _find_model_file(  $file  ){
		Nnull::str( $file );

		static $models = array(); //每次model文件目录有变化一定会重启脚本的，所以这里是合理的
		if( ! $models ){
			$models = Dir::list_dir_file( self::$_models_dir );
		}
 
		if( ! $models ){
			throw new Exception('no model file find');
		}
		 
		//find file from filelist
		foreach( $models as $m ){
			$full_dir = self::$_models_dir . $m."/".$file; 
			if( file_exists( $full_dir ) ){
				return $full_dir;
			}
		}
		return false;
		 
	}

 
}