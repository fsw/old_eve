<?php 
/**
 */
class model_Regions extends model_set_Tree
{
	protected static function initFields()
	{
		return array_merge(
			parent::initFields(),
			array(
				'slug' => new field_Slug('nominative'),
	 			'nominative' => new field_Text(), //Kalisz
	 			'locative' => new field_Text(), //w Kaliszu
			)
		);
	}
	
	public static function getIdForPath($names)
	{
		$id = 0;
		$tree = $this->getTree();
		$ptr =& $tree;
		foreach ($names as $name)
		{
			$found = false;
			foreach ($ptr as $row)
			{
				if ($row['nominative'] == $name)
				{
					$found = true;
					$id = $row['id'];
					$ptr =& $row['children'];	
					break;
				}
			}
			if (!$found)
			{
				$id = $this->add(['parent' => $id, 'nominative' => $name]);
				$ptr = array();
			}	
		}
		return $id;
	}
}