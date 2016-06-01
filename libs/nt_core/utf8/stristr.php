<?php   

function _stristr($str, $search)
{
	if (utf8::is_ascii($str) AND utf8::is_ascii($search))
		return stristr($str, $search);

	if ($search == '')
		return $str;

	$str_lower = utf8::strtolower($str);
	$search_lower = utf8::strtolower($search);

	preg_match('/^(.*?)'.preg_quote($search_lower, '/').'/s', $str_lower, $matches);

	if (isset($matches[1]))
		return substr($str, strlen($matches[1]));

	return FALSE;
}
