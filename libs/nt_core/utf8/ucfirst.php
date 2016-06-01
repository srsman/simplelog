<?php  

function _ucfirst($str)
{
	if (utf8::is_ascii($str))
		return ucfirst($str);

	preg_match('/^(.?)(.*)$/us', $str, $matches);
	return utf8::strtoupper($matches[1]).$matches[2];
}
