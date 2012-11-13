<?php
/** 
 * @package CadoLibs
 * @author fsw
 */

class Html
{
	private static function attrs($attrs)
	{
		$ret = '';
		foreach ($attrs as $key=>$value)
		{
			$ret .= ' ' . $key . '="' . $value . '"';
		}
		return $ret;
	}
	
	public static function select($attrs, $options)
	{
		$ret[] = '<select' . self::attrs($attrs) . '>';
		foreach ($options as $key => $value)
		{
			$ret[] = '<option value="' . $key . '">' . $value . '</option>';
		}
		$ret[] = '</select>';
		return implode('', $ret);
	}
	
	public static function ulTree($data, $callback, $subKey = 'children', $ulAttrs = array(), $liAttrs = array())
	{
		$ret[] = '<ul' . self::attrs($ulAttrs) . '>';
		foreach ($data as $row)
		{
			$attrs = $liAttrs;
			if (!empty($row['class']))
			{
				$attrs['class'] = $row['class']; 
			}
			$ret[] = '<li' . self::attrs($attrs) . '>';
			$ret[] = $callback($row);
			if (!empty($row[$subKey]))
			{
				$ret[] = self::ulTree($row[$subKey], $callback, $subKey);
			}
			$ret[] = '</li>';
		}
		$ret[] = '</ul>';
		return implode('', $ret);
	}
	
}