<?php
/**
 * 
 * @author fsw
 *
 */
class Fs
{
	const TYPE_ANY = 0;
	const TYPE_FILE = 1;
	const TYPE_DIR = 2;
	
	static function exists($path)
	{
		return file_exists($path);
	}
	
	static function isDir($path)
	{
		return is_dir($path);
	}
	
	static function isFile($path)
	{
		return is_file($path);
	}
	
	static function mkdir($path)
	{
		mkdir($path);
	}
	
	static function read($path)
	{
		return file_get_contents($path);
	}
	
	static function write($path, $contents)
	{
		return file_put_contents($path, $contents);
	}
	
	static function listFiles($path)
	{
		return self::_list($path, self::TYPE_FILE);
	}
	
	static function listDirs($path)
	{
		return self::_list($path, self::TYPE_DIR);
	}
	
	static function listAll($path)
	{
		return self::_list($path, self::TYPE_ANY);
	}
	
	private static function _is($path, $what)
	{
		return (($what == self::TYPE_ANY) || ($what == self::TYPE_FILE && is_file($path)) || ($what == self::TYPE_DIR && is_dir($path)));
	}
	
	private static function _list($path, $what)
	{
		$ret = array();
		if ($handle = opendir($path))
		{
			while (false !== ($entry = readdir($handle)))
			{
				if ($entry != '.' && $entry != '..')
				{
					if (self::_is($path . '/' . $entry, $what))
					{
						$ret[] = $entry;
					}
				}
			}
			closedir($handle);
		}
		return $ret;
	}
	
	static function copyr($source, $dest, $preprocessFunc = null)
	{
	    if (is_link($source))
	    {
	        return symlink(readlink($source), $dest);
	    }
	     
	    if (is_file($source))
	    {	    	
	    	if ($preprocessFunc !== null)
	    	{
	    		$ret = $preprocessFunc($source);
	    		if ($ret === true)
	    		{
	    			return copy($source, $dest);
	    		}
	    		elseif ($ret !== false)
	    		{
	    			return file_put_contents($dest, $ret);
	    		}
	    		else
	    		{
	    			return false;
	    		}
	    	}
	    	else
	    	{
	        	return copy($source, $dest);
	    	}
	    }
	 
	    if (!is_dir($dest))
	    {
	        mkdir($dest);
	    }
	 
	    $dir = dir($source);
	    while (false !== $entry = $dir->read())
	    {
	        if ($entry == '.' || $entry == '..')
	        {
	            continue;
	        }
	        
	        self::copyr($source . '/' . $entry, $dest . '/' . $entry, $preprocessFunc);
	    }
	    $dir->close();
	    return true;
	}
	
	static function remove($path, $rec = false)
	{
		if (is_dir($path))
		{
			if ($rec)
			{
    			$dir = dir($path);
			    while (false !== $entry = $dir->read())
			    {
			        if ($entry == '.' || $entry == '..')
			        {
			            continue;
			        }
			        self::remove($path . '/' . $entry, $rec);
			    }
			    $dir->close();
			}
	    	rmdir($path);
		}
    	else
    	{
    		unlink($path);
    	}
	}
}