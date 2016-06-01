<?php  

function _rtrim($str, $charlist = NULL)
{
	if ($charlist === NULL)
		return rtrim($str);

	if (utf8::is_ascii($charlist))
		return rtrim($str, $charlist);

	$charlist = preg_replace('#[-\[\]:\\\\^/]#', '\\\\$0', $charlist);

	return preg_replace('/['.$charlist.']++$/uD', '', $str);
}
