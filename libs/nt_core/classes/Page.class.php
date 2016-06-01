<?php 
/**
*	page	 
*	@package hp
*   @category system
*	@author Jersey Mars
*	 
*	//特别说明   -- 对于get_offset  get_links get_links2 get_links3 方法 ，当前地址上没有p参数的时候，就传递空字符串，否则会发生分页列表url地址错误
*/
class Page{
	protected $_count_all = null;
	protected $_count_per_page = null;
	protected $_url = null;
	protected $_current_page = null;

	/**
	*	初始化page
	*	@param integer $count_all 总条数
	*   @param integer $count_per_page 每页的条数
	*	@param string $url 需要拼接页码的地址  其中:p:将被当前页码替换
	*	@return object
	*	 
	*/
	public function __construct( $count_all, $count_per_page, $url){
		Assert::not_empty( $count_all );
		Assert::num_gt_zero( $count_per_page );
		Assert::not_empty( $url );
		$this->_count_all = (int)$count_all;
		$this->_count_per_page = (int)$count_per_page;
		$this->_url = $url; 
		 
		$this->_current_page  = 1; //当没有设置当前页面的时候，默认1 
	}
	


	/**
	*	得到offset用于数据库查询操作
	*	@param integer $current_page 当前页码
	*   @return integer
	*
	*	 
	*	 
	*/
	public function get_offset( $current_page ){
		Assert::num_gt_zero( $current_page ); 
		if(!$current_page){
			$current_page = $this->_current_page;
		}
		
		$count_per_page = $this->_count_per_page;
		$count_all = $this->_count_all;
		$min = 1;
		$max = ceil($count_all/$count_per_page);
		if($current_page <= 0){
			$current_page = $min;
		}elseif($current_page > $max  ){
			$current_page = $max;
		}
		$this->_current_page = $current_page;
		 
		$offset = $count_per_page*($current_page - 1);
		if($offset < 0){
			$offset = 0;
		}
		if(!$offset){
			$offset = 0;
		}
		return $offset;
	}
	public function offset( $current_page ){
		return $this->get_offset( $current_page );
	}
	
	//获取页码的html代码  分别有几种style 1 2 3
	public function html( $current_page , $style = 1 ){
		if(!$current_page){
			$current_page = $this->_current_page;
		}
		 
		 
		$count_per_page = $this->_count_per_page;
		$count_all = $this->_count_all;

		if( $style == 1 ){
			return $this->_get_links(  $current_page, $count_per_page, $count_all  ) ;
		}elseif( $style == 2 ){
			return $this->_get_links2(  $current_page, $count_per_page, $count_all  ) ;
		}elseif( $style == 3 ){
			return $this->_get_links3(  $current_page, $count_per_page, $count_all  ) ;
		}elseif( $style == 4 ){
			return $this->_get_links4(  $current_page, $count_per_page, $count_all  ) ;
		}else{
			return $this->_get_links(  $current_page, $count_per_page, $count_all  ) ;
		}
	}

	/**
	*	获取页码字符串
	*	@param integer $current_page
	*   @return string 
	*
	*	 
	*	 
	*/
	protected function _get_links( $current_page, $count_per_page, $count_all ){
		 
 
		   
		$min = 1;
		$max = ceil($count_all/$count_per_page);
		if($current_page <= 0){
			$current_page = $min;
		}elseif($current_page > $max  ){
			$current_page = $max;
		}

		$range = $this->_get_page_list_range($current_page);
		if($current_page != 1){
			$prev = $current_page-1;
			$links = "<a href=\"".$this->_set_p_to_url($min)."\">  第一页  </a>";
			$links .= "<a href=\"".$this->_set_p_to_url($prev)."\">  上页  </a>";
		}else{
			$links = '';
		}
		
		$range_top = self::_get_min_num($range[1],$max );
		for($i=$range[0];$i<=$range_top;$i++){
			if($current_page == $i){
				$links .= "<span class=\"active\"> $i </span>";
			}else{
				$links .= "<a href=\"".$this->_set_p_to_url($i)."\">  $i  </a>";
			}
		}

		if($current_page == $max ){
			$links .= '';
		}else{
			$next =  $current_page+1;
			$links .= "<a href=\"".$this->_set_p_to_url($next)."\">  下页  </a>";
			$links .= "<a href=\"".$this->_set_p_to_url($max)."\">  末页  </a>";
		}
		$links .= "({$current_page}/{$max})"; 

		if($count_all<=$count_per_page){
			$links = '';
		}
		return $links;
	}
	 
	/**
	*	获取页码字符串 另一种显示方式
	*	@param integer $current_page
	*   @return string 
	*
	*	 
	*	 
	*/
	protected function _get_links2( $current_page, $count_per_page, $count_all  ){
		 
		  
		$min = 1;
		$max = ceil($count_all/$count_per_page);
		if($current_page <= 0){
			$current_page = $min;
		}elseif($current_page > $max  ){
			$current_page = $max;
		}
		
		$range_low = self::_get_max_num( $current_page-4, $min );
		$range = array();
		for($i=$range_low;$i<$range_low+9;$i++){
			if($i<=$max){
				$range[] = $i;
			}
		}


		 
		if($current_page != 1){
			$prev = $current_page-1;
			$links = "<a href=\"".$this->_set_p_to_url($min)."\">  第一页  </a>";
			$links .= "<a href=\"".$this->_set_p_to_url($prev)."\">  上页  </a>";
		}else{
			$links = '';
		}
		
		 
		foreach($range as $v){
			if($current_page == $v){
				$links .= "<span class=\"active\"> $v </span>";
			}else{
				$links .= "<a href=\"".$this->_set_p_to_url($v)."\">  $v  </a>";
			}
		}

		if($current_page == $max ){
			$links .= '';
		}else{
			$next =  $current_page+1;;
			$links .= "<a href=\"".$this->_set_p_to_url($next)."\">  下页  </a>";
			$links .= "<a href=\"".$this->_set_p_to_url($max)."\">  末页  </a>";
		}
		$links .= "({$current_page}/{$max})"; 

		if($count_all<=$count_per_page){
			$links = '';
		}
		return $links;
	}
	/**
	*	获取页码字符串 另一种上一页下一页的显示方式
	*	@param integer $current_page
	*   @return string 
	*
	*	 
	*	 
	*/
	protected function _get_links3(  $current_page, $count_per_page, $count_all  ){
		 
		 
		$min = 1;
		$max = ceil($count_all/$count_per_page);
		if($current_page <= 0){
			$current_page = $min;
		}elseif($current_page > $max  ){
			$current_page = $max;
		}
 		$links = '';
		if($current_page != 1){
			$prev = $current_page-1;
			//$links = "<a href=\"".concat_url($url,  $min,'c_args' )."\">  第一页  </a>";
			$links .= "<a href=\"".$this->_set_p_to_url($prev)."\">  上页  </a>";
		}else{
			$links = ' 上页 ';
		}


	  
		if($current_page == $max ){
			$links .= '已到末页';
		}else{
			$next =  $current_page+1;
			$links .= "<a href=\"".$this->_set_p_to_url($next)."\">  下页  </a>";
			//$links .= "<a href=\"".concat_url($url, $max,'c_args' )."\">  末页  </a>";
		}
		$links .= "({$current_page}/{$max})"; 

		if($count_all<=$count_per_page){
			$links = '';
		}
		return $links;
	}
	//第四种分页风格
	 
	protected function _get_links4(  $current_page, $count_per_page, $count ){

		if( !$count ){
			return false;
		}
		if( $current_page <= 0 ){
			$current_page=1;
		}
		$data['firstRow']=($current_page*$count_per_page)-$count_per_page;
		$data['listRows']=$count_per_page;
		$mod = 0;
		if( $count % $count_per_page !=0 ){
			$mod = 1;
		}
		$data['page'] = intval( $count / $count_per_page ) + $mod;
		$data['count'] = $count;
		$data['num'] = $count_per_page;
		$data['hml'] = "";
		if($current_page>$data['page']){
			$current_page = $data['page'];
		}

		$shang= intval(intval($current_page) - 2 );
		$xia= intval(intval($current_page) + 2 );
		if( $shang > 0 && (($current_page+2)<=$data['page'])){ //在页数大于0和总页数时
			//echo "1";
			if($current_page==1){
				$data['hml'].="<a  class='prev'>←</a>";
			}else{
				$data['hml'].="<a href='".$this->_set_p_to_url($current_page-1)."' class='prev'>←</a>";
			}
			if($shang!=1){
				$data['hml'].="<a href='".$this->_set_p_to_url('1')."' class='first' >1</a>";
				$data['hml'].="<a class='notclick'>...</a>";
			}
			for ( $i = ($current_page-2) ; $i <= ($current_page+2) ; $i++ ) { 
				if($current_page==$i){
					$data['hml'].="<a href='".$this->_set_p_to_url($i)."' class='active'>".$i."</a>";	
				}else{
					$data['hml'].="<a href='".$this->_set_p_to_url($i)."' class='num'>".$i."</a>";	
				}
			}
			if(($current_page+2)<$data['page']){
				$data['hml'].="<a class='notclick'>...</a>";
				$data['hml'].="<a href='".$this->_set_p_to_url($data['page'])."' class='end'>".$data['page']."</a>";
				$data['hml'].="<a href='".$this->_set_p_to_url($current_page+1)."' class='next'>→</a>";
			}else{
				$data['hml'].="<a href='".$this->_set_p_to_url($current_page+1)."' class='next'>→</a>";
			}
		}else{
			if($shang<=0){ //当前页数减去2小于0时
				if($data['page']>5){  //当满足上述条件并页码书大于5时
					//echo "2";
					if($current_page ==1 || $current_page==2){
						if($current_page==1){
							$data['hml'].="<a class='prev'>←</a>";	
						}else{
							$data['hml'].="<a href='".$this->_set_p_to_url($current_page-1)."' class='prev'>←</a>";	
						}
						for ($i=1; $i <=5 ; $i++) { 
							if($current_page==$i){
								$data['hml'].="<a href='".$this->_set_p_to_url($i)."' class='active'>".$i."</a>";	
							}else{
								$data['hml'].="<a href='".$this->_set_p_to_url($i)."' class='num'>".$i."</a>";	
							}
						}
						$data['hml'].="<a class='notclick'>...</a>";
						$data['hml'].="<a href='".$this->_set_p_to_url($data['page'])."' class='end'>".$data['page']."</a>";
						$data['hml'].="<a href='".$this->_set_p_to_url($current_page+1)."' class='next'>→</a>";
					}
				}else{//当满足上述条件并页码书小于5时
					//echo "3";
					if($current_page ==1 || $current_page==2){
						if($current_page==1 && $current_page==$data['page']){
							$data['hml'].="<a  class='prev'>←</a>";	
							$data['hml'].="<a href='".$this->_set_p_to_url(1)."' class='active'>1</a>";	
							$data['hml'].="<a  class='next'>→</a>";
						}else{
							if($current_page==1){
								$data['hml'].="<a  class='prev'>←</a>";	
							}else{
								$data['hml'].="<a href='".$this->_set_p_to_url($current_page-1)."' class='prev'>←</a>";	
							}
							for ($i=1; $i <=$data['page'] ; $i++) { 
								if($current_page==$i){
									$data['hml'].="<a href='".$this->_set_p_to_url($i)."' class='active'>".$i."</a>";	
								}else{
									$data['hml'].="<a href='".$this->_set_p_to_url($i)."' class='num'>".$i."</a>";	
								}
							}
							if($data['page']>5){
								$data['hml'].="<a class='notclick'>...</a>";
								$data['hml'].="<a href='".$this->_set_p_to_url($data['page'])."' class='end'>".$data['page']."</a>";
							}
							if($current_page==$data['page']){
								$data['hml'].="<a  class='next'>→</a>";
							}else{
								$data['hml'].="<a href='".$this->_set_p_to_url($current_page+1)."' class='next'>→</a>";
							}
						}
						
					}
				}
			}else{ //当前页数减去2大于0时
				//echo "4";
				if(($data['page']-4>=1)){
					$count_per_page1=$data['page']-1;
					$count_per_page2=$data['page']-2;
					$data['hml'].="<a href='".$this->_set_p_to_url($current_page-1)."' class='prev'>←</a>";
					if($data['page']>5){
						$data['hml'].="<a href='".$this->_set_p_to_url(1)."' class='first' >1</a>";
						$data['hml'].="<a class='notclick'>...</a>";
					}
					for ( $i = $data['page']-4 ; $i <= $data['page'] ; $i++) { 
						if($current_page==$i){
							$data['hml'].="<a href='".$this->_set_p_to_url($i)."' class='active'>".$i."</a>";	
						}else{
							$data['hml'].="<a href='".$this->_set_p_to_url($i)."' class='num'>".$i."</a>";	
						}
					}
					if($current_page==$data['page']){
						$data['hml'].="<a  class='next'>→</a>";
					}else{
						$data['hml'].="<a href='".$this->_set_p_to_url($current_page+1)."' class='next'>→</a>";
					}
				}else{
					$data['hml'].="<a href='".$this->_set_p_to_url($current_page-1)."' class='prev'>←</a>";
					for ( $i = 1 ; $i <= $data['page'] ; $i++) { 
						if($current_page==$i){
							$data['hml'].="<a href='".$this->_set_p_to_url($i)."' class='active'>".$i."</a>";	
						}else{
							$data['hml'].="<a href='".$this->_set_p_to_url($i)."' class='num'>".$i."</a>";	
						}
					}
					if($current_page==$data['page']){
						$data['hml'].="<a  class='next'>→</a>";
					}else{
						$data['hml'].="<a href='".$this->_set_p_to_url($current_page+1)."' class='next'>→</a>";
					}

				}
			}
		}
		return $data['hml'];
	}
	/**
	*	获取两个数字中较小的
	*	@param integer $num1
	*	@param integer $num2
	*   @return integer
	*
	*	 
	*	 
	*/
	static protected function _get_min_num($num1,$num2){
		if($num1 < $num2){
			return $num1;
		}else{
			return $num2;
		}
	}
	/**
	*	获取两个数字中较大的
	*	@param integer $num1
	*	@param integer $num2
	*   @return integer
	*
	*	 
	*	 
	*/
	static protected function _get_max_num($num1, $num2){
		if($num1 > $num2){
			return $num1;
		}else{
			return $num2;
		}
	}

	/**
	*	根据当前页码改变现在的url
	*
	*/
	protected function _set_p_to_url( $p='' ){
		if(!$p) $p = 1;
		$url = $this->_url ;
		$url = str_replace(':p:', $p, $url ); 
		return $url;

	}

	/**
	*	服务于get_links的函数，得到当前显示页码列表的范围
	*	@param integer $current_page
	*   @return array
	*
	*	 
	*	 
	*/
	protected function _get_page_list_range( $current_page ){
		$top = ceil($current_page/10)*10;
		$bottom = $top - 9;
		return array($bottom,$top);
	}
}

