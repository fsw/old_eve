<?php 

class model_Categories extends model_Set
{
	use model_set_Tree;
	use model_set_Slugged;
	
	protected static function initFields()
	{
		return array_merge(
			parent::initFields(),
			array(
				'slug' => new field_Slug('plural'),
	 			'title' => new field_Text(), //Alergologia
	 			'nominative' => new field_Text(), //Alergolog
	 			'plural' => new field_Text(), //Alergolodzy
				'instrumental' => new field_Text(), //z Alergologiem
				'genitive' => new field_Text(), //Alergologa
				'plural_genitive' => new field_Text(), //Alergologów
			)
		);
	}
}