<?php

class StackTest{

	static public function test_stack(){

		$a = new Stack();
		$a->push('a');
		$a->push('bb');

		TestAuto::assert_equal( $a->pop(), 'bb' );
		TestAuto::assert_equal( $a->pop(), 'a' );
		TestAuto::assert_equal( $a->pop(), '' );
	}
}

