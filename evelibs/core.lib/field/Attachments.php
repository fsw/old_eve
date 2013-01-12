<?php

class field_Attachments extends Field
{
	
	public function __construct()
	{
		
	}
	
	public function getDbDefinition()
	{
		return 'int(11) NOT NULL';
	}
	
	public function toDb($cell, $key, $code, &$row)
	{
		if (empty($cell['id']) && !empty($cell['files']))
		{
			if (!is_dir(Site::getDataDir() . 'attachments'))
			{
				mkdir(Site::getDataDir() . 'attachments');
			}
			//TODO!
			$id = count(Fs::listDirs(Site::getDataDir() . 'attachments'))+1;
			mkdir(Site::getDataDir() . 'attachments' . DS . $id);
			$cell['id'] = $id;
		}
		$current = empty($cell['id']) ? [] : array_flip(Fs::listFiles(Site::getDataDir() . 'attachments' . DS . $cell['id']));
		foreach ($cell['files'] as $file)
		{
			if (is_scalar($file) && array_key_exists($file, $current))
			{
				unset($current[$file]);
			}
			elseif (empty($file['error']))
			{
				$ext = substr($file['name'], strrpos($file['name'], '.') + 1);
				$basename = Text::slug(substr($file['name'], 0, strrpos($file['name'], '.')));
				$path = Site::getDataDir() . 'attachments' . DS . $cell['id'] . DS . $basename . '.' . $ext;
				move_uploaded_file($file['tmp_name'], $path);
			}
			else
			{
				throw new model_Exception('Upload error!');
			}
		}
		if (!empty($current))
		{
			foreach ($current as $file => $dummy)
			{
				Fs::remove(Site::getDataDir() . 'attachments' . DS . $cell['id'] . DS . $file);				
			}
		}
		return empty($cell['id']) ? 0 : $cell['id'];
	}
	
	public function fromDb($cell, $key, $code, &$row)
	{
		$ret = ['id' => $cell, 'files' => []];
		if ($cell && is_dir(Site::getDataDir() . 'attachments' . DS . $cell))
		{
			$files = Fs::listFiles(Site::getDataDir() . 'attachments' . DS . $cell);
			foreach ($files as $file)
			{
				$ret['files'][] = [
					'name' => $file,
					'url' => '/uploads/attachments/' . $cell . '/' . $file,
					'thumb' => '',
				];
			}
		}
		return $ret;
	}
	
	public function getFormInput($key, $value)
	{
		$ret = '';
		if ($value !== null)
		{
			$ret .= '<input type="hidden" name="' . $key . '[id]" value="' . $value['id'] . '"/>';
			foreach ($value['files'] as $file)
			{
				$ret .= '<div class="file">';
				$ret .= '<input type="checkbox" checked="checked" name="' . $key . '[files][]" value="' . $file['name'] . '"/>';	
				$ret .= '<a href="' . $file['url'] . '" target="_blank" >';
				if (!empty($file['thumb']))
				{
					$ret .= '<img src="' . $file['thumb'] . '" />';
					$ret .= '<br/>';
				}
				$ret .= $file['name'] . '</a>';
				$ret .= '</div>';
			}
		}
		$ret .= '<div class="file"> add new: ';
		$ret .= '<input multiple="multiple" type="file" name="' . $key . '[files][]"/>';
		$ret .= '</div>';
		return $ret;
	}
	
	public function fromPost($post)
	{
		/*
		foreach ($post as $file)
		{
			
		}*/
		return $post;
	}
	
}
