<?php

class field_File extends Field
{
	protected $types;
	static protected $extMap = array(
			'mp4' => 'video',
			'jpg' => 'jpg',
			'jpeg' => 'jpg',
			'png' => 'png',
			'gif' => 'gif',
			'pdf' => 'pdf',
	);
	protected $thumbnails;
	
	public function __construct($types = array('video', 'image', 'pdf'), $thumbnails = array())
	{
		$this->types = $types;
		$this->thumbnails = $thumbnails;
	}
	
	public function getDbDefinition()
	{
		//type video, image, pdf, data	
		return array('type' => 'varchar(8) NOT NULL', 'name' => 'varchar(32) NOT NULL', 'ring' => 'int(11) NOT NULL');
	}
	
	public function toDb($value)
	{
		//var_dump($value);
		//die();
		return array('type' => $value['type'], 'name' => $value['name'], 'ring' => $value['ring']);
	}
	
	public function fromDb($cell)
	{
		switch ($cell['type'])
		{
			case 'video':
				$cell['url'] = 'http://' . Eve::$domains[0] . '/uploads/' . $cell['name'] . '.mp4';
				$cell['url2'] = 'http://' . Eve::$domains[0] . '/uploads/' . $cell['name'] . '.webm';
				//$cell['url3'] = 'http://' . Eve::$domains[0] . '/uploads/' . $cell['name'] . '.ogg';
				$cell['thumb'] = 'http://' . Eve::$domains[0] . '/uploads/' . $cell['name'] . '_t.jpg';
				$cell['thumb_small'] = 'http://' . Eve::$domains[0] . '/uploads/' . $cell['name'] . '_ts.jpg';
				break;
			default:
				$cell['url'] = '/uploads/' . $cell['name'] . '.' . $cell['type'];
				$cell['thumb'] = '/uploads/' . $cell['name'] . '_t.jpg';
				break;
		}
		return $cell;
	}

	public static function saveGdThumb($source_image, $path)
	{
		$desired_width = 100;
		$width = imagesx($source_image);
		$height = imagesy($source_image);
		$desired_height = floor($height * ($desired_width / $width));
		$virtual_image = imagecreatetruecolor($desired_width, $desired_height);
		imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);
		imagejpeg($virtual_image, $path);
	}
	
	public static function fromFile($path, $name = null)
	{
		$video = array('type' => '', 'name' => $name, 'ring' => 0);
		if (empty($name))
		{
			$video['name'] = uniqid('f_');
		}
		$ext = strtolower(substr($path, strrpos($path, '.') + 1));
		
		if (empty(static::$extMap[$ext]))
		{
			$video['error'] = 'unknown extension ' . $ext;
			return $video;
		}
		
		$video['type'] = static::$extMap[$ext];
		
		Fs::copyr($path, Eve::$uploads . $video['name'] . '.' . $video['type']);
		
		switch ($video['type'])
		{
			case 'mp4' :
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
				break;
			case 'pdf' :
				break;
			case 'jpg' :
				$source_image = imagecreatefromjpeg($path);
				static::saveGdThumb($source_image, Eve::$uploads . $video['name'] . '_t.jpg');
				break;
			case 'png' :
				$source_image = imagecreatefrompng($path);
				static::saveGdThumb($source_image, Eve::$uploads . $video['name'] . '_t.jpg');
				break;
			case 'gif' :
				$source_image = imagecreatefromgif($path);
				static::saveGdThumb($source_image, Eve::$uploads . $video['name'] . '_t.jpg');
				break;
			default:
				break;
		}
		
		return $video;
	}
	
	public function fromPost($post)
	{
		$video = array('type' => '', 'name' => '', 'ring' => 0);
		if (empty($post['error']))
		{
			$ext = substr($post['name'], strrpos($post['name'], '.') + 1);
			$path = Eve::$fileCache . uniqid() . '.' . $ext;
			move_uploaded_file($post['tmp_name'], $path);
			$video = self::fromFile($path);
		}
		else
		{
			$video['error'] = 'Upload error (' . $post['error'] . ')';
		}
		return $video;
	}
	
	public function validate($value)
	{
		/*if (!in_array($value, $this->types))
		{
			return 'Unknown type';
		}*/
		return empty($value['error']) ? true : $value['error'];
	}
		
	public function getFormInput($key, $value)
	{
		$ret = '';
		if (!empty($value['thumb']))
		{
			$ret .= '<img src="' . $value['thumb'] . '" />';
			$ret .= '<br/>';
		}
		$ret .= '<input type="file" name="' . $key . '"/>'; // value="' . $value . '">';
		return $ret;
	}

}
