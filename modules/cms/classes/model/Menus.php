<?php
class model_Menus extends model_TreeCollection
{
	public function getFields()
	{
		return array_merge(
				parent::getFields(),
				array(
						'slug' => new field_Text(),
						'title' => new field_Text(),
						'type' => new field_Enum(array(
								'group' => 'Group',
								'content' => 'Content',
								'external' => 'External'
								)),
						'content' => new field_relation_One('contents'),
						'external' => new field_Text(),
				)
		);
	}
	
	public function explode(&$row)
	{
		parent::explode($row);
		
		switch ($row['type'])
		{
			case 'content':
				$content = $this->getSibling('contents')->getById($row['content']);
				$row['href'] = actions_Content::hrefIndex($content['slug']);
				break;
			case 'external':
				$row['href'] = $row['external'];
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