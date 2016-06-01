<?php

class JLog{

	//记录进入mongo
	static public function save( $data ){

		$bulk = new MongoDB\Driver\BulkWrite;
		$bulk->insert( $data  );
		return MyMongo::write('mongo', 'jlog.log', $bulk ); 
	}

	//查询日志
	static public function findlog( $module, $level, $name , $start_time = 0 , $end_time = PHP_INT_MAX , $filter_ext = array() ){

		$filter = array();
		if( $module ){
			$filter['module'] = trim( $module );
		}
		if( $level ){
			$filter['level'] = trim( $level );
		}
		if( $name ){
			$filter['name'] = trim( $name );
		}
		 
		if( $start_time && $end_time ){
			$filter['createtime'] = array("\$gte" => $start_time, "\$lte" => $end_time );
		}else{
			exit('need time_start and time_end');
		}
		
		if( $filter_ext ){
			$filter = array_merge( $filter_ext, $filter );
		}
		//var_dump($filter);
		$options = [
		     
		    'sort' => ['createtime' => -1 ],
		];

		$list = MyMongo::read('mongo', 'jlog.log', $filter, $options );
		$return = array();
		foreach( $list as $v ){
			$temp = (array)$v;
			unset( $temp['_id'] );
			unset( $temp['port'] );

			$temp['date'] = date('Y-m-d H:i:s', $temp['createtime'] );
			unset( $temp['createtime'] );
			unset( $temp['address'] );
			$return[] = $temp;
		}
		return $return;


	}
	//测试一下组合查询日志 or and in
	static public function test(){
		$filter = array();
		//$filter = ['module' => "module2"  ] ;
		
		$filter["\$or"] = array( array("name" => "name2","module"=>"module23"), array( "name" => "name20")  ); 

		//array( "name"=>"name2", "name"=>"name20" );

		//$filter['createtime'] = array("\$gte" => 1, "\$lte" =>PHP_INT_MAX  );
		//$filter["level"] =   array(  "\$in" => array('level17','level19')  );
		//$filter["name"] =   array(  "\$in" => array('name1','name2')  );
		//$filter['module'] = array("\$in" => array('module1', 'module2') );
		$options = [
		     
		    'sort' => ['createtime' => -1 ],
		];


//var_dump($filter);var_dump($options);
		$list = MyMongo::read('mongo', 'jlog.log', $filter, $options );
		return $list;
	}


	
}