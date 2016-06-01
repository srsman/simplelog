<?php  

function _strrpos($str, $search, $offset = 0)
{
	$offset = (int) $offset;

	if (utf8::is_ascii($str) AND utf8::is_ascii($search))
		return strrpos($str, $search, $offset);

	if ($offset == 0)
	{
		$array = explode($search, $str, -1);
		return isset($array[0]) ? utf8::strlen(implode($search, $array)) : FALSE;
	}

	$str = utf8::substr($str, $offset);
	$pos = utf8::strrpos($str, $search);
	return ($pos === FALSE) ? FALSE : ($pos + $offset);
}
