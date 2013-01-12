<?php 

class cms_data_Contents extends cms_Data
{
	protected $modelClass = 'model_Contents';
	
	protected function getColumns()
	{
		return array('id', 'slug', 'title');
	}
	
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
			preg_match_all('~<img.*?src=.([\/.%a-z0-9:_-]+).*?>~si', $data['body'], $matches);
			foreach ($matches[1] as $src)
			{
				if (strpos($src, "http://") === 0)
				{
					$file = array(
						'name' => $src,
						'file' => field_File::fromUrl($src)
					);
					model_Files::add($file);
					$data['body'] = str_replace($src, $file['file']['url'], $data['body']);
				}
			}				
		}
		unset($data['download_remote']);
		return parent::saveFormData($data);
		
	}
}