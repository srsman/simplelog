<?php  

function _strcasecmp($str1, $str2)
{
	if (utf8::is_ascii($str1) AND utf8::is_ascii($str2))
		return strcasecmp($str1, $str2);

	$str1 = utf8::strtolower($str1);
	$str2 = utf8::strtolower($str2);
	return strcmp($str1, $str2);
}
