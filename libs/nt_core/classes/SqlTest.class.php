<?php


class SqlTest{

	static public function test_select(){


		list( $sql, $params) = Sql::fac()->table('test')->where('id = ?', 23)->select();
		 
		TestAuto::assert_equal( $sql, 'select * from test where id = ?' );
		TestAuto::assert_equal( $params, array(23) );


		list( $sql, $params) = Sql::fac()->table('test')->in('id', array(1,2,3))->select();
		 
		TestAuto::assert_equal( $sql, 'select * from test where id in ( ? ,  ? ,  ?)' );
		TestAuto::assert_equal( $params, array(1,2,3) );
	}


	static public function test_insert( ){
		list( $sql, $params) = Sql::fac()->table('test')->data(array('name'=>'aaa'))->insert();
		 


		TestAuto::assert_equal( $sql, 'insert into test set  `name` = ?' );
		TestAuto::assert_equal( $params, array('aaa') );

	}

	static public function test_update(){
		list( $sql, $params) = Sql::fac()->table('test')->data(array('name'=>'aaa'))->where('id =? ',34 )->update();
		 


		TestAuto::assert_equal( $sql, 'update test set  `name` = ?  where id =?' );
		TestAuto::assert_equal( $params, array('aaa',34 ) );
	}


	static public function test_delete(){

		list( $sql, $params) = Sql::fac()->table('test')->where('id = ?', 23)->delete();
		 
		TestAuto::assert_equal( $sql, 'delete from test where id = ?' );
		TestAuto::assert_equal( $params, array(23) );

	}

}



