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
		$cell['url'] = '/uploads/' . $cell['name'] . '.mp4';
		$cell['thumb'] = '/uploads/' . $cell['name'] . '_t.jpg';
		return $cell;
	}
	
	public function fromPost($post)
	{
		$video = array('name' => '', 'ring' => 0);
		if (empty($post['error']))
		{
			//["type"]
			$video['name'] = uniqid('f_');
			Fs::copyr($post['tmp_name'], CADO_FILE_UPLOADS . $video['name'] . '.mp4');
			$cmd = 'ffmpeg -ss 10 -i ' . CADO_FILE_UPLOADS . $video['name'] . '.mp4 -vframes 1 -s 320x240 ' . CADO_FILE_UPLOADS . $video['name'] . '_t.jpg';
			exec($cmd);
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
