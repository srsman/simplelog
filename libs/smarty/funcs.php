<?php
class My_Smarty extends Smarty{
	public $doc;
	 
	/**
	*	给模板赋予值 同时过滤标签。
	*	$controller->assign('data', $data)
	*   @param string $name
	*	@param mixed $value
	*	@param boolean $strip_tags 是否过滤html标签
	*	@return void
	*/
	public function assign( $name, $value=null, $strip_tags = true ){
		if($value === null){
			$value = '';
		}
		if($strip_tags){
			$value = Input::dhtmlspecialchars($value);
		}
		parent::assign( $name, $value );
	}

}

//获取一个模板引擎的实例
function get_smarty( $template_dir = '' ){

	
	
	$config = NT::load_config('smarty');
	
	$smarty_config = $config;

	 
	$smarty = new My_Smarty();
	if( DEV_MODE ){
		//关闭缓存
		$smarty->caching = 0;
		 
	}
	if(!$template_dir){
		$smarty->template_dir = $smarty_config['html_dir'];
	}else{
		$smarty->template_dir = $template_dir;
	}
	
	$smarty->compile_dir = $smarty_config['smarty_file_dir'].'templates_c/';
	$smarty->config_dir = $smarty_config['smarty_file_dir'].'configs/';
	$smarty->cache_dir = $smarty_config['smarty_file_dir'].'cache/';
	$smarty->left_delimiter = "{{";  //定义左边   
	$smarty->right_delimiter = "}}"; //定义右边  
	 
	$smarty->assign('url', Req::get( 'url')  );
	 
	$smarty->assign('base', Req::get( 'view_url') );
	
	 
	return $smarty;
}
