<?php

class field_File extends Field
{
	protected function getAllowedMimes()
	{
		return array();
	}
	
	public function getDbDefinition()
	{
		return array('name' => 'varchar(32) NOT NULL', 'ring' => 'int(11) NOT NULL');
	}
	
	public function toDb($value)
	{
		//var_dump($value);
		//die();
		return array('name' => $value['name'], 'ring' => $value['ring']);
	}
	
	public function fromDb($cell)
	{
		$cell['url'] = 'http://' . Eve::$domains[0] . '/uploads/' . $cell['name'] . '.mp4';
		$cell['url2'] = 'http://' . Eve::$domains[0] . '/uploads/' . $cell['name'] . '.webm';
		//$cell['url3'] = 'http://' . Eve::$domains[0] . '/uploads/' . $cell['name'] . '.ogg';
		$cell['thumb'] = 'http://' . Eve::$domains[0] . '/uploads/' . $cell['name'] . '_t.jpg';
		$cell['thumb_small'] = 'http://' . Eve::$domains[0] . '/uploads/' . $cell['name'] . '_ts.jpg';
		return $cell;
	}
	
	public static function fromFile($path, $name = null)
	{
		$video = array('name' => $name, 'ring' => 0);
		if (empty($name))
		{
			$video['name'] = uniqid('f_');
		}
		Fs::copyr($path, Eve::$uploads . $video['name'] . '.mp4');
		
		foreach (array('480x270' => '_t.jpg', '170x96' => '_ts.jpg') as $tsize => $tsufix)
		{
			foreach (array(100, 10, 1) as $sec)
			{
				$cmd = 'ffmpeg -y -ss ' . $sec .' -i ' . Eve::$uploads . $video['name'] . '.mp4 -vframes 1 -s ' . $tsize . ' ' . Eve::$uploads . $video['name'] . $tsufix;
				exec($cmd);
				if (Fs::exists(Eve::$uploads . $video['name'] . $tsufix))
				{
					break;
				}
			}
		}
				
		$cmd = 'ffmpeg -y -i ' . Eve::$uploads . $video['name'] . '.mp4 ' . Eve::$uploads . $video['name'] . '.webm';
		exec($cmd);
		
		//$cmd = 'ffmpeg -i ' . Eve::$uploads . $video['name'] . '.mp4 ' . Eve::$uploads . $video['name'] . '.ogg';
		//exec($cmd);
		return $video;
	}
	
	public function fromPost($post)
	{
		$video = array('name' => '', 'ring' => 0);
		if (empty($post['error']))
		{
			$video = self::fromFile($post['tmp_name']);
		}
		else
		{
			$video['error'] = 'Upload error (' . $post['error'] . ')';
		}
		return $video;
	}
	
	public function validate($value)
	{
		return empty($value['error']) ? true : $value['error'];
	}
		
	public function getFormInput($key, $value)
	{
		return '<input type="file" name="' . $key . '"/>'; // value="' . $value . '">';
	}

}
