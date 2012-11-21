<?php

class model_Files extends model_Collection
{
	protected function initFields()
	{
		return array_merge(
			parent::initFields(),
			array(
	 			'name' => new field_Text(),
	 			'file' => new field_File(),
			)
		);
	}
		
}
