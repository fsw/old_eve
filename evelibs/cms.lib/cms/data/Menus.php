<?php 

class cms_data_Menus extends cms_Data
{
	protected $modelClass = 'model_Menus';
	
	protected function getColumns()
	{
		return array('id', 'order', 'title');
	}
}