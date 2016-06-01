<?php

//时间相关、
//时间格式， 一种是常用的unix时间戳秒数，另一种是比较可读的字符串例如 '2014-12-23-12-45-55'依次是年月日时分秒
if (!function_exists('cal_days_in_month')) 
{ 
	function cal_days_in_month($calendar, $month, $year) 
	{ 
		return date('t', mktime(0, 0, 0, $month, 1, $year)); 
	} 
} 
if (!defined('CAL_GREGORIAN')) 
	define('CAL_GREGORIAN', 1); 



class TimePoint{

	protected $_time = null;
	public $year = null;
	public $month = null;
	public $day = null;
	public $mins = null;
	public $seconds = null;
	public $hours = null;


	//初始化一个时间点。可以是时间戳，也可以是-分割的时间字符串
	public function __construct( $time ){
		//认为是一个类似2014-12-23-12-45-55格式的时间
		if( strlen( $time ) == 19 and strpos( $time, '-' ) !== false ){
			$temp = explode( '-', $time );
			$timestamp = getdate( $temp[3],$temp[4],$temp[5],$temp[1],$temp[2],$temp[0] );

			$this->_time = $timestamp;
		}else{
			$this->_time = (int)$time;
		}

		$this->year = (int)date('Y', $this->_time );
		$this->month = (int)date('m', $this->_time );
		$this->day = (int)date('d', $this->_time );
		$this->mins = (int)date('i', $this->_time );
		$this->seconds = (int)date('s', $this->_time );
		$this->hours = (int)date( 'H', $this->_time );

	}
	//今天的开始
	public function day_head(){
		$time = $this->_time;
		
		$result = mktime( 0,0,0, date( 'm', $time ),date('d', $time ), date('Y', $time) );
		return $result  ;
	}

	//今天的结束
	public function day_foot(){
		$time = $this->_time;
		
		$result = mktime( 23,59,59, date( 'm', $time ),date('d', $time ), date('Y', $time) );
		return $result  ;
	}
	 
	//获取时间点的月开始
	public function month_head(){
		$time = $this->_time;
		
		$result = mktime( 0,0,0, date( 'm', $time ),1, date('Y', $time) );
		return $result  ;

	}
	//获取时间点的月结束
	public function month_foot(){
		$time = $this->_time ;
		$result = mktime( 23,59,59, date( 'm', $time ), cal_days_in_month(CAL_GREGORIAN, date( 'm', $time ), date( 'Y', $time ))   , date( 'Y', $time ) );
		return $result;

	}

	 
	//获取当前时间戳
	public function time(){
		return mktime( $this->hours, $this->mins, $this->seconds, $this->month, $this->day, $this->year );
		
	}

	 

}


 