<?php
class model_Menu extends model_TreeCollection
{
	public function getFields()
	{
		return array_merge(
				parent::getFields(),
				array(
						'slug' => new field_Text(),
						'title' => new field_Text(),
						'type' => new field_Enum(array(
								'empty' => 'Empty',
								'content' => 'Content'
								)),
						'param' => new field_Text(),
				)
		);
	}
	
	public function explode(&$row)
	{
		parent::explode($row);
		
		switch ($row['type'])
		{
			case 'content':
				$row['href'] = actions_Content::hrefIndex($row['param']);
				break;
			default:
				$row['href'] = '#';
		}
	}
	
	public function implode(&$row)
	{
		unset($row['href']);
		parent::implode($row);
	}
	
	public function getMenu($slug)
	{
		$head = $this->getByField('slug', $slug);
		return $this->getTree($head['id']);
	}
	
}