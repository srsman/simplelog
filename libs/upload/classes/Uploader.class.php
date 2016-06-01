<?php  
/*
解决什么问题

1 上传文件的附件存放的位置没有统一规划 
2 没有统一规则命名
3 自己写文件类型判断比较麻烦  比如专有的图片上传有固定模式的判断标准  文档也是. 
4 不能非常方便的获取文件的相对路径 ， 和通过相对路径获取url， 获取绝对路径 。 //通过url获取相对路径/


解决方案
1 统一放在data/attachment
2 命名规则是uuid。文件目录是日期例如2014-09-12 并且下面使用两级目录防止一个目录文件过多
3 写好img和文档两种判断标准
4 写好这几个功能的方法

$result = Uploader::save( 'xxx', $_FILES['filex'] );
$is_img = Uploader::is_img( $_FILES['filex'] );
$is_doc = Uploader::is_doc( $_FILES['doc'] );
$result => data/attachments/xxx/2013-2-2/xy/yx/uuid.jpg
$url = Uploader::url( $result );
$full_dir = Uploader::full_dir( $result );
$dir = Uploader::dir( $url );
 

*/
 

class Uploader{
	
	protected static  $_dir = NT_ATTACHMENT_PATH ;
	 
 	//获取文件名的后缀名 包括点
 	static public function get_ext( $filename ){
 		//如果没有设置文件名，那就改为一个完全不一样的名字

		$temp = trim( $filename ) ;
		if( strlen( $temp) == 0 ){
			return '';
		}
		$temp = explode('.',$temp);
		if( ! $temp || count( $temp ) == 1 ){
			return '';
		}
		 
		$temp = array_reverse($temp);
		$ext =  $temp[0];

		if( strlen( $ext ) != 0  ){
			return '.'.$ext;
		}else{
			return '';
		}
		 
 	}
 	//获取中间目录名 也就是从日期到自动生成的防止一个目录下文件过多的目录名
 	static public function get_mid_dir( $filename ){
 		return date('Y-m-d', time()).'/'. self::hash_dir( $filename );
 	}

	/**
	 * Save an uploaded file to a new location. If no filename is provided,
	 * the original filename will be used, with a unique prefix added.
	 *
	 * This method should be used after validating the $_FILES array:
	 *
	 *     if ($array->check())
	 *     {
	 *         // upload is valid, save it
	 *         upload::save($array['file']);
	 *     }
	 *
	 * @param   array   $file       uploaded file data
	 * @param   string  $filename   new filename
	 * @param   string  $directory  new directory
	 * @param   integer $chmod      chmod mask
	 * @return  string  on success, not full path to new file, but one folder name and a file name like 2011-02-22/xxxx.jpg
	 * @return  FALSE   on failure
	 */
	//保存通过页面上传的文件
	//第一个参数是attachements下的一级目录名
	//第3个参数是相对于data/attachments下的相对目录包括文件名
	static public function save( $name,  array $file, $fulldir = NULL )
	{	
		if ( ! isset($file['tmp_name']) OR ! is_uploaded_file($file['tmp_name']))
		{
			// Ignore corrupted uploads
			return FALSE;
		}

		$name = trim( $name );
		if( strlen( $name ) == 0 ){
			throw new Exception("name can not be null");
		}
		$filename = NULL ;
		if( strlen( $fulldir ) != 0 ){
			$filename = self::$_dir .'/'.$name.'/' . $fulldir;
		}
		 
		 
		
		if ($filename === NULL)
		{
			// Use the default filename, with a timestamp pre-pended
			//如果没有设置文件名，那就改为一个完全不一样的名字
			$f_name =   trim( get_guid() ,'{}') . self::get_ext( $file['name'] );
			$filename = self::$_dir.  $name.'/'.self::get_mid_dir( $f_name ). '/' .$f_name;
		}


		$directory = self::$_dir.  $name.'/'.self::get_mid_dir( $f_name ). '/';

		if ( ! is_dir($directory) OR ! is_writable(realpath($directory)))
		{
			//创建文件夹
			if( false === mkdir($directory, 0777, true)){
				throw new  exception("Directory :dir $directory mkdir failed");
			}
				 
		}
  
		 
		if ( move_uploaded_file( $file['tmp_name'], $filename ))
		{
			 
			// Set permissions on filename
			chmod($filename, 0644 );
			 

			// Return new file path
			//返回文件名和最后一级目录的部分
			return substr( $filename, strlen( self::$_dir ) );
			 
		}

		return FALSE;
	}

	//和save唯一的区别是第二个参数是二进制数据
	public function save2(  $name,  $data, $fulldir = NULL ){
		 

	}
	//判断这个上传的数据是有数据的
	static public function valid( $file ){
		if (isset($file['error']) AND isset($file['name']) AND isset($file['type'])	AND isset($file['tmp_name']) AND isset($file['size']) ){
			if(isset($file['error']) AND isset($file['tmp_name']) AND $file['error'] === 0	AND is_uploaded_file($file['tmp_name'])){
				return true;
			}
		}

		return false;
	}

	//上传文件的类型检查
	static public function type( $file, $allowed_types ){
		$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
		return in_array( $ext, $allowed_types );
	}
	  
	public static function is_img(array $file )
	{
		if ( !self::valid($file)){
			return false;
		}

		if( self::type( $file , array('jpg','jpeg','ico','png','psd') ) ){
			return true;
		}
		return false;
		 
	}
	public static function is_doc(array $file )
	{
		if ( !self::valid($file)){
			return false;
		}

		if( self::type( $file , array('txt','doc','pdf' ) ) ){
			return true;
		}
		return false;
		 
	}
	/**
	*	得到上传后的文件的url地址 从数据库取出从上传文件时返回的字符串放入这个方法作为参数，可以得到文件的url
	*	@param string $filename 文件名	like 2013-03-23/xx.jpg  如果是http开头的表示不用转换了
	*	@return string
	*
	*
	*
	*/
	static public function url( $dir ){
		if(strpos($dir, 'http') === 0){
			return $dir;
		}
		if($dir){
			 
			$url =  Req::get('attachment_url').$dir;
			return $url;
		}else{
			return '';
		}
	}
	static public function dir( $url ){
		if( $url ){
			return substr( $url , strlen( Req::get('attachment_url') ));
		}
		return '';
	}
	
	
	//得到上传之后的文件的绝对路径   filename是半截路径
	static public function full_dir( $dir ){
		if( $dir ){
			 
			return self::$_dir. $dir;
		}else{
			return '';
		}
	}
	 
	//通过新文件名生产两级目录，防止同一个目录之下文件太多
	static public function hash_dir( $str ) {

		Assert::str_not_null( $str );
		$base32 = array (
		'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h',
		'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p',
		'q', 'r', 's', 't', 'u', 'v', 'w', 'x',
		'y', 'z', '0', '1', '2', '3', '4', '5');
		$prefix='a';
		$suffix='z';
		$hex = md5($prefix.$str.$suffix);
		$hexLen = strlen($hex);
		$subHexLen = $hexLen / 8;
		$output = array();

		for ($i = 0; $i < $subHexLen; $i++) {
			$subHex = substr ($hex, $i * 8, 8);
			$int = 0x3FFFFFFF & (1 * ('0x'.$subHex));
			$out = '';
			for ($j = 0; $j < 6; $j++) {
				$val = 0x0000001F & $int;
				$out .= $base32[$val];
				$int = $int >> 5;
			}
			$output[] = $out;
		}
		return substr( $output[0] ,0, 2 ).'/'.substr( $output[1] , 0 , 2 );
	}



}
