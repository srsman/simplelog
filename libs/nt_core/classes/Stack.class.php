<?php

//栈   push pop
class Stack{
	protected $data = array();

	public function push( $var ){
		if($var === null){
			return $this;
		} 
		array_unshift($this->data , $var);
		return $this;

	}

	public function pop(){

		return array_shift($this->data);
	}
	public function get_data(){
		$data = $this->data;
		return  $data ;
		 

	}
	public function data(){
		return $this->get_data();
	}
	//反转
	public function reverse(){
		$data = array_reverse($this->data);
		$this->data = $data;
		return $this;
	}

}