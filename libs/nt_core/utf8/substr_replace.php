<?php  
 
function _substr_replace($str, $replacement, $offset, $length = NULL)
{
	if (utf8::is_ascii($str))
		return ($length === NULL) ? substr_replace($str, $replacement, $offset) : substr_replace($str, $replacement, $offset, $length);

	$length = ($length === NULL) ? utf8::strlen($str) : (int) $length;
	preg_match_all('/./us', $str, $str_array);
	preg_match_all('/./us', $replacement, $replacement_array);

	array_splice($str_array[0], $offset, $length, $replacement_array[0]);
	return implode('', $str_array[0]);
}
