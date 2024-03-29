<?php  

function _ucwords($str)
{
	if (utf8::is_ascii($str))
		return ucwords($str);

	// [\x0c\x09\x0b\x0a\x0d\x20] matches form feeds, horizontal tabs, vertical tabs, linefeeds and carriage returns.
	// This corresponds to the definition of a 'word' defined at http://php.net/ucwords
	return preg_replace(
		'/(?<=^|[\x0c\x09\x0b\x0a\x0d\x20])[^\x0c\x09\x0b\x0a\x0d\x20]/ue',
		'utf8::strtoupper(\'$0\')',
		$str
	);
}
