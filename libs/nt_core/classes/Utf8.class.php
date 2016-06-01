<?php   
/**
*	utf8	 
*	@package hp
*   @category system
*	@author kohana team
*	 
*	 
*/
class Utf8 {

	/**
	 * @var  boolean  Does the server support utf-8 natively?
	 */
	public static $server_utf8 = false;

	/**
	 * @var  array  List of called methods that have had their required file included.
	 */
	public static $called = array();

	/**
	 * Recursively cleans arrays, objects, and strings. Removes ASCII control
	 * codes and converts to the requested charset while silently discarding
	 * incompatible characters.
	 *
	 *     Utf8::clean($_GET); // Clean GET data
	 *
	 * @param   mixed   $var        variable to clean
	 * @param   string  $charset    character set, defaults to 'UTF-8'
	 * @return  mixed
	 * @uses    Utf8::clean
	 * @uses    Utf8::strip_ascii_ctrl
	 * @uses    Utf8::is_ascii
	 */
	public static function clean($var, $charset = NULL)
	{
		if ( ! $charset)
		{
			// Use the application character set
			$charset = 'UTF-8';
		}

		if (is_array($var) OR is_object($var))
		{
			foreach ($var as $key => $val)
			{
				// Recursion!
				$var[Utf8::clean($key)] = Utf8::clean($val);
			}
		}
		elseif (is_string($var) AND $var !== '')
		{
			// Remove control characters
			$var = Utf8::strip_ascii_ctrl($var);

			if ( ! Utf8::is_ascii($var))
			{
				// Disable notices
				$error_reporting = error_reporting(~E_NOTICE);

				$var = mb_convert_encoding($var, $charset, $charset);

				// Turn notices back on
				error_reporting($error_reporting);
			}
		}

		return $var;
	}

	/**检查数据是不是全部由ascii码组成不存在ascii范围以外的，对于utf-8编码，字节首位是0的就是ascii范围内的。
	 * Tests whether a string contains only 7-bit ASCII bytes. This is used to
	 * determine when to use native functions or utf-8 functions.
	 *
	 *     $ascii = Utf8::is_ascii($str);
	 *
	 * @param   mixed   $str    string or array of strings to check
	 * @return  boolean
	 */
	public static function is_ascii($str)
	{
		if (is_array($str))
		{
			$str = implode($str);
		}

		return ! preg_match('/[^\x00-\x7F]/S', $str);
	}

	/**清除ascii范围内的设备控制字符
	 * Strips out device control codes in the ASCII range.
	 *
	 *     $str = Utf8::strip_ascii_ctrl($str);
	 *
	 * @param   string  $str    string to clean
	 * @return  string
	 */
	public static function strip_ascii_ctrl($str)
	{
		return preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S', '', $str);
	}

	/**只保留ascii范围内的字符
	 * Strips out all non-7bit ASCII bytes.
	 *
	 *     $str = Utf8::strip_non_ascii($str);
	 *
	 * @param   string  $str    string to clean
	 * @return  string
	 */
	public static function strip_non_ascii($str)
	{
		return preg_replace('/[^\x00-\x7F]+/S', '', $str);
	}

	/**把一些外语中的非ascii码字母等转化为对应的英文ascii码
	 * Replaces special/accented utf-8 characters by ASCII-7 "equivalents".
	 *
	 *     $ascii = Utf8::transliterate_to_ascii($utf8);
	 *
	 * @author  Andreas Gohr <andi@splitbrain.org>
	 * @param   string  $str    string to transliterate
	 * @param   integer $case   -1 lowercase only, +1 uppercase only, 0 both cases
	 * @return  string
	 */
	public static function transliterate_to_ascii($str, $case = 0)
	{
		if ( ! isset(Utf8::$called[__FUNCTION__]))
		{
			require NT_CORE_PATH.'utf8/'.__FUNCTION__.'.php';

			// Function has been called
			Utf8::$called[__FUNCTION__] = TRUE;
		}

		return _transliterate_to_ascii($str, $case);
	}

	/**计算字符串的长度
	 * Returns the length of the given string. This is a utf8-aware version
	 * of [strlen](http://php.net/strlen).
	 *
	 *     $length = Utf8::strlen($str);
	 *
	 * @param   string  $str    string being measured for length
	 * @return  integer
	 * @uses    Utf8::$server_utf8
	 * @uses    'UTF-8'
	 */
	public static function strlen($str)
	{
		if (Utf8::$server_utf8)
			return mb_strlen($str, 'UTF-8');

		if ( ! isset(Utf8::$called[__FUNCTION__]))
		{
			require NT_CORE_PATH.'utf8/'.__FUNCTION__.'.php';

			// Function has been called
			Utf8::$called[__FUNCTION__] = TRUE;
		}

		return _strlen($str);
	}

	/** utf8版本的strpos
	 * Finds position of first occurrence of a utf-8 string. This is a
	 * utf8-aware version of [strpos](http://php.net/strpos).
	 *
	 *     $position = Utf8::strpos($str, $search);
	 *
	 * @author  Harry Fuecks <hfuecks@gmail.com>
	 * @param   string  $str    haystack
	 * @param   string  $search needle
	 * @param   integer $offset offset from which character in haystack to start searching
	 * @return  integer position of needle
	 * @return  boolean FALSE if the needle is not found
	 * @uses    Utf8::$server_utf8
	 * @uses    'UTF-8'
	 */
	public static function strpos($str, $search, $offset = 0)
	{
		if (Utf8::$server_utf8)
			return mb_strpos($str, $search, $offset, 'UTF-8');

		if ( ! isset(Utf8::$called[__FUNCTION__]))
		{
			require NT_CORE_PATH.'utf8/'.__FUNCTION__.'.php';

			// Function has been called
			Utf8::$called[__FUNCTION__] = TRUE;
		}

		return _strpos($str, $search, $offset);
	}

	/**
	 * Finds position of last occurrence of a char in a utf-8 string. This is
	 * a utf8-aware version of [strrpos](http://php.net/strrpos).
	 *
	 *     $position = Utf8::strrpos($str, $search);
	 *
	 * @author  Harry Fuecks <hfuecks@gmail.com>
	 * @param   string  $str    haystack
	 * @param   string  $search needle
	 * @param   integer $offset offset from which character in haystack to start searching
	 * @return  integer position of needle
	 * @return  boolean FALSE if the needle is not found
	 * @uses    Utf8::$server_utf8
	 */
	public static function strrpos($str, $search, $offset = 0)
	{
		if (Utf8::$server_utf8)
			return mb_strrpos($str, $search, $offset, 'UTF-8');

		if ( ! isset(Utf8::$called[__FUNCTION__]))
		{
			require NT_CORE_PATH.'utf8/'.__FUNCTION__.'.php';

			// Function has been called
			Utf8::$called[__FUNCTION__] = TRUE;
		}

		return _strrpos($str, $search, $offset);
	}

	/**
	 * Returns part of a utf-8 string. This is a utf8-aware version
	 * of [substr](http://php.net/substr).
	 *
	 *     $sub = Utf8::substr($str, $offset);
	 *
	 * @author  Chris Smith <chris@jalakai.co.uk>
	 * @param   string  $str    input string
	 * @param   integer $offset offset
	 * @param   integer $length length limit
	 * @return  string
	 * @uses    Utf8::$server_utf8
	 * @uses    'UTF-8'
	 */
	public static function substr($str, $offset, $length = NULL)
	{
		if (Utf8::$server_utf8)
			return ($length === NULL)
				? mb_substr($str, $offset, mb_strlen($str), 'UTF-8')
				: mb_substr($str, $offset, $length, 'UTF-8');

		if ( ! isset(Utf8::$called[__FUNCTION__]))
		{
			require NT_CORE_PATH.'utf8/'.__FUNCTION__.'.php';

			// Function has been called
			Utf8::$called[__FUNCTION__] = TRUE;
		}

		return _substr($str, $offset, $length);
	}

	/**
	 * Replaces text within a portion of a utf-8 string. This is a utf8-aware
	 * version of [substr_replace](http://php.net/substr_replace).
	 *
	 *     $str = Utf8::substr_replace($str, $replacement, $offset);
	 *
	 * @author  Harry Fuecks <hfuecks@gmail.com>
	 * @param   string  $str            input string
	 * @param   string  $replacement    replacement string
	 * @param   integer $offset         offset
	 * @return  string
	 */
	public static function substr_replace($str, $replacement, $offset, $length = NULL)
	{
		if ( ! isset(Utf8::$called[__FUNCTION__]))
		{
			require NT_CORE_PATH.'utf8/'.__FUNCTION__.'.php';

			// Function has been called
			Utf8::$called[__FUNCTION__] = TRUE;
		}

		return _substr_replace($str, $replacement, $offset, $length);
	}

	/**
	 * Makes a utf-8 string lowercase. This is a utf8-aware version
	 * of [strtolower](http://php.net/strtolower).
	 *
	 *     $str = Utf8::strtolower($str);
	 *
	 * @author  Andreas Gohr <andi@splitbrain.org>
	 * @param   string  $str mixed case string
	 * @return  string
	 * @uses    Utf8::$server_utf8
	 * @uses    'UTF-8'
	 */
	public static function strtolower($str)
	{
		if (Utf8::$server_utf8)
			return mb_strtolower($str, 'UTF-8');

		if ( ! isset(Utf8::$called[__FUNCTION__]))
		{
			require NT_CORE_PATH.'utf8/'.__FUNCTION__.'.php';

			// Function has been called
			Utf8::$called[__FUNCTION__] = TRUE;
		}

		return _strtolower($str);
	}

	/**
	 * Makes a utf-8 string uppercase. This is a utf8-aware version
	 * of [strtoupper](http://php.net/strtoupper).
	 *
	 * @author  Andreas Gohr <andi@splitbrain.org>
	 * @param   string  $str mixed case string
	 * @return  string
	 * @uses    Utf8::$server_utf8
	 * @uses    'UTF-8'
	 */
	public static function strtoupper($str)
	{
		if (Utf8::$server_utf8)
			return mb_strtoupper($str, 'UTF-8');

		if ( ! isset(Utf8::$called[__FUNCTION__]))
		{
			require NT_CORE_PATH.'utf8/'.__FUNCTION__.'.php';

			// Function has been called
			Utf8::$called[__FUNCTION__] = TRUE;
		}

		return _strtoupper($str);
	}

	/**
	 * Makes a utf-8 string's first character uppercase. This is a utf8-aware
	 * version of [ucfirst](http://php.net/ucfirst).
	 *
	 *     $str = Utf8::ucfirst($str);
	 *
	 * @author  Harry Fuecks <hfuecks@gmail.com>
	 * @param   string  $str mixed case string
	 * @return  string
	 */
	public static function ucfirst($str)
	{
		if ( ! isset(Utf8::$called[__FUNCTION__]))
		{
			require NT_CORE_PATH.'utf8/'.__FUNCTION__.'.php';

			// Function has been called
			Utf8::$called[__FUNCTION__] = TRUE;
		}

		return _ucfirst($str);
	}

	/**
	 * Makes the first character of every word in a utf-8 string uppercase.
	 * This is a utf8-aware version of [ucwords](http://php.net/ucwords).
	 *
	 *     $str = Utf8::ucwords($str);
	 *
	 * @author  Harry Fuecks <hfuecks@gmail.com>
	 * @param   string  $str mixed case string
	 * @return  string
	 */
	public static function ucwords($str)
	{
		if ( ! isset(Utf8::$called[__FUNCTION__]))
		{
			require NT_CORE_PATH.'utf8/'.__FUNCTION__.'.php';

			// Function has been called
			Utf8::$called[__FUNCTION__] = TRUE;
		}

		return _ucwords($str);
	}

	/**
	 * Case-insensitive utf-8 string comparison. This is a utf8-aware version
	 * of [strcasecmp](http://php.net/strcasecmp).
	 *
	 *     $compare = Utf8::strcasecmp($str1, $str2);
	 *
	 * @author  Harry Fuecks <hfuecks@gmail.com>
	 * @param   string  $str1   string to compare
	 * @param   string  $str2   string to compare
	 * @return  integer less than 0 if str1 is less than str2
	 * @return  integer greater than 0 if str1 is greater than str2
	 * @return  integer 0 if they are equal
	 */
	public static function strcasecmp($str1, $str2)
	{
		if ( ! isset(Utf8::$called[__FUNCTION__]))
		{
			require NT_CORE_PATH.'utf8/'.__FUNCTION__.'.php';

			// Function has been called
			Utf8::$called[__FUNCTION__] = TRUE;
		}

		return _strcasecmp($str1, $str2);
	}

	/**
	 * Returns a string or an array with all occurrences of search in subject
	 * (ignoring case) and replaced with the given replace value. This is a
	 * utf8-aware version of [str_ireplace](http://php.net/str_ireplace).
	 *
	 * [!!] This function is very slow compared to the native version. Avoid
	 * using it when possible.
	 *
	 * @author  Harry Fuecks <hfuecks@gmail.com
	 * @param   string|array    $search     text to replace
	 * @param   string|array    $replace    replacement text
	 * @param   string|array    $str        subject text
	 * @param   integer         $count      number of matched and replaced needles will be returned via this parameter which is passed by reference
	 * @return  string  if the input was a string
	 * @return  array   if the input was an array
	 */
	public static function str_ireplace($search, $replace, $str, & $count = NULL)
	{
		if ( ! isset(Utf8::$called[__FUNCTION__]))
		{
			require NT_CORE_PATH.'utf8/'.__FUNCTION__.'.php';

			// Function has been called
			Utf8::$called[__FUNCTION__] = TRUE;
		}

		return _str_ireplace($search, $replace, $str, $count);
	}

	/**
	 * Case-insensitive utf-8 version of strstr. Returns all of input string
	 * from the first occurrence of needle to the end. This is a utf8-aware
	 * version of [stristr](http://php.net/stristr).
	 *
	 *     $found = Utf8::stristr($str, $search);
	 *
	 * @author  Harry Fuecks <hfuecks@gmail.com>
	 * @param   string  $str    input string
	 * @param   string  $search needle
	 * @return  string  matched substring if found
	 * @return  FALSE   if the substring was not found
	 */
	public static function stristr($str, $search)
	{
		if ( ! isset(Utf8::$called[__FUNCTION__]))
		{
			require NT_CORE_PATH.'utf8/'.__FUNCTION__.'.php';

			// Function has been called
			Utf8::$called[__FUNCTION__] = TRUE;
		}

		return _stristr($str, $search);
	}

	/**
	 * Finds the length of the initial segment matching mask. This is a
	 * utf8-aware version of [strspn](http://php.net/strspn).
	 *
	 *     $found = Utf8::strspn($str, $mask);
	 *
	 * @author  Harry Fuecks <hfuecks@gmail.com>
	 * @param   string  $str    input string
	 * @param   string  $mask   mask for search
	 * @param   integer $offset start position of the string to examine
	 * @param   integer $length length of the string to examine
	 * @return  integer length of the initial segment that contains characters in the mask
	 */
	public static function strspn($str, $mask, $offset = NULL, $length = NULL)
	{
		if ( ! isset(Utf8::$called[__FUNCTION__]))
		{
			require NT_CORE_PATH.'utf8/'.__FUNCTION__.'.php';

			// Function has been called
			Utf8::$called[__FUNCTION__] = TRUE;
		}

		return _strspn($str, $mask, $offset, $length);
	}

	/**
	 * Finds the length of the initial segment not matching mask. This is a
	 * utf8-aware version of [strcspn](http://php.net/strcspn).
	 *
	 *     $found = Utf8::strcspn($str, $mask);
	 *
	 * @author  Harry Fuecks <hfuecks@gmail.com>
	 * @param   string  $str    input string
	 * @param   string  $mask   mask for search
	 * @param   integer $offset start position of the string to examine
	 * @param   integer $length length of the string to examine
	 * @return  integer length of the initial segment that contains characters not in the mask
	 */
	public static function strcspn($str, $mask, $offset = NULL, $length = NULL)
	{
		if ( ! isset(Utf8::$called[__FUNCTION__]))
		{
			require NT_CORE_PATH.'utf8/'.__FUNCTION__.'.php';

			// Function has been called
			Utf8::$called[__FUNCTION__] = TRUE;
		}

		return _strcspn($str, $mask, $offset, $length);
	}

	/**
	 * Pads a utf-8 string to a certain length with another string. This is a
	 * utf8-aware version of [str_pad](http://php.net/str_pad).
	 *
	 *     $str = Utf8::str_pad($str, $length);
	 *
	 * @author  Harry Fuecks <hfuecks@gmail.com>
	 * @param   string  $str                input string
	 * @param   integer $final_str_length   desired string length after padding
	 * @param   string  $pad_str            string to use as padding
	 * @param   string  $pad_type           padding type: STR_PAD_RIGHT, STR_PAD_LEFT, or STR_PAD_BOTH
	 * @return  string
	 */
	public static function str_pad($str, $final_str_length, $pad_str = ' ', $pad_type = STR_PAD_RIGHT)
	{
		if ( ! isset(Utf8::$called[__FUNCTION__]))
		{
			require NT_CORE_PATH.'utf8/'.__FUNCTION__.'.php';

			// Function has been called
			Utf8::$called[__FUNCTION__] = TRUE;
		}

		return _str_pad($str, $final_str_length, $pad_str, $pad_type);
	}

	/**
	 * Converts a utf-8 string to an array. This is a utf8-aware version of
	 * [str_split](http://php.net/str_split).
	 *
	 *     $array = Utf8::str_split($str);
	 *
	 * @author  Harry Fuecks <hfuecks@gmail.com>
	 * @param   string  $str            input string
	 * @param   integer $split_length   maximum length of each chunk
	 * @return  array
	 */
	public static function str_split($str, $split_length = 1)
	{
		if ( ! isset(Utf8::$called[__FUNCTION__]))
		{
			require NT_CORE_PATH.'utf8/'.__FUNCTION__.'.php';

			// Function has been called
			Utf8::$called[__FUNCTION__] = TRUE;
		}

		return _str_split($str, $split_length);
	}

	/**
	 * Reverses a utf-8 string. This is a utf8-aware version of [strrev](http://php.net/strrev).
	 *
	 *     $str = Utf8::strrev($str);
	 *
	 * @author  Harry Fuecks <hfuecks@gmail.com>
	 * @param   string  $str string to be reversed
	 * @return  string
	 */
	public static function strrev($str)
	{
		if ( ! isset(Utf8::$called[__FUNCTION__]))
		{
			require NT_CORE_PATH.'utf8/'.__FUNCTION__.'.php';

			// Function has been called
			Utf8::$called[__FUNCTION__] = TRUE;
		}

		return _strrev($str);
	}

	/**
	 * Strips whitespace (or other utf-8 characters) from the beginning and
	 * end of a string. This is a utf8-aware version of [trim](http://php.net/trim).
	 *
	 *     $str = Utf8::trim($str);
	 *
	 * @author  Andreas Gohr <andi@splitbrain.org>
	 * @param   string  $str        input string
	 * @param   string  $charlist   string of characters to remove
	 * @return  string
	 */
	public static function trim($str, $charlist = NULL)
	{
		if ( ! isset(Utf8::$called[__FUNCTION__]))
		{
			require NT_CORE_PATH.'utf8/'.__FUNCTION__.'.php';

			// Function has been called
			Utf8::$called[__FUNCTION__] = TRUE;
		}

		return _trim($str, $charlist);
	}

	/**
	 * Strips whitespace (or other utf-8 characters) from the beginning of
	 * a string. This is a utf8-aware version of [ltrim](http://php.net/ltrim).
	 *
	 *     $str = Utf8::ltrim($str);
	 *
	 * @author  Andreas Gohr <andi@splitbrain.org>
	 * @param   string  $str        input string
	 * @param   string  $charlist   string of characters to remove
	 * @return  string
	 */
	public static function ltrim($str, $charlist = NULL)
	{
		if ( ! isset(Utf8::$called[__FUNCTION__]))
		{
			require NT_CORE_PATH.'utf8/'.__FUNCTION__.'.php';

			// Function has been called
			Utf8::$called[__FUNCTION__] = TRUE;
		}

		return _ltrim($str, $charlist);
	}

	/**
	 * Strips whitespace (or other utf-8 characters) from the end of a string.
	 * This is a utf8-aware version of [rtrim](http://php.net/rtrim).
	 *
	 *     $str = Utf8::rtrim($str);
	 *
	 * @author  Andreas Gohr <andi@splitbrain.org>
	 * @param   string  $str        input string
	 * @param   string  $charlist   string of characters to remove
	 * @return  string
	 */
	public static function rtrim($str, $charlist = NULL)
	{
		if ( ! isset(Utf8::$called[__FUNCTION__]))
		{
			require NT_CORE_PATH.'utf8/'.__FUNCTION__.'.php';

			// Function has been called
			Utf8::$called[__FUNCTION__] = TRUE;
		}

		return _rtrim($str, $charlist);
	}

	/**
	 * Returns the unicode ordinal for a character. This is a utf8-aware
	 * version of [ord](http://php.net/ord).
	 *
	 *     $digit = Utf8::ord($character);
	 *
	 * @author  Harry Fuecks <hfuecks@gmail.com>
	 * @param   string  $chr    utf-8 encoded character
	 * @return  integer
	 */
	public static function ord($chr)
	{
		if ( ! isset(Utf8::$called[__FUNCTION__]))
		{
			require NT_CORE_PATH.'utf8/'.__FUNCTION__.'.php';

			// Function has been called
			Utf8::$called[__FUNCTION__] = TRUE;
		}

		return _ord($chr);
	}

	/**
	 * Takes an utf-8 string and returns an array of ints representing the Unicode characters.
	 * Astral planes are supported i.e. the ints in the output can be > 0xFFFF.
	 * Occurrences of the BOM are ignored. Surrogates are not allowed.
	 *
	 *     $array = Utf8::to_unicode($str);
	 *
	 * The Original Code is Mozilla Communicator client code.
	 * The Initial Developer of the Original Code is Netscape Communications Corporation.
	 * Portions created by the Initial Developer are Copyright (C) 1998 the Initial Developer.
	 * Ported to PHP by Henri Sivonen <hsivonen@iki.fi>, see <http://hsivonen.iki.fi/php-utf8/>
	 * Slight modifications to fit with phputf8 library by Harry Fuecks <hfuecks@gmail.com>
	 *
	 * @param   string  $str    utf-8 encoded string
	 * @return  array   unicode code points
	 * @return  FALSE   if the string is invalid
	 */
	public static function to_unicode($str)
	{
		if ( ! isset(Utf8::$called[__FUNCTION__]))
		{
			require NT_CORE_PATH.'utf8/'.__FUNCTION__.'.php';

			// Function has been called
			Utf8::$called[__FUNCTION__] = TRUE;
		}

		return _to_unicode($str);
	}

	/**
	 * Takes an array of ints representing the Unicode characters and returns a utf-8 string.
	 * Astral planes are supported i.e. the ints in the input can be > 0xFFFF.
	 * Occurrences of the BOM are ignored. Surrogates are not allowed.
	 *
	 *     $str = Utf8::to_unicode($array);
	 *
	 * The Original Code is Mozilla Communicator client code.
	 * The Initial Developer of the Original Code is Netscape Communications Corporation.
	 * Portions created by the Initial Developer are Copyright (C) 1998 the Initial Developer.
	 * Ported to PHP by Henri Sivonen <hsivonen@iki.fi>, see http://hsivonen.iki.fi/php-utf8/
	 * Slight modifications to fit with phputf8 library by Harry Fuecks <hfuecks@gmail.com>.
	 *
	 * @param   array   $str    unicode code points representing a string
	 * @return  string  utf8 string of characters
	 * @return  boolean FALSE if a code point cannot be found
	 */
	public static function from_unicode($arr)
	{
		if ( ! isset(Utf8::$called[__FUNCTION__]))
		{
			require NT_CORE_PATH.'utf8/'.__FUNCTION__.'.php';

			// Function has been called
			Utf8::$called[__FUNCTION__] = TRUE;
		}

		return _from_unicode($arr);
	}

}

 
