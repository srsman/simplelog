<?php

class AssertTest{

	static public function test_email(){
		 
		TestAuto::assert_equal( Assert::email( 'allinxx@126.com' ) , 'allinxx@126.com' );
		TestAuto::assert_equal( Assert::email( 'allinxx@126.com' ) , 'allinxx@126.com' );

	}
 


	static public function test_emaildomain(){
		 
		TestAuto::assert_equal( Assert::email_domain('126.com'), '126.com' );
	}

	static public function test_emaildomain2(){
		$test = 'xxx';
		try{
			Assert::email_domain( $test );
		}catch( Exception $e ){
			TestAuto::test_ok();
		}
		 
	}


	static public function test_url(){
		 
		TestAuto::assert_equal( Assert::url( 'http://www.abc.com/atc/sdf' ) , 'http://www.abc.com/atc/sdf' );
	}


	static public function test_ip(){
		TestAuto::assert_equal( Assert::ip( '115.12.23.56') , '115.12.23.56' );
		 
	}


	static public function test_phone(){
		TestAuto::assert_equal( Assert::phone( '18145124512') , '18145124512' );
		 
	}


	 


	


}

