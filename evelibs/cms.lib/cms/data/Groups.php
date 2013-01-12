<?php 

class cms_data_Groups extends cms_Data
{
	protected $modelClass = 'model_Groups';

	protected function getColumns()
	{
		return array('id', 'name', 'description', 'privilages');
	}
		
}