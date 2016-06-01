<?php

//数据库
class Sql{
	 
	 
	protected    $_table = '';                     //对应的表的名字
	 
	 

	protected    $_where = array( 'str'=>'', 'params'=>array() );                     //查询条件  str params
	protected	 $_data = array();                 //数据，用于更新和insert
	protected	 $_order = '';                     //排序语句
	protected	 $_limit = '';                     //limit 语句
	protected	 $_select = '';                    //select语句的字段名
	protected    $_empty_in = false;               //当查询的时候  设置了in条件 第二个参数却为空数组的情况下，这个值会被设置为true;
	protected    $_sql_str = ''; //sql语句
	protected    $_sql_params = array(); //sql?参数
	
	static public function fac(){
		return new Sql();
	} 
 
	/**
	*	table方法，设置表名
	*	@param string $table 
	*   @return obj
	*	 
	*/
	public function table( $table ){
		$this->_table = trim( $table );
		return $this;
	}

	/**
	*	data方法，设置数据 参数只能是一d数组 
	*	data(array('name'=>'mike','age'=>25))
	*	@param array $data     
	*	@return obj
	*	 
	*/
	public function data( $data  ){
		
		
		$new2 = array();
		foreach( $data as $key2 => $var2 ){
			if( isset($var2) ){
				$new2[$key2] = $var2;
			}
			
		}
		$data = $new2;
		$this->_data = $data;
		return $this;
		
	}
	 
	/**
	*	 
	*	where方法，设置查询条件
	*	where( array('id'=>5,'name'=>'a') ) 多条件and
	*	where("id = 5")
	*	where("id = ?", $id )
	*	where("id = ?", array($id))
	*	@param mixed $where 条件   
	*	@param array $args 对应占位符的参数
	*	@return obj
	*	 
	*/
	public function where( $where='', $args = array() ){ 
		if( is_array( $where ) ){
			$str = '';
			$args = array();
			foreach( $where as $k=>$v ){
				$str .= " `$k` = ? and ";
				$args[] = $v;
			}
			$str = trim( $str, ' and' );
			$this->_where['str'] = $str;
			$this->_where['params'] = $args;

		}else{
			if( is_array( $args ) ){
				$this->_where['str'] = trim( $where );
				$this->_where['params'] = $args;
			
			}else{
				$this->_where['str'] = trim( $where );
				$needles = func_get_args();
				array_shift( $needles );
				if( $needles ){
					$this->_where['params'] = $needles;
				}
			}
		}
		
		return $this;
	}
	/**
	*	in( 'user_id', 1,2,3,4);
	*	in( 'user_id', array(1,2,3,4) );
	*
	*/
	
	//设置in条件      如果in_arr为空 则必然查询结果为空
	public function in( $in_field, $in_arr = array()  ){
		if( !is_array($in_arr) ){
			$needles = func_get_args();
			array_shift( $needles );
			$in_arr = $needles;
		}
		if( !$in_arr ){
			$this->_empty_in = true;
		}
		$question_mark = "(";
		 
		foreach( $in_arr as $v ){
			$question_mark .= " ? , ";
			 
		}
		$question_mark = trim( $question_mark, ', ' );
		$question_mark .= ")";
		$str = "$in_field in $question_mark ";
		$this->_where['str'] = $str;
		$this->_where['params'] = $in_arr;  
		return $this;
	}
	 
	/**
	*	order方法，设置排序
	*	order("age asc")
	*	order("createtime desc");
	*	@param string $order
	*	@return obj
	*	 
	*/
	public function order($order=''){
		$this->_order = trim($order);
		return $this;
	}
	 
	/**
	*	limit方法,设置limit
	*	@param integer $offset
	*   @param integer $limit
	*	@return obj
	*	 
	*	 
	*/
	public function limit( $offset, $limit = null ){
		if( $offset === null ){
			$this->_limit = '';
			return $this;
		}
		if( $offset == 0 ){
			$this->_limit = '';
			return $this;
		} 
		if( !$limit ){
			$offset = (int)$offset;
			$this->_limit = "limit $offset";
		}else{
			$offset = (int)$offset;
			$limit = (int)$limit;
			$this->_limit = "limit $offset, $limit";
		}
		
		return $this;
	}
	//为select方法做准备
    protected function _pre_select( $select='*' ){
		if( strlen( $select ) == 0 ) $select = '*';		 
		$this->_select = $select;
		
		//查询sql
		 
		if( strlen( $this->_where['str'] ) !=0 ){
			$where_str = "where ".$this->_where['str']; 
		}else{
			$where_str = '';
		}
		 
		if($this->_order){
			$order_str = "order by ".$this->_order;
		}else{
			$order_str = '';
		}
		if($this->_limit){
			$limit_str = $this->_limit;
		}else{
			$limit_str = '';
		}
		 		
		$sql = "select ".$this->_select." from ".$this->_table." $where_str $order_str $limit_str";
		$this->_sql_str =  $sql;
		$this->_sql_params = $this->_where['params'];
	}
	/**
	*	生成select语句
	*	@param string $select
	*   @return obj
	*   对返回结果直接作为参数执行callback
	*	对返回结果数组的每一个元素执行callback_array  若结果不是数组会出错
	*	
	*/
	public function select( $select='*' ){
		
		$this->_pre_select( $select );
		return array( trim( $this->_sql_str ), $this->_sql_params );
    	 
	 
	}
	 
	//select count
	public function select_count(){
		$this->_pre_select('count(*) as c');
		return array( trim( $this->_sql_str ), $this->_sql_params );
    }
	 
	//解析data()获取的数据
	protected function _parse_data(){
		$data = $this->_data;
		if( !$data  ){
			throw new Exception('不能插入空数据');
		}
		$str = " set ";
		$params = array();
		foreach( $data as $k=>$v ){
			$str .=" `$k` = ? ,";
			$params[] = $v; 
		}

		$str  = trim( $str, ',' );
		return array( $str, $params );

	}
  
	/**
	*	save函数 
	*	@return obj	 
	*/
	public function insert(){
		//检查一些必要条件
		 

		list( $str, $params ) = $this->_parse_data();
		$sql = "insert into ".$this->_table."$str";  
		  
		return array( trim( $sql ), $params );	
		 
		
	}
	 
	/**
	*	update函数 执行update
	*	@return obj
	*    	 
	*/
	public function update(){
		if(!$this->_data){
			throw new Exception('更新空数据');
		}
		list( $str, $params ) = $this->_parse_data();
		 
		if( strlen( $this->_where['str'] ) !=0 ){
			$where_str = "where ".$this->_where['str']; 
		}else{
			$where_str = '';
		}
		
		if($this->_limit){
			$limit_str = $this->_limit;
		}else{
			$limit_str = '';
		}
		$sql = "update ".$this->_table."$str $where_str $limit_str";
		 
		return array( trim( $sql ), array_merge( $params, $this->_where['params'] ) );
		 
		
	}
	 
	/**
	*	delete函数 , 执行delete
	*	@return obj	 
	*/
	public function delete(){
		

		//条件
		if( strlen( $this->_where['str'] ) !=0 ){
			$where_str = "where ".$this->_where['str']; 
		}else{
			$where_str = '';
		}
		 
		if($this->_limit){
			$limit_str = $this->_limit;
		}else{
			$limit_str = '';
		}
		$sql = "delete from ".$this->_table." $where_str $limit_str";
		
		return array( trim( $sql ), $this->_where['params'] );
		 
		
	}
 
     
}