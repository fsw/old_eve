<?php

class model_Files extends model_Collection
{
	public function getFields()
	{
		return array_merge(
			parent::getFields(),
			array(
	 			'name' => new field_Text(),
	 			'file' => new field_File(),
			)
		);
	}
		
}
