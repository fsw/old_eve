<?php 

class controller_cms_Contents extends controller_cms_Data
{
	
	protected $model = 'contents';
	protected $columns = array('id', 'slug', 'title');
	
	protected function getFormFields()
	{
		$fields = parent::getFormFields();
		Arr::insertAfter($fields, 'body', 'download_remote', new field_Bool());
		return $fields;
	}
	
	protected function saveFormData($data)
	{
		if ($data['download_remote'])
		{
			preg_match_all('~<img.*?src=.([\/.a-z0-9:_-]+).*?>~si', $data['body'], $matches);
			foreach ($matches[1] as $src)
			{
				if (strpos($src, "http://") === 0)
				{
					$file = array(
						'name' => $src,
						'file' => field_File::fromUrl($src)
					);
					Site::model('files')->add($file);
					$data['body'] = str_replace($src, $file['file']['url'], $data['body']);
				}
			}				
		}
		unset($data['download_remote']);
		return parent::saveFormData($data);
		
	}
}