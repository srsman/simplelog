<?php  
//对称加密解密


//这样的情况下不要使用向量，因为每次向量不一样导致不用同一个 xcrypt对象无法解 省去保存向量的麻烦
class Xcrypt{

	protected $key = 'a8c934fs';//选用8位字符串 

	public function __construct( $key = '' ){
		$key = trim($key);
		switch (strlen( $key )){  
         case 0:  
             
            break; 
		case 8:  
             
            break;  
        case 16:  
             
            break;  
        case 32:  
              
            break;  
        default:  
            throw new Exception("Key size must be 8/16/32");  
        }
		if(strlen($key) !== 0 ){
			$this->key = $key;
		}
		

	}
	 
	 
	//加密
	public function en($str){
		if( strlen($str)== 0 ){
			throw new Exception('str length zero');
		}

		$m = new NT_Xcrypt( $this->key, 'ecb', 'off');  
		return  $m->encrypt($str, 'hex');  
	}
	//解密
	public function de( $str ){
		if( strlen($str)== 0 ){
			throw new Exception('str length zero');
		}
		$m = new NT_Xcrypt( $this->key, 'ecb', 'off'); 
		//解密  
		return $m->decrypt($str, 'hex');
	}

}