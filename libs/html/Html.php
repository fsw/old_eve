<?php
/**
 * 
 * @author fsw
 *
 */
class Html
{
	public static function select($attributes, $options)
	{
		$ret[] = '<select>';
		foreach ($options as $key => $value)
		{
			$ret[] = '<option value="' . $key . '">' . $value . '</option>';
		}
		$ret[] = '</select>';
		return implode('', $ret);
	}
	
	public static function tree($data, $callback, $subKey = 'children')
	{
		return 'TODO';
	}
	
}