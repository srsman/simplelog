<?php  

function _trim($str, $charlist = NULL)
{
	if ($charlist === NULL)
		return trim($str);

	return utf8::ltrim(utf8::rtrim($str, $charlist), $charlist);
}
