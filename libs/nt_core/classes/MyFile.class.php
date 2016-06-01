<?php
class Myfile{

	static public function get_content_array( $file_name, $limit = 10000 , $func = null ){
		 
		$data = array(); 
		$f = fopen( $file_name  , 'r' );
		$i = 0;
		if( $f ){
			while( !feof( $f )){
				$line = fgets( $f );
				$i++;

				$data[] = $line;
				if($limit){
					if( $i == (int)$limit ){
						break;
					}
				}
			}
			fclose( $f );
		}
		if( $func ){
			$data = map( $func, $data );
		}
		return $data;
	}
}