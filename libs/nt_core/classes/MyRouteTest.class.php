<?php

class MyRouteTest{


	static public function test_all(){

		TestAuto::assert_equal( MyRoute::get_file( '')  , NT_CONTROLLER_PATH.'index/index.php' );
		TestAuto::assert_equal( MyRoute::get_file( '/')  , NT_CONTROLLER_PATH.'index/index.php' );
		TestAuto::assert_equal( MyRoute::get_file( '//')  , NT_CONTROLLER_PATH.'index/index.php' );
		TestAuto::assert_equal( MyRoute::get_file( '/  ')  , NT_CONTROLLER_PATH.'index/index.php' );



	}

}