<?php

//所有的test类的命名以Test.class.php结尾  method以test_开头
class TestAuto{
	static public $dirs = array(
		//NT_LIBS_PATH,
		NT_MODEL_PATH,

	);

	static public function test_ok(){
		echo "test sucess ! </br> \n";
	}
	static public function test_failed(){
		echo "test error ! </br> \n";
		throw new Exception( 'exit:test failed !' );
	}

	static public function assert_true( $v ){
		if( $v == true ){

			echo "assert_true: test success ! </br> \n";
		}else{
			echo "test failed : assert_true  error </br> \n";
			var_dump($v);
			throw new Exception( 'exit:test failed !' );
		}
	}

	static public function assert_false( $v ){
		if( $v == false ){

			echo "assert_false: test success ! </br> \n";
		}else{
			echo "test failed : assert_false  error </br> \n";
			var_dump($v);
			throw new Exception( 'exit:test failed !' );
		}
	}
	static public function assert_diff( $a, $b ){
		if( $a == $b ){
			echo "test failed : assert_diff error </br> \n";
			var_dump($args);
			throw new Exception( 'exit:test failed !' );
		}else{
			echo "assert_diff: test success ! </br> \n";
		}
	}

	static public function assert_num( $var ){
		if( !is_numeric($var)){
			echo "test failed : assert_num error </br> \n";
			var_dump($args);
			throw new Exception( 'exit:test failed !' );
		}else{
			echo "assert_num: test success ! </br> \n";
		 
		}
	}


	static public function assert_equal(){
		$args = func_get_args();
		$count = count( $args );
		$good = true;
		for( $i = 0 ; $i < $count ; $i++ ){
			if( isset( $args[ $i+1 ] ) ){
				if( $args[$i] != $args[ $i+1 ] ){
					$good = false;
				}
			}
			
		}
		if( $good ){
			echo "assert_equal: test success ! </br> \n";
		}else{
			echo "test failed : assert_equal  error </br> \n";
			var_dump($args);
			throw new Exception( 'exit:test failed !' );
		}
		

	}

	static public function run( $model_files = null ){
		 
		$files = self::get_files( $model_files );
		 
		

		foreach( $files as $k => $v ){
			$temp = explode('.', $k );
			$class = $temp[0];
			require_once( $v );
			$re = new ReflectionClass( $class );
			$methods = $re->getMethods( ReflectionMethod::IS_STATIC );
			echo "</br> \n</br> \n $v </br> \n";
			if( method_exists( $class, 'a')){
				call_user_func( array( $class, 'a')  );
			}

			foreach ($methods as $m ) {
				if( strpos($m->name, 'test_') === 0 ){
					call_user_func(array( $class, $m->name ));
				}
			}
			if( method_exists( $class, 'z')){
				call_user_func( array( $class, 'z')  );
			}
			 
			 
		}

		 


	}


	/*
	array (size=8)
	  'BalanceTest.class.php' => string 'D:\wamp\www\new\model/balance/BalanceTest.class.php' (length=51)
	  'CardTest.class.php' => string 'D:\wamp\www\new\model/card/CardTest.class.php' (length=45)
	  'ApimsgTest.class.php' => string 'D:\wamp\www\new\model/common/ApimsgTest.class.php' (length=49)
	  'ToolTest.class.php' => string 'D:\wamp\www\new\model/common/ToolTest.class.php' (length=47)
	  'CreditTest.class.php' => string 'D:\wamp\www\new\model/credit/CreditTest.class.php' (length=49)
	  'InterPmsConfigTest.class.php' => string 'D:\wamp\www\new\model/member/InterPmsConfigTest.class.php' (length=57)
	  'MemberLoginInfoTest.class.php' => string 'D:\wamp\www\new\model/member/MemberLoginInfoTest.class.php' (length=58)
	  'MemberTest.class.php' => string 'D:\wamp\www\new\model/member/MemberTest.class.php' (length=49)

	*/
	static public function get_files( $model_files = null ){

		$all = array(); 
		$files = array();
		foreach( self::$dirs as $var ){
			$files = array_merge( $files, Dir::list_dir_file2( $var ) );
		}
		if( !$files ){
			return array();
		}
		 
		foreach( $files as $v ){
			if( strpos( $v, '.class.php')  ){
				$all[] = $v;
			}
			if( is_dir( $v ) ){
				$files2 =  Dir::list_dir_file2( $v ) ;
				foreach( $files2 as $vv ){
					if( strpos( $vv, '.class.php')  ){
						$all[] = $vv;
					}
					if( is_dir( $v ) ){
						$all = array_merge( $all, Dir::list_dir_file2( $vv ) );
					}
				}
			}
		}

		$new = array();

		foreach( $all as $t2 ){
			if( strpos( $t2, 'Test.class.php')  ){ 
				$new[basename( $t2 )] = $t2;
			}
		}
		asort( $new );

		if( $model_files ){
			$return = array();
			foreach( $model_files as $mf ){
				if( isset( $new[$mf])  ){
					$return[$mf] = $new[$mf];
				}
			}
			return $return;
		}
		return $new;

		
	} 
}