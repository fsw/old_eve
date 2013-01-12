<?php 

class cms_data_Contacts extends cms_Data
{
	protected $modelClass = 'model_Contacts';
		
	protected function getColumns()
	{
		return array('id', 'email');
	}
}