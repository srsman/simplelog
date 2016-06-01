<?php  
/**
*	 
*	 
*/
class MyCurl{
	/**
	*	进行get操作
	*	mycurl::send_get('www.baidu.com')
	*   @param string $url
	*	@param array $header  array('Accept:xxx','Host:xx')
	*	@return string
	*	 
	*	 
	*/
	static public function send_get( $url,  $header=array() ){
		$url = trim($url);
		$ch = curl_init();
		// 设置URL和相应的选项
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch, CURLOPT_TIMEOUT , 5);
		if($header){
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		}
		$start = microtime( true );
		$return = curl_exec($ch);
		$end = microtime( true );
		$time = round( $end - $start , 6 );
		if(!$return){
			$data = array(
					
					'url' => $url,
					'errno'=> curl_errno($ch),
					'error'=> curl_error($ch ),
					
				);
			Log::write('mycurl_err',$data );
			 
		}else{
			Log::write('mycurlget_time', array('time'=>$time,'url'=>$url,'return'=>$return ));
		}
		curl_close($ch);
		 
		return $return;
	}
	/**
	*	进行post操作
	*	@param string $url
	*   @param string $data 需要post出去的数据
	*	@param array $header  array('Accept:xxx','Host:xx')
	*	@return string
	*	 
	*	 
	*/
	static public function send_post($url, $data, $header=array()){
		$url = trim($url);
		$ch = curl_init();
		// 设置URL和相应的选项
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query( $data ));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch, CURLOPT_TIMEOUT , 5);
		if($header){
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		}
		$start = microtime( true );
		$return = curl_exec($ch);
		$end = microtime( true );
		$time = round( $end - $start , 6 );
		if(!$return){
			$data = array(
					
					'url' => $url,
					'errno'=> curl_errno($ch),
					'error'=> curl_error($ch ),
					
				);
			Log::write('mycurl_err',$data );
			 
		}else{
			Log::write('mycurlpost_time', array('time'=>$time,'url'=>$url,'return'=>$return));
		}
		curl_close($ch);
		return $return;
	}
}