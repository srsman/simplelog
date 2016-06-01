<?php
class NnullTest{

	static public function test_all(){
		TestAuto::assert_equal( 'a', Nnull::str('a') );
		TestAuto::assert_equal( array(1), Nnull::arr( array(1)));
		TestAuto::assert_equal( 1, Nnull::num(1) );

	}
}