<?php 

class cms_data_Users extends cms_Data
{
	protected $modelClass = 'model_Users';

	protected function getColumns()
	{
		return array('id', 'email');
	}
}