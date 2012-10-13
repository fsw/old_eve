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
		$cell['url'] = 'http://' . CADO_DOMAIN . '/uploads/' . $cell['name'] . '.mp4';
		$cell['url2'] = 'http://' . CADO_DOMAIN . '/uploads/' . $cell['name'] . '.webm';
		$cell['url3'] = 'http://' . CADO_DOMAIN . '/uploads/' . $cell['name'] . '.ogg';
		$cell['thumb'] = 'http://' . CADO_DOMAIN . '/uploads/' . $cell['name'] . '_t.jpg';
		return $cell;
	}
	
	public static function fromFile($path, $name = null)
	{
		$video = array('name' => $name, 'ring' => 0);
		if (empty($name))
		{
			$video['name'] = uniqid('f_');
		}
		Fs::copyr($path, CADO_FILE_UPLOADS . $video['name'] . '.mp4');
		$cmd = 'ffmpeg -ss 10 -i ' . CADO_FILE_UPLOADS . $video['name'] . '.mp4 -vframes 1 -s 479x290 ' . CADO_FILE_UPLOADS . $video['name'] . '_t.jpg';
		exec($cmd);
		
		$cmd = 'ffmpeg -i ' . CADO_FILE_UPLOADS . $video['name'] . '.mp4 ' . CADO_FILE_UPLOADS . $video['name'] . '.webm';
		exec($cmd);
		
		$cmd = 'ffmpeg -i ' . CADO_FILE_UPLOADS . $video['name'] . '.mp4 ' . CADO_FILE_UPLOADS . $video['name'] . '.ogg';
		exec($cmd);
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
